<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Agama;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MasterAgamaController extends Controller
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

    public function index(Request $request, Agama $agama)
    {
			$data = $agama->get();
			if ($request->ajax()) {
					return DataTables::of($data)
						->addIndexColumn()
						->addColumn('action', function ($data){
							return '
							<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#ubah-'.$data->id.'"><i class="fas fa-pen"></i></button>
							<a onclick="confirm_delete( \''.route('agama.destroy',$data->id).'\', \'Are you sure want to delete data ?\')" class="btn btn-danger text-light"><i class="fas fa-trash "></i></a>
							';
						})
						->rawColumns(['action'])
						->make(true);
				}
			return view('admin.dashboard-agama', compact('data'))->with(['cekNav3' => 'agama']);
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
    public function store(Request $request, Agama $agama)
    {
      try {
        $cek = $agama->create($request->all());
        if ($cek) {
          return back()->with('success','Data Agama Behasil ditambah');
        } else {
          return back()->with('error','Data Agama Gagal ditambah');
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
    public function update(Request $request, Agama $agama)
    {
			try {
        $cek = $agama->update($request->all());
        if ($cek) {
          return back()->with('success','Data Agama Behasil Diubah');
        } else {
          return back()->with('error','Data Agama Gagal Diubah');
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
    public function destroy(Agama $agama)
    {
			try {
				$data = $agama->delete();
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
