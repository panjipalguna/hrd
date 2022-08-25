<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeGaji extends Model
{
    use HasFactory;

    protected $table = 'periode_gajis';

    protected $fillable = [
        'nama_periode',
        'mulai',
        'selesai',
        'tgl_cetak',
    ];
}
