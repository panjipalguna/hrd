<?php

namespace App\Http\Controllers\admin;

use App\Exports\AbsenExport;
use App\Exports\DetailAbsensiExport;
use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\KaryawanAbsen;
use App\Models\KardosModel;
use App\Models\Log;
use App\Models\Libur;
use App\Models\GroupJadwal;
use App\Models\DetailGroupJadwal;
use App\Models\PeriodeGaji;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use PDF;

class AbensiController extends Controller
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


    public function proses(Request $request, PeriodeGaji $periodeGaji, Absensi $absensi, GroupJadwal $groupJadwal, Karyawan $karyawan)
		{
			$pd = $periodeGaji->find($request->periode);
			$ky = $karyawan->find($request->karyawan_id);
			$gj = $ky->groupJadwal->detailGroupJadwal;

            $libur=Libur::whereBetween('tgl_mulai',[$pd->mulai, $pd->selesai])
            ->orWhere(function($query,$pd) {

                $query->whereBetween('tgl_selesai', [$pd->mulai, $pd->selesai]);

            })->get();

            // dd($libur);

            $awal=$pd->mulai;
//isi tanggal kosong log absen dengan default A
            while($awal <= $pd->selesai){
                $cek=$absensi->where('karyawan_id',$request->karyawan_id)->where('tanggal',$awal)->get();
                if(!$cek){
                    $databasensi=[
                        'karyawan_id'=>$request->karyawan_id,
                        'tanggal'   =>date('Ymd',strtotime($awal)),
                        'status_absensi'    =>'A',
                    ];
                    if(date('w',$awal)==0){  //set libur hari minggu
                        $databasensi=[
                            'karyawan_id'=>$request->karyawan_id,
                            'tanggal'   =>date('Ymd',strtotime($awal)),
                            'status_absensi'    =>'L',
                        ];
                    }

                    $absensi->create($databasensi);  //tambah baru log absensi
                }else{  //jika  ketemu log absensi pada hari ini

                    $jam_masuk=$cek->jam_masuk;


                }


                date_add($awal,date_interval_create_from_date_string("1 days"));
            }


			if ($pd) {
				$data = $absensi->where('karyawan_id',$request->karyawan_id)->WhereBetween('tanggal',[$pd->mulai,$pd->selesai])->get();
			} else {
				$data = $absensi->where('karyawan_id',$request->karyawan_id)->get();
			}

			foreach ($data as $i) {
				$jamMasuk = Carbon::parse($i->jam_masuk)->hour;
				foreach ($gj as $j) {
					$waktuDatang = Carbon::parse($j->jamKerja->waktu_mulai)->hour;
					$cek[] = $waktuDatang-$jamMasuk;
				}
				return $cek;
			}

		}



    public function coba(Request $request, Karyawan $karyawan, Absensi $absensi)
	{

		//$abs = AbsensiModel::where('karyawan_id',7)->orderBy('id','desc')->limit(1)->first();

        $jam_masuk=$absensi->jam_masuk;
        $detailgj = $karyawan->groupJadwal->detailGroupJadwal;

        $jam = date('H', strtotime($jam_masuk));
        $menit = date('i', strtotime($jam_masuk));

        $m = ltrim($menit, "0");
        $j = ltrim($jam, "0");

        $num = $j.".".$m;



		// 		$set_group_jadwal = DB::table('tb_jam_kerja')->select(DB::raw('HOUR(waktu_mulai) as waktu_mulai'))->whereIn('id', function($query) use ($group_jadwal){
		// 	$id_kar = $group_jadwal->group_jadwal_id;
		//  $query->select('jam_kerja_id')->from('detail_group_jadwals')->where('group_jadwal_id',$id_kar); })->get();



				$bungkus=[];
						foreach($detailgj as $a){
								$bungkus[] = $a->waktu_mulai;
						}


				//echo json_encode($bungkus);


				//$array = $b;
				$smallest = [];

				foreach ($bungkus as $i) {
				    $smallest[$i] = abs($i - $num);

				}
				asort($smallest);
				$hasil_jam = key($smallest);


				 $penentuan_jam_kerja = DB::table('tb_jam_kerja')->where(DB::raw('hour(waktu_mulai)'),$hasil_jam)->first();


				 $jam_kerja_id = $penentuan_jam_kerja->id;



		$to = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $penentuan_jam_kerja->waktu_mulai);
		$from = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $jam_masuk);

		$jam_1 = date('H:i',strtotime($penentuan_jam_kerja->waktu_mulai));
		$jam_2 = date('H:i',strtotime($jam_masuk));


		$difference = $to->diff($from);
		$m = $difference->i;

		$h = $difference->h;


		if($jam_2 > $jam_1){

			if(!empty($h)){

				$word = 'lebih '.$h.' jam, '.$m.' Menit';


			}else{

				$word = 'lebih '.$m.' Menit';

		    }

		}else{

			$word = "on Time";
		}



        return  compact('word');



	}

	public function simpan_karyawan()
	{
		// code...
		$dos = KardosModel::all();


  //  $db_ext = DB::connection('pgsql_ext');
foreach ($dos as $key) {


      $dataLog = array(
                  //  'id'     => $key->str_id_kad,
                    'nik' => $key->str_NIP,
                    'nama_lengkap' => $key->str_nm_kad,
                    'alamat_domisili' => $key->str_almt_kad1,


                    //'no_telp' => $key->str_telp_kad,
                    'no_telp' => $key->str_hp_kad,
                    'tmp_lahir' => $key->str_tmp_lhr_kad,
                    'tgl_lahir' => $key->tgl_lhr_kad,
                  //  'bol_jk' => $key->bol_jk,
                  //  'str_gol_darah' => $key->str_gol_darah,
                    'agama_id' => $key->str_kd_agm,
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
                    'tgl_masuk' => $key->tgl_masuk,
                    'tgl_resign' => $key->tgl_keluar,
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
                    //'num_sts_pegawai' => $key->num_sts_pegawai,
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



}
