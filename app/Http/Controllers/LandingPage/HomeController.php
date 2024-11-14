<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Sekolah;

class HomeController extends Controller
{
    public function index()
    {
        return view('landing-page.home.index');
    }

    public function markerSekolah()
    {
        $sekolahs = Sekolah::where('is_dummy', '0')
                        ->where('is_active', '1')
                        ->where('status_kelengkapan_data', '1')
                        ->get();
        $datas = [];
        foreach ($sekolahs as $sekolah) {
            $alamat_lengkap = $sekolah->alamat. ', '.$sekolah->kelurahan->nama.', '.$sekolah->kecamatan->nama.', '.$sekolah->kabupaten->nama.', '.$sekolah->provinsi->nama;
            $datas[] = [
                'id' => Crypt::encryptString($sekolah->id),
                'nama' => $sekolah->nama,
                'alamat' => $alamat_lengkap,
                'logo' => $sekolah->siaplah_sekolah_id ? $sekolah->logo:asset($sekolah->logo),
                'ikon_peta' => $sekolah->master_jenjang_sekolah->ikon_peta,
                'lng' => (float)$sekolah->lng,
                'lat' => (float)$sekolah->lat
            ];
        }

        return response()->json($datas);
    }
}
