<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterJenjangSekolah extends Model
{
    use HasFactory;

    public function sekolah()
    {
        return $this->hasMany('App\Models\Sekolah', 'master_jenjang_sekolah_id');
    }
}
