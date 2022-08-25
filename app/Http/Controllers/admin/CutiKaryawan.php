<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\TransCutiKaryawanModel;
use App\Models\Karyawan;
use App\Models\CutiModel;


use Illuminate\Support\Facades\DB;




class CutiKaryawan extends Controller
{
  public function __construct()
  {
    return $this->middleware('isAuth');
  }

  public function index(TransCutiKaryawanModel $transCutiKaryawanModel, Request $request)
  {
    $periode_cuti = DB::table('master_cuties')->get();
    $data = $transCutiKaryawanModel->get();

    $karyawan = Karyawan::get();
    if ($request->ajax()) {
        return DataTables::of($data)
          ->addIndexColumn()
          ->addColumn('action', function ($data){
            return '
            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#ubah-'.$data->id.'"><i class="fas fa-pen"></i></button>
            <a onclick="confirm_delete( \''.route('jabatan.destroy',$data->id).'\', \'Are you sure want to delete data ?\')" class="btn btn-danger text-light"><i class="fas fa-trash "></i></a>
            ';
          })
          ->editColumn('karyawan_id',function ($data){
            return $data->karyawan->nama_lengkap;
          })
          ->rawColumns(['action'])
          ->make(true);
      }
    return view('admin.dashboard-trans-cuti', compact('data','periode_cuti','karyawan'));
  }

  public function store(Request $request, TransCutiKaryawanModel $transCutiKaryawanModel)
  {
    try {
    //  $cek = $departement->create($request->all());

    $cek = TransCutiKaryawanModel::updateOrCreate(['id' => $request->id], [

                'karyawan_id' => $request->karyawan_id,
                'awal_cuti'=>  date('Y-m-d', strtotime($request->awal_cuti)),
                'akhir_cuti'=> date('Y-m-d', strtotime($request->akhir_cuti)),
                'jumlah'=> $request->jumlah,
                'periode_cuti'=> $request->periode_cuti,
                'bool_persetujuan_atasan'=> $request->bool_persetujuan_atasan,
                'tgl_persetujuan_atasan'=> date('Y-m-d'),
                'alamat_cuti'=> $request->alamat_cuti,
                'telp_cuti'=> $request->telp_cuti,
                'keperluan'=> $request->keperluan,
           ]);

      if ($cek) {
        return back()->with('success','Departement Behasil ditambah');
      }

    } catch (\Throwable $th) {
      return back()->with('error',$th->getMessage());
    }
  }

        public function master_cuti()
        {
          $master_cuti = DB::table('master_cuties')->get();


        //  $karyawan = Karyawan::get();
          if ($request->ajax()) {
              return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data){
                  return '
                  <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#ubah-'.$data->id.'"><i class="fas fa-pen"></i></button>
                  <a onclick="confirm_delete( \''.route('jabatan.destroy',$data->id).'\', \'Are you sure want to delete data ?\')" class="btn btn-danger text-light"><i class="fas fa-trash "></i></a>
                  ';
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.dashboard-master-cuti', compact('data','periode_cuti','karyawan'));

      }


      public function simpan_cuti()
      {
        // code...
        try {
        //  $cek = $departement->create($request->all());

        $cek = CutiModel::updateOrCreate(['id' => $request->id], [

                    'tahun' => $request->tahun,
                    'awal'=>  date('Y-m-d', strtotime($request->awal_cuti)),
                    'akhir'=> date('Y-m-d', strtotime($request->akhir_cuti)),
                    'jumlah'=> $request->jumlah,

               ]);

          if ($cek) {
            return back()->with('success','Cuti Behasil ditambah');
          }

        } catch (\Throwable $th) {
          return back()->with('error',$th->getMessage());
        }
      }


}
