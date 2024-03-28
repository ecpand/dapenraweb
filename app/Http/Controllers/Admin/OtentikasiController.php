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
            })->editColumn('rekam_jenis', function ($otentikasi) {
                if($otentikasi->rekam_jenis == 0){
                  return "Pegawai";
                }else if($otentikasi->rekam_jenis == 1){
                  return "Janda";
                }else if($otentikasi->rekam_jenis == 2){
                  return "Duda";
                }else{
                  return "Anak";
                }
            })->editColumn('status_otentikasi', function ($otentikasi) {
                if($otentikasi->status_penerima == 1){
                  return "Berhasil";
                }else{
                  return "Gagal";
                }
            })->editColumn('foto', function ($otentikasi) {
                if ($otentikasi->foto) {
                    $image = asset("storage/" . $otentikasi->foto);
                    return '<img width="100px" height="100px" src="' . $image . '" alt="Foto Posisi Depan">';
                } else {
                  $image = asset("admin/assets/img/video.png");
                  $link = asset("storage/" . $otentikasi->suara);
                  return '<a href="'.$link.'" target=_blank><img width="50px" height="50px" src="' . $image . '" alt="Dokumen"></a>';
                }
            })->rawColumns(['foto'])->make(true);
        }



        return view('admin.otentikasi.index');
    }


}
