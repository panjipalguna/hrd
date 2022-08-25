<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\PeriodeGaji;
use App\Models\Departement;
use DataTables;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DetailAbsensiExport;

class MasterLaporanAbsenKaryawanController extends Controller
{
  public function index(Request $request, Karyawan $karyawan, PeriodeGaji $periodeGaji, Departement $departement, Absensi $absensi)
	{
		$data = $karyawan->latest();
		if ($request->ajax()) {
			return DataTables::of($data)
				->addIndexColumn()
				->addColumn('action', function ($cb) use ($request){
					$pd = PeriodeGaji::where('id',$request->get('periode'))->first();
					if ($cb->absensi->WhereBetween('tanggal',[$pd->mulai,$pd->selesai]) != "[]") {
						return '
						<a href="'.route('laporan-exel_detail', $cb->id).'?&period='.$request->get('periode').'" class="btn btn-success"><i class="fas fa-file-csv"></i></a>
						<a target="_blank" href="'.route('laporan-pdf_detail', $cb->id).'?&period='.$request->get('periode').'" class="btn btn-primary"><i class="fas fa-file-pdf"></i></a>
						';
					}
				})
				->editColumn('departement_id', function ($ab){
					return $ab->departement->nama_departement;
				})
				->editColumn('jabatan_id', function ($ab){
					return $ab->jabatan->nama_jabatan;
				})
				->filter(function ($instance) use ($request) {
					if ($request->get('dapartement')!=null) {
						$instance->where('departement_id',$request->get('dapartement'))->get();
					}

					if (!empty($request->get('search'))) {
						$instance
						->where('nama_lengkap', 'LIKE', ["%{$request->get('search')}%"])
						->get();
					}
				})
				->rawColumns(['action'])
				->make(true);
		}
		return view('admin.dashboard-laporan', compact('periodeGaji','departement'))->with(['cekNav'=>'laporan']);
	}

	public function pdfDetail(Request $request, Karyawan $karyawan, PeriodeGaji $periodeGaji, Absensi $absensi)
	{
		// $key = $request->period;
		// return $karyawan;

		$periodes = $request->period;
		$ky_id = $karyawan->id;
		$pd = $periodeGaji->find($periodes);
		// $ky= $karyawan->find($ky_id);

		if ($pd != null) {
			$abs = $absensi->where('karyawan_id',$ky_id)->WhereBetween('tanggal',[$pd->mulai,$pd->selesai])->get();
		}else {
			$abs = $absensi->where('karyawan_id',$ky_id)->get();
		}

			$data = [
				'period'=>$pd,
				'data' => $abs,
				'hadir' => $abs->where('status_absensi', 'H')->count(),
				'ijin' => $abs->where('status_absensi', 'I')->count(),
				'cuti' => $abs->where('status_absensi', 'C')->count(),
				'sakit' => $abs->where('status_absensi', 'S')->count(),
				'alpa' => $abs->where('status_absensi', 'A')->count(),
				'count_jam_pulang' => $abs->where('jam_pulang', '!=', null)->where('jam_pulang', '!=', 'Belum Absen Pulang')->count(),
				'count_jam_masuk' => $abs->where('jam_masuk', '!=', null)->count(),
			];

			$pdf = PDF::loadView('layout.pdf-absen', $data);

			return $pdf->stream('Detail-absen.pdf');
	}

	public function exelDetail(Request $request, Karyawan $karyawan)
	{
		// return $karyawan;
		if ($request->period == null) {
			$request['periode'] = 0;
		}
		return (new DetailAbsensiExport($karyawan->id,$request->period))->download('DetailAbsen.xlsx');
	}
}
