<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;
use DB;
use Validator;
use DataTables;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Crypt;

use App\Models\MasterKurikulum;
use App\Models\MasterJenjangSekolah;
use App\Models\MasterKecepatanInternet;
use App\Models\Sekolah;
use App\Models\Wilayah;

class SekolahController extends Controller
{
    public function index()
    {
        if(request()->ajax())
        {
            $data = new Sekolah;
            $data = $data->where('is_active', '1');
            $data = $data->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function($data){
                    $id = Crypt::encryptString($data->id);
                    $button_detail = '<button type="button" name="detail" id="'.$id.'" class="detail btn btn-icon waves-effect btn-info" title="Detail Data"><i class="fas fa-eye"></i></button>';
                    $button_edit = '<button type="button" name="edit" id="'.$id.'" class="edit btn btn-icon waves-effect btn-warning" title="Edit Data"><i class="fas fa-edit"></i></button>';
                    $button_delete = '<button type="button" name="delete" id="'.$id.'" class="delete btn btn-icon waves-effect btn-danger" title="Delete Data"><i class="fas fa-trash"></i></button>';
                    $button_melengkapi_data = '<button type="button" id="'.$id.'" class="btn-melengkapi-data btn btn-icon waves-effect waves-light btn-success" title="Lengkapi Data"><i class="far fa-check-square"></i></button>';

                    if($data->siaplah_sekolah_id)
                    {
                        if($data->status_kelengkapan_data == '1')
                        {
                            $button = $button_detail . ' ' . $button_delete;
                        } else {
                            $button = $button_melengkapi_data .' '. $button_detail . ' ' . $button_delete;
                        }
                    } else {
                        $button = $button_edit .' '. $button_detail . ' ' . $button_delete;
                    }

                    return $button;
                })
                ->editColumn('master_jenjang_sekolah_id', function($data){
                    return $data->master_jenjang_sekolah ? $data->master_jenjang_sekolah->nama : '';
                })
                ->editColumn('master_kurikulum_id', function($data){
                    return $data->master_kurikulum ? $data->master_kurikulum->nama : '';
                })
                ->editColumn('logo', function($data){
                    if($data->siaplah_sekolah_id)
                    {
                        $html = '<img src="'.$data->logo.'" style="height: 5rem;">';
                    } else {
                        $html = '<img src="'.asset($data->logo).'" style="height: 5rem;">';
                    }

                    return $html;
                })
                ->editColumn('provinsi_id', function($data){
                    return $data->provinsi ? $data->provinsi->nama : '';
                })
                ->editColumn('kabupaten_id', function($data){
                    return $data->kabupaten ? $data->kabupaten->nama : '';
                })
                ->editColumn('kecamatan_id', function($data){
                    return $data->kecamatan ? $data->kecamatan->nama : '';
                })
                ->editColumn('kelurahan_id', function($data){
                    return $data->kelurahan ? $data->kelurahan->nama : '';
                })
                ->editColumn('is_dummy', function($data){
                    if($data->is_dummy == '1')
                    {
                        return 'Sekolah Dummy';
                    } else {
                        return 'Sekolah Asli';
                    }
                })
                ->rawColumns(['aksi', 'logo'])
                ->make(true);
        }

        $masterJenjangSekolah = MasterJenjangSekolah::where('is_active', '1')->pluck('nama', 'id');
        $masterKurikulum = MasterKurikulum::where('is_active', '1')->pluck('nama', 'id');
        $masterKecepatanInternet = MasterKecepatanInternet::where('is_active', '1')->pluck('nama', 'id');
        $provinsis = Wilayah::whereRaw('LENGTH(kode) = 2')->pluck('nama', 'id');

