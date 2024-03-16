<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otentikasi extends Model
{
    use HasFactory;
    protected $table = "otentikasi";

    protected $fillable = [
        'id_rekam',
        'tanggal_waktu',
        'foto',
        'suara',
        'video',
        'status_penerima',
        'status_otentikasi',
    ];

    public function toRekam(){
        return $this->belongsTo('App\Models\Rekam','id_rekam');
    }


}
