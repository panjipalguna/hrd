<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MasterKaryawanAdminController extends Controller
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

    public function index(Request $request, Karyawan $karyawan)
    {
			$data = $karyawan->where('hak_akses',1)->get();
			$data2 = $karyawan->where('hak_akses','!=',1)->get();
			// return $data2;
			if ($request->ajax()) {
				return DataTables::of($data)
					->addIndexColumn()
					->addColumn('action', function ($data){
							return '
							<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#admin-'.$data->id.'">
							<i class="fas fa-solid fa-user-minus"></i>
							</button>
							';
					})
					->editColumn('departement_id',function ($row)
					{
						return $row->departement->nama_departement;
					})
					->editColumn('jabatan_id',function ($row)
					{
						return $row->jabatan->nama_jabatan;
					})
					->rawColumns(['action'])
					->make(true);
			}
			return view('admin.dashboard-karyawan-admin', compact('data', 'data2'))->with(['cekNav' => 'admin']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Karyawan $karyawan)
    {
			$data2 = $karyawan->where('hak_akses','!=',1)->get();
			// return $data2;
			if ($request->ajax()) {
				return DataTables::of($data2)
					->addIndexColumn()
					->addColumn('action', function ($data){
							return '
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#data-'.$data->id.'">
							<i class="fas fa-solid fa-user-plus text-light"></i>
							</button>
							';
					})
					->editColumn('departement_id',function ($row)
					{
						return $row->departement->nama_departement;
					})
					->editColumn('jabatan_id',function ($row)
					{
						return $row->jabatan->nama_jabatan;
					})
					->rawColumns(['action'])
					->make(true);
			}
			return view('admin.dashboard-karyawan-admin', compact('data2'))->with(['cekNav' => 'admin']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Karyawan $karyawan)
    {
			try {
				$cek = $karyawan->update($request->all());
				if ($cek) {
					return back()->with('success','Berhasil Mengganti Status');
				}
			} catch (\Throwable $th) {
				return back()->with('error',$th->getMessage());
			}
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
}
