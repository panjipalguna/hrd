<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class PendidikanModel extends Model
{
     use HasFactory;
     use HasApiTokens;
     public $timestamps = true;
     protected $table = "pendidikans";

     protected $fillable=['id', 'nama_Pendidikan', 'created_at', 'updated_at'];
}
