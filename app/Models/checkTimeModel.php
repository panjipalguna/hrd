<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class checkTimeModel extends Model
{
    use HasFactory;

    protected $table = 'checkMaster';

    protected $fillable = ["badgeNumber", "CHECKTYPE", "VERIFYCODE", "SENSORID", "Memoinfo", "WorkCode", sn, "UserExtFmt", "idShift", "CHECKTIME"];
}
