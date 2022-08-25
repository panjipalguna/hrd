<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class KaryawanAbsenModel extends Model
{
    use HasFactory;
    use HasApiTokens;
     public $timestamps = true;
     protected $table = "karyawan_absens";

     protected $fillable=['id', 'karyawan_id', 'tanggal_mulai', 'tanggal_selesai', 'durasi', 'status', 'gambar', 'keperluan', 'jenis_absen', 'tanggal_persetujuan', 'updated_at', 'created_at'];
}
