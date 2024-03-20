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
            })->editColumn('status', function ($tertanggung) {
              if($tertanggung->status == 1){
                if($tertanggung->jenkel == "L"){
                    return "Suami";
                }else{
                    return "Istri";
                }
              }else{
                return "Anak";
              }

            })->rawColumns(['tanggal_lahir','status'])->make(true);
        }

        return view('admin.tertanggung.index');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nik' => 'required|string',
            'no_urut' => 'required',
            'nama' => 'required',
            'jenkel' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
            'status' => 'required',
        ]);

        $pegawai = Pegawai::where('nik',$request->nik)->get();
        $id = $pegawai[0]->id;
        $data = array(
          "id_pegawai" => $id,
          "no_urut" => $request->input('no_urut'),
          "nama_tertanggung" => $request->input('nama'),
          "jenkel" => $request->input('jenkel'),
          "tempat_lahir" => $request->input('tempat_lahir'),
          "tanggal_lahir" => $request->input('tanggal_lahir'),
          "alamat" => $request->input('alamat'),
          "telepon" => $request->input('telepon'),
          "status" => $request->input('status')
        );

        Tertanggung::create($data);
    }

    public function edit($id)
    {
      $pegawai = Tertanggung::findOrFail($id);
      return response()->json([
          "data" => $pegawai,
          "nik" => $pegawai->nik,
      ]);
    }

    public function update(Request $request, $id)
    {
      $this->validate($request, [
          'no_urut' => 'required',
          'nama' => 'required',
          'jenkel' => 'required',
          'tempat_lahir' => 'required',
          'tanggal_lahir' => 'required',
          'alamat' => 'required',
          'telepon' => 'required',
          'status' => 'required',
      ]);

      $pegawai = Tertanggung::findOrFail($request->input('id'));

      $pegawai->update([
          "no_urut" => $request->input('no_urut'),
          "nama_tertanggung" => $request->input('nama'),
          "jenkel" => $request->input('jenkel'),
          "tempat_lahir" => $request->input('tempat_lahir'),
          "tanggal_lahir" => $request->input('tanggal_lahir'),
          "alamat" => $request->input('alamat'),
          "telepon" => $request->input('telepon'),
          "status" => $request->input('status'),
      ]);
    }

    public function destroy($id)
    {
        $pegawai = Tertanggung::findOrFail($id);
        $pegawai->delete();
    }

    public function show()
    {
        //$pegawai = Pegawai::limit(6);
        //echo json_encode($pegawai);
    }


}
