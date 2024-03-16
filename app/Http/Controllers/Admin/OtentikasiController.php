<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Otentikasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class OtentikasiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $otentikasi = Otentikasi::with('toRekam')->get();
            $otentikasi->map(function($rekam){
                $rekam->rekam_id = $rekam->toRekam["id"];
                $rekam->rekam_nip = $rekam->toRekam["nik"];
                $rekam->rekam_nama = $rekam->toRekam["nama_penerima"];
                $rekam->rekam_jenis = $rekam->toRekam["jenis_manfaat"];
                $rekam->rekam_tempat_lahir = $rekam->toRekam["tempat_lahir"];
                $rekam->rekam_tanggal_lahir = $rekam->toRekam["tanggal_lahir"];
                $rekam->rekam_alamat = $rekam->toRekam["alamat"];
                $rekam->rekam_telepon = $rekam->toRekam["telepon"];
                $rekam->rekam_teleponk = $rekam->toRekam["telepon_kerabat"];
                //$rekam->rekam_status = $rekam->toRekam["status_penerima"];
                //$rekam->rekam_statuso = $rekam->toRekam["status_otentikasi"];
                unset($rekam->toRekam);
            });
            //echo json_encode($otentikasi);
            return DataTables::of($otentikasi)->editColumn('tanggal_lahir', function ($otentikasi) {
                return Carbon::parse($otentikasi->tanggal_lahir)->isoFormat('D MMMM Y ');
            })->editColumn('status_penerima', function ($otentikasi) {
                if($otentikasi->status_penerima == 1){
                  return "Pegawai";
                }else{
                  return "Tertanggung";
                }
            })->editColumn('status_otentikasi', function ($otentikasi) {
                if($otentikasi->status_penerima == 1){
                  return "Berhasil";
                }else{
                  return "Gagal";
                }
            })->rawColumns(['tanggal_lahir'])->make(true);
        }



        return view('admin.otentikasi.index');
    }


}
