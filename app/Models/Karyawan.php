<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Karyawan extends Authenticatable
{
    use HasFactory;

    protected $table = 'karyawans';

    protected $fillable =[
        'agama_id',
        'jabatan_id',
        'departement_id',
        'pendidikan_id',
        'no_karyawan',
        'nama_lengkap',
        'nik',
        'no_kk',
        'npwp',
        'bpjs_kesehatan',
        'bpjs_tenaker',
        'jk',
        'gd',
        'tmp_lahir',
        'tgl_lahir',
        'sts_nikah',
        'alamat_asal',
        'alamat_domisili',
        'no_telp',
        'nama_ayah',
        'nama_ibu',
        'sts_karyawan',
        'sts_kerja',
        'tgl_masuk',
        'no_rek',
        'bank',
        'tgl_resign',
        'jabatan_skr',
        'foto',
        'email',
        'password',
        'hak_akses',
        'group_jadwal_id',
        'keterangan_resign',
        'jumlah_anak',
        'reward',
        'punishment'
    ];

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function groupJadwal()
    {
        return $this->belongsTo(GroupJadwal::class);
    }
    public function karyawanAbsen()
    {
        return $this->hasMany(KaryawanAbsen::class);
    }
    public function agama()
    {
        return $this->belongsTo(Agama::class);
    }
    public function pendidikan()
    {
        return $this->belongsTo(Pendidikan::class);
    }

}
