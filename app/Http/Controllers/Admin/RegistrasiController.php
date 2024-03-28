<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rekam;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class RegistrasiController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $registrasi = Rekam::query();

            return DataTables::of($registrasi)->editColumn('tanggal_lahir', function ($registrasi) {
                return Carbon::parse($registrasi->tanggal_lahir)->isoFormat('D MMMM Y ');
            })->editColumn('jenis_manfaat', function ($registrasi) {
                if($registrasi->jenis_manfaat == 0){
                  return "Pensiunan";
                }else if($registrasi->jenis_manfaat == 1){
                  return "Janda";
                }else if($registrasi->jenis_manfaat == 2){
                  return "Duda";
                }else{
                  return "Anak";
                }
            })->editColumn('depan', function ($registrasi) {
                if ($registrasi->depan) {
                    $image = asset("storage/" . $registrasi->depan);
                    return '<img width="100px" height="100px" src="' . $image . '" alt="Foto Posisi Depan">';
                } else {
                    return 'No picture';
                }
            })->editColumn('kiri', function ($registrasi) {
                if ($registrasi->kiri) {
                    $image = asset("storage/" . $registrasi->kiri);
                    return '<img width="100px" height="100px" src="' . $image . '" alt="Foto Posisi Kiri">';
                } else {
                    return 'No picture';
                }
            })->editColumn('kanan', function ($registrasi) {
                if ($registrasi->kanan) {
                    $image = asset("storage/" . $registrasi->kanan);
                    return '<img width="100px" height="100px" src="' . $image . '" alt="Foto Posisi Kanan">';
                } else {
                    return 'No picture';
                }
            })->editColumn('dokumen', function ($registrasi) {
                if ($registrasi->dokumen) {
                    $image = asset("admin/assets/img/pdf.png");
                    $link = asset("storage/" . $registrasi->dokumen);
                    return '<a href="'.$link.'" target=_blank><img width="50px" height="50px" src="' . $image . '" alt="Dokumen"></a>';
                } else {
                    return 'No picture';
                }
            })->rawColumns(['tanggal_lahir','jenis_manfaat','depan', 'kiri', 'kanan', 'dokumen'])->make(true);
        }

        return view('admin.registrasi.index');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|string',
            'tanggal_mulai' => 'required',
            'tanggal_akhir' => 'required',
        ]);

        Pegawai::create($request->all());
    }

    public function edit($id)
    {
        $registrasi = Pegawai::findOrFail($id);
        return response()->json([
            "data" => $registrasi,
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama' => 'required|string',
            'tanggal_mulai' => 'required',
            'tanggal_akhir' => 'required',
        ]);

        $registrasi = Pegawai::findOrFail($id);

        $registrasi->update([
            "nama" => $request->input('nama'),
            "tanggal_mulai" => $request->input('tanggal_mulai'),
            "tanggal_akhir" => $request->input('tanggal_akhir'),
        ]);
    }

    public function destroy($id)
    {
        $registrasi = Pegawai::findOrFail($id);
        $registrasi->delete();
    }

    public function resetwajah(Request $request){
      $id = $request->input('id');
      //echo $id;
      $data = [ "depan" => NULL,
                "kiri" => NULL,
                "kanan" => NULL,
                "atas" => NULL,
                "bawah" => NULL,
                "status" => 2];
      Rekam::findOrFail($id)->update($data);
    }

    public function konfirmasi(Request $request){
        $id = $request->input('id');
        echo $id;
        $data = ["status" => 3];
        Rekam::findOrFail($id)->update($data);
    }

    public function nonaktifkan(Request $request){
      $id = $request->input('id');
      //echo $id;
      $data = [ "status_aktif" => 2];
      Rekam::findOrFail($id)->update($data);
    }

}
