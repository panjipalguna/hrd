<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class AbsensiModel extends Model
{
     use HasFactory;
     use HasApiTokens;
     public $timestamps = true;
     protected $table = "absensies";

      protected $fillable = [
         'id', 'karyawan_id', 'tanggal', 'jam_masuk', 'jam_pulang', 'status_absensi', 'keterangan', 'image', 'lat', 'lang', 'masuk_via', 'url_masuk', 'url_keluar', 'pulang_via', 'posisi_masuk', 'posisi_pulang', 'keterangan_masuk', 'keterangan_pulang', 'jarak_masuk', 'jarak_pulang','jam_kerja_id','ot_in','ot_out',
      ];
}
