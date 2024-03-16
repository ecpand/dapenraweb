<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\PegawaiImport;
use App\Models\Pegawai;
use App\Models\Tertanggung;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
// use Excel;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;


class TertanggungController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $tertanggung = Tertanggung::with('toPegawai')->get();


            $tertanggung->map(function($pegawai){
                $pegawai->pegawai_id = $pegawai->toPegawai["id"];
                $pegawai->pegawai_nip = $pegawai->toPegawai["nik"];
                $pegawai->pegawai_nama = $pegawai->toPegawai["nama"];
                unset($pegawai->toPegawai);
            });
            //echo json_encode($tertanggung);
            return DataTables::of($tertanggung)->editColumn('tanggal_lahir', function ($tertanggung) {
                return Carbon::parse($tertanggung->tanggal_lahir)->isoFormat('D MMMM Y ');
            })->rawColumns(['tanggal_lahir'])->make(true);
        }

        return view('admin.tertanggung.index');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nik' => 'required|string',
            'noktp' => 'required|string',
            'nama' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required',
            'jenkel' => 'required|string',
            'alamat' => 'required',
            'rtrw' => 'required|string',
            'kelurahan' => 'required|string',
            'kecamatan' => 'required|string',
            'kota' => 'required|string',
            'provinsi' => 'required|string',
            'kodepos' => 'required|string',
            'telepon' => 'required|string',
        ]);

        Pegawai::create($request->all());
    }

    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return response()->json([
            "data" => $pegawai,
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama' => 'required|string',
            'tanggal_mulai' => 'required',
            'tanggal_akhir' => 'required',
        ]);

        $pegawai = Pegawai::findOrFail($id);

        $pegawai->update([
            "nama" => $request->input('nama'),
            "tanggal_mulai" => $request->input('tanggal_mulai'),
            "tanggal_akhir" => $request->input('tanggal_akhir'),
        ]);
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete();
    }

    public function show()
    {
        //$pegawai = Pegawai::limit(6);
        //echo json_encode($pegawai);
    }


}
