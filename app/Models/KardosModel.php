<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class KardosModel extends Model
{
    use HasFactory;
    use HasApiTokens;
    protected $table = "kary_dos";
    public $timestamps = false;
  //  protected $primaryKey = 'str_id_kad';

    protected $fillable = [

      'str_id_kad','str_NIP','str_nm_kad','str_almt_kad1','str_almt_kad2','str_kd_kako',
      'str_telp_kad','str_hp_kad','str_tmp_lhr_kad','tgl_lhr_kad','bol_jk','str_gol_darah',
      'str_kd_agm','str_pdk_akhir','str_bid_jur','str_univ','bol_jab_fungs','bol_sts',
      'str_lokasi_foto','bol_dosen','bol_bagian',
      'bol_status_dosen','bol_aktif','bol_presenter','nm_singkat','no_ktp','nidn',
      'status_pegawai','status_kawin','tgl_masuk','tgl_keluar','tgl_coba','no_sk_coba',
      'tgl_angkat','no_sk_angkat','wajib_sks','str_kd_prop','str_kd_kab','kd_kec','kd_kel',
      'kd_pos','str_hp2_kad','email','str_kd_neg','kd_group_shift',
      'pass_karyawan','trf_gaji','trf_lembur','trf_ajar_r','trf_ajar_e','trf_ajar_i',
      'badgenumer','userid','kd_jab_struktur','kd_jab_fungsi','kd_jab_bagian','str_kd_prodi',
      'str_nama_asli','str_gelar_depan','str_gelar_belakang','str_serdos',
      'str_bidang_keahlian','ER_PeriodeUpdtDtPubDos_Last','ER_lastlogin','bol_pjj',
      'kd_bidang_ilmu_1','kd_bidang_ilmu_2','str_standar_pendidikan','str_standar_pelatihan',
      'str_jobdesk',
      'tanggal_nidn','status_karyawanDosen','tanggal_keluar','tanggal_masuk','tanggal_lahir',
      'num_sts_pegawai','tanggal_input','userinput','sk_dosen_yayasan','tahun_serdos',
      'no_serdik','bid_ilmu_sekarang','sts_asdos','bol_user_moodel','id_user_moodel','NPWP'


    ];
}
