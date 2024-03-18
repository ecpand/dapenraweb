<?php

namespace App\Http\Controllers\Apk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use App\Models\Rekam;
use App\Models\Pegawai;
use App\Models\Jadwal;
use App\Models\Otentikasi;
use App\Models\Informasi;
use App\Models\Kontak;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;


class DapenraController extends Controller
{

  public function login(Request $request)
  {

    $validation = Validator::make($request->all(),[
      'nik' => 'required',
      'password' => 'required',
    ]);

    if($validation->fails()){
      return response()->json([
        'success' => false,
        'message' => "Ada Kesalahan",
        'data' => $validation->errors()
      ]);
    }

    $rekamlogin = Rekam::where('nik', $request->nik)->where('status_aktif',1)->get();
    $crekamlogin = count($rekamlogin);
    if($crekamlogin > 0){
      if (Hash::check($request->password, $rekamlogin[0]->password)) {
        $sukses['token'] = $rekamlogin[0]->createToken('auth_token')->plainTextToken;
        $sukses['nama'] = $rekamlogin[0]->nama_penerima;
        return response()->json([
          'success' => true,
          'message' => "Tersimpan",
          'data' => $sukses
        ]);
      } else {
        return response()->json([
            'success' => false,
            'message' => "Gagal login, cek kembali Password Anda."
        ]);
      }

    }else{
      return response()->json([
          'success' => false,
          'message' => "Gagal login, cek kembali NIP Anda."
      ]);
    }

  }

  public function logout(){
    echo "berhasil logout";
  }

  public function getOt(Request $request){
      $validation = Validator::make($request->all(),[
        'id_rekam' => 'required'
      ]);

      if($validation->fails()){
        return response()->json([
          'success' => false,
          'message' => "Ada Kesalahan",
          'data' => $validation->errors()
        ]);
      }

    $tanggals = date('Y-m-d');
    //echo $tanggals;
    $tanggal = Carbon::today()->toDateString();
    //echo $tanggal;
    $data = Jadwal::whereDate('tanggal_mulai', '<=', $tanggal)->whereDate('tanggal_akhir','>=', $tanggal)->take(10)->get();//Carbon::today()->toDateString())->get();

//whereDate('tanggal_mulai', '>=' , 2024-03-01)->whereDate('tanggal_akhir','<=', 2024-03-01)->get();


    //$data = Jadwal::where(function($query) use ($tanggals){
   //         $query->whereDate('tanggal_mulai', '>=', $tanggals);
    //})->get();

    //echo json_encode($data);
    $info = Informasi::whereIn('id_rekam', [0, $request->input('id_rekam')])->orderBy('id','DESC')->get();
    if(count($data) > 0){
        $id = $request->input('id_rekam');
        $tanggalmulai = $data[0]['tanggal_mulai'];
        $tanggalakhir = $data[0]['tanggal_akhir'];
        $dataotentikasisukses = Otentikasi::where('id_rekam',$id)->whereDate('tanggal_waktu','>=',$tanggalmulai)->whereDate('tanggal_waktu','<=', $tanggalakhir)->where('status_otentikasi',1)->get();
        if(count($dataotentikasisukses) > 0){
            return response()->json([
            'success' => true,
            'message' => "Status Otentikasi Sukses",
            'kode' => 1,
            'data' => "Anda Telah Melakukan Otentikasi",
            'informasi' => $info
          ]);
        }else{
            $dataotentikasigagal = Otentikasi::where('id_rekam',$id)->where('status_otentikasi',2)->take(5)->get();
          if(count($dataotentikasigagal) > 0){
              if(count($dataotentikasigagal) == 5){
                  return response()->json([
                'success' => true,
                'message' => "Status Otentikasi Gagal",
                'kode' => 5,
                'data' => $dataotentikasigagal,
                'informasi' => $info
            ]);
              }else{
                  return response()->json([
                'success' => true,
                'message' => "Status Otentikasi Gagal",
                'kode' => 2,
                'data' => $dataotentikasigagal,
                'informasi' => $info
            ]);
              }

    }else{
              return response()->json([
            'success' => true,
            'message' => "Informasi",
            'kode' => 3,
            'data' => "Silahkan Lakukan Otentikasi",
            'informasi' => $info
          ]);
          }
        }

    }else{
          return response()->json([
            'success' => true,
            'message' => "Informasi",
            'kode' => 4,
            'data' => "Sampai Ketemu pada Jadwal Otentikasi Selanjutnya",
            'informasi' => $info
          ]);
    }

  }

