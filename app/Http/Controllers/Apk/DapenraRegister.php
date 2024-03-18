<?php

namespace App\Http\Controllers\Apk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use App\Models\Rekam;
use App\Models\Pegawai;
use App\Models\Tertanggung;
use Illuminate\Support\Facades\Validator;



class DapenraRegister extends Controller
{

    public function registerpensiun(Request $request){
      $validation = Validator::make($request->all(),[
        'nik' => 'required',
        'nama_penerima' => 'required',
        'tempat_lahir' => 'required',
        'tanggal_lahir' => 'required',
        'alamat' => 'required',
        'telepon' => 'required',
        'telepon_kerabat' => 'required',
        'password' => 'required',
        'dokumen' => 'required',
      ]);

      if($validation->fails()){
        return response()->json([
          'success' => false,
          'message' => "Mohon Isi Data Secara Lengkap",
          'data' => $validation->errors()
        ]);
      }

      $users = Pegawai::where('nik', $request->input('nik'))->get();
      $count = $users->count();

      if($count > 0){
          $rekam = Rekam::where('id_pegawai',$users[0]->id)->get();
          $countrekam = $rekam->count();
          if($countrekam > 0){
            return response()->json([
              'success' => false,
              'message' => "Nip Telah Terdaftar, Silahkan Hubungi Administrator.",
              'data' => "Anda Telah Terdaftar"
            ]);
          }else{
            $idpegawai = $users[0]->id;
            $folder = $users[0]->nik;
            if ($request->file('dokumen')) {
                $dokumen = $request->file('dokumen')->store('dokumen/rekam/'.$folder, 'public');
            }
            $password = bcrypt($request->input('password'));
            $post = Rekam::create([
              'id_pegawai' => $idpegawai,
              'id_tertanggung' => 0,
              'nik' => $request->input('nik'),
              'nama_penerima' => $request->input('nama_penerima'),
              'jenis_manfaat' => 0,
              'tempat_lahir' => $request->input('tempat_lahir'),
              'tanggal_lahir' => $request->input('tanggal_lahir'),
              'alamat' => $request->input('alamat'),
              'telepon' => $request->input('telepon'),
              'telepon_kerabat' => $request->input('telepon_kerabat'),
              'password' => $password,
              'dokumen' => $dokumen,
              'status' => 0,
              'status_aktif' => 1
            ]);

            $sukses['token'] = $post->createToken('auth_token')->plainTextToken;
            $sukses['nama'] = $post->nama_penerima;
            // Berikan respons sukses
            return response()->json([
              'success' => true,
              'message' => "Tersimpan",
              'data' => $sukses
            ]);
          }


      }else{
        return response()->json([
          'success' => false,
          'message' => "NIP Tidak Ditemukan",
          'data' => "404"
        ]);
      }


    }

