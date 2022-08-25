<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanAbsen extends Model
{
    use HasFactory;

    protected $table ='karyawan_absens';

    protected $fillable = [
       'karyawan_id',
       'tanggal_mulai',
       'durasi',
       'status',
       'atasan_id',
       'persetujuan_atasan',
       'tgl_persetujuan_atasan',
       'jenis_absen',
       'gambar',
       'keperluan',
       'tanggal_persetujuan',
       'sdm_id',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
    public function sdi()
    {
        return $this->belongsTo(Karyawan::class,'sdm_id','id');
    }

    public function atasan()
    {
        return $this->belongsTo(Karyawan::class,'atasan_id','id');
    }
}
