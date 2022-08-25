<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;

    protected $table = 'departements';

    protected $fillable =[
        'nama_departement',
        'sub_departement',
        'pimpinan',
        'jabatan',
    ];

    // public function karyawan()
    // {
    //     return $this->hasMany(Karyawan::class);
    // }

  public function sub_departement()
  {
     return $this->belongsTo(Departement::class);
  }

    public function karyawan(){
       return $this->belongsTo(Karyawan::class);
   }


}
