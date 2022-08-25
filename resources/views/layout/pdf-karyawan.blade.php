<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PDF</title>

  <style>
    .kanan{
      float:right;
    }
    img{
      width:250px;
      height:250px;
      margin-bottom:20px;
      border-radius:100%;
    }
  </style>
</head>
<body>
  <div class="kiri"></div>
  <div class="kanan">
    <img src="{{$data->foto}}" alt="" srcset="">
  </div>

  <?php
    $agama = !empty($data->agama->nama_agama) ? $data->agama->nama_agama : 0;

    $nama_group = !empty($data->groupJadwal->nama_grup) ? $data->groupJadwal->nama_grup : 0;

    $jabatan = !empty($data->jabatan->nama_jabatan) ? $data->jabatan->nama_jabatan : 0;

    $nama_departement = !empty($data->departement->nama_departement) ? $data->departement->nama_departement : 0;

    $pendidikan = !empty($data->pendidikan->nama_pendidikan) ? $data->pendidikan->nama_pendidikan : 0;


  ?>
  <h1>Biodata Karyawan</h1>
  <table>
    <tr>
      <td>Nama Lengkap</td>
      <td>:</td>
      <td>{{$data->nama_lengkap}}</td>
    </tr>
    <tr>
      <td>Email</td>
      <td>:</td>
      <td>{{$data->email}}</td>
    </tr>
    <tr>
      <td>No Telpon</td>
      <td>:</td>
      <td>{{$data->no_telp}}</td>
    </tr>
    <tr>
      <td>Group Jadwal</td>
      <td>:</td>
      <td>{{$nama_group}}</td>data->pendidikan->nama_pendidikan
    </tr>
    <tr>
      <td>Jabatan</td>
      <td>:</td>
      <td>{{$jabatan}}</td>
    </tr>
    <tr>
      <td>Dapartement</td>
      <td>:</td>
      <td>{{$nama_departement}}</td>
    </tr>
    <tr>
      <td>Pendidikan</td>
      <td>:</td>
      <td>{{$pendidikan}}</td>
    </tr>
    <tr>
      <td>Agama</td>
      <td>:</td>
      <td>{{$agama}}</td>
    </tr>
    <tr>
      <td>NIK</td>
      <td>:</td>
      <td>{{$data->nik}}</td>
    </tr>
    <tr>
      <td>No KK</td>
      <td>:</td>
      <td>{{$data->no_kk}}</td>
    </tr>
    <tr>
      <td>NPWP</td>
      <td>:</td>
      <td>{{$data->npwp}}</td>
    </tr>
    <tr>
      <td>BPJS Kesehatan</td>
      <td>:</td>
      <td>{{$data->bpjs_kesehatan}}</td>
    </tr>
    <tr>
      <td>Golongan Darah</td>
      <td>:</td>
      <td>{{$data->gd}}</td>
    </tr>
    <tr>
      <td>Tempat Lahir</td>
      <td>:</td>
      <td>{{$data->tmp_lahir}}</td>
    </tr>
    <tr>
      <td>Tanggal Lahir</td>
      <td>:</td>
      <td>{{$data->tgl_lahir}}</td>
    </tr>
    <tr>
      <td>Status Menikah</td>
      <td>:</td>
      <td>{{$data->sts_nikah}}</td>
    </tr>
    <tr>
      <td>Jumlah Anak</td>
      <td>:</td>
      <td>{{$data->jml_anak}}</td>
    </tr>
    <tr>
      <td>Alamat Asal</td>
      <td>:</td>
      <td>{{$data->alamat_asal}}</td>
    </tr>
    <tr>
      <td>Alamat Domisili</td>
      <td>:</td>
      <td>{{$data->alamat_domisili}}</td>
    </tr>
    <tr>
      <td>Nama Ayah</td>
      <td>:</td>
      <td>{{$data->nama_ayah}}</td>
    </tr>
    <tr>
      <td>Nama Ibu</td>
      <td>:</td>
      <td>{{$data->nama_ibu}}</td>
    </tr>
    <tr>
      <td>Status Karyawan</td>
      <td>:</td>
      <td>{{$data->sts_karyawan}}</td>
    </tr>
    <tr>
      <td>Status Kerja</td>
      <td>:</td>
      <td>{{$data->sts_kerja}}</td>
    </tr>
    <tr>
      <td>Tanggal Masuk</td>
      <td>:</td>
      <td>{{$data->tgl_masuk}}</td>
    </tr>
    <tr>
      <td>No Rekening</td>
      <td>:</td>
      <td>{{$data->no_rek}}</td>
    </tr>
    <tr>
      <td>Nama Bank</td>
      <td>:</td>
      <td>{{$data->bank}}</td>
    </tr>
    <tr>
      <td>Tanggal Resign</td>
      <td>:</td>
      <td>{{$data->tgl_resign}}</td>
    </tr>
    <tr>
      <td>Keterangan Resign</td>
      <td>:</td>
      <td>{{$data->keterangan_resign}}</td>
    </tr>
  </table>
</body>
</html>
