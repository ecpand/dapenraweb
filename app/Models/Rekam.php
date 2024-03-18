<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Rekam extends Model

{
    use HasApiTokens, HasFactory, Notifiable;
    //use HasFactory;//, HasApiTokens;
    protected $table = "rekam";

    protected $fillable = [
        'id_pegawai',
        'nik',
        'nama_penerima',
        'jenis_manfaat',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'telepon',
        'telepon_kerabat',
        'password',
        'depan',
        'kiri',
        'kanan',
        'atas',
        'bawah',
        'dokumen',
        'status',
        'status_aktif'
    ];


}