  public function informasi(Request $request){
        $validation = Validator::make($request->all(),[
          'id' => 'required'
        ]);

        if($validation->fails()){
          return response()->json([
            'success' => false,
            'message' => "Ada Kesalahan",
            'data' => $validation->errors()
          ]);
        }
      $info = Informasi::whereIn('id_rekam', [0, $request->input('id')])->get();

  }

  public function simpanRekam(Request $request){
      //$data = ["depan" => "aaa"];
      //Rekam::findOrFail(30)->update($data);
      $validation = Validator::make($request->all(),[
            'id' => 'required',
            'nik' => 'required',
            'photo1' => 'required|image|mimes:jpeg,png,jpg,gif', //|max:2048',
            'photo2' => 'required|image|mimes:jpeg,png,jpg,gif', //|max:2048',
            'photo3' => 'required|image|mimes:jpeg,png,jpg,gif', //|max:2048',
            //'photo4' => 'required|image|mimes:jpeg,png,jpg,gif',//|max:2048',
            //'photo5' => 'required|image|mimes:jpeg,png,jpg,gif',//|max:2048',
        ]);

        if($validation->fails()){
          return response()->json([
            'success' => false,
            'message' => "Ada Kesalahan",
            'data' => $validation->errors()
          ]);
        }
        $id = $request->input('id');
        $folder = $request->input('nik');
        //echo $request->file('photo1');

          if ($request->file('photo1')) {
              $depan = $request->file('photo1')->store('rekam/'.$folder, 'public');
          }

          if ($request->file('photo2')) {
              $kiri = $request->file('photo2')->store('rekam/'.$folder, 'public');
          }

          if ($request->file('photo3')) {
              $kanan = $request->file('photo3')->store('rekam/'.$folder, 'public');
          }


          $data = [
                "depan" => $depan,
                "kiri" => $kiri,
                "kanan" => $kanan,
                "atas" => "",
                "bawah" => "",
                "status" => 1
              ];
          $update = Rekam::findOrFail($id)->update($data);
  }

  public function simpanOt(Request $request){
      $tanggal = date('Y-m-d H:i:s');
      $validation = Validator::make($request->all(),[
            'id_rekam' => 'required',
            //'tanggal_waktu' => 'required',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif', //|max:2048',
            'status_penerima' => 'required',
            'status_otentikasi' => 'required'
        ]);

        if($validation->fails()){
          return response()->json([
            'success' => false,
            'message' => "Ada Kesalahan",
            'data' => $validation->errors()
          ]);
        }

        $folder = $request->input('id_rekam');
        if ($request->file('foto')) {
              $foto = $request->file('foto')->store('otentikasi/'.$folder, 'public');
        }

        $post = Otentikasi::create([
              'id_rekam' => $request->input('id_rekam'),
              'tanggal_waktu' => $tanggal,
              'foto' => $foto,
              'suara' => "",
              'status_penerima' => $request->input('status_penerima'),
              'status_otentikasi' => $request->input('status_otentikasi')
        ]);
  }

  public function simpanEx(Request $request){

      $validation = Validator::make($request->all(),[
            'id_rekam' => 'required',
            //'tanggal_waktu' => 'required',
            'video'  => 'required|mimes:mp4,mov,ogg,qt', //| max:20000',
            'status_penerima' => 'required',
            'status_otentikasi' => 'required'
        ]);

        if($validation->fails()){
          return response()->json([
            'success' => false,
            'message' => "Ada Kesalahan",
            'data' => $validation->errors()
          ]);
        }

        $tanggal = date('Y-m-d H:i:s');

        $folder = $request->input('id_rekam');

        if ($request->file('video')) {
              $video = $request->file('video')->store('otentikasi/'.$folder.'/Exception', 'public');
        }

        $post = Otentikasi::create([
              'id_rekam' => $request->input('id_rekam'),
              'tanggal_waktu' => $tanggal,
              'foto' => "",
              'suara' => $video,
              'status_penerima' => $request->input('status_penerima'),
              'status_otentikasi' => $request->input('status_otentikasi')
        ]);
  }

  public function getKontak(){
      $kontak =  Kontak::all();
      return $kontak;
  }



}
