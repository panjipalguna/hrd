<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;


class LogModel extends Model
{
    //protected $connection = 'mysql';

    public function Log($tabel, $action, $log, $serial, $iduser)
     {

 		      $db_ext = DB::connection('mysql_ext');
 		      $dataLog['tabel']=$tabel;
 		      $dataLog['aksi']=$action;
 		      $dataLog['user']= $iduser;
 		      $dataLog['ip']= \Request::ip();
 		      $dataLog['ket']=$log;
 		      $dataLog['serial']=$serial;
 		      $db_ext->table('tb_logs')->insert($dataLog);

     }
}
