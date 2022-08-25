<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\KaryawanAbsen;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Exports\CutiExport;

class MasterKaryawanAbsenController extends Controller
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

    public function index(Request $request, KaryawanAbsen $karyawanAbsen)
    {
			$data = $karyawanAbsen->latest();
			// return $data->karyawan;
			if ($request->ajax()) {
				return DataTables::of($data)
					->addIndexColumn()
					->addColumn('action', function ($data){
						return '
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#data-'.$data->id.'"><i class="fas fa-regular fa-eye"></i></button>
						';
					})
					->editColumn('karyawan_id',function ($data){
						return $data->karyawan->nama_lengkap;
					})
					->filter(function ($instance) use ($request) {
						if ($request->get('tahun')!=null) {
							$instance->whereYear('tanggal_mulai', 'LIKE', ["%{$request->get('tahun')}%"])->get();
						}

						if ($request->get('bulan')!=null) {
							$instance->whereMonth('tanggal_mulai', '=', $request->get('bulan'))->get();
						}

						if (!empty($request->get('search'))) {
							$instance
							->whereHas('karyawan', function($q) use ($request) {
								$q->where('nama_lengkap', 'LIKE', ["%{$request->get('search')}%"]);
							})
							->get();
						}
					})
					->rawColumns(['action'])
					->make(true);
			}


			return view('admin.dashboard-karyawan_absen',compact('data'))->with(['cekNav'=>'kabsen']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    public function update(Request $request, KaryawanAbsen $karywanAbsen)
    {
			try {
        $request['sdm_id'] = auth()->guard('karyawan')->user()->id;
				$data = $karywanAbsen->update($request->all());
				if ($data) {
					return back()->with('success','Persetujuan Absen Karyawan '.$request->status);
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

    public function exelAbsenKaryawan(Request $request)
    {
			// if ($req) {
			// 	# code...
			// }
			return (new CutiExport($request->bulan,$request->tahun))->download('Cuti-Karyawan.xlsx');
    }
}
