<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DetailGroupJadwal;
use App\Models\GroupJadwal;
use App\Models\JamKerja;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MasterGroupController extends Controller
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
    public function index(Request $request, GroupJadwal $groupJadwal)
    {
			$data = $groupJadwal->get();
			// return $data[0]->detailGroupJadwal;
			if ($request->ajax()) {
				return DataTables::of($data)
					->addIndexColumn()
					->addColumn('action', function ($data){
						return '
						<a href="'.route('group.show', $data->id).'" class="btn btn-primary"><i class="fas fa-regular fa-eye"></i></a>
						<a onclick="confirm_delete( \''.route('group.destroy',$data->id).'\', \'Are you sure want to delete data ?\')" class="btn btn-danger text-light"><i class="fas fa-calendar-minus"></i></a>
						';
					})
					->rawColumns(['action'])
					->make(true);
			}
			return view('admin.dashboard-group', compact('data'))->with(['cekNav'=>'group']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    { 

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, GroupJadwal $groupJadwal)
    {
			try {
				$cek = $groupJadwal->create($request->all());
				if ($cek) {
					return back()->with('success','Group Karyawan Berhasil Ditambah');
				}
			} catch (\Throwable $th) {
				return back()->with('error',$th->getMessage());
			}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(GroupJadwal $groupJadwal,Request $request, JamKerja $jamKerja,Karyawan $karyawan)
    {
			$data = $groupJadwal->detailGroupJadwal;
			$kry = $karyawan->where('group_jadwal_id',null)->get();
			// return $data[0]->detailGroupJadwal;
			if ($request->ajax()) {
				return DataTables::of($data)
					->addIndexColumn()
					->addColumn('action', function ($data){
						return '
						<a onclick="confirm_delete( \''.route('detailGroup.destroy',$data->id).'\', \'Are you sure want to delete data ?\')" class="btn btn-danger text-light"><i class="fas fa-calendar-minus"></i></a>
						';
					})
					->editColumn('jam_kerja_id',function ($data){
						return $data->jamKerja->nama_shift;
					})
					->editColumn('datang',function ($data){
						return $data->jamKerja->waktu_mulai;
					})
					->editColumn('pulang',function ($data){
						return $data->jamKerja->waktu_akhir;
					})
					->rawColumns(['action'])
					->make(true);
			}
			return view('admin.dashboard-detail_group', compact('groupJadwal','jamKerja','kry'))->with(['cekNav'=>'group']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(GroupJadwal $groupJadwal, Request $request)
    {
			// show karyawan 
			$data = $groupJadwal->karyawan;
			// return $data;
			if ($request->ajax()) {
				return DataTables::of($data)
					->addIndexColumn()
					->addColumn('action', function ($data){
						return '
						<a onclick="ubahData( \''.route('group-karyawan',$data->id).'\', \'Are you sure want to delete data ?\')" class="btn btn-danger text-light"><i class="fas fa-user-minus"></i></a>
						';
					})
					->editColumn('jabatan_id',function ($data){
						return $data->jabatan->nama_jabatan;
					})
					->rawColumns(['action'])
					->make(true);
			}
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GroupJadwal $groupJadwal, Karyawan $karyawan)
    {
			try {
				$data = $karyawan->find($request->karyawan_id);
				$cek = $data->update($request->all());
				
				return back()->with('success','Berhasil Menambah Karyawan Group');
			} catch (\Throwable $th) {
				return back()->with('error',$th->getMessage());
			}
    }

		public function updateKaryawan(Karyawan $karyawan)
		{
			try {
				$data = $karyawan->update(['group_jadwal_id'=> null]);
				if ($data) {
					return response()->json(['success'=>'Success']);
				}
				else {
					return response()->json(['error'=>'Product Update failed.']);
				}
			} catch (\Throwable $th) {
				return response()->json(['error'=>$th->getMessage()]);
			}
		}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(GroupJadwal $groupJadwal, Karyawan $karyawan)
    {
			try {

				//hapus Detail Group
				$dtg = $groupJadwal->detailGroupJadwal;
				for ($c=0; $c < $dtg->count(); $c++) { 
					$dtg[$c]->delete();
				}

				//ubah Karyawan
				$kyt = $groupJadwal->karyawan;
				for ($i=0; $i < $kyt->count(); $i++) { 
					$kyt[$i]->update(['group_jadwal_id'=>null]);
				}

				$data = $groupJadwal->delete();
				if ($data) {
					return response()->json(['success'=>'Success']);
				}
				else {
					return response()->json(['error'=>'Product Update failed.']);
				}
			} catch (\Throwable $th) {
				return back()->with('error',$th->getMessage());
			}
    }
}
