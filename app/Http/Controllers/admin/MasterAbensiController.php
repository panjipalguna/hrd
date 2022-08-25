<?php

namespace App\Http\Controllers\admin;

use App\Exports\AbsenExport;
use App\Exports\DetailAbsensiExport;
use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Jabatan;
use App\Models\JamKerja;
use App\Models\Karyawan;
use App\Models\KardosModel;
use App\Models\KaryawanAbsen;
use App\Models\AbsensiModel;
use App\Models\Log;
use App\Models\Libur;
use App\Models\GroupJadwal;
use App\Models\PeriodeGaji;
use App\Models\Departement;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\DB;

class MasterAbensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
		public function __construct()
    {
      return $this->middleware('isAuth');
    }

    public function index(Karyawan $karyawan, Request $request, PeriodeGaji $periodeGaji, Jabatan $jabatan, Departement $departement)
    {
			$data = $karyawan->get();
			// return $data[0]->absensi;

			if ($request->ajax()) {
				return DataTables::of($data)
					->addIndexColumn()
					->addColumn('action', function ($data){
						if (!empty($data->absensi)) {
							if ($data->absensi != '[]') {
								return '
								<a class="btn btn-secondary text-light" href="'.route('karywan-absen',$data->id).'"><i class="fas fa-regular fa-eye"></i></a>
								';
							}
						}
					})
					->editColumn('departement_id',function ($data){
						return $data->departement->nama_departement;
					})
					->editColumn('jabatan_id',function ($data){
						return $data->jabatan->nama_jabatan;
					})
					->rawColumns(['action'])
					->make(true);
			}
			return view('admin.dashboard-absensi', compact('data','periodeGaji', 'jabatan', 'departement'))->with(['cekNav'=>'absen']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Absensi $absensi)
    {
			$data = $absensi->latest();
			if ($request->ajax()) {
				return DataTables::of($data)
					->addIndexColumn()
					->editColumn('karyawan_id',function ($data){
						return $data->karyawan->nama_lengkap;
					})
					->filter(function ($instance) use ($request) {
						if ($request->get('karyawan_id')!=null) {
							$instance->where('karyawan_id', $request->get('karyawan_id'))->get();
						}

						if ($request->get('tanggal')!=null) {
							$instance->whereDate('tanggal', $request->get('tanggal'))->get();
						}


						if (!empty($request->get('search'))) {
							$instance
							->whereHas('karyawan', function($q) use ($request) {
								$q->where('nama_lengkap', 'LIKE', ["%{$request->get('search')}%"]);
							})
							->get();
						}
					})
					->rawColumns([])
					->make(true);
			}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Absensi $absensi, Log $log, KaryawanAbsen $karyawanAbsen, Libur $libur)
    {

			// try {
				$lb=  $libur->get();

				$data = $absensi->where('karyawan_id',$request->karyawan_id)->where('tanggal',date('Y-m-d', strtotime($request->tanggal) ))
				->first();
				$data2 = $karyawanAbsen->where('karyawan_id',$request->karyawan_id)->get();
				$request['jam_kerja_id'] = 0;
				$request['status_absensi']= 'A';

				for ($i=0; $i < $data2->count(); $i++) {
					if ($request->tanggal >= $data2[$i]->tanggal_mulai && $request->tanggal <= $data2[$i]->tanggal_selesai) {
						if ($data2[$i]->status == 'DISETUJUI') {
							if ($data2[$i]->jenis_absen == 'Izin') {
								$request['status_absensi']= 'I';
							}
							else if ($data2[$i]->jenis_absen == 'Sakit') {
								$request['status_absensi']= 'S';
							}
							else if ($data2[$i]->jenis_absen == 'Cuti') {
								$request['status_absensi']= 'C';
							}
						}
					}
					else{
						for ($j=0; $j <$lb->count() ; $j++) {
							if ($request->tanggal >= $lb[$j]->tanggal_awal && $request->tanggal <= $lb[$j]->tanggal_selesai) {
								$request['status_absensi']= 'L';
							}
						}
					}
				}

				if ($request->kt == 'in') {
					$request['jam_masuk']= $request->waktu;
					$tanggal_from_up = date('Y-m-d', strtotime($request->waktu));
					$work_time=$this->penentuan_jam_kerja($request->karyawan_id,$request->waktu,'1');

					if ($data != [] || $data != null) {

					//	$cek = $data->update($request->all());

//haiiiiiiiiii

$entry =   date('Y-m-d H:i:s',strtotime($request->jam_masuk));
$cek = ([
				'jam_kerja_id' => $request->jam_kerja_id,
				'jam_masuk' => $entry,
				'status_absensi' => $request->status_absensi,

			 ]);



			$simpan = DB::table('absensies')->where('id',$data->id)->update($cek);

						// dd($cek);
						$logs =[
							'tanggal'=>now(),
							'tabel'=> 'absensies',
							'aksi'=> 'Update',
							'user' => auth()->guard('karyawan')->user()->hak_akses.'-'.auth()->guard('karyawan')->user()->id,
							'ip' => $request->ip(),
							'keterangan' => $data,
							'serial' => route('absensi.store'),
						];
					}
					else {

						$early_time = date('Y-m-d H:i:s', strtotime($request->waktu));

					$tanggal_from_up = date('Y-m-d', strtotime($request->waktu));
						$cek = AbsensiModel::create([
							'karyawan_id' => $request->karyawan_id,
							'jam_masuk' => $early_time,
							'jam_pulang' => "Belum Absen Pulang",
							'tanggal' => $tanggal_from_up,
							'masuk_via' => 'Laptop',
							'jam_kerja_id' => $work_time['jam_kerja_id'],
							'ot_in' => $work_time['ot_in'],
							'status_absensi' => 'T',
						 ]);

						$logs =[
							'tanggal'=>now(),
							'tabel'=> 'absensies',
							'aksi'=> 'Insert',
							'user' => auth()->guard('karyawan')->user()->hak_akses.'-'.auth()->guard('karyawan')->user()->id,
							'ip' => $request->ip(),
							'keterangan' => $cek,
							'serial' => route('absensi.store'),
						];
					}
				}
				else {

				$tanggal_absen = date('Y-m-d',strtotime($request->waktu));

				$jam_pulang_absen = date('Y-m-d H:i:s',strtotime($request->waktu));

				$get_id = DB::table('absensies')->where('karyawan_id',$request->karyawan_id)->where('tanggal',$tanggal_absen)->first();


				if ($get_id == null) {
					return back()->with('warning','Absen Masuk Karyawan Kosong');
				}
				// return $status_ab;
					$request['jam_pulang']= $request->waktu;

					// dd ($get_id);
					$work_time =$this->penentuan_jam_kerja($request->karyawan_id,$request->waktu,'2');
					$request['jam_kerja_id']=$work_time;
					$ot_in = !empty($work_time['ot_in']) ? $work_time['ot_in'] : $get_id->ot_in;

					//$cek = $data->update($request->all());

					  $cek = ([
								    'karyawan_id' => $request->karyawan_id,
								    'jam_pulang' => $jam_pulang_absen,
								    'pulang_via' => 'Laptop',
								    //'posisi_pulang' => $posisi,
								    //'url_keluar' => $currentURL,
								    //'jarak_pulang' => $request->jarak,
								    'jam_kerja_id' => $work_time['jam_kerja_id'],
								    'keterangan' => $work_time['word'],
								    'ot_out' => $work_time['ot_out'],
								    'ot_in' => $ot_in,
								    'status_absensi' => 'H',
							     ]);

						      $simpan = DB::table('absensies')->where('id',$get_id->id)->update($cek);

					$logs =[
						'tanggal'=>now(),
						'tabel'=> 'absensies',
						'aksi'=> 'Update',
						'user' => auth()->guard('karyawan')->user()->hak_akses.'-'.auth()->guard('karyawan')->user()->id,
						'ip' => $request->ip(),
						'keterangan' => $data,
						'serial' => route('absensi.store'),
					];
				}

				$log->create($logs);

				if ($cek) {
					if ($data != [] || $data != null) {
						return back()->with('success','Absensi Diubah')->withInput();
						// return return response(['success'=>'Absensi Diubah']);
					}else {
						return back()->with('success','Absensi Ditambah')->withInput();
					}
				}
				else {
					return back()->with('error','Gagal Menambah Absensi');
				}
			// } catch (\Throwable $th) {
				// return back()->with('error',$th->getMessage());
			//}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Absensi $absensi)
    {
			return view('admin.dashboard-lokasi',compact('absensi'))->with(['cekNav'=>'absen']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

	public function penentuan_jam_kerja($karyawan_id,$waktu_kerja, $jenis){


    	$group_jadwal = DB::table('karyawans')->select('group_jadwal_id')->where('id',$karyawan_id)->first();

		$nowtime = date("Y-m-d H:i:s");

		$date=date('Y-m-d H:i:s', strtotime($waktu_kerja));

		//dd($date);
		//$date = "2022-06-21 18:45:00";

				// penentuan masuk pagi apa siang========================



				$jam = date('H', strtotime($date));
				$menit = date('i', strtotime($date));


				$m = ltrim($menit, "0");
				$j = ltrim($jam, "0");



				$num = $j.".".$m;

				//$num = "4.1";



//================================== waktu_mulai

if($jenis == 1){

		$set_group_jadwal = DB::table('jam_kerjas')->select(DB::raw('extract(hour from waktu_mulai) as waktu_mulai'))->whereIn('id', function($query) use ($group_jadwal){
		$id_kar = $group_jadwal->group_jadwal_id;
		$query->select('jam_kerja_id')->from('detail_group_jadwals')->where('group_jadwal_id',$id_kar); })->get();


			// cari jam yang terdekat

				$bungkus=[];
						foreach($set_group_jadwal as $a){

								$bungkus[] = $a->waktu_mulai;
						}



				$smallest = [];

				foreach ($bungkus as $i) {
				    $smallest[$i] = abs($i - $num);

				}

				 asort($smallest);
				$hasil_jam = key($smallest);
				//print_r($hasil_jam);

				//dd($bungkus);


				 $penentuan_jam_kerja =  DB::table('jam_kerjas')->where(DB::raw('extract(hour from waktu_mulai)'),$hasil_jam)->first();

				 $data['jam_kerja_id'] = $penentuan_jam_kerja->id;


				 $waktu_masuk_shift_masuk_to_time = date("H:i:s",strtotime($penentuan_jam_kerja->waktu_mulai));
//dd($waktu_masuk_shift_masuk_to_time);
				$date_to_time = date("H:i:s",strtotime($date));

								$shift_time = \Carbon\Carbon::createFromFormat('H:i:s', $waktu_masuk_shift_masuk_to_time );
								$time_work = \Carbon\Carbon::createFromFormat('H:i:s', $date_to_time);
								//$time_work = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', "2022-05-05 09:55:00");

							//	dd($shift_time);

								$jam_1 = date('H:i',strtotime($penentuan_jam_kerja->waktu_mulai));
								$jam_2 = date('H:i',strtotime($date));
								//$jam_2 = date('H:i',strtotime("09:55:00"));



								$difference = $shift_time->diff($time_work);
								$m = $difference->i;
								$h = $difference->h;


								if($jam_2 > $jam_1){

										if(!empty($h)){

											$data['word'] = 'Terlambat '.$h.' jam, '.$m.' Menit';
											$data['ot_in'] = '-'.$h.'.'.$m;

										}else{

											$data['word'] = 'Terlambat '.$m.' Menit';
											$data['ot_in'] = '-'.$m;

									   }
								}else{

									$data['word'] = "on Time";

									if(!empty($h)){


											$data['ot_in'] = '+'.$h.'.'.$m;

										}else{


											$data['ot_in'] = 0;
									    }
								}

	}else{

		// jam Pulang=====================================================

				$set_group_jadwal = DB::table('jam_kerjas')->select(DB::raw('extract(hour from waktu_akhir)  as waktu_akhir'))->whereIn('id', function($query) use ($group_jadwal){
				$id_kar = $group_jadwal->group_jadwal_id;
				$query->select('jam_kerja_id')->from('detail_group_jadwals')->where('group_jadwal_id',$id_kar); })->orderBy('waktu_mulai','asc')->get();

			// cari jam yang terdekat

				$bungkus=[];
						foreach($set_group_jadwal as $a){

								$bungkus[] = $a->waktu_akhir;

								//if()
						}


				$smallest = [];

				foreach ($bungkus as $i) {
				    $smallest[$i] = abs($i - $num);

				}


				//print_r($bungkus);
				// dd($smallest);
				 asort($smallest);
				$hasil_jam = key($smallest);

				$index_num = array_search($hasil_jam, $bungkus);



				 $penentuan_jam_kerja = DB::table('jam_kerjas')->where(DB::raw('extract(hour from waktu_akhir)'),$hasil_jam)->first();
				// dd($penentuan_jam_kerja);

				 $penentuan_jam_kerja_waktu_akhir = date('H',strtotime($penentuan_jam_kerja->waktu_akhir));
				// dd($penentuan_jam_kerja_waktu_akhir);
				 $index_waktu_jam_pulang = array_search($penentuan_jam_kerja_waktu_akhir, $bungkus);
				// dd($index_waktu_jam_pulang);
				 $data['jam_kerja_id'] = $penentuan_jam_kerja->id;
				//  dd($data);

				  $tanggal_from_up = date('Y-m-d', strtotime($date));

				//	dd($tanggal_from_up);
				  // $absen_morning = DB::table('absensies')->select('jam_masuk','jam_kerja_id','keterangan')->where('karyawan_id',$karyawan_id)->where('tanggal',$tanggal_from_up )->orderBy('id','desc')->limit(1)->first();
				 $absen_morning = DB::table('absensies')->where('karyawan_id',$karyawan_id)->where('tanggal',$tanggal_from_up )->first();
				//	 dd($absen_morning->jam_kerja_id);
				  $jam_kerja_morning = DB::table('jam_kerjas')->where('id',$absen_morning->jam_kerja_id)->first();

				//  dd($absen_morning);
				  $index_morning = array_search(date('H',strtotime($jam_kerja_morning->waktu_akhir)), $bungkus);


				  $jam_absen_masuk =  date('H',strtotime($absen_morning->jam_masuk));


				 $jam_pulang_ditambah_satu = date('H', strtotime($date . ' + 1 hours'));


				// print_r($jam_pulang_ditambah_satu);

// bila index waktu jam pulang lebih kecil dari waktu akhir ketika dia absen masuk maka dan jika dia baru absen masuk trus ga lama
// kemudian absen lagi maka...  dan baru absen masuk satu jam  kemudian absen pulang maka...
		if($index_waktu_jam_pulang  < $index_morning ||  $jam_absen_masuk == $jam ||
		$jam_absen_masuk ==   $jam_pulang_ditambah_satu){

//dd('masuk');
				$penentuan_jam_kerja_waktu_akhir_id_sama = date('H:i',strtotime($penentuan_jam_kerja->waktu_akhir));

			$home_time = date('H:i',strtotime($num));
		//	if($home_time < $penentuan_jam_kerja_waktu_akhir_id_sama)	{
						$data['penentuan_jam_kerja'] = $penentuan_jam_kerja_waktu_akhir_id_sama;
						$data['jam_kerja_id'] = $absen_morning->jam_kerja_id;
				$waktu_akhir_shift_masuk = DB::table('jam_kerjas')->where('id',$absen_morning->jam_kerja_id)->first();
				$waktu_akhir_shift_masuk_to_time = date("H:i:s",strtotime($waktu_akhir_shift_masuk->waktu_akhir));
				$date_to_time = date("H:i:s",strtotime($date));
				$shift_time = \Carbon\Carbon::createFromFormat('H:i:s', $waktu_akhir_shift_masuk_to_time);
				$time_work = \Carbon\Carbon::createFromFormat('H:i:s', $date_to_time);
			//	$time_work = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', "2022-05-05 12:01:00");
				$difference = $shift_time->diff($time_work);
				$m = $difference->i;
				$h = $difference->h;

				if(!empty($h)){

							$data['word'] =  'Masuk '.$absen_morning->keterangan.', Pulangya - '.$h.' jam, '.$m.' Menit';

							$data['ot_out'] = 0;


					}else{

							$data['word'] = 'Masuk '.$absen_morning->keterangan.',Pulangya  - '.$m.' Menit';

							$data['ot_out'] = 0;

				    }

						$data['pulang'] = '0000-00-00 00:00:00';

						$data['status'] = 'T';

//dd($data);
				  return $data;


		//	}


		}




		// penentuan jam pulang id Apabila sama dengan id saat absen masuk maka

		if($penentuan_jam_kerja->id == $absen_morning->jam_kerja_id){


			$x = $index_num + 1;


//print_r("expression");
		// jam pulang akan dinaikkan index array nya

				  if(!empty($bungkus[$x])){

				  //	print_r("masuk");

						$penentuan_kerja_lembur = DB::table('jam_kerjas')->where(DB::raw('extract(hour from waktu_akhir)'),$bungkus[$x])->first();

						$penentuan_jam_kerja_waktu_akhir  = date('H',strtotime($penentuan_kerja_lembur->waktu_akhir));




						$index_waktu_akhir = array_search($penentuan_jam_kerja_waktu_akhir, $bungkus);

					}else{
			// jika indexnya over maka kembali ke 0

						//print_r("masuk");
						$penentuan_kerja_lembur = DB::table('jam_kerjas')->where(DB::raw('extract(hour from waktu_akhir)'),$bungkus[0])->first();

						$penentuan_jam_kerja_waktu_akhir  = date('H',strtotime($penentuan_kerja_lembur->waktu_akhir));

						$index_waktu_akhir = array_search($penentuan_jam_kerja_waktu_akhir, $bungkus);

					}


// jika waktunya kurang dari jam akhir
			$penentuan_jam_kerja_waktu_akhir_id_sama = date('H:i',strtotime($penentuan_jam_kerja->waktu_akhir));

			$home_time = date('H:i',strtotime($num));

		//	print_r($home_time);

			if($home_time < $penentuan_jam_kerja_waktu_akhir_id_sama)	{


				$waktu_akhir_shift_masuk = DB::table('jam_kerjas')->where('id',$absen_morning->jam_kerja_id)->first();

				$waktu_akhir_shift_masuk_to_time = date("H:i:s",strtotime($waktu_akhir_shift_masuk->waktu_akhir));

				$date_to_time = date("H:i:s",strtotime($date));

				$shift_time = \Carbon\Carbon::createFromFormat('H:i:s', $waktu_akhir_shift_masuk_to_time);
				$time_work = \Carbon\Carbon::createFromFormat('H:i:s', $date_to_time);
			//	$time_work = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', "2022-05-05 12:01:00");





				$difference = $shift_time->diff($time_work);
				$m = $difference->i;
				$h = $difference->h;

				$data['penentuan_jam_kerja'] = $penentuan_jam_kerja_waktu_akhir_id_sama;
						$data['jam_kerja_id'] = $absen_morning->jam_kerja_id;

				if(!empty($h)){

							$data['word'] =  'Masuk '.$absen_morning->keterangan.',Pulangya - '.$h.' jam, '.$m.' Menit';

							$data['ot_out'] = '-'.$h.'.'.$m;


					}else{

							$data['word'] = 'Masuk '.$absen_morning->keterangan.',Pulangya - '.$m.' Menit';

							$data['ot_out'] = '-'.$m;
				    }



				 		// $data['word'] =   'Masuk '.$absen_morning->keterangan.", Jam Pulang Kurang Dari Waktu Pulang";


				  return $data;


			}


		}

	// index [13,18,5]
// index waktu jam pulang lebih besar dari index jam akhir ketika dia absen masuk

		if($index_waktu_jam_pulang > $index_morning){
//print_r("masuk");

			$penentuan_jam_kerja_waktu_akhir = date('H',strtotime($penentuan_jam_kerja->waktu_akhir));




			$index_waktu_akhir = array_search($penentuan_jam_kerja_waktu_akhir, $bungkus);




		}



// bila index saat absen masuk lebih kecil dari index jam pulang dannn nilai jam masuk lebih besar dari jam pulang shift yg sudah di tentukan
// contoh bila dia masuk pagi jam 8.30 ternyata dia pulang nya jam 18.01 maka dia akan di rubah jam_kerja_id nya atau shiftnya dari pagi menjadi siang
		if(($index_morning  < $index_waktu_jam_pulang)  && ($num > $penentuan_jam_kerja_waktu_akhir)){


//print_r("mashu");

						    $waktu_akhir_shift_masuk = DB::table('jam_kerjas')->where('id',$penentuan_jam_kerja->id)->first();

							$waktu_akhir_shift_masuk_to_time = date("H:i:s",strtotime($waktu_akhir_shift_masuk->waktu_akhir));

							$waktu_mulai_shift_masuk_to_time = date("H:i:s",strtotime($waktu_akhir_shift_masuk->waktu_mulai));

							$date_to_time = date("H:i:s",strtotime($date));

							$shift_time = \Carbon\Carbon::createFromFormat('H:i:s', $waktu_akhir_shift_masuk_to_time);

							$shift_time_mulai = \Carbon\Carbon::createFromFormat('H:i:s', $waktu_mulai_shift_masuk_to_time);

							$time_work = \Carbon\Carbon::createFromFormat('H:i:s', $date_to_time);
							//$time_work = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', "2022-05-05 16:01:00");

							$jam_1_mulai = date('H:i',strtotime($waktu_akhir_shift_masuk->waktu_mulai));
							$jam_1 = date('H:i',strtotime($waktu_akhir_shift_masuk->waktu_akhir));
							$jam_2 = date('H:i',strtotime($date));
							//$jam_2 = date('H:i',strtotime("16:01:00"));



							$difference = $shift_time->diff($time_work);
							$m = $difference->i;
							$h = $difference->h;



							$difference_mulai = $shift_time_mulai->diff($time_work);
							$m_mulai = $difference_mulai->i;
							$h_mulai = $difference_mulai->h;






								if(!empty($h)){

										$data['word'] =  'Masuk '.$absen_morning->keterangan.', Pulang Overtime '.$h.' jam, '.$m.' Menit';

										$data['ot_out'] = $h;
										$data['ot_in'] = '+'.$h_mulai.'.'.$m_mulai;


								}else{

										$data['word'] = 'Masuk '.$absen_morning->keterangan.', Pulang Overtime '.$m.' Menit';
										$data['ot_out'] = 0;
										$data['ot_in'] = '+'.$h_mulai.'.'.$m_mulai;


							    }



						//$data['word'] = "Ontime";




						return $data;


		}

	//	print_r($index_waktu_akhir);

// index [13,18,5]
// bila index waktu masuk pulang sama dengan index waktu akhir pada jam_kerjas di cek
		// contoh index num = 13  dan index waktu akhir = 13
		// cek jam pulangnya dia sama dengan shift kerluarnya di jam_kerjas
		//  contoh shift pagi jam 8 - 13, jika dia pulang lebih dari jam 13 maka masuk ke fungsi ini
		//  akan di hitung overtimenya
		if($index_num  == $index_waktu_akhir){

			// print_r("masuk");

				$waktu_akhir_shift_masuk = DB::table('jam_kerjas')->where('id',$absen_morning->jam_kerja_id)->first();

				$waktu_akhir_shift_masuk_to_time = date("H:i:s",strtotime($waktu_akhir_shift_masuk->waktu_akhir));

				$date_to_time = date("H:i:s",strtotime($date));

				$shift_time = \Carbon\Carbon::createFromFormat('H:i:s', $waktu_akhir_shift_masuk_to_time);
				$time_work = \Carbon\Carbon::createFromFormat('H:i:s', $date_to_time);
				//$time_work = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', "2022-05-05 16:01:00");

				$jam_1 = date('H:i',strtotime($waktu_akhir_shift_masuk->waktu_akhir));
				$jam_2 = date('H:i',strtotime($date));
				//$jam_2 = date('H:i',strtotime("16:01:00"));



				$difference = $shift_time->diff($time_work);
				$m = $difference->i;
				$h = $difference->h;




				$data['jam_kerja_id'] = $absen_morning->jam_kerja_id;

					if(!empty($h)){

							$data['word'] =  'Masuk '.$absen_morning->keterangan.', Pulang Overtime '.$h.' jam, '.$m.' Menit';

							$data['ot_out'] = $h;


					}else{

							$data['word'] = 'Masuk '.$absen_morning->keterangan.', Pulang Overtime '.$m.' Menit';
							$data['ot_out'] = 0;

				    }

		}



// bila index waktu pulang lebih kecil dari index waktu akhir pada jam_kerjas,
// misal dia shift pagi waktu akhir nya jam 13 sedangkan waktu pulangnta jam 13.01 maka akan dihitung overtime
// contoh pada prosesnya saat jam pulang sama dengan waktu akhir shift pagi maka index_waktu_akhir yg semula 0, dinaikkan jadi 1
// supaya bisa masuk proses over time

		if($index_num  < $index_waktu_akhir){
		//	print_r($index_num);
			//print_r("masuk");

				$waktu_akhir_shift_masuk = DB::table('jam_kerjas')->where('id',$absen_morning->jam_kerja_id)->first();

				$waktu_akhir_shift_masuk_to_time = date("H:i:s",strtotime($waktu_akhir_shift_masuk->waktu_akhir));

				$date_to_time = date("H:i:s",strtotime($date));

				$shift_time = \Carbon\Carbon::createFromFormat('H:i:s', $waktu_akhir_shift_masuk_to_time);
				$time_work = \Carbon\Carbon::createFromFormat('H:i:s', $date_to_time);
				//$time_work = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', "2022-05-05 17:01:00");

				$jam_1 = date('H:i',strtotime($waktu_akhir_shift_masuk->waktu_akhir));
				$jam_2 = date('H:i',strtotime($date));
				//$jam_2 = date('H:i',strtotime("17:01:00"));



				$difference = $shift_time->diff($time_work);
				$m = $difference->i;
				$h = $difference->h;




				$data['jam_kerja_id'] = $absen_morning->jam_kerja_id;

					if(!empty($h)){

							$data['word'] =  'Masuk '.$absen_morning->keterangan.', Pulang Overtime '.$h.' jam, '.$m.' Menit';

							$data['ot_out'] = $h;


					}else{

							$data['word'] = 'Masuk '.$absen_morning->keterangan.', Pulang Overtime '.$m.' Menit';
							$data['ot_out'] = 0;
				    }

				//$data['word'] = "Lembur";
		}

// index [13,18,5]
// ini terjadi ketika shift malam pulangny jam 5, kasusnya ketika di pulang lebih dari jam 5
// misal di pulang jam 5.1 (index_num) = 2  dan  jam 5 waktu akhir index ke 2 ($index_waktu_akhir)
// pada prosesnya dinaikkan indexnya, karena sudah mentok di index 5 maka prosesnya dikembalikan ke index 0
// jadi index_num lebih dari index_waktu_akhir

		if($index_num  > $index_waktu_akhir){
			//print_r($index_waktu_akhir);


				$waktu_akhir_shift_masuk = DB::table('jam_kerjas')->where('id',$absen_morning->jam_kerja_id)->first();

				$waktu_akhir_shift_masuk_to_time = date("H:i:s",strtotime($waktu_akhir_shift_masuk->waktu_akhir));

				$date_to_time = date("H:i:s",strtotime($date));

				$shift_time = \Carbon\Carbon::createFromFormat('H:i:s', $waktu_akhir_shift_masuk_to_time);
				$time_work = \Carbon\Carbon::createFromFormat('H:i:s', $date_to_time);
				//$time_work = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', "2022-05-05 17:01:00");

				$jam_1 = date('H:i',strtotime($waktu_akhir_shift_masuk->waktu_akhir));
				$jam_2 = date('H:i',strtotime($date));
				//$jam_2 = date('H:i',strtotime("17:01:00"));



				$difference = $shift_time->diff($time_work);
				$m = $difference->i;
				$h = $difference->h;


				$data['jam_kerja_id'] = $absen_morning->jam_kerja_id;

					if(!empty($h)){

							$data['word'] =  'Masuk '.$absen_morning->keterangan.', Pulang Overtime '.$h.' jam, '.$m.' Menit';
							$data['ot_out'] = $h;

					}else{

							$data['word'] = 'Masuk '.$absen_morning->keterangan.', Pulang Overtime '.$m.' Menit';
							$data['ot_out'] = 0;
				    }

				//$data['word'] = "Lembur";
		}




	}


		return $data;


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
			return $request->all();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

		public function absensi(Karyawan $karyawan, Request $request, Absensi $absensi,PeriodeGaji $periodeGaji)
		{
			$data = $absensi->where('karyawan_id', $karyawan->id)->latest();
			if ($request->ajax()) {
				return DataTables::of($data)
					->addIndexColumn()
					->editColumn('url_masuk',function ($data){
						return '
						<div class="d-flex">
							<button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#masuk-'.$data->id.'"><i class="fas fa-regular fa-eye"></i>
							</button>
							<a href="'.route('absensi.show', $data->id).'" class="btn btn-primary text-light mr-2"><i class="fas fa-globe"></i>
							</a>
							<a onclick="confirm_update( \''.route('put_jam_masuk',$data->id).'\', \'Are you sure want to delete data ?\')" class="btn btn-danger text-light"><i class="fas fa-trash "></i></a>
							</div>
							';
					})
					->editColumn('url_keluar',function ($data){
						return '
						<div class="d-flex">
							<button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#pulang-'.$data->id.'"><i class="fas fa-regular fa-eye"></i>
							</button>
							<a href="'.route('lokasi_pulang', $data->id).'" class="btn btn-primary mr-2 text-light"><i class="fas fa-globe"></i>
							</a>
							<a onclick="confirm_update( \''.route('put_jam_pulang',$data->id).'\', \'Are you sure want to delete data ?\')" class="btn btn-danger text-light"><i class="fas fa-trash "></i></a>
						</div>
						';
					})
					->filter(function ($instance) use ($request) {
						if ($request->get('periode')!=null) {
							$pd = PeriodeGaji::where('id',$request->get('periode'))->first();
							$instance->whereBetween('tanggal',[$pd->mulai, $pd->selesai])->get();
						}
						if ($request->get('tahun')!=null) {
							$instance->whereYear('tanggal', 'LIKE', ["%{$request->get('tahun')}%"])->get();
						}
						if ($request->get('bulan')!=null) {
							$instance->whereMonth('tanggal', '=', $request->get('bulan'))->get();
						}

						if (!empty($request->get('search'))) {
							$instance
							->where('tanggal', 'LIKE', ["%{$request->get('search')}%"])
							->orWhere('keterangan', 'LIKE', ["%{$request->get('search')}%"])
							->get();
						}
					})
					->rawColumns([ 'url_masuk', 'url_keluar', 'lokasi-d', 'lokasi-p'])
					->make(true);
			}

			return view('admin.detail-absensi',compact('karyawan','periodeGaji'))->with(['cekNav'=>'absen']);
		}

		public function lokasiP(Absensi $absensi)
		{
			return view('admin.dashboard-lokasi-pulang',compact('absensi'))->with(['cekNav'=>'absen']);
		}
		public function exelAbsen(Request $request)
		{
			// return $request->all();
			// return Excel::download(new AbsenExport, 'Absen.xlsx');
			return (new AbsenExport($request->periode))->download('Absen.xlsx');
		}

		public function exelDetailAbsen(Karyawan $karyawan, Request $request)
		{
			if ($request->periode == null) {
				$request['periode'] = 0;
			}
			return (new DetailAbsensiExport($request->karyawan_id,$request->periode))->download('DetailAbsen.xlsx');
		}

		public function pdfDetailAbsen(Karyawan $karyawan, Request $request, Absensi $absensi, PeriodeGaji $periodeGaji)
		{
			$periode = $request->input('periode');
			$ky_id = $request->input('karyawan_id');
			$period = $periodeGaji->find($periode);
			$ky= $karyawan->find($ky_id);

			if ($period != null) {
				$abs = $absensi->where('karyawan_id',$ky_id)->WhereBetween('tanggal',[$period->mulai,$period->selesai])->get();
			}else {
				$abs = $absensi->where('karyawan_id',$ky_id)->get();
			}

			$data = [
				'period'=>$period,
				'data' => $abs,
				'hadir' => $abs->where('status_absensi', 'H')->count(),
				'ijin' => $abs->where('status_absensi', 'I')->count(),
				'cuti' => $abs->where('status_absensi', 'C')->count(),
				'sakit' => $abs->where('status_absensi', 'S')->count(),
				'alpa' => $abs->where('status_absensi', 'A')->count(),
				'count_jam_pulang' => $abs->where('jam_pulang', '!=', null)->where('jam_pulang', '!=', 'Belum Absen Pulang')->count(),
				'count_jam_masuk' => $abs->where('jam_masuk', '!=', null)->count(),
			];

			$pdf = PDF::loadView('layout.pdf-absen', $data);

			return $pdf->download('Detail-absen.pdf');

			// $data = $abs;
			// $hadir = $abs->where('status_absensi', 'H')->count();
			// $ijin = $abs->where('status_absensi', 'I')->count();
			// $cuti = $abs->where('status_absensi', 'C')->count();
			// $sakit = $abs->where('status_absensi', 'S')->count();
			// $alpa = $abs->where('status_absensi', 'A')->count();

			// $count_jam_pulang = $abs->where('jam_pulang', '!=', null)->where('jam_pulang', '!=', 'Belum Absen Pulang')->count();
			// $count_jam_masuk = $abs->where('jam_masuk', '!=', null)->count();

			// return view('layout.pdf-absen',compact('data','period',	'hadir','ijin', 'cuti', 'sakit', 'alpa','count_jam_masuk','count_jam_pulang'));

		}

		public function pdfabsen(Request $request, PeriodeGaji $periodeGaji, Absensi $absensi, Departement $departement, Karyawan $karyawan)
		{
			$pd = $periodeGaji->find($request->periode);

			if ($request->departement_id != null) {
				$abs = $absensi->WhereBetween('tanggal',[$pd->mulai,$pd->selesai]);
				$abs = $abs->with('karyawan')->whereRelation('karyawan','departement_id',$request->departement_id)->get();
			}
			else {
				$abs = $absensi->WhereBetween('tanggal',[$pd->mulai,$pd->selesai])->get();
			}

			$period = $pd;
			$data = $abs->groupBy(['karyawan_id']);
			// return view('layout.pdf-absensi',compact('data','period'));

				$data = [
					'period' => $pd,
					'data' => $abs->groupBy(['karyawan_id']),
				];
			// dd($data);
			$pdf = PDF::loadView('layout.pdf-absensi', $data);

			return $pdf->stream('Absensi-Karyawan.pdf');
		}

		public function getDetAbsen(Request $request)
		{
			// dd($request->id, $request->per);
			return (new DetailAbsensiExport($request->karyawan_id,$request->periode))->download('DetailAbsen.xlsx');
		}

		public function cekAbsensi(Absensi $absensi, Request $request)
		{
			$id_karyawan =$request->id_karyawan;
			$tanggal = $request->tanggal;
		}

		public function sortingAbsen(Request $request, PeriodeGaji $periodeGaji, Absensi $absensi, GroupJadwal $groupJadwal, Karyawan $karyawan, JamKerja $jamKerja, Libur $libur)
		{
			$pd = $periodeGaji->find($request->periode);
			$ky = $karyawan->find($request->karyawan_id);
			$gj = $ky->groupJadwal->detailGroupJadwal;

			$data = $absensi->where('karyawan_id',$ky->id)->whereBetween('tanggal',[$pd->mulai, $pd->selesai])->get();

			$awal = date_create($pd->mulai);
			$akhir = date_create($pd->selesai);
			$lb = $libur->where('tanggal_awal', '>=', $awal)->where('tanggal_selesai', '<=', $awal)->first();

			do {
				if (date_format($awal, 'w') == 0) {
					$status_ab = 'L';
				}
				elseif ($lb != null) {
					$status_ab = 'C';
				}
				else {
					// echo date_format($awal,"Y-m-d").'<br>';
					$status_ab = 'A';
				}

				$data_ct = [
					'karyawan_id' => $request->karyawan_id,
					'tanggal' => $awal,
					'status_absensi' => $status_ab,
				];

				$cek = $absensi->where('karyawan_id',$ky->id)->where('tanggal', $awal)->first();
				if (!$cek) {
					$absensi->create($data_ct);
				}
				else{
					$jam_masuk=$cek->jam_masuk;
					// $lbs = $libur->where('tanggal_awal', '>=', date_create($cek->tanggal))->where('tanggal_selesai', '<=', date_create($cek->tanggal))->first();

					if ($jam_masuk != null) {
						$jam = date('H', strtotime($jam_masuk));
						$menit = date('i', strtotime($jam_masuk));

						$m = ltrim($menit, "0");
						$j = ltrim($jam, "0");

						$num[] = $j.".".$m;

						$bungkus=[];
						foreach($gj as $a){
								$bungkus[] = $a->waktu_mulai;
						}

						$smallest = [];
						foreach ($bungkus as $i => $v) {
							foreach ($num as $n => $x) {
								$smallest[$x] = abs($v - $x);
							}
						}

						asort($smallest);
						$hasil_jam = key($smallest);

						$penentuan_jam_kerja = $jamKerja->whereRaw('extract(hour from waktu_mulai)',$hasil_jam)->first();
						$jam_kerja_id = $penentuan_jam_kerja->id;

						$to = Carbon::createFromFormat('Y-m-d H:i:s', $penentuan_jam_kerja->waktu_mulai);
						$from = Carbon::createFromFormat('Y-m-d H:i:s', $jam_masuk);

						$jam_1 = date('H:i',strtotime($penentuan_jam_kerja->waktu_mulai));
						$jam_2 = date('H:i',strtotime($jam_masuk));

						$difference = $to->diff($from);
						$m = $difference->i;
						$h = $difference->h;

						if($jam_2 > $jam_1){
							if(!empty($h)){
								$word[] = 'lebih '.$h.' jam, '.$m.' Menit';
							}else{
								$word[] = 'lebih '.$m.' Menit';
							}
							$hd = 'T';
						}else{
							$word[] = "on Time";
							$hd = 'H';
						}
						// $request['keterangan'] = $word[$dt];
						$request['status_absensi'] = $hd;
					}
					// elseif ($jam_masuk == null && $lb != null) {
					// 	$request['status_absensi'] = 'L';
					// }
					// else {
					// 	$request['status_absensi'] = 'A';
					// }
					$cek->update($request->all());

				}
				$date_end = date_add($awal,date_interval_create_from_date_string("1 days"));
			} while ($awal <= $akhir);

			//

			// foreach ($data as $dt => $v) {
				// $jam_masuk=$v->jam_masuk;
				// $jam = date('H', strtotime($jam_masuk));
				// $menit = date('i', strtotime($jam_masuk));

				// $m = ltrim($menit, "0");
				// $j = ltrim($jam, "0");

				// $num[] = $j.".".$m;

				// $bungkus=[];
				// foreach($gj as $a){
				// 		$bungkus[] = $a->waktu_mulai;
				// }

				// $smallest = [];
				// foreach ($bungkus as $i => $v) {
				// 	foreach ($num as $n => $x) {
				// 		$smallest[$x] = abs($v - $x);
				// 	}
				// }

				// asort($smallest);
				// $hasil_jam = key($smallest);

				// $penentuan_jam_kerja = $jamKerja->whereRaw('HOUR(waktu_mulai)',$hasil_jam)->first();
				// $jam_kerja_id = $penentuan_jam_kerja->id;

				// $to = Carbon::createFromFormat('Y-m-d H:i:s', $penentuan_jam_kerja->waktu_mulai);
				// $from = Carbon::createFromFormat('Y-m-d H:i:s', $jam_masuk);

				// $jam_1 = date('H:i',strtotime($penentuan_jam_kerja->waktu_mulai));
				// $jam_2 = date('H:i',strtotime($jam_masuk));

				// $difference = $to->diff($from);
				// $m = $difference->i;
				// $h = $difference->h;

				// if($jam_2 > $jam_1){
				// 	if(!empty($h)){
				// 		$word[] = 'lebih '.$h.' jam, '.$m.' Menit';
				// 	}else{
				// 		$word[] = 'lebih '.$m.' Menit';
				// 	}
				// 	$hd[] = 'T';
				// }else{
				// 	$word[] = "on Time";
				// 	$hd[] = 'H';
				// }
				// $request['keterangan'] = $word[$dt];
				// $request['status_absensi'] = $hd[$dt];
				// // return ;
				// $data[$dt]->update($request->all());
			// }

			// $data->update($request->all());
			return back();
		}

		public function putJamMasuk(Request $request, Absensi $absensi)
		{
			try {
				$destination = storage_path('app/public/absen_masuk/');
				// if ($absensi->url_masuk != null) {
				// 	try {
				// 		unlink($destination.$absensi->url_masuk);
				// 	} catch (\Throwable $th) {}
				// }
				$request['url_masuk'] = null;
				$request['jam_masuk'] = null;
				$request['ot_in'] = null;
				$request['masuk_via'] = null;
				$request['posisi_masuk'] = null;
				$request['jarak_masuk'] = null;
				$request['keterangan_masuk'] = null;
				// $request['keterangan'] = 'A';
				$request['status_absensi'] = 'A';

				$cek = $absensi->update($request->all());
				if ($cek) {
					return response()->json(['success'=>'Success']);
				}
			} catch (\Throwable $th) {
				return response()->json(['error'=>$th->getMessage()]);
			}
		}

		public function putJamPulang(Request $request, Absensi $absensi)
		{
			try {
				$destination = storage_path('app/public/absen_keluar/');
				if ($absensi->jam_pulang != null) {
					try {
						unlink($destination.$absensi->jam_pulang);
					} catch (\Throwable $th) {}
					$request['jam_pulang'] = null;
				}
				$request['jam_pulang'] = null;
				$request['ot_in'] = null;
				$request['masuk_via'] = null;
				$request['posisi_masuk'] = null;
				$request['jarak_masuk'] = null;
				$request['keterangan_masuk'] = null;
				// $request['keterangan'] = 'A';
				$request['status_absensi'] = 'T';

				$cek = $absensi->update($request->all());
				if ($cek) {
					return response()->json(['success'=>'Success']);
				}
			} catch (\Throwable $th) {
				return response()->json(['error'=>$th->getMessage()]);
			}
		}


		public function pisah(Request $request)
		{

					$check = DB::table('checkMaster')->get();

					foreach($check as $a){

						$tanggal_sama =  date('Y-m-d', strtotime($a->CHECKTIME));
						$same_date =  DB::table('checkMaster')->whereRaw('date("CHECKTIME") = ?',$tanggal_sama)->where('badgeNumber',$a->badgeNumber )->get();

						$bungkus_tanggal_sama=[];
								foreach($same_date as $a){

										$bungkus_tanggal_sama[] = $a->CHECKTIME;
								}

						//	$jumlah_tanggal_sama =	count($bungkus_tanggal_sama);

							//$pulang = '';
							// if($jumlah_tanggal_sama == 1){
							// 	$pulang = 'A';
							// }else{
								$pulang = max($bungkus_tanggal_sama);
						//	}
							$masuk = min($bungkus_tanggal_sama);


	$work_time =$this->penentuan_jam_kerja($a->badgeNumber,$masuk,'1');



	$cek_same_date =  AbsensiModel::where('tanggal',$tanggal_sama)->where('karyawan_id',$a->badgeNumber )->first();

//	dd($cek_same_date->karyawan_id);
if(empty($cek_same_date->karyawan_id)){
	$tanggal = date('Y-m-d', strtotime($a->CHECKTIME));
						$cek = AbsensiModel::create([
							'karyawan_id' => $a->badgeNumber,
							'jam_masuk' => $masuk,
							//'jam_pulang' => $pulang,
							'tanggal' => $tanggal,
							'masuk_via' => 'Laptop',
							'jam_kerja_id' =>$work_time['jam_kerja_id'],
							'ot_in' => $work_time['ot_in'],
							'status_absensi' => 'T',
						 ]);
					 	}

//$tanggal_absen = date('Y-m-d',strtotime($pulang));

$get_id = DB::table('absensies')->where('karyawan_id',$a->badgeNumber)->where('tanggal',$tanggal_sama)->first();

if ($get_id == null) {
	return back()->with('warning','Absen Masuk Karyawan Kosong');
}


	$work_time =$this->penentuan_jam_kerja($a->badgeNumber,$pulang,'2');



	$ot_in = !empty($work_time['ot_in']) ? $work_time['ot_in'] : $get_id->ot_in;
	$hommy_time;
if(empty($work_time['pulang'])){
	$hommy_time = $pulang;
}else {
	$hommy_time = $work_time['pulang'];
}

$stat = empty($work_time['status']) ? 'H' : 'T';

						$cekUpdate = ([
									 'jam_pulang' => $hommy_time,
									 'pulang_via' => 'Laptop',
									 //'posisi_pulang' => $posisi,
									 //'url_keluar' => $currentURL,
									 //'jarak_pulang' => $request->jarak,
									 'jam_kerja_id' => $work_time['jam_kerja_id'],
									 'keterangan' => $work_time['word'],
									 'ot_out' => $work_time['ot_out'],
									 'ot_in' => $ot_in,
									 'status_absensi' => $stat,
									]);

								 $simpan = DB::table('absensies')->where('id',$get_id->id)->update($cekUpdate);

				}
		}

		public function simpan_karyawan()
		{
			// code...
			$dos = KardosModel::all();


	  //  $db_ext = DB::connection('pgsql_ext');
	foreach ($dos as $key) {

$agama = !empty($key->str_kd_agm) ? $key->str_kd_agm : 0;
	      $dataLog = array(

	                    'nik' => $key->str_NIP,
	                    'nama_lengkap' => $key->str_nm_kad,
	                    'alamat_domisili' => $key->str_almt_kad1,


	                    //'no_telp' => $key->str_telp_kad,
	                    'no_telp' => $key->str_hp_kad,
	                    'tmp_lahir' => $key->str_tmp_lhr_kad,
	                    'tgl_lahir' => date('Y-m-d',strtotime($key->tgl_lhr_kad)),
	                  //  'bol_jk' => $key->bol_jk,
	                  //  'str_gol_darah' => $key->str_gol_darah,
	                    'agama_id' => $agama,
	                    //'str_pdk_akhir' => $key->str_pdk_akhir,
	                    //'str_bid_jur' => $key->str_bid_jur,
	                    //'str_univ' => $key->str_univ,
	                    //'bol_jab_fungs' => $key->bol_jab_fungs,
	                    //'bol_sts' => $key->bol_sts,
	                    //'str_lokasi_foto' => $key->str_lokasi_foto,
	                    //'bol_dosen' => $key->bol_dosen,
	                    //'bol_bagian' => $key->bol_bagian,
	                    //'bol_status_dosen' => $key->bol_status_dosen,
	                    //'bol_aktif' => $key->bol_aktif,
	                    //'bol_presenter' => $key->bol_presenter,
	                    //'nm_singkat' => $key->nm_singkat,
	                //    'no_ktp' => $key->no_ktp,
	                  //  'nidn' => $key->nidn,
	                    'status_pegawai' => $key->status_pegawai,
	                    'sts_nikah' => $key->status_kawin,
	                    'tgl_masuk' => date('Y-m-d',strtotime($key->tgl_masuk)) ,
	                    'tgl_resign' => date('Y-m-d',strtotime($key->tgl_keluar)),
	                    //'tgl_coba' => $key->tgl_coba,
	                    //'no_sk_coba' => $key->no_sk_coba,
	                    //'tgl_angkat' => $key->tgl_angkat,
	                    //'no_sk_angkat' => $key->no_sk_angkat,
	                    //'wajib_sks' => $key->wajib_sks,
	                    //'str_kd_prop' => $key->str_kd_prop,
	                    //'str_kd_kab' => $key->str_kd_kab,
	                  //  'kd_kec' => $key->kd_kec,
	                    //'kd_kel' => $key->kd_kel,
	                    //'kd_pos' => $key->kd_pos,
	                    //'str_hp2_kad' => $key->str_hp2_kad,
	                    'email' => $key->email,
	                  //  'str_kd_neg' => $key->str_kd_neg,
	                  //  'kd_group_shift' => $key->kd_group_shift,
	                  //  'pass_karyawan' => $key->pass_karyawan,
	                //    'trf_gaji' => $key->trf_gaji,
	                //    'trf_lembur' => $key->trf_lembur,
	                //    'trf_ajar_r' => $key->trf_ajar_r,
	                //    'trf_ajar_e' => $key->trf_ajar_e,
	//'trf_ajar_i' => $key->trf_ajar_i,
	                //    'badgenumer' => $key->userid,
	                  //  'kd_jab_struktur' => $key->kd_jab_struktur,
	                  //  'kd_jab_fungsi' => $key->kd_jab_fungsi,
	                    //'kd_jab_bagian' => $key->kd_jab_bagian,
	                    //'str_kd_prodi' => $key->str_kd_prodi,
	                    //'str_nama_asli' => $key->str_nama_asli,
	                    //'str_gelar_depan' => $key->str_gelar_depan,
	                    //'str_gelar_belakang' => $key->str_gelar_belakang,
	                    //'str_serdos' => $key->str_serdos,
	                    //'str_bidang_keahlian' => $key->str_bidang_keahlian,
	                    //'ER_PeriodeUpdtDtPubDos_Last' => $key->ER_PeriodeUpdtDtPubDos_Last,
	                    //'ER_lastlogin' => $key->ER_lastlogin,
	                    //'bol_pjj' => $key->bol_pjj,
	                    //'kd_bidang_ilmu_1' => $key->kd_bidang_ilmu_1,
	                    //'kd_bidang_ilmu_2' => $key->kd_bidang_ilmu_2,
	                    //'str_standar_pendidikan' => $key->str_standar_pendidikan,
	                    //'str_standar_pelatihan' => $key->str_standar_pelatihan,
	                    //'str_jobdesk' => $key->str_jobdesk,
	                    //'tanggal_nidn' => $key->tanggal_nidn,
	                  //  'status_karyawanDosen' => $key->status_karyawanDosen,
	                  //  'tanggal_keluar' => $key->tanggal_keluar,
	                  //  'tanggal_masuk' => $key->tanggal_masuk,
	                    //'tanggal_lahir' => $key->tanggal_lahir,
	                    'sts_karyawan' => $key->num_sts_pegawai,
	                    //'tanggal_input' => $key->tanggal_input,
	                    //'userinput' => $key->userinput,
	                    //'sk_dosen_yayasan' => $key->sk_dosen_yayasan,
	                    //'tahun_serdos' => $key->tahun_serdos,
	                    //'no_serdik' => $key->no_serdik,
	                    //'bid_ilmu_sekarang' => $key->bid_ilmu_sekarang,
	                    //'sts_asdos' => $key->sts_asdos,
	                    //'bol_user_moodel' => $key->bol_user_moodel,
	                  //  'id_user_moodel' => $key->id_user_moodel,
	                    'npwp' =>$key->NPWP,
	                  );



	      $res =  DB::table('karyawans')->insert($dataLog);
		}

	}

	public function test()
	{
		// code...
		  return view('data_karyawan');
	}



}
