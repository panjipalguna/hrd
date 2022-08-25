<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;



class MasterAuthController extends Controller
{
  public function cekLogin(Request $request, Karyawan $karyawan)
	{


		if (auth()->guard('karyawan')->attempt($request->only('email', 'password'),false)) {
						$cek = $karyawan->where('email',$request->email)->first();
			if ($cek->hak_akses == "1") {
				return redirect()->route('admin.index');
			}
			else if($cek->hak_akses == "2") {
				return redirect()->route('karyawan-op.index');
			}
			else {
				return back()->with('warning','Hanya Admin Yang Boleh Login');
			}
		}

		else{
			return redirect()->route('home')->with('error','Email / Password Salah');
		}
	}

	public function logout()
	{
		auth()->guard('karyawan')->logout();
		return redirect()->route('home');
	}

	public function cekEmail(Request $request, Karyawan $karyawan, PasswordReset $passwordReset)
	{
		try {
			$token = Str::random(15);
			$request['token'] = $token;
			$request['created_at'] = Carbon::now();
			$request['/email'] = $request->email;

			// return $request->all();

			$cek_email = $karyawan->where('email',$request->email)->first();
			if ($cek_email) {
				$passwordReset->insert(request()->except(['_token']));

        Mail::send('auth.email', ['token' => $token], function($message) use($request){
					$message->to($request->email);
					$message->subject('Email Verification Mail');
			});
				return back()->with('success','Silahkan Cek Email Anda');
			} else {
				return back()->with('warning','Email Tidak Terdaftar');
			}

		} catch (\Throwable $th) {
			return back()->with('error',$th->getMessage());
		}
	}

	public function verifyPass($token, PasswordReset $passwordReset)
	{
		try {
			$cek = $passwordReset->find($token);
			if ($cek) {
				return view('auth.password-change', compact('cek', 'token'));
			} else {
				return redirect()->route('home')->with('error', 'Maaf Permintaan Anda Sudah Kadaluwarsa');
			}
		} catch (\Throwable $th) {
			return back()->with('error',$th->getMessage());
		}
	}

	public function resetPass(Request $request, Karyawan $karyawan, PasswordReset $passwordReset)
	{
		// return $request->all();
		try {
			$cek = $karyawan->where(['email'=>$request->email])->update(['password'=>bcrypt($request->password)]);
			$passwordReset->delete();

			if ($cek) {
				return redirect()->route('home')->with('success', 'Password Anda Sudah Diganti Silahkan Login');
			} else {
				return redirect()-back()->with('error', 'Gagal Memperbarui Password');
			}
		} catch (\Throwable $th) {
			return back()->with('error',$th->getMessage());
		}

	}
}
