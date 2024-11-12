<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;

    protected $table = 'wilayah';
    protected $guarded = 'id';

    public function sekolah_provinsi()
    {
        return $this->hasMany('App\Models\Sekolah', 'provinsi_id');
    }

    public function sekolah_kabupaten()
    {
        return $this->hasMany('App\Models\Sekolah', 'kabupaten_id');
    }

    public function sekolah_kecamatan()
    {
        return $this->hasMany('App\Models\Sekolah', 'kecamatan_id');
    }

    public function sekolah_kelurahan()
    {
        return $this->hasMany('App\Models\Sekolah', 'kelurahan_id');
    }
}
