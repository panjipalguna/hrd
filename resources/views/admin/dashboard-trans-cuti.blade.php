@extends('layout.app')
@section('main-content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<style>

.ui-autocomplete {
  z-index:2147483647;
}
</style>
<script type="text/javascript">
$( function() {
var availableTags = [

       <?php foreach ($karyawan as $key) {  ?>
           {
             label :'<?php echo $key->nama_lengkap ?>',
             id : '<?php echo $key->id ?>',
           },
     <?php } ?>
];

     $("#fooInput").autocomplete({
     source: availableTags,
     select: function(event, ui) {
       var e = ui.item;
       var result = "<p>label : " + e.label + " - id : " + e.id + "</p>";
       // $("#result").append(result);

    //   console.log(e.id);

       $('#karyawan_id').val(e.id);


     }
     });


     $("#fooInput2").autocomplete({
     source: availableTags,
     select: function(event, ui) {
       var e = ui.item;
       var result = "<p>label : " + e.label + " - id : " + e.id + "</p>";

       $('#karyawan_id2').val(e.id);

     }
     });

 });
</script>

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
              <label for="inputEmail4">Nama karyawan</label>
              <input type="text" name="nama_karyawan" class="form-control" id="fooInput">
              <input type="hidden" name="karyawan_id" class="form-control" id="karyawan_id">
            </div>

            <div class="form-group col-md-12">
              <label for="inputEmail4">Awal Cuti</label>
              <input type="date" name="awal_cuti" class="form-control" id="awal_cuti">
            </div>

            <div class="form-group col-md-12">
              <label for="inputEmail4">Akhir Cuti</label>
              <input type="date" name="akhir_cuti" class="form-control" id="akhir_cuti">
            </div>

            <div class="form-group col-md-12">
              <label for="inputEmail4">Durasi</label>
              <input type="number" name="jumlah" class="form-control" id="jumlah">
            </div>

            <div class="form-group col-md-12">
              <label for="inputEmail4">Periode Cuti</label>
              <select name="periode_cuti" class="form-control">
                @foreach($periode_cuti as $dt)
                  <option value="{{$dt->id}}">{{$dt->tahun}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group col-md-12">
              <label for="inputEmail4">Persetujuan Atasan</label>
              <select name="bool_persetujuan_atasan" class="form-control">
                  <option value=""> </option>
                  <option value="true"> DISETUJUI </option>
                  <option value="false"> TIDAK DISETUJUI  </option>
              </select>
            </div>

            <div class="form-group col-md-12">
              <label for="inputEmail4">Alamat Cuti</label>
              <input type="text" name="alamat_cuti" class="form-control" id="alamat_cuti">
            </div>

            <div class="form-group col-md-12">
              <label for="inputEmail4">Telp Cuti</label>
              <input type="text" name="telp_cuti" class="form-control" id="telp_cuti">
            </div>

            <div class="form-group col-md-12">
              <label for="inputEmail4">Keperluan</label>
              <input type="text" name="keperluan" class="form-control" id="keperluan">
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
        <form action="{{route('cuti.store')}}" method="post">
          @csrf

          <div class="modal-body">
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="inputEmail4">Nama karyawan</label>
                <input type="text" name="nama_karyawan" value="<?= $dp->karyawan->nama_lengkap; ?>" class="form-control" id="fooInput2">
                <input type="hidden" name="karyawan_id" class="form-control" value="<?= $dp->karyawan_id;  ?>" id="karyawan_id2">

                <input type="hidden" name="id" class="form-control" value="<?= $dp->id;  ?>">
              </div>

              <div class="form-group col-md-12">
                <label for="inputEmail4">Awal Cuti</label>
                <input type="date" name="awal_cuti" value="<?= $dp->awal_cuti; ?>" class="form-control" id="awal_cuti">
              </div>

              <div class="form-group col-md-12">
                <label for="inputEmail4">Akhir Cuti</label>
                <input type="date" name="akhir_cuti" value="<?= $dp->akhir_cuti; ?>" class="form-control" id="akhir_cuti">
              </div>

              <div class="form-group col-md-12">
                <label for="inputEmail4">Durasi</label>
                <input type="number" name="jumlah" value="<?= $dp->jumlah; ?>" class="form-control" id="jumlah">
              </div>

              <div class="form-group col-md-12">
                <label for="inputEmail4">Periode Cuti</label>
                <select name="periode_cuti" class="form-control">


                  @foreach($periode_cuti as $dt)
                    <option {{$dp->periode_cuti == $dt->id ? 'selected':''}} value="{{$dt->id}}">{{$dt->tahun}}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group col-md-12">
                <label for="inputEmail4">Persetujuan Atasan</label>
                <select name="bool_persetujuan_atasan" class="form-control">

                    <option value="true"> DISETUJUI </option>
                    <option value="false"> TIDAK DISETUJUI  </option>
                </select>
              </div>

              <div class="form-group col-md-12">
                <label for="inputEmail4">Alamat Cuti</label>
                <input type="text" name="alamat_cuti" value="<?= $dp->alamat_cuti; ?>" class="form-control" id="alamat_cuti">
              </div>

              <div class="form-group col-md-12">
                <label for="inputEmail4">Telp Cuti</label>
                <input type="text" name="telp_cuti" value="<?= $dp->telp_cuti; ?>" class="form-control" id="telp_cuti">
              </div>

              <div class="form-group col-md-12">
                <label for="inputEmail4">Keperluan</label>
                <input type="text" name="keperluan" value="<?= $dp->keperluan; ?>" class="form-control" id="keperluan">
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
