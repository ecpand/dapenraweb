<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tertanggung extends Model
{
    use HasFactory;
    protected $table = "tertanggung";

    protected $fillable = [
        'id_pegawai',
        'no_urut',
        'nama_tertanggung',
        'jenkel',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'telepon',
        'status',
    ];

    public function toPegawai(){
        return $this->belongsTo( 'App\Models\Pegawai','id_pegawai');
    }

    public function getNikAttribute()
    {
        return $this->toPegawai->nik;
    }
}