    public function registertertanggung(Request $request){
        $validation = Validator::make($request->all(),[
          'nik' => 'required',
          'nama_penerima' => 'required',
          'jenis_manfaat' => 'required',
          'tempat_lahir' => 'required',
          'tanggal_lahir' => 'required',
          'alamat' => 'required',
          'telepon' => 'required',
          'telepon_kerabat' => 'required',
          'password' => 'required',
          'dokumen' => 'required',
          'id_tertanggung' => 'required',
        ]);

        if($validation->fails()){
          return response()->json([
            'success' => false,
            'message' => "Mohon Isi Data Secara Lengkap",
            'data' => $validation->errors()
          ]);
        }

        $users = Pegawai::where('nik', $request->input('nik'))->get();
        $count = $users->count();

        if($count > 0){
            $rekam = Rekam::where('id_pegawai',$users[0]->id)->where('status_aktif',1)->get();
            $countrekam = $rekam->count();
            if($countrekam > 0){
              return response()->json([
                'success' => false,
                'message' => "Pengguna Belum dapat dialihkan kepada penerima manfaat, harap hub. Administrator",
                'data' => "Pengguna Belum dapat dialihkan kepada penerima manfaat, harap hub. Administrator"
              ]);
            }else{
                $ttanggung = Tertanggung::where('id', $request->id_tertanggung)->get();
                if(count($ttanggung) > 0){
                  $idpegawai = $users[0]->id;
                  $folder = $users[0]->nik;
                  if ($request->file('dokumen')) {
                      $dokumen = $request->file('dokumen')->store('dokumen/rekam/'.$folder, 'public');
                  }
                  $password = bcrypt($request->input('password'));
                  $niknya = $request->input('nik');
                  $idt = $ttanggung[0]->id;
                  $post = Rekam::create([
                    'id_pegawai' => $idpegawai,
                    'id_tertanggung' => $idt,
                    'nik' => $niknya,
                    'nama_penerima' => $request->input('nama_penerima'),
                    'jenis_manfaat' => $request->input('jenis_manfaat'),
                    'tempat_lahir' => $request->input('tempat_lahir'),
                    'tanggal_lahir' => $request->input('tanggal_lahir'),
                    'alamat' => $request->input('alamat'),
                    'telepon' => $request->input('telepon'),
                    'telepon_kerabat' => $request->input('telepon_kerabat'),
                    'password' => $password,
                    'dokumen' => $dokumen,
                    'status' => 0,
                    'status_aktif' => 1
                  ]);

                  $sukses['token'] = $post->createToken('auth_token')->plainTextToken;
                  $sukses['nama'] = $post->nama_penerima;
                  // Berikan respons sukses
                  return response()->json([
                    'success' => true,
                    'message' => "Tersimpan",
                    'data' => $sukses
                  ]);
                }
            }

            // Berikan respons sukses
            return response()->json('Sukses Menyimpan Data');

        }else{
          return response()->json([
            'success' => false,
            'message' => "NIP Tidak Ditemukan",
            'data' => "404"
          ]);
        }
    }

    public function getPensiun(Request $request){

      $validation = Validator::make($request->all(),[
        'nik' => 'required'
      ]);

      if($validation->fails()){
        return response()->json([
          'success' => false,
          'message' => "Ada Kesalahan",
          'data' => $validation->errors()
        ]);
      }

      $pensiun = Pegawai::where('nik', $request->input('nik'))->get();
      $count = $pensiun->count();
      if($count > 0){
        $tertanggung = Tertanggung::where('id_pegawai', $pensiun[0]->id)->get();
        $datatampil = [];
        $datat = [];
        foreach($tertanggung as $val){
            if($val->status != 1){
              $umur = date_diff(date_create($val->tanggal_lahir), date_create('today'))->y;

              if($umur < 25){
                  $data['id'] = $val->id;
                  $data['nama_tertanggung'] = $val->nama_tertanggung;
                  $data['jenis_manfaat'] = "anak";
                  $data['alamat'] = $val->alamat;
                  $data['tanggal_lahir'] = $val->tanggal_lahir;
                  $datat[] = $data;
              }
            }else{
                if($val->status == 1){
                    $data['id'] = $val->id;
                    $data['nama_tertanggung'] = $val->nama_tertanggung;
                    if($val->jenkel == "L"){
                        $data['jenis_manfaat'] = "duda";
                    }else{
                        $data['jenis_manfaat'] = "janda";
                    }
                    $data['alamat'] = $val->alamat;
                    $data['tanggal_lahir'] = $val->tanggal_lahir;
                    $datat[] = $data;
                }

            }



        }
        $datatampil['tertanggung']= $datat;
        $datatampil['pegawai'] = $pensiun;
        return response()->json([
          'success' => true,
          'message' => "NIP Pegawai Ditemukan",
          'data' => $datatampil
        ]);
      }else{
        return response()->json([
          'success' => false,
          'message' => "NIK Tidak Ditemukan",
          'data' => "404"
        ]);
      }

    }




}
