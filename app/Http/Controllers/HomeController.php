<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kontak;
use App\Models\Pegawai;
use App\Models\Tertanggung;
use App\Models\Rekam;
use Yajra\DataTables\DataTables;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $kontak = Kontak::query();
            return DataTables::of($kontak)->make(true);
        }

        $countpegawai = Pegawai::count();
        $counttertanggung = Tertanggung::count();
        $countrekam = Rekam::count();
        return view('home', compact(['countpegawai','counttertanggung','countrekam']));
    }

    public function edit($id)
    {
        $jadwal = Kontak::findOrFail($id);
        return response()->json([
            "data" => $jadwal,
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama' => 'required|string',
            'telepon' => 'required',
        ]);

        $kontak = Kontak::findOrFail($id);

        $kontak->update([
            "nama" => $request->input('nama'),
            "telepon" => $request->input('telepon'),
        ]);
    }




}
