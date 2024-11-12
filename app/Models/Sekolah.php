<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    use HasFactory;

    public function provinsi()
    {
        return $this->belongsTo('App\Models\Wilayah', 'provinsi_id');
    }

    public function kabupaten()
    {
        return $this->belongsTo('App\Models\Wilayah', 'kabupaten_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo('App\Models\Wilayah', 'kecamatan_id');
    }

    public function kelurahan()
    {
        return $this->belongsTo('App\Models\Wilayah', 'kelurahan_id');
    }

    public function master_jenjang_sekolah()
    {
        return $this->belongsTo('App\Models\MasterJenjangSekolah', 'master_jenjang_sekolah_id');
    }

    public function master_kecepatan_internet()
    {
        return $this->belongsTo('App\Models\MasterKecepatanInternet', 'master_kecepatan_internet_id');
    }

    public function master_kurikulum()
    {
        return $this->belongsTo('App\Models\MasterKurikulum', 'master_kurikulum_id');
    }
}
