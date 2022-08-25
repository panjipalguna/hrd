<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodeGaji;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MasterGajiController extends Controller
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
    public function index(PeriodeGaji $periodeGaji, Request $request)
    {
			$data = $periodeGaji->get();
			if ($request->ajax()) {
					return DataTables::of($data)
						->addIndexColumn()
						->addColumn('action', function ($data){
							return '
							<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#ubah-'.$data->id.'"><i class="fas fa-pen"></i></button>
							<a onclick="confirm_delete( \''.route('gaji.destroy',$data->id).'\', \'Are you sure want to delete data ?\')" class="btn btn-danger text-light"><i class="fas fa-trash "></i></a>
							';
						})
						->rawColumns(['action'])
						->make(true);
				}
			return view('admin.dashboard-ped_gaji', compact('data'))->with(['cekNav2' => 'gaji']);;
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
    public function store(Request $request, PeriodeGaji $periodeGaji)
    {
			try {
				$cek = $periodeGaji->create($request->all());
				if ($cek) {
					return back()->with('success', 'Periode Gaji Karyawan Berhasil Ditambah');
				} else {
					return back()->with('error', 'Periode Gaji Karyawan Gagal Ditambah');
				}
				
			} catch (\Throwable $th) {
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
    public function update(Request $request, PeriodeGaji $periodeGaji)
    {
			try {
				$cek = $periodeGaji->update($request->all());
				if ($cek) {
					return back()->with('success', 'Periode Gaji Karyawan Berhasil Diubah');
				} else {
					return back()->with('error', 'Periode Gaji Karyawan Gagal Diubah');
				}
				
			} catch (\Throwable $th) {
				return back()->with('error', $th->getMessage());
			}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PeriodeGaji $periodeGaji)
    {
			try {
				$data = $periodeGaji->delete();
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
}
