@extends('layout.app')
@section('main-content')

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Tambah Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{route('karyawan.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="inputEmail4">Nama Lengkap</label>
              <input type="text" name="nama_lengkap" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">NIK</label>
              <input type="text" name="nik" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">No KK</label>
              <input type="number" name="no_kk" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">NPWP</label>
              <input type="text" name="npwp" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">BPJS Kesehatan</label>
              <input type="text" name="bpjs_kesehatan" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">BPJS Tenaga Kerja</label>
              <input type="text" name="bpjs_tenaker" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">Jenis Kelamin</label>
              <select name="jk" class="form-control">
                <option value="Laki Laki">Laki Laki</option>
                <option value="Perempuan">Perempuan</option>
                <option value="Rahasia">Rahasia</option>
              </select>
              {{-- <input type="text" value="{{$dp->jk}}" name="jk" class="form-control"> --}}
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">Golongan Darah</label>
              <input type="text"  name="gd" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">Tempat Lahir</label>
              <input type="text" name="tmp_lahir" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">Tanggal Lahir</label>
              <input type="date" name="tgl_lahir" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">Status Pernikahan</label>
              <select name="sts_nikah" class="form-control">
                <option value="BELUM MENIKAH">BELUM MENIKAH</option>
                <option value="SUDAH MENIKAH">SUDAH MENIKAH</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">Alamat Asal</label>
              <input type="text" name="alamat_asal" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">Alamat Domisili</label>
              <input type="text" name="alamat_domisili" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">Nomer Telpon</label>
              <input type="text" name="no_telp" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">Nama Ayah</label>
              <input type="text" name="nama_ayah" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">Nama Ibu</label>
              <input type="text" name="nama_ibu" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">Status Kerja</label>
              <select name="sts_kerja" class="form-control">
                <option value="A">A - Aktif</option>
                <option value="R">R - Risign</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">Tanggal Masuk</label>
              <input type="date" name="tgl_masuk" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">No Rekening</label>
              <input type="text" name="no_rek" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">Bank Rekening</label>
              <input type="text" name="bank" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">Tanggal Resign</label>
              <input type="date" name="tgl_resign" class="form-control">
            </div>
            <!-- <div class="form-group col-md-6">
              <label for="inputEmail4">Jabatan Sekarang</label>
              <input type="text" name="jabatan_skr" class="form-control">
            </div> -->
            <div class="form-group col-md-6">
              <label for="inputEmail4">Profile Karyawan</label>
              <input type="file" name="image" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">Email</label>
              <input type="email" name="email" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">Password</label>
              <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">Status Kariyawan</label>
              <select name="sts_karyawan" class="form-control">
                <option value="T">T - Tetap</option>
                <option value="K">K - Kontrak</option>
                <option value="M">M - Magang</option>
                <option value="P">P - Percobaan</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">Hak Akses</label>
              <select name="hak_akses" class="form-control">
                <option value="1">Administrator</option>
                <option value="2">Operator</option>
                <option selected value="3">Karyawan</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">Agama</label>
              <select name="agama_id" class="form-control">
                @foreach ($agama->get() as $agm)
                  <option value="{{$agm->id}}">{{$agm->nama_agama}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">Jabatan</label>
              <select name="jabatan_id" class="form-control">
                @foreach ($jabatan->get() as $jb)
                  <option value="{{$jb->id}}">{{$jb->nama_jabatan}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">Dapartement</label>
              <select name="departement_id" class="form-control">
                @foreach ($departement->get() as $dp)
                  <option value="{{$dp->id}}">{{$dp->nama_departement}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail4">Pendidikan</label>
              <select name="pendidikan_id" class="form-control">
                @foreach ($pendidikan->get() as $pd)
                  <option value="{{$pd->id}}">{{$pd->nama_pendidkan}}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="riset" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>

@foreach ($data as $dp)
  <div class="modal fade" id="ubah-{{$dp->id}}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="ubah-{{$dp->id}}Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ubah-{{$dp->id}}Label">Ubah Data</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{route('karyawan.update', $dp->id)}}" method="post" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="inputEmail4">Nama Lengkap</label>
                <input type="text" value="{{$dp->nama_lengkap}}" name="nama_lengkap" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">NIK</label>
                <input type="text" value="{{$dp->nik}}" name="nik" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">No KK</label>
                <input type="number" value="{{$dp->no_kk}}" name="no_kk" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">NPWP</label>
                <input type="text" value="{{$dp->npwp}}" name="npwp" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">BPJS Kesehatan</label>
                <input type="text" value="{{$dp->bpjs_kesehatan}}" name="bpjs_kesehatan" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">BPJS Tenaga Kerja</label>
                <input type="text" value="{{$dp->bpjs_tenaker}}" name="bpjs_tenaker" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">Jenis Kelamin</label>
                <select name="jk" class="form-control">
                  <option {{$dp->jk == 'Laki Laki'?'selected':''}} value="Laki Laki">Laki Laki</option>
                  <option {{$dp->jk == 'Perempuan'?'selected':''}} value="Perempuan">Perempuan</option>
                  <option {{$dp->jk == 'Rahasia'?'selected':''}} value="Rahasia">Rahasia</option>
                </select>
                {{-- <input type="text" value="{{$dp->jk}}" name="jk" class="form-control"> --}}
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">Golongan Darah</label>
                <input type="text" value="{{$dp->gd}}" name="gd" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">Tempat Lahir</label>
                <input type="text" value="{{$dp->tmp_lahir}}" name="tmp_lahir" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">Tanggal Lahir</label>
                <input type="date" value="{{$dp->tgl_lahir}}" name="tgl_lahir" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">Status Pernikahan</label>
                <select name="sts_nikah" class="form-control">
                  <option {{$dp->sts_nikah == 'BELUM MENIKAH'?'selected':''}} value="BELUM MENIKAH">BELUM MENIKAH</option>
                  <option {{$dp->sts_nikah == 'SUDAH MENIKAH'?'selected':''}} value="SUDAH MENIKAH">SUDAH MENIKAH</option>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">Alamat Asal</label>
                <input type="text" value="{{$dp->alamat_asal}}" name="alamat_asal" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">Alamat Domisili</label>
                <input type="text" value="{{$dp->alamat_domisili}}" name="alamat_domisili" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">Nomer Telpon</label>
                <input type="text" value="{{$dp->no_telp}}" name="no_telp" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">Nama Ayah</label>
                <input type="text" value="{{$dp->nama_ayah}}" name="nama_ayah" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">Nama Ibu</label>
                <input type="text" value="{{$dp->nama_ibu}}" name="nama_ibu" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">Status Kerja</label>
                <select name="sts_kerja" class="form-control">
                  <option {{$dp->sts_kerja == 'A'?'selected':''}} value="A">A - Aktif</option>
                  <option {{$dp->sts_kerja == 'R'?'selected':''}} value="R">R - Risign</option>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">Tanggal Masuk</label>
                <input type="date" value="{{$dp->tgl_masuk}}" name="tgl_masuk" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">No Rekening</label>
                <input type="text" value="{{$dp->no_rek}}" name="no_rek" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">Bank Rekening</label>
                <input type="text" value="{{$dp->bank}}" name="bank" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">Tanggal Resign</label>
                <input type="date" value="{{$dp->tgl_resign}}" name="tgl_resign" class="form-control">
              </div>
              <!-- <div class="form-group col-md-6">
                <label for="inputEmail4">Jabatan Sekarang</label>
                <input type="text" value="{{$dp->jabatan_skr}}" name="jabatan_skr" class="form-control">
              </div> -->
              <div class="form-group col-md-6">
                <label for="inputEmail4">Profile Karyawan</label>
                <input type="file" name="image" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">Email</label>
                <input type="email" readonly  value="{{$dp->email}}" name="email" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">Password</label>
                <input type="password" name="password" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">Status Kariyawan</label>
                <select name="sts_karyawan" class="form-control">
                  <option {{$dp->sts_karyawan == 'T'?'selected':''}} value="T">T - Tetap</option>
                  <option {{$dp->sts_karyawan == 'K'?'selected':''}} value="K">K - Kontrak</option>
                  <option {{$dp->sts_karyawan == 'M'?'selected':''}} value="M">M - Magang</option>
                  <option {{$dp->sts_karyawan == 'P'?'selected':''}} value="P">P - Percobaan</option>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">Hak Akses</label>
                <select name="hak_akses" class="form-control">
                  <option {{$dp->hak_akses == '1'?'selected':''}} value="1">Administrator</option>
                  <option {{$dp->hak_akses == '2'?'selected':''}} value="2">Operator</option>
                  <option {{$dp->hak_akses == '2'?'selected':''}} value="3">Karyawan</option>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">Agama</label>
                <select name="agama_id" class="form-control">
                  @foreach ($agama->get() as $agm)
                    <option {{$dp->agama_id == $agm->id?'selected':''}} value="{{$agm->id}}">{{$agm->nama_agama}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">Jabatan</label>
                <select name="jabatan_id" class="form-control">
                  @foreach ($jabatan->get() as $jb)
                    <option {{$dp->jabatan_id == $jb->id?'selected':''}} value="{{$jb->id}}">{{$jb->nama_jabatan}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">Dapartement</label>
                <select name="departement_id" class="form-control">
                  @foreach ($departement->get() as $dpt)
                    <option {{$dp->departement_id == $dpt->id?'selected':''}} value="{{$dpt->id}}">{{$dpt->nama_departement}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">Pendidikan</label>
                <select name="pendidikan_id" class="form-control">
                  @foreach ($pendidikan->get() as $pd)
                    <option {{$dp->pendidikan_id == $pd->id?'selected':''}} value="{{$pd->id}}">{{$pd->nama_pendidkan}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="inputEmail4">Group Jadwal Karyawan</label>
                <input type="text" class="form-control" readonly
                @if ($dp->groupJadwal !='')
                  value="{{$dp->groupJadwal->nama_grup}}"
                @endif
                >
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="riset" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Ubah</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endforeach


@foreach($data as $pfl)
<div class="modal fade" id="profile-{{$pfl->id}}" tabindex="-1" aria-labelledby="profile-{{$pfl->id}}Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="profile-{{$pfl->id}}Label">Profile Karyawan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img style="width:100%;" src="{{$pfl->foto}}" alt="" srcset="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endforeach
  <div class="list-data-karyawan">
      <div class="container-fluid">
  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Karyawan</h1>
    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">
      Tambah
    </button> -->
    <a href="{{route('karyawan.create')}}" class="btn btn-primary text-light">Tambah</a>
  </div>

    <div class="table-responsive">
        <table class="table table-bordered" id="dataTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Profile</th>
                    <th>Dapartement</th>
                    <th>Jabatan</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                  <th>No</th>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Profile</th>
                  <th>Dapartement</th>
                  <th>Jabatan</th>
                  <th>Action</th>
                </tr>
            </tfoot>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
</div>
<script>
	var table = $('#dataTable').DataTable({
		processing: true,
		serverSide: true,
		responsive: true,
		autoWidth:false,
		ajax: {
			url: "{{ route('karyawan.index') }}",
		},
		columns: [
				{data: 'DT_RowIndex', name: 'DT_Row_Index', orderable: false, searchable: false},
				{data: 'nama_lengkap', name: 'nama_lengkap'},
				{data: 'email', name: 'email'},
				{data: 'profile', name: 'profile'},
				{data: 'departement_id', name: 'departement_id'},
				{data: 'jabatan_id', name: 'jabatan_id'},
				{data: 'action', name: 'action', orderable: false, searchable: false},
		],
		});
</script>

@endsection
