@extends('layout.app')
@section('main-content')
  <div class="list-data-karyawan">
    <div class="container-fluid">
  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Ubah Karyawan</h1>
    <a href="{{route('karyawan.index')}}" class="btn btn-warning hover-left">
      <i class="fas fa-caret-left mr-2"></i>
      Kembali
    </a>
  </div>

  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Profile</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <img src="{{$karyawan->foto}}" class="w-100">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <form method="POST" action="{{route('karyawan.update', $karyawan->id)}}" enctype="multipart/form-data" class="row">
    @csrf
    @method('PUT')

    <div class="col-md-6 mb-3">
      <label for="inputEmail4" class="form-label">Profile</label>
      <input type="file" class="form-control" name="image">
    </div>
    <div class="col-md-6 mb-3">
      <label for="inputEmail4" class="form-label d-block">Gambar Sebelumnya</label>
      @if($karyawan->foto != null)
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-regular fa-eye"></i></button>
      @else
        <h5 class="text-danger">Profile Kosong</h5>
      @endif
    </div>
    <div class="col-md-12 my-3">
      <div class="border border-secondary w-100"></div>
    </div>
    <div class="col-md-6 mb-3">
      <label for="inputEmail4" class="form-label">Email</label>
      <input type="email" class="form-control" name="email" value="{{$karyawan->email}}" readonly required>
    </div>
    <div class="col-md-6 mb-3">
      <label for="inputEmail4" class="form-label">Nama</label>
      <input type="text" class="form-control" name="nama_lengkap" value="{{$karyawan->nama_lengkap}}" required>
    </div>
    <div class="col-md-6 mb-3">
      <label for="inputEmail4" class="form-label">No Hp</label>
      <input type="number" class="form-control" name="no_telp" value="{{$karyawan->no_telp}}">
    </div>

    <div class="col-md-6 mb-3">
      <label for="inputEmail4" class="form-label">Password Baru</label>
      <input type="password" class="form-control" name="newPass">
    </div>
    <div class="col-md-6 mb-3">
      <label for="inputEmail4" class="form-label">Konfirmasi Password</label>
      <input type="password" class="form-control" name="confirmPass">
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Status Kariyawan</label>
      <select name="sts_karyawan" class="form-control">
        <option {{$karyawan->sts_karyawan == 'T'?'selected':'' }} value="T">T - Tetap</option>
        <option {{$karyawan->sts_karyawan == 'K'?'selected':'' }} value="K">K - Kontrak</option>
        <option {{$karyawan->sts_karyawan == 'M'?'selected':'' }} value="M">M - Magang</option>
        <option {{$karyawan->sts_karyawan == 'P'?'selected':'' }} value="P">P - Percobaan</option>
      </select>
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Hak Akses</label>
      <select name="hak_akses" class="form-control">
        <option {{$karyawan->hak_akses == '1'?'selected':'' }} value="1">Administrator</option>
        <option {{$karyawan->hak_akses == '2'?'selected':'' }} value="2">Operator</option>
        <option {{$karyawan->hak_akses == '3'?'selected':'' }} value="3">Karyawan</option>
      </select>
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Agama</label>
      <select name="agama_id" class="form-control">
        @foreach ($agama->get() as $agm)
          <option {{$karyawan->agama_id == $agm->id?'selected':'' }} value="{{$agm->id}}">{{$agm->nama_agama}}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Jabatan</label>
      <select name="jabatan_id" class="form-control">
        @foreach ($jabatan->get() as $jb)
          <option {{$karyawan->jabatan_id == $jb->id?'selected':'' }} value="{{$jb->id}}">{{$jb->nama_jabatan}}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Dapartement</label>
      <select name="departement_id" class="form-control">
        @foreach ($departement->get() as $dp)
          <option {{$karyawan->departement_id == $dp->id?'selected':'' }} value="{{$dp->id}}">{{$dp->nama_departement}}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Pendidikan</label>
      <select name="pendidikan_id" class="form-control">
        @foreach ($pendidikan->get() as $pd)
          <option {{$karyawan->pendidikan_id == $pd->id?'selected':'' }} value="{{$pd->id}}">{{$pd->nama_pendidikan}}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Status Kerja</label>
      <select name="sts_kerja" class="form-control">
        <option {{$karyawan->sts_kerja == 'A'?'selected':'' }} value="A">A - Aktif</option>
        <option {{$karyawan->sts_kerja == 'R'?'selected':'' }} value="R">R - Risign</option>
      </select>
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Group Jadwal</label>
      <select name="group_jadwal_id" class="form-control">
      @foreach ($groupJadwal->get() as $a)
        <option {{$karyawan->group_jadwal_id == $a->id?'selected':'' }}  value="{{$a->id}}">{{$a->nama_grup}}</option>
      @endforeach
      </select>
    </div>
    <div class="col-md-12 my-3">
      <div class="border border-secondary w-100"></div>
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Jenis Kelamin</label>
      <select name="jk" class="form-control">
        <option {{$karyawan->jk == 'Laki Laki'?'selected':'' }} value="Laki Laki">Laki Laki</option>
        <option {{$karyawan->jk == 'Perempuan'?'selected':'' }} value="Perempuan">Perempuan</option>
        <option {{$karyawan->jk == 'Rahasia'?'selected':'' }} value="Rahasia">Rahasia</option>
      </select>
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Alamat Asal</label>
      <input type="text" name="alamat_asal" value="{{$karyawan->alamat_asal}}" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Alamat Domisili</label>
      <input type="text" name="alamat_domisili" value="{{$karyawan->alamat_domisili}}" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Nama Ayah</label>
      <input type="text" name="nama_ayah" value="{{$karyawan->nama_ayah}}" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Nama Ibu</label>
      <input type="text" name="nama_ibu" value="{{$karyawan->nama_ibu}}" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Golongan Darah</label>
      <input type="text"  name="gd" value="{{$karyawan->gd}}" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Tempat Lahir</label>
      <input type="text" name="tmp_lahir" value="{{$karyawan->tmp_lahir}}" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Tanggal Lahir</label>
      <input type="date" name="tgl_lahir" value="{{$karyawan->tgl_lahir}}" class="form-control">
    </div>
    <div class="col-md-12 my-3">
      <div class="border border-secondary w-100"></div>
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">NIK</label>
      <input type="number" name="nik" value="{{$karyawan->nik}}" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">No KK</label>
      <input type="number" name="no_kk" value="{{$karyawan->no_kk}}" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">NPWP</label>
      <input type="text" name="npwp" value="{{$karyawan->npwp}}" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">BPJS Kesehatan</label>
      <input type="text" name="bpjs_kesehatan" value="{{$karyawan->bpjs_kesehatan}}" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">BPJS Tenaga Kerja</label>
      <input type="text" name="bpjs_tenaker" value="{{$karyawan->bpjs_tenaker}}" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">No Rekening</label>
      <input type="text" name="no_rek" value="{{$karyawan->no_rek}}" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Bank Rekening</label>
      <input type="text" name="bank"  value="{{$karyawan->bank}}" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Status Pernikahan</label>
      <select  onchange="cekNikah(this)" name="sts_nikah" class="form-control">
        <option {{$karyawan->sts_nikah == 'BELUM MENIKAH'?'selected':'' }} value="BELUM MENIKAH">BELUM MENIKAH</option>
        <option {{$karyawan->sts_nikah == 'SUDAH MENIKAH'?'selected':'' }} value="SUDAH MENIKAH">SUDAH MENIKAH</option>
      </select>
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Reward</label>
      <textarea name="reward" class="form-control" cols="30" rows="5">{{$karyawan->reward}}</textarea>
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Punisment</label>
      <textarea name="punishment" class="form-control" cols="30" rows="5">{{$karyawan->punishment}}</textarea>
    </div>
    <div class="form-group col-md-6" @if($karyawan->sts_nikah == 'BELUM MENIKAH') style="display:none;" @endif id="ket_nikah">
      <label for="inputEmail4">Jumlah Anak</label>
      <input type="number"  value="{{$karyawan->jumlah_anak}}" name="jumlah_anak" class="form-control">
    </div>
    <div class="col-md-12 my-3">
      <div class="border border-secondary w-100"></div>
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Tanggal Masuk</label>
      <input type="date" value="{{date('Y-m-d', strtotime(now()) )}}" value="{{$karyawan->tgl_masuk}}" name="tgl_masuk" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Karyawan Resign</label>
      <select onchange="cekResign(this)" class="form-control">
        <option value="tidak"selected>Tidak</option>
        <option {{$karyawan->tgl_resign != null?'selected':'' }} value="ya">Ya</option>
      </select>
    </div>
    <div class="form-group col-md-6" @if($karyawan->tgl_resign == null) style="display:none;"@endif id="tgl_resign">
      <label for="inputEmail4">Tanggal Resign</label>
      <input type="date" value="{{$karyawan->tgl_resign}}" name="tgl_resign" class="form-control">
    </div>
    <div class="form-group col-md-6"@if($karyawan->tgl_resign == null) style="display:none;"@endif id="ket_resign">
      <label for="inputEmail4">Alasan resign</label>
      <input type="text" value="{{$karyawan->keterangan_resign}}" name="keterangan_resign" class="form-control">
    </div>

    <div class="col-12 mt-2">
      <button type="submit" class="btn btn-primary">Ubah</button>
    </div>
  </form>
</div>
</div>
<style scoped>
  .hover-left i{
    transition: 0.5s;
  }
  .hover-left:hover > i{
    padding-right: 15px;
    transition: 0.5s;
  }

</style>

<script>
  function cekResign(that) {
    if (that.value == 'ya') {
        document.getElementById("ket_resign").style.display = "block";
        document.getElementById("tgl_resign").style.display = "block";
    }
    else {
        document.getElementById("ket_resign").style.display = "none";
        document.getElementById("tgl_resign").style.display = "none";
    }
  }

  function cekNikah(a) {
    if (a.value == 'SUDAH MENIKAH') {
        document.getElementById("ket_nikah").style.display = "block";
    }
    else {
        document.getElementById("ket_nikah").style.display = "none";
    }
  }
</script>
@endsection
