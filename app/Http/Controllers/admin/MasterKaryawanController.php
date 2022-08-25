<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Agama;
use App\Models\Departement;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Pendidikan;
use App\Models\GroupJadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use PDF;
use App\Models\Log;

class MasterKaryawanController extends Controller
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

    public function index(Karyawan $karyawan, Request $request, Jabatan $jabatan, Departement $departement, Agama $agama, Pendidikan $pendidikan)
    {
			$data = $karyawan->get();
			// $data = $karyawan->where('hak_akses','!=',1)->get();
			if ($request->ajax()) {
				return DataTables::of($data)
					->addIndexColumn()
					->addColumn('action', function ($data){
						// <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#ubah-'.$data->id.'"><i class="fas fa-pen"></i></button>
						return '
						<a href="'.route('download-karyawan',$data->id).'" class="btn btn-primary text-light"><i class="fas fa-print"></i></a>
						<a href="'.route('karyawan.edit',$data->id).'" class="btn btn-warning text-light"><i class="fas fa-pen"></i></a>
						<a onclick="confirm_delete( \''.route('karyawan.destroy',$data->id).'\', \'Are you sure want to delete data ?\')" class="btn btn-danger text-light"><i class="fas fa-trash "></i></a>
						';
					})
					->editColumn('departement_id',function ($row)
					{
						return $row->departement->nama_departement;
					})
					->editColumn('profile',function ($row)
					{
						if ($row->foto != null) {
							return '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#profile-'.$row->id.'"><i class="fas fa-regular fa-eye"></i></button>';
						}
					})
					->editColumn('jabatan_id',function ($row)
					{
						return $row->jabatan->nama_jabatan;
					})
					->rawColumns(['action','profile'])
					->make(true);
			}
			return view('admin.dashboard-karyawan', compact('data','jabatan','departement', 'agama', 'pendidikan'))->with(['cekNav2' => 'karyawan']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Karyawan $karyawan, Request $request, Jabatan $jabatan, Departement $departement, Agama $agama, Pendidikan $pendidikan, GroupJadwal $groupJadwal)
    {
			// return 'a';
			return view('admin.tambah.tambah-karyawan', compact('jabatan','departement', 'agama', 'pendidikan','groupJadwal'))->with(['cekNav2' => 'karyawan']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Karyawan $karyawan)
    {
			DB::beginTransaction();
      try {
				$image = $request->file('image');

				if ($request->file('image') != null) {
					$imageName = 'profile_pengguna_'.Str::random(5).'.'.$image->extension();

					$image->storeAs('/public/pengguna/',$imageName);

					$request['foto'] = 'https://absensi.mbcconsulting.id/storage/pengguna/'.$imageName;
				}

				if ($request->newPass == $request->confirmPass) {
					$request['password'] = bcrypt($request->newPass);
				} else {
					return back()->with('error','Samakan Password & Konfirmasi Password');
				}

				$cek = $karyawan->create($request->all());
				if ($cek) {
					DB::commit();
					return back()->with('success','Data Pengguna Berhasil Ditambah');
				} else {
					DB::rollBack();
					return back()->with('error','Data Pengguna Gagal Ditambah');
				}

			} catch (\Throwable $th) {
				DB::rollBack();
				return back()->with('error', $th->getMessage());
			}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Karyawan $karyawan,Jabatan $jabatan, Departement $departement, Agama $agama, Pendidikan $pendidikan, GroupJadwal $groupJadwal)
    {
			return view('admin.ubah.ubah-karyawan', compact('jabatan','departement', 'agama', 'pendidikan','groupJadwal','karyawan'))->with(['cekNav2' => 'karyawan']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Karyawan $karyawan, Log $log)
    {
			DB::beginTransaction();
      try {
				$image = $request->file('image');

				if ($request->file('image') != null) {
					$imageName = 'profile_pengguna_'.Str::random(5).'.'.$image->extension();

					$destination = storage_path('app/public/pengguna/');
					if ($karyawan->foto != null) {
						try {
							unlink($destination.$karyawan->foto);
						} catch (\Throwable $th) {}
					}
					$image->storeAs('/public/pengguna/',$imageName);

					$request['foto'] = 'https://absensi.mbcconsulting.id/storage/pengguna/'.$imageName;
				}

				if ($request->newPass != null) {
					if ($request->newPass == $request->confirmPass) {
						$request['password'] = bcrypt($request->newPass);
					} else {
						return back()->with('error','Samakan Password & Konfirmasi Password');
					}
				}

				$logs =[
					'tanggal'=>now(),
					'tabel'=> 'tb_karyawan',
					'aksi'=> 'Update',
					'user' => auth()->guard('karyawan')->user()->hak_akses.'-'.auth()->guard('karyawan')->user()->id,
					'ip' => $request->ip(),
					'keterangan' => $karyawan,
					'serial' => route('karyawan.update',$karyawan->id),
				];

				$log->create($logs);

				$cek = $karyawan->update($request->all());
				if ($cek) {
					DB::commit();
					return back()->with('success','Data Pengguna Berhasil Ditambah');
				} else {
					DB::rollBack();
					return back()->with('error','Data Pengguna Gagal Ditambah');
				}

			} catch (\Throwable $th) {
				DB::rollBack();
				return back()->with('error', $th->getMessage());
			}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Karyawan $karyawan)
    {
			DB::beginTransaction();
			try {
				$destination = storage_path('app/public/pengguna/');
				if ($karyawan->foto != null) {
					try {
						unlink($destination.$karyawan->foto);
					} catch (\Throwable $th) {}
				}

				$cek = $karyawan->delete();
				if ($cek) {
					DB::commit();
					return response()->json(['success'=>'Success']);
				} else {
					DB::rollBack();
					return response()->json(['error'=>'Product Update failed.']);
				}
			} catch (\Throwable $th) {
				DB::rollBack();
				return back()->with('error', $th->getMessage());
			}
    }

		public function downloadKaryawan(Karyawan $karyawan)
		{
			// $data = $karyawan;
			// return view('layout.pdf-karyawan',compact('data'));
			$data = [
				'date'=>now(),
				'data' => $karyawan,
			];

			$pdf = PDF::loadView('layout.pdf-karyawan', $data);

			return $pdf->download('Karyawan.pdf');
		}
}
