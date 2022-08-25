@extends('layout.app')
@section('main-content')

@foreach ($data2 as $dt)
  <div class="modal fade" id="data-{{$dt->id}}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="data-{{$dt->id}}Label" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="data-{{$dt->id}}Label">Tambah Admin</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{route('adminKaryawan.update', $dt->id)}}" method="POST">
        <div class="modal-body">
            @csrf
            @method('PUT')
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="inputEmail4">Nama Karyawan</label>
                <input type="text" readonly value="{{$dt->nama_lengkap}}" class="form-control" id="inputEmail4">
              </div>
              <div class="form-group col-md-12">
                <label for="inputEmail4">Hak Akses Karyawan</label>
                <select name="hak_akses" class="form-control" placeholder="Status Karyawan">
                  <option value=""></option>
                  <option value="1">Admin</option>
                  <option value="2">Operator</option>
                  <option value="3">Karyawan</option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Ubah</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endforeach

@foreach ($data as $dt)
  <div class="modal fade" id="admin-{{$dt->id}}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="admin-{{$dt->id}}Label" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="admin-{{$dt->id}}Label">Tambah Admin</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{route('adminKaryawan.update', $dt->id)}}" method="POST">
        <div class="modal-body">
            @csrf
            @method('PUT')
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="inputEmail4">Nama Karyawan</label>
                <input type="text" readonly value="{{$dt->nama_lengkap}}" class="form-control" id="inputEmail4">
              </div>
              <div class="form-group col-md-12">
                <label for="inputEmail4">Hak Akses Karyawan</label>
                <select name="hak_akses" class="form-control" placeholder="Status Karyawan">
                  <option value=""></option>
                  <option value="1">Admin</option>
                  <option value="2">Operator</option>
                  <option value="3">Karyawan</option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Ubah</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endforeach

<div class="container-fluid">
  <nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
      <a class="nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Data Admin</a>
      <a class="nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Tambah Admin</a>
    </div>
  </nav>
  <div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
      <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-4">
        <h1 class="h3 mb-0 text-gray-800">Data Admin</h1>
      </div>

        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable">
              <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
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
    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
      <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Admin</h1>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered" id="data">
          <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
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
  <!-- Page Heading -->

</div>

<script>
	var table = $('#dataTable').DataTable({
		processing: true,
		serverSide: true,
		responsive: true,
		autoWidth:false,
		ajax: {
			url: "{{ route('adminKaryawan.index') }}",
		},
		columns: [
        {data: 'DT_RowIndex', name: 'DT_Row_Index', orderable: false, searchable: false},
				{data: 'nama_lengkap', name: 'nama_lengkap'},
				{data: 'email', name: 'email'},
				{data: 'departement_id', name: 'departement_id'},
				{data: 'jabatan_id', name: 'jabatan_id'},
				{data: 'action', name: 'action', orderable: false, searchable: false},
		  ],
		});
</script>
<script>
	var table = $('#data').DataTable({
		processing: true,
		serverSide: true,
		responsive: true,
		autoWidth:false,
		ajax: {
			url: "{{ route('adminKaryawan.create') }}",
		},
		columns: [
        {data: 'DT_RowIndex', name: 'DT_Row_Index', orderable: false, searchable: false},
				{data: 'nama_lengkap', name: 'nama_lengkap'},
				{data: 'email', name: 'email'},
				{data: 'departement_id', name: 'departement_id'},
				{data: 'jabatan_id', name: 'jabatan_id'},
				{data: 'action', name: 'action', orderable: false, searchable: false},
		],
		});
</script>

@endsection
