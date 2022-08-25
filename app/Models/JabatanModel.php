<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class JabatanModel extends Model
{
     use HasFactory;
     use HasApiTokens;
     public $timestamps = true;
     protected $table = "jabatans";

     protected $fillable=['id', 'nama_jabatan', 'created_at', 'updated_at'];
}
