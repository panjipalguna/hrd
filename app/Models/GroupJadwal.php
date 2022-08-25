<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupJadwal extends Model
{
    use HasFactory;

    protected $fillable=[
        'nama_grup',
        'keterangan',
    ];

    public function detailGroupJadwal()
    {
        return $this->hasMany(DetailGroupJadwal::class);
    }

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }
}
