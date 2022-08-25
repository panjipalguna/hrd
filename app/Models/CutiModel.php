<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutiModel extends Model
{
    use HasFactory;
    protected $table = 'master_cuties';

    protected $fillable =[
      'tahun', 'awal', 'akhir', 'jumlah',
    ];
}
