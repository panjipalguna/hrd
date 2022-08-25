<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensies';

    protected $fillable =[
        'jam_kerja_id',
        'karyawan_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'masuk_via',
        'pulang_via',
        'url_masuk',
        'url_keluar',
        'posisi_masuk',
        'posisi_pulang',
        'jarak_masuk',
        'jarak_pulang',
        'keterangan_masuk',
        'keterangan_pulang',
        'status_absensi',
        'keterangan',
        'ot_in',
        'ot_out',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function jamKerja()
    {
        return $this->belongsTo(JamKerja::class);
    }

}
