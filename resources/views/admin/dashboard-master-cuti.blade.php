@extends('layout.app')
@section('main-content')



<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Tambah Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{route('cuti.store')}}" method="post">
        @csrf
        <div class="modal-body">
          <div class="form-row">

            <div class="form-group col-md-12">
              <label for="inputEmail4">tahun</label>
              <input type="text" name="tahun" class="form-control" id="tahun">
            </div>

            <div class="form-group col-md-12">
              <label for="inputEmail4">Awal</label>
              <input type="date" name="awal" class="form-control" id="awal">
            </div>

            <div class="form-group col-md-12">
              <label for="inputEmail4">Akhir</label>
              <input type="date" name="akhir" class="form-control" id="akhir">
            </div>

            <div class="form-group col-md-12">
              <label for="inputEmail4">Jumlah</label>
              <input type="number" name="jumlah" class="form-control" id="jumlah">
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
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ubah-{{$dp->id}}Label">Ubah Data</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ url('cuti.store')}}" method="post">
          @csrf

          <div class="modal-body">
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="inputEmail4">tahun</label>
                <input type="text" name="tahun" value="<?= $dp->tahun ?>" class="form-control" id="tahun">
              </div>

              <div class="form-group col-md-12">
                <label for="inputEmail4">Awal</label>
                <input type="date" name="awal" value="<?= $dp->awal ?>" class="form-control" id="awal">
              </div>

              <div class="form-group col-md-12">
                <label for="inputEmail4">Akhir</label>
                <input type="date" name="akhir" value="<?= $dp->akhir ?>" class="form-control" id="akhir">
              </div>

              <div class="form-group col-md-12">
                <label for="inputEmail4">Jumlah</label>
                <input type="number" name="jumlah" value="<?= $dp->jumlah ?>" class="form-control" id="jumlah">
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

  <div class="list-data-karyawan">
    <div class="container-fluid">
      <!-- Page Heading -->
      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Cuti Karyawan</h1>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">
          Tambah
        </button>
        {{-- <button class="btn btn-primary">Tambah</button> --}}
      </div>

        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Karyawan</th>
                        <th>Awal Cuti</th>
                        <th>Akhir Cuti</th>
                        <th>Durasi</th>
                        <th>Disetujui</th>
                        <th>Tanggal Persetujuan</th>
                        <th>Keperluan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                      <th>No</th>
                      <th>Nama Karyawan</th>
                      <th>Awal Cuti</th>
                      <th>Akhir Cuti</th>
                      <th>Durasi</th>
                      <th>Disetujui</th>
                      <th>Tanggal Persetujuan</th>
                      <th>Keperluan</th>
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
			url: "{{ route('cuti.index') }}",
			// data: function (d) {
			// 	d.jenis = $('#jenis').val(),
			// 	d.search = $('input[type="search"]').val()
			// 	}
		},
		columns: [
				{data: 'DT_RowIndex', name: 'DT_Row_Index', orderable: false, searchable: false},
				{data: 'karyawan_id', name: 'karyawan_id'},
				{data: 'awal_cuti', name: 'awal_cuti'},
        {data: 'akhir_cuti', name: 'akhir_cuti'},
        {data: 'jumlah', name: 'jumlah'},
        {data: 'bool_persetujuan_atasan', name: 'bool_persetujuan_atasan'},
        {data: 'tgl_persetujuan_atasan', name: 'tgl_persetujuan_atasan'},
        {data: 'keperluan', name: 'keperluan'},
        //{data: 'sub_departement', name: 'sub_departement'},
				{data: 'action', name: 'action', orderable: false, searchable: false},
		],
		});
</script>

@endsection
