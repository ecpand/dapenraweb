<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;
    protected $table = "pegawai";

    protected $fillable = [
        'nik',
        'noktp',
        'npwp',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jenkel',
        'alamat',
        'rtrw',
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
        'telepon',
        'kodepos',
        'stkwn'
    ];

    
}
