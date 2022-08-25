@extends('layout.app')
@section('main-content')


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
        <form action="{{route('jamKerja.update', $dp->id)}}" method="post">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="inputEmail4">Nama Shift</label>
                <input type="text" name="nama_shift" value="{{$dp->nama_shift}}" class="form-control" id="inputEmail4">

                  <input type="hidden" name="id" value="{{$dp->id}}" class="form-control" id="inputEmail4">
              </div>
              <div class="form-group col-md-12">
                <label for="inputEmail4">Waktu Mulai</label>
                <input type="time" name="waktu_mulai" value="{{$dp->waktu_mulai}}" class="form-control" id="inputEmail4">
              </div>
              <div class="form-group col-md-12">
                <label for="inputEmail4">waktu Akhir</label>
                <input type="time" name="waktu_akhir" value="{{$dp->waktu_akhir}}" class="form-control" id="inputEmail4">
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
        <h1 class="h3 mb-0 text-gray-800">Data Dapartemen</h1>
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
                        <th>Nama Shift</th>
                        <th>Waktu Awal</th>
                        <th>Waktu Akhit</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                      <th>No</th>
                      <th>Nama Shift</th>
                      <th>Waktu Awal</th>
                      <th>Waktu Akhir</th>
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
			url: "{{ route('jamKerja.index') }}",
			// data: function (d) {
			// 	d.jenis = $('#jenis').val(),
			// 	d.search = $('input[type="search"]').val()
			// 	}
		},
		columns: [
				{data: 'DT_RowIndex', name: 'DT_Row_Index', orderable: false, searchable: false},
				{data: 'nama_shift', name: 'nama_shift'},
				{data: 'waktu_mulai', name: 'waktu_mulai'},
        {data: 'waktu_akhir', name: 'waktu_akhir'},
				{data: 'action', name: 'action', orderable: false, searchable: false},
		],
		});
</script>

@endsection
