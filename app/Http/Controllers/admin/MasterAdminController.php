<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Departement;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MasterAdminController extends Controller
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

    public function index(Karyawan $karyawan, Departement $departement, Jabatan $jabatan)
    {
      $data = $karyawan->where('sts_karyawan','!=','SUPERADMIN')
      ->where('sts_karyawan','!=','ADMIN')->get();

      return view('admin.data_karyawan', compact('data','departement', 'jabatan'))->with(['cekNav' => 'dashboard']);

    //  return view('admin.data_karyawan');
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
    public function show(Karyawan $karyawan)
    {

      return view('admin.dashboard-profile_karyawan', compact('karyawan'))->with(['cekNav' => 'kary']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    public function updateProfileKaryawan(Karyawan $karyawan,Request $request)
    {
      try {

        if ($request->newPass != null) {
          if ($request->newPass == $request->confirmPass) {
            $request['password'] = bcrypt($request->newPass);
          } else {
            return back()->with('error', 'Silahkan Benarkan Penulisan Konfirmasi Password');
          }

        }

				$image = $request->file('image');
        if ($request->file('image') != null) {
					$imageName = 'profile_'.Str::random(5).'.'.$image->extension();

					$destination = storage_path('app/public/pengguna/');
					if ($karyawan->foto != null) {
						try {
							unlink($destination.$karyawan->foto);
						} catch (\Throwable $th) {}
					}
					$image->storeAs('/public/pengguna/',$imageName);

					$request['foto'] = 'https://absensi.mbcconsulting.id//storage/pengguna/'.$imageName;
				}


        $cek = $karyawan->update($request->all());
        if ($cek) {
          return back()->with('success', 'Profile Anda Dirubah');
        }
      } catch (\Throwable $th) {
        return back()->with('error', $th->getMessage());
      }
    }

		public function profile(Profile $profile)
		{
			$data = $profile->first();
      return view('admin.dashboard-profile', compact('data'))->with(['cekNav' => 'setting']);
		}

		public function updateProfile(Profile $profile, Request $request)
		{
			try {
				$image = $request->file('image');

				if ($request->file('image') != null) {
					$imageName = 'profile_'.Str::random(5).'.'.$image->extension();

					$destination = storage_path('app/public/profile/');
					if ($profile->gambar != null) {
						try {
							unlink($destination.$profile->gambar);
						} catch (\Throwable $th) {}
					}
					$image->storeAs('/public/profile/',$imageName);

					$request['gambar'] = 'https://absensi.mbcconsulting.id//storage/profile/'.$imageName;
				}

				$cek = $profile->update($request->all());
				if ($cek) {
					return back()->with('success','Profile Perusahaan Berhasil Diubah');
				} else {
					return back()->with('error','Profile Perusahaan Gagal Diubah');
				}

			} catch (\Throwable $th) {
				return back()->with('error',$th->getMessage());
			}
		}
}