        return view('admin.sekolah.index', [
            'masterJenjangSekolah' => $masterJenjangSekolah,
            'masterKurikulum' => $masterKurikulum,
            'masterKecepatanInternet' => $masterKecepatanInternet,
            'provinsis' => $provinsis
        ]);
    }

    public function getKabupaten(Request $request)
    {
        $wilayah = Wilayah::find($request->id);
        $kabupaten = Wilayah::where('kode', 'like', '%'.$wilayah->kode.'.%')
                        ->whereRaw('length(kode) = 5')
                        ->pluck('nama', 'id');
        return response()->json($kabupaten);
    }

    public function getKecamatan(Request $request)
    {
        $wilayah = Wilayah::find($request->id);
        $kecamatan = Wilayah::where('kode', 'like', '%'.$wilayah->kode.'.%')
                        ->whereRaw('length(kode) = 8')
                        ->pluck('nama', 'id');
        return response()->json($kecamatan);
    }

    public function getKelurahan(Request $request)
    {
        $wilayah = Wilayah::find($request->id);
        $kelurahan = Wilayah::where('kode', 'like', '%'.$wilayah->kode.'.%')
                        ->whereRaw('length(kode) = 13')
                        ->pluck('nama', 'id');
        return response()->json($kelurahan);
    }

    public function sinkroninasiDataSekolah()
    {
        $client = new Client([
            'base_uri' => env('URL_SIAPLAH'),
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => false
            ]
        ]);
        $apiUrl = 'api/sekolah';
        try {
            $datas = json_decode($client->get($apiUrl)->getBody(), true);
            foreach ($datas as $data) {
                $cekJenjangSekolah = MasterJenjangSekolah::where('nama', 'like', '%'.$data['jenjang_sekolah'].'%')->first();
                $cekKecepatanInternet = MasterKecepatanInternet::where('nama', 'like', '%'.$data['kecepatan_internet'].'%')->first();
                $cekKurikulum = MasterKurikulum::where('nama', 'like', '%'.$data['kurikulum'].'%')->first();

                $cekSekolah = Sekolah::where('siaplah_sekolah_id', $data['id'])->first();
                if($cekSekolah)
                {
                    $sekolah = Sekolah::find($cekSekolah->id);
                } else {
                    $sekolah = new Sekolah;
                }
                $sekolah->master_jenjang_sekolah_id = $cekJenjangSekolah ? $cekJenjangSekolah->id : '';
                $sekolah->nama = $data['nama'];
                $sekolah->npsn = $data['npsn'];
                $sekolah->email = $data['email'];
                $sekolah->status_sekolah = $data['status_sekolah'];
                $sekolah->status_internet = $data['status_internet'];
                $sekolah->jaringan_internet = $data['jaringan_internet'] == 'data seluler' ? 'data_seluler' : 'wifi';
                $sekolah->master_kecepatan_internet_id = $cekKecepatanInternet ? $cekKecepatanInternet->id : '';
                $sekolah->logo = $data['logo'];
                $sekolah->nama_kepsek = $data['nama_kepsek'];
                $sekolah->tanda_tangan_kepsek = $data['tanda_tangan_kepsek'];
                $sekolah->nip_kepsek = $data['nip_kepsek'];
                $sekolah->alamat = $data['alamat'];
                $sekolah->status_rawan_banjir = $data['rawan_banjir'];
                $sekolah->total_siswa = $data['total_siswa'];
                $sekolah->no_hp = $data['no_hp'];
                $sekolah->master_kurikulum_id = $cekKurikulum ? $cekKurikulum->id : '';
                $sekolah->kode_penerbitan = $data['kode_penerbitan'];
                $sekolah->kode_jenjang_pendidikan = $data['kode_jenjang_pendidikan'];
                $sekolah->lama_program_belajar_smk = $data['lama_program_belajar_smk'];
                $sekolah->akreditasi = $data['akreditasi'];
                $sekolah->berkas_sertifikat_akreditasi = $data['sertifikat_akreditasi'];
                $sekolah->is_dummy = $data['is_dummy'];
                $sekolah->is_active = $data['is_active'] == null || $data['is_active'] == '1' ? '1' : '0';
                $sekolah->siaplah_sekolah_id = $data['id'];
                $sekolah->save();
            }

            return response()->json(['success' => 'Berhasil Sinkronisasi Data']);
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        $errors = Validator::make($request->all(), [
            'nama' => 'required | max:255',
            'npsn' => 'required | max:255',
            'email' => 'required | max:255 | unique:sekolahs',
            'master_jenjang_sekolah_id' => 'required',
            'status_sekolah' => 'required',
            'master_kurikulum_id' => 'required',
            'akreditasi' => 'required',
            'total_siswa' => 'required',
            'nama_kepsek' => 'required',
            'nip_kepsek' => 'required',
            'no_hp' => 'required',
            'status_internet' => 'required',
            'jaringan_internet' => 'required',
            'master_kecepatan_internet_id' => 'required',
            'status_rawan_banjir' => 'required',
            'is_dummy' => 'required',
            'alamat' => 'required',
            'provinsi_id' => 'required',
            'kabupaten_id' => 'required',
            'kecamatan_id' => 'required',
            'kelurahan_id' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'logo' => 'required | mimes:png,jpg,jpeg,webp',
            'berkas_sertifikat_akreditasi' => 'required | mimes:png,jpg,jpeg,webp',
            'tanda_tangan_kepsek' => 'required | mimes:png,jpg,jpeg,webp',
            'kode_penerbitan' => 'required',
            'kode_jenjang_pendidikan' => 'required'
        ]);

        if($errors -> fails())
        {
            return response()->json(['errors' => $errors->errors()->all()]);
        }

        $logoExtension = $request->logo->extension();
        $logoName = uniqid().'.'.$logoExtension;
        $logo = Image::make($request->logo);
        $cropSize1 = public_path('images/sekolah/logo/'.$logoName);
        $logo->save($cropSize1, 60);

        $tandaTanganKepsekExtension = $request->tanda_tangan_kepsek->extension();
        $tandaTanganKepsekName = uniqid().'.'.$tandaTanganKepsekExtension;
        $tandaTanganKepsek = Image::make($request->tanda_tangan_kepsek);
        $cropSize2 = public_path('images/sekolah/tanda-tangan-kepsek/'.$tandaTanganKepsekName);
        $tandaTanganKepsek->save($cropSize2, 60);

        $berkasSertifikatAkreditasiExtension = $request->berkas_sertifikat_akreditasi->extension();
        $berkasSertifikatAkreditasiName = uniqid().'.'.$berkasSertifikatAkreditasiExtension;
        $berkasSertifikatAkreditasi = Image::make($request->berkas_sertifikat_akreditasi);
        $cropSize2 = public_path('images/sekolah/berkas-sertifikat-akreditasi/'.$berkasSertifikatAkreditasiName);
        $berkasSertifikatAkreditasi->save($cropSize2, 60);

        $sekolah = new Sekolah;
        $sekolah->master_jenjang_sekolah_id = $request->master_jenjang_sekolah_id;
        $sekolah->nama = $request->nama;
        $sekolah->npsn = $request->npsn;
        $sekolah->email = $request->email;
        $sekolah->status_sekolah = $request->status_sekolah;
        $sekolah->status_internet = $request->status_internet;
        $sekolah->jaringan_internet = $request->jaringan_internet;
        $sekolah->master_kecepatan_internet_id = $request->master_kecepatan_internet_id;
        $sekolah->logo = 'images/sekolah/logo/'.$logoName;
        $sekolah->nama_kepsek = $request->nama_kepsek;
        $sekolah->tanda_tangan_kepsek = 'images/sekolah/tanda-tangan-kepsek/'.$tandaTanganKepsekName;
        $sekolah->nip_kepsek = $request->nip_kepsek;
        $sekolah->alamat = $request->alamat;
        $sekolah->status_rawan_banjir = $request->status_rawan_banjir;
        $sekolah->total_siswa = $request->total_siswa;
        $sekolah->no_hp = $request->no_hp;
        $sekolah->master_kurikulum_id = $request->master_kurikulum_id;
        $sekolah->provinsi_id = $request->provinsi_id;
        $sekolah->kabupaten_id = $request->kabupaten_id;
        $sekolah->kecamatan_id = $request->kecamatan_id;
        $sekolah->kelurahan_id = $request->kelurahan_id;
        $sekolah->kode_penerbitan = $request->kode_penerbitan;
        $sekolah->kode_jenjang_pendidikan = $request->kode_jenjang_pendidikan;
        if($request->lama_program_belajar_smk)
        {
            $sekolah->lama_program_belajar_smk = $request->lama_program_belajar_smk;
        }
        $sekolah->akreditasi = $request->akreditasi;
        $sekolah->berkas_sertifikat_akreditasi = 'images/sekolah/berkas-sertifikat-akreditasi/'.$berkasSertifikatAkreditasiName;
        $sekolah->lng = $request->lng;
        $sekolah->lat = $request->lat;
        $sekolah->is_dummy = $request->is_dummy;
        $sekolah->is_active = '1';
        $sekolah->status_kelengkapan_data = '1';
        $sekolah->save();

        return response()->json(['success' => 'Sekolah ' .$request->nama. ' berhasil ditambahkan']);
    }

    public function lengkapiDataSekolahData($id)
    {
        $id = Crypt::decryptString($id);

        $sekolah = Sekolah::find($id);

        $data = [
            'id' => Crypt::encryptString($sekolah->id),
            'nama' => $sekolah->nama,
            'master_jenjang_sekolah_id' => $sekolah->master_jenjang_sekolah_id,
            'master_kurikulum_id' => $sekolah->master_kurikulum_id,
            'master_kecepatan_internet_id' => $sekolah->master_kecepatan_internet_id,
            'alamat' => $sekolah->alamat,
            'provinsi_id' => $sekolah->provinsi_id,
            'kabupaten_id' => $sekolah->kabupaten_id,
            'kecamatan_id' => $sekolah->kecamatan_id,
            'kelurahan_id' => $sekolah->kelurahan_id,
        ];

        return response()->json(['result' => $data]);
    }

    public function lengkapiDataSekolah(Request $request)
    {
        $errors = Validator::make($request->all(), [
            'lengkapi_data_master_jenjang_sekolah_id' => 'required ',
            'lengkapi_data_master_kurikulum_id' => 'required ',
            'lengkapi_data_master_kecepatan_internet_id' => 'required',
            'lengkapi_data_provinsi_id' => 'required',
            'lengkapi_data_kabupaten_id' => 'required',
            'lengkapi_data_kecamatan_id' => 'required',
            'lengkapi_data_kelurahan_id' => 'required',
            'lengkapi_data_lat' => 'required',
            'lengkapi_data_lng' => 'required',
            'lengkapi_data_hidden_id' => 'required'
        ]);

        if($errors -> fails())
        {
            return response()->json(['errors' => $errors->errors()->all()]);
        }

        try {
            $id = Crypt::decryptString($request->lengkapi_data_hidden_id);

            $sekolah = Sekolah::find($id);
            $sekolah->master_jenjang_sekolah_id = $request->lengkapi_data_master_jenjang_sekolah_id;
            $sekolah->master_kurikulum_id = $request->lengkapi_data_master_kurikulum_id;
            $sekolah->master_kecepatan_internet_id = $request->lengkapi_data_master_kecepatan_internet_id;
            $sekolah->provinsi_id = $request->lengkapi_data_provinsi_id;
            $sekolah->kabupaten_id = $request->lengkapi_data_kabupaten_id;
            $sekolah->kecamatan_id = $request->lengkapi_data_kecamatan_id;
            $sekolah->kelurahan_id = $request->lengkapi_data_kelurahan_id;
            $sekolah->lat = $request->lengkapi_data_lat;
            $sekolah->lng = $request->lengkapi_data_lng;
            $sekolah->status_kelengkapan_data = '1';
            $sekolah->save();

            return response()->json(['success' => 'Berhasil Melengkapi Data Sekolah']);
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()]);
        }
    }

    public function edit($id)
    {
        $id = Crypt::decryptString($id);
        $data = Sekolah::find($id);
        $data->zoom = $data->lat != null ? 15 : 5;
        $data->lat = $data->lat ? (float)$data->lat : (float)-2.500000;
        $data->lng = $data->lng ? (float)$data->lng : (float)118.000000;
        $data->logo = asset($data->logo);
        $data->tanda_tangan_kepsek = asset($data->tanda_tangan_kepsek);
        $data->berkas_sertifikat_akreditasi = asset($data->berkas_sertifikat_akreditasi);

        return response()->json(['result' => $data]);
    }

    public function update(Request $request)
    {
        $errors = Validator::make($request->all(), [
            'nama' => 'required | max:255',
            'npsn' => 'required | max:255',
            'master_jenjang_sekolah_id' => 'required',
            'status_sekolah' => 'required',
            'master_kurikulum_id' => 'required',
            'akreditasi' => 'required',
            'total_siswa' => 'required',
            'nama_kepsek' => 'required',
            'nip_kepsek' => 'required',
            'no_hp' => 'required',
            'status_internet' => 'required',
            'jaringan_internet' => 'required',
            'master_kecepatan_internet_id' => 'required',
            'status_rawan_banjir' => 'required',
            'is_dummy' => 'required',
            'alamat' => 'required',
            'provinsi_id' => 'required',
            'kabupaten_id' => 'required',
            'kecamatan_id' => 'required',
            'kelurahan_id' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'kode_penerbitan' => 'required',
            'kode_jenjang_pendidikan' => 'required'
        ]);

        if($errors -> fails())
        {
            return response()->json(['errors' => $errors->errors()->all()]);
        }

        if($request->logo)
        {
            $errors = Validator::make($request->all(), [
                'logo' => 'mimes:png,jpg,jpeg,webp',
            ]);

            if($errors -> fails())
            {
                return response()->json(['errors' => $errors->errors()->all()]);
            }
        }

        if($request->berkas_sertifikat_akreditasi)
        {
            $errors = Validator::make($request->all(), [
                'berkas_sertifikat_akreditasi' => 'mimes:png,jpg,jpeg,webp',
            ]);

            if($errors -> fails())
            {
                return response()->json(['errors' => $errors->errors()->all()]);
            }
        }

        if($request->tanda_tangan_kepsek)
        {
            $errors = Validator::make($request->all(), [
                'tanda_tangan_kepsek' => 'mimes:png,jpg,jpeg,webp',
            ]);

            if($errors -> fails())
            {
                return response()->json(['errors' => $errors->errors()->all()]);
            }
        }

        $id = Crypt::decryptString($request->hidden_id);

        $sekolah = Sekolah::find($id);
        $sekolah->master_jenjang_sekolah_id = $request->master_jenjang_sekolah_id;
        $sekolah->nama = $request->nama;
        $sekolah->npsn = $request->npsn;
        $sekolah->email = $request->email;
        $sekolah->status_sekolah = $request->status_sekolah;
        $sekolah->status_internet = $request->status_internet;
        $sekolah->jaringan_internet = $request->jaringan_internet;
        $sekolah->master_kecepatan_internet_id = $request->master_kecepatan_internet_id;

        if($request->logo)
        {
            File::delete(public_path($sekolah->logo));

            $logoExtension = $request->logo->extension();
            $logoName = uniqid().'.'.$logoExtension;
            $logo = Image::make($request->logo);
            $cropSize1 = public_path('images/sekolah/logo/'.$logoName);
            $logo->save($cropSize1, 60);

            $sekolah->logo = 'images/sekolah/logo/'.$logoName;
        }

        $sekolah->nama_kepsek = $request->nama_kepsek;

        if($request->tanda_tangan_kepsek)
        {
            File::delete(public_path($sekolah->tanda_tangan_kepsek));

            $tandaTanganKepsekExtension = $request->tanda_tangan_kepsek->extension();
            $tandaTanganKepsekName = uniqid().'.'.$tandaTanganKepsekExtension;
            $tandaTanganKepsek = Image::make($request->tanda_tangan_kepsek);
            $cropSize2 = public_path('images/sekolah/tanda-tangan-kepsek/'.$tandaTanganKepsekName);
            $tandaTanganKepsek->save($cropSize2, 60);

            $sekolah->tanda_tangan_kepsek = 'images/sekolah/tanda-tangan-kepsek/'.$tandaTanganKepsekName;
        }

        $sekolah->nip_kepsek = $request->nip_kepsek;
        $sekolah->alamat = $request->alamat;
        $sekolah->status_rawan_banjir = $request->status_rawan_banjir;
        $sekolah->total_siswa = $request->total_siswa;
        $sekolah->no_hp = $request->no_hp;
        $sekolah->master_kurikulum_id = $request->master_kurikulum_id;
        $sekolah->provinsi_id = $request->provinsi_id;
        $sekolah->kabupaten_id = $request->kabupaten_id;
        $sekolah->kecamatan_id = $request->kecamatan_id;
        $sekolah->kelurahan_id = $request->kelurahan_id;
        $sekolah->kode_penerbitan = $request->kode_penerbitan;
        $sekolah->kode_jenjang_pendidikan = $request->kode_jenjang_pendidikan;
        if($request->lama_program_belajar_smk)
        {
            $sekolah->lama_program_belajar_smk = $request->lama_program_belajar_smk;
        }
        $sekolah->akreditasi = $request->akreditasi;
        if($request->berkas_sertifikat_akreditasi)
        {
            File::delete(public_path($sekolah->berkas_sertifikat_akreditasi));

            $berkasSertifikatAkreditasiExtension = $request->berkas_sertifikat_akreditasi->extension();
            $berkasSertifikatAkreditasiName = uniqid().'.'.$berkasSertifikatAkreditasiExtension;
            $berkasSertifikatAkreditasi = Image::make($request->berkas_sertifikat_akreditasi);
            $cropSize2 = public_path('images/sekolah/berkas-sertifikat-akreditasi/'.$berkasSertifikatAkreditasiName);
            $berkasSertifikatAkreditasi->save($cropSize2, 60);

            $sekolah->berkas_sertifikat_akreditasi = 'images/sekolah/berkas-sertifikat-akreditasi/'.$berkasSertifikatAkreditasiName;
        }

        $sekolah->lng = $request->lng;
        $sekolah->lat = $request->lat;
        $sekolah->is_dummy = $request->is_dummy;
        $sekolah->is_active = '1';
        $sekolah->status_kelengkapan_data = '1';
        $sekolah->save();

        return response()->json(['success' => 'Sekolah ' .$request->nama. ' berhasil diubah']);
    }

    public function detail($id)
    {
        $id = Crypt::decryptString($id);
        $sekolah = Sekolah::find($id);
        $data = [
            'master_jenjang_sekolah' => $sekolah->master_jenjang_sekolah ? $sekolah->master_jenjang_sekolah->nama : '',
            'nama' => $sekolah->nama,
            'npsn' => $sekolah->npsn,
            'status_sekolah' => $sekolah->status_sekolah,
            'status_internet' => $sekolah->status_internet,
            'jaringan_internet' => $sekolah->jaringan_internet,
            'master_kecepatan_internet' => $sekolah->master_kecepatan_internet ? $sekolah->master_kecepatan_internet->nama : '',
            'logo' => $sekolah->siaplah_sekolah_id ? $sekolah->logo : asset($sekolah->logo),
            'nama_kepsek' => $sekolah->nama_kepsek,
            'tanda_tangan_kepsek' => $sekolah->siaplah_sekolah_id ? $sekolah->tanda_tangan_kepsek : asset($sekolah->tanda_tangan_kepsek),
            'nip_kepsek' => $sekolah->nip_kepsek,
            'alamat' => $sekolah->alamat,
            'status_rawan_banjir' => $sekolah->status_rawan_banjir,
            'total_siswa' => $sekolah->total_siswa,
            'no_hp' => $sekolah->no_hp,
            'master_kurikulum' => $sekolah->master_kurikulum ? $sekolah->master_kurikulum->nama : '',
            'provinsi' => $sekolah->provinsi ? $sekolah->provinsi->nama : '',
            'kabupaten' => $sekolah->kabupaten ? $sekolah->kabupaten->nama : '',
            'kecamatan' => $sekolah->kecamatan ? $sekolah->kecamatan->nama : '',
            'kelurahan' => $sekolah->kelurahan ? $sekolah->kelurahan->nama : '',
            'kode_penerbitan' => $sekolah->kode_penerbitan,
            'kode_jenjang_pendidikan' => $sekolah->kode_jenjang_pendidikan,
            'lama_program_belajar_smk' => $sekolah->lama_program_belajar_smk,
            'akreditasi' => $sekolah->akreditasi,
            'berkas_sertifikat_akreditasi' => $sekolah->siaplah_sekolah_id ? $sekolah->berkas_sertifikat_akreditasi : asset($sekolah->berkas_sertifikat_akreditasi),
            'lng' => (float) $sekolah->lng,
            'lat' => (float) $sekolah->lat,
            'is_dummy' => $sekolah->is_dummy == '1' ? 'Ya' : 'Tidak',
            ''
        ];

        return response()->json(['result' => $data]);
    }

    public function destroy($id)
    {
        try {
            $id = Crypt::decryptString($id);
            $sekolah = Sekolah::find($id);
            $sekolah->is_active = '0';
            $sekolah->save();
            return response()->json(['success' => 'Berhasil Menghapus Sekolah '. $sekolah->nama]);
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()]);
        }
    }
}
