<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKecepatanInternet extends Model
{
    use HasFactory;

    public function sekolah()
    {
        return $this->hasMany('App\Models\Sekolah', 'master_kecepatan_internet_id');
    }
}
