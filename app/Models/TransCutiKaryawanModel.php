<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransCutiKaryawanModel extends Model
{
    use HasFactory;
    protected $table = 'trans_cuties';

    protected $fillable =[
      'karyawan_id', 'awal_cuti',
      'akhir_cuti', 'jumlah', 'periode_cuti',
      'atasan_langsung', 'bool_persetujuan_atasan',
       'tgl_persetujuan_atasan', 'atasan_atasan',
       'bool_persetujuan_atasan_atasan',
        'tgl_persetujuan_atasan_atasan',
         'mengetahui', 'bool_mengetahui',
         'tgl_mengetahui', 'alamat_cuti',
         'telp_cuti', 'keperluan'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
