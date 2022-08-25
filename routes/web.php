<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\MasterAdminController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [MasterAdminController::class,'index'])->middleware('isAuth')->name('dashboard');
Route::get('/login',function (){
    return view('auth.login-admin');
})->name('home');

Route::get('/forgot-email', function () {
    return view('auth.forgot_pass-admin');
})->name('cek-email');

//Route::get('dashboard', 'admin\MasterAdminController@index')->name('dashboard');

Route::get('forgot-password/{token}', 'admin\MasterAuthController@verifyPass')->name('password-update');
Route::put('forgot-password/{passwordReset}', 'admin\MasterAuthController@resetPass')->name('resetPass');

Route::get('logout', 'admin\MasterAuthController@logout')->name('logout');
Route::post('login', 'admin\MasterAuthController@cekLogin')->name('login');
Route::post('email', 'admin\MasterAuthController@cekEmail')->name('email-verfy');

//Route::get('coba', 'admin\AbsensiController@coba')->name('coba');
Route::post('simpan_cuti', 'admin\MasterAbensiController@sortingAbsen')->name('sorting-detail');

Route::get('admin/download/karyawan/{karyawan}', 'admin\MasterKaryawanController@downloadKaryawan')->name('download-karyawan');
Route::post('admin/sorting', 'admin\MasterAbensiController@sortingAbsen')->name('sorting-detail');
Route::post('admin/detail/pdf', 'admin\MasterAbensiController@pdfDetailAbsen')->name('pdf-detail');
Route::post('admin/absen/pdf', 'admin\MasterAbensiController@pdfabsen')->name('pdf-absen');
Route::get('admin/setting/{profile}', 'admin\MasterAdminController@profile')->name('profile');
Route::put('admin/setting/{profile}', 'admin\MasterAdminController@updateProfile')->name('update-profile');
Route::put('karyawan/profile/{karyawan}', 'admin\MasterAdminController@updateProfileKaryawan')->name('profile-karyawan');
Route::get('absensi/karyawan/{karyawan}', 'admin\MasterAbensiController@absensi')->name('karywan-absen');
Route::get('absensi/lokasi/{absensi}', 'admin\MasterAbensiController@lokasiP')->name('lokasi_pulang');
Route::put('absensi/jamMasuk/{absensi}', 'admin\MasterAbensiController@putJamMasuk')->name('put_jam_masuk');
Route::put('absensi/jamPulang/{absensi}', 'admin\MasterAbensiController@putJamPulang')->name('put_jam_pulang');
Route::post('dowload/absen', 'admin\MasterAbensiController@exelAbsen')->name('download-absen');
Route::post('dowload/detail', 'admin\MasterAbensiController@exelDetailAbsen')->name('download-detail-absen');
Route::post('dowload/cuti', 'admin\MasterKaryawanAbsenController@exelAbsenKaryawan')->name('download-cuti');
Route::get('dowload/detabsen', 'admin\MasterAbensiController@getDetAbsen')->name('download-detabsen');
Route::put('group/karyawan/{karyawan}', 'admin\MasterGroupController@updateKaryawan')->name('group-karyawan');
Route::post('/save_konfirmation_pagi', 'Absensi@save_konfirmation_pagi')->name('save_konfirmation_pagi');
Route::get('/laporan', 'admin\MasterLaporanAbsenKaryawanController@index')->name('laporan-index');
Route::get('/laporan/{karyawan}', 'admin\MasterLaporanAbsenKaryawanController@pdfDetail')->name('laporan-pdf_detail');
Route::get('/laporan-exel/{karyawan}', 'admin\MasterLaporanAbsenKaryawanController@exelDetail')->name('laporan-exel_detail');
Route::get('pisah', 'admin\MasterAbensiController@pisah')->name('pisah');

Route::resource('admin', 'admin\MasterAdminController',['names'=>'admin']);
Route::resource('cuti', 'admin\CutiKaryawan',['names'=>'cuti']);

Route::resource('jabatan', 'admin\MasterJabatanController',['names'=>'jabatan']);
Route::resource('departement', 'admin\MasterDapartementController',['names'=>'dapartement']);
Route::resource('jamKerja', 'admin\MasterJamKerjaController',['names'=>'jamKerja']);
Route::resource('karyawan', 'admin\MasterKaryawanController',['names'=>'karyawan']);
Route::resource('pendidikan', 'admin\MasterPendidikanController',['names'=>'pendidikan']);
Route::resource('agama', 'admin\MasterAgamaController',['names'=>'agama']);
Route::resource('periodeGaji', 'admin\MasterGajiController',['names'=>'gaji']);
Route::resource('absensi', 'admin\MasterAbensiController',['names'=>'absensi']);
Route::resource('karywanAbsen', 'admin\MasterKaryawanAbsenController',['names'=>'karywanAbsen']);
Route::resource('karyawans', 'admin\MasterKaryawanAdminController',['names'=>'adminKaryawan']);
Route::resource('groupJadwal', 'admin\MasterGroupController',['names'=>'group']);
Route::resource('detailGroupJadwal', 'admin\MasterDetailGroupController',['names'=>'detailGroup']);
Route::resource('libur', 'admin\MasterLiburController',['names'=>'libur']);
Route::resource('log', 'admin\MasterLogController',['names'=>'log']);


Route::resource('OP/karyawan', 'operator\MasterOperatorController',['names'=>'karyawan-op']);
Route::resource('OP/karywanAbsen', 'operator\MasterIzinController',['names'=>'izin-op']);
