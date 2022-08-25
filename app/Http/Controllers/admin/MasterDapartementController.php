<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Departement;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MasterDapartementController extends Controller
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

    public function index(Departement $departement, Request $request, Karyawan $karyawan)
    {
        $data = $departement->get();

        if ($request->ajax()) {
            return DataTables::of($data)
              ->addIndexColumn()
              ->addColumn('action', function ($data){
                return '
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#ubah-'.$data->id.'"><i class="fas fa-pen"></i></button>
								<a onclick="confirm_delete( \''.route('dapartement.destroy',$data->id).'\', \'Are you sure want to delete data ?\')" class="btn btn-danger text-light"><i class="fas fa-trash "></i></a>
								';
              })
							->editColumn('sub_departement',function ($data){

										$nama_departement =	Departement::where('id',$data->sub_departement)->first();


		            return $nama_departement->nama_departement;
		          })
              ->rawColumns(['action'])
              ->make(true);
          }
        return view('admin.dashboard-departement', compact('data','karyawan'))->with(['cekNav2' => 'dapartement']);
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
    public function store(Request $request, Departement $departement)
    {
			try {
				$cek = $departement->create($request->all());
				if ($cek) {
					return back()->with('success','Departement Behasil ditambah');
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
    public function update(Request $request, Departement $departement)
    {
			try {
        // return $departement;
		//		$cek = $departement->update($request->all());
						$id = $request->id;
		$cek = ([

		      		 	'nama_departement' => $request->nama_departement,
				        'sub_departement'=> $request->sub_departement,
								'id_karyawan'=> $request->pimpinan,
								'jabatan'=> $request->jabatan,
		       ]);



		     $hasil = Departement::where('id',$id)->update($cek);

				if ($cek) {
					return back()->with('success','Departement Behasil diubah');
				} else {
					return back()->with('error','Departement Gagal diubah');
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
    public function destroy(Departement $departement)
    {
			try {
				$data = $departement->delete();
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
