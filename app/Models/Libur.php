<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libur extends Model
{
    use HasFactory;

    protected $table ='liburs';

    protected $fillable =[
        'tanggal_awal',
        'tanggal_selesai',
        'keterangan',
    ];
}
