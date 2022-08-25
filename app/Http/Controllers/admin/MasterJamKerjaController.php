<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\JamKerja;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MasterJamKerjaController extends Controller
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

    public function index(Request $request, JamKerja $jamKerja)
    {
			$data = $jamKerja->get();
			if ($request->ajax()) {
				return DataTables::of($data)
					->addIndexColumn()
					->addColumn('action', function ($data){
						return '
						<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#ubah-'.$data->id.'"><i class="fas fa-pen"></i></button>
						<a onclick="confirm_delete( \''.route('jamKerja.destroy',$data->id).'\', \'Are you sure want to delete data ?\')" class="btn btn-danger text-light"><i class="fas fa-trash "></i></a>
						';
					})
					->editColumn('waktu_mulai',function ($row) {
						return date('H:i', strtotime($row->waktu_mulai));
					})
					->editColumn('waktu_akhir',function ($row) {
						return date('H:i', strtotime($row->waktu_akhir));
					})
					->rawColumns(['action'])
					->make(true);
			}
			return view('admin.dashboard-jamkerja', compact('data'))->with(['cekNav2' => 'jamKerja']);
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
    public function store(Request $request, JamKerja $jamKerja)
    {
			try {
				$cek = $jamKerja->create($request->all());
				if ($cek) {
					return back()->with('success','Jam Kerja Behasil ditambah');
				} else {
					return back()->with('error','Jam Kerja Gagal ditambah');
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
		
    public function update(Request $request, JamKerja $jamKerja)
    {
			try {
				$cek = $jamKerja->update($request->all());
				if ($cek) {
					return back()->with('success','Jam Kerja Behasil diubah');
				} else {
					return back()->with('error','Jam Kerja Gagal diubah');
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
    public function destroy(JamKerja $jamKerja)
    {
			try {
				$data = $jamKerja->delete();
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
