<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamKerja extends Model
{
    use HasFactory;

    protected $table = 'jam_kerjas';

    protected $fillable =[
        'nama_shift',
        'waktu_mulai',
        'waktu_akhir',
    ];
}
