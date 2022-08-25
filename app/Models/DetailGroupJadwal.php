<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailGroupJadwal extends Model
{
    use HasFactory;

    // protected $table ='detail_group_jadwals';

    protected $fillable=[
        'group_jadwal_id',
        'jam_kerja_id',
    ];

    public function jamKerja()
    {
        return $this->belongsTo(JamKerja::class);
    }

    public function grupJadwal()
    {
        return $this->belongsTo(GroupJadwal::class);
    }
}
