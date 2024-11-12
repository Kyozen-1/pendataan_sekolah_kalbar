<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKurikulum extends Model
{
    use HasFactory;

    public function sekolah()
    {
        return $this->hasMany('App\Models\Sekolah', 'master_kurikulum_id');
    }
}
