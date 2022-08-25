<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartementModel extends Model
{
     use HasFactory;
     protected $table="departements";
     public $timestamps = true;
     protected $fillable=['id', 'nama_departement', 'sub_departement', 'nama_pimpinan', 'jabatan', 'created_at', 'updated_at'];
}
