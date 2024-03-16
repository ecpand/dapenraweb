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


class PegawaiController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $pegawai = Pegawai::query();

            return DataTables::of($pegawai)->editColumn('tanggal_lahir', function ($pegawai) {
                return Carbon::parse($pegawai->tanggal_lahir)->isoFormat('D MMMM Y ');
            })->rawColumns(['tanggal_lahir'])->make(true);
        }

        return view('admin.pegawai.index');
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

        $pegawai = Pegawai::findOrFail($id);

        $pegawai->update($request->all());
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



    public function importdata(Request $request)
    {
      $file = $request->file('name');

      if ($file) {
          // Correct usage, $file is not null
          $filePath = $file->store('uploads');
          $rows = Excel::toCollection([], storage_path("app/$filePath"))[0];
          //echo $rows;
      } else {
          // Handle the case where the file is not present
      }

      $result = [];
      $result1 = [];
      $tampung = [];
      $header = null;

      foreach ($rows as $index => $row) {
          if (!$header) {
              $header = $row->toArray();
              continue;
          }else{
            $tampung[] = $row;
          }

          $result1[] = [
              'no_urut' => $row[14],
              'nama' => $row[15],
              'jenkel' => $row[16],
              'tanggal_lahir' => $row[17],
              'status' => $row[18],
          ];

          $key = null;
          foreach ($row as $index => $value) {
              if (strcasecmp($header[$index], 'NOKTP') === 0) {
                  $key = $value;
                  break;
              }
          }
          if (!$key) {
              continue;
          }
          if (!isset($result[$key])) {
              $result[$key] = $row->toArray();
          }
      }
      //echo json_encode($result);
      //echo json_encode(count($tampung));
      foreach($result as $key => $val){
        //echo " ".$key." ";

        $split = explode(" - ",$val[10]);
        //$tanggallahir = date('Y-m-d', strtotime($val['3']));
        $tanggallahir = Carbon::parse($val[3])->format('Y-m-d');
        //echo $tanggallahir;
        $record = new Pegawai();
        $record->noktp = $key;
        $record->npwp = "";
        $record->nik = $val[0];
        $record->nama = $val[1];
        $record->tempat_lahir = $val[2];
        $record->tanggal_lahir = $tanggallahir;
        $record->jenkel = $val[4];
        $record->stkwn = $val[5];
        $record->alamat = $val[6];
        $record->rtrw = $val[7];
        $record->kelurahan = $val[8];
        $record->kecamatan = $val[9];
        $record->kota = $split[0];
        $record->provinsi = $split[1];
        $record->kodepos = $val[11];
        $record->telepon = $val[12];
        $record->save();
        $insertedData = $record->id;
        //echo $insertedData;
        //echo json_encode($record);
        foreach($tampung as $valt){
          $tanggallahirt = Carbon::parse($valt[17])->format('Y-m-d');
          if($key == $valt[13]){
              //echo " ".$tanggallahirt." ";
              if(!empty($valt[15])){
                $recordt = new Tertanggung();
                $recordt->id_pegawai = $insertedData;
                $recordt->no_urut = $valt[14];
                $recordt->nama_tertanggung = $valt[15];
                $recordt->jenkel = $valt[16];
                $recordt->tempat_lahir = "";
                $recordt->tanggal_lahir = $tanggallahirt;
                $recordt->alamat = $val[6];
                $recordt->telepon = $val[12];
                $recordt->status = $valt[18];
                //echo $recordt;
                $recordt->save();
              }


          }
        }
      }

      Storage::delete($filePath);
      return redirect('admin/pegawai');

        //$rows = Excel::toCollection([], $tempFilePath)[0];
        /*
        $result = [];
        $result1 = [];
        $header = null;

        foreach ($rows as $index => $row) {
            if (!$header) {
                $header = $row->toArray();
                continue;
            }
            $result1[] = [
                'norut_kel' => $row[14],
                'namap_kel' => $row[15],
                'jnkel_kel' => $row[16],
                'tgl_lhr_kel' => $row[17],
                'stkel_kel' => $row[18],
            ];

            $key = null;
            foreach ($row as $index => $value) {
                if (strcasecmp($header[$index], 'NOKTP') === 0) {
                    $key = $value;
                    break;
                }
            }
            if (!$key) {
                continue;
            }
            if (!isset($result[$key])) {
                $result[$key] = $row->toArray();
            }
        }

        $resultCount = count($result);
        if (count($result1) < $resultCount) {
            $result1 = array_merge($result1, array_fill(0, $resultCount - count($result1), null));
        } elseif (count($result1) > $resultCount) {
            $result1 = array_slice($result1, 0, $resultCount);
        }

        $insertedData = [];
        foreach ($result as $key => $data) {
            $record = new Pegawai();
            $record->noktp = $key;
            $record->npwp = "";
            $record->nik = $data['0'];
            $record->nama = $data['1'];
            $record->tempat_lahir = $data['2'];
            $record->tanggal_lahir = $data['3'];
            $record->jenkel = $data['4'];
            $record->stkwn = $data['5'];
            $record->alamat = $data['6'];
            $record->rtrw = $data['7'];
            $record->kelurahan = $data['8'];
            $record->kecamatan = $data['9'];
            $record->kota = $data['10'];
            $record->kodepos = $data['11'];
            $record->telepon = $data['12'];
            $record->save();

            $insertedData[] = $record->id;
        }

        $dataToInsert = [];
        foreach ($result1 as $key => $data) {
            $dataToInsert[] = [
                'id_pegawai' => $insertedData[$key],
                'no_urut' => $data['norut_kel'],
                'nama_tertanggung' => $data['namap_kel'],
                'jenkel' => $data['jnkel_kel'],
                'tgl_lhr_kel' => $data['tgl_lhr_kel'],
                'stkel_kel' => $data['stkel_kel'],
            ];
        }

        Tertanggung::insert($dataToInsert);

        return response()->json($dataToInsert);*/
    }
}
