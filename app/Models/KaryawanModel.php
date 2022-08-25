<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class KaryawanModel extends Model
{
    use HasFactory;
    use HasApiTokens;

    protected $table="karyawans";
    public $timestamps = false;
    protected $fillable=['id', 'no_karyawan', 'nama_lengkap', 'nik', 'no_kk', 'npwp', 'bpjs_kesehatan', 'bpjs_tenaker', 'jk', 'gd', 'tmp_lahir', 'tgl_lahir', 'agama_id', 'pendidikan_id', 'sts_nikah', 'jabatan_id', 'alamat_asal', 'alamat_domisili', 'no_telp', 'nama_ayah', 'nama_ibu', 'sts_karyawan', 'created_at', 'updated_at', 'departement_id', 'sts_kerja', 'tgl_masuk', 'no_rek', 'bank', 'tgl_resign', 'jabatan_skr', 'foto', 'email', 'password'];


    // public function departement(){
    //     return $this->belongsTo(DepartementModel::class,'departement_id','id');
    // }
    //
    //  public function jabatan(){
    //     return $this->belongsTo(JabatanModel::class,'jabatan_id','id');
    // }
    //
    //  public function pendidikan(){
    //     return $this->belongsTo(PendidikanModel::class,'pendidikan_id','id');
    // }
}
