<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class TokenModel extends Model
{
    use HasFactory;
    use HasApiTokens;
    public $timestamps = false;
    protected $table = "mbc_token";

    protected $fillable = [
       'id', 'token',
    ];
}
