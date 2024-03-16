<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use App\Models\Jadwal;
use App\Models\Rekam;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class InformasiController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $jadwal = Informasi::with('toRekam')->get();
            //echo json_encode($jadwal->to_rekam);

            $jadwal->map(function($rekam){
                //echo json_encode($rekam->toRekam);
                //$ada = !isset($rekam) ? 0 : count($rekam);

                // if(!isset($rekam)){
                  // $rekam->rekam = 0;
                  // $rekam->rekam_nip = "Semua";
                  // $rekam->rekam_nama = "Semua Pengguna";
                // }else{
                if(!isset($rekam->toRekam)){
                  $rekam->rekam = 0;
                  $rekam->rekam_nip = "Semua";
                  $rekam->rekam_nama = "Semua Pengguna";
                }else{
                  $rekam->rekam = $rekam->toRekam["id"];
                  $rekam->rekam_nip = $rekam->toRekam["nik"];
                  $rekam->rekam_nama = $rekam->toRekam["nama_penerima"];
                }
                unset($rekam->toRekam);

                //}

            });

            return DataTables::of($jadwal)->editColumn('tanggal_waktu', function ($jadwal) {
                return Carbon::parse($jadwal->tanggal_waktu)->isoFormat('D MMMM Y ');
            })->rawColumns(['tanggal_waktu'])->make(true);

        }


        $pegawai = Rekam::all();

        return view('admin.informasi.index', compact('pegawai'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_rekam' => 'required',
            'tanggal_waktu' => 'required',
            'pesan' => 'required',
        ]);

        Informasi::create($request->all());
        return redirect()->back();
    }

    public function edit($id)
    {
        $jadwal = Informasi::findOrFail($id);
        return response()->json([
            "data" => $jadwal,
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'id_rekam' => 'required|string',
            'tanggal_waktu' => 'required',
            'pesan' => 'required',
        ]);

        $jadwal = Informasi::findOrFail($id);

        $jadwal->update([
            "id_rekam" => $request->input('id_rekam'),
            "tanggal_waktu" => $request->input('tanggal_waktu'),
            "pesan" => $request->input('pesan'),
        ]);
    }

    public function destroy($id)
    {
        $jadwal = Informasi::findOrFail($id);
        $jadwal->delete();
    }
}
