@extends('layout.app')
@section('main-content')




      <h1>Data Karyawan</h1>

      <div class="list-data-karyawan">
        <div class="pengajuan-lembur">
          <h3>List Pengajuan Lembur Terbaru</h3>
          <table id="lembur" class="display nowrap" style="width:90%">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Karyawan</th>
                <th>Departmen</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Indrianto</td>
                <td>Keuangan</td>
                <td>Menunggu</td>
              </tr>
              <tr>
                <td>2</td>
                <td>Fitra</td>
                <td>Sumber Daya Manusia</td>
                <td>Menunggu</td>
              </tr>
              <tr>
                <td>3</td>
                <td>Devin Nuriansyah</td>
                <td>Managemen</td>
                <td>Menunggu</td>
              </tr>
            </tbody>
          </table>
          <button class="btn btn-success"><a href="/index.html">Lihat Semua</a></button>
        </div>
      </div>

  <script>
    $(document).ready(function () {
      $('#lembur').DataTable({
        scrollX: true,
      });
      $('#izin').DataTable({
        scrollX: true,
      });
    });
  </script>

@endsection
