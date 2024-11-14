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
use App\Models\MasterJenjangSekolah;

class MasterJenjangSekolahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(request()->ajax())
        {
            $data = MasterJenjangSekolah::where('is_active', '1')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function($data){
                    $button_detail = '<button type="button" name="detail" id="'.$data->id.'" class="detail btn btn-icon waves-effect btn-success" title="Detail Data"><i class="fas fa-eye"></i></button>';
                    $button_edit = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-icon waves-effect btn-warning" title="Edit Data"><i class="fas fa-edit"></i></button>';
                    $button_delete = '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-icon waves-effect btn-danger" title="Delete Data"><i class="fas fa-trash"></i></button>';
                    $button  = $button_detail .' '. $button_edit . ' ' . $button_delete;
                    return $button;
                })
                ->editColumn('ikon_peta', function($data){
                    return '<img src="'.asset($data->ikon_peta).'" style="height:5rem">';
                })
                ->rawColumns(['aksi', 'ikon_peta'])
                ->make(true);
        }

        return view('admin.master-jenjang-sekolah.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $errors = Validator::make($request->all(), [
            'nama' => 'required',
            'ikon_peta' => 'required | mimes:png,jpg,jpeg,webp'
        ]);

        if($errors -> fails())
        {
            return response()->json(['errors' => $errors->errors()->all()]);
        }

        $ikonPetaExtension = $request->ikon_peta->extension();
        $ikonPetaName = uniqid().'.'.$ikonPetaExtension;
        $ikonPeta = Image::make($request->ikon_peta);
        $cropSize1 = public_path('images/ikon-peta/'.$ikonPetaName);
        $ikonPeta->save($cropSize1, 60);

        $master_jenjang_sekolah = new MasterJenjangSekolah;
        $master_jenjang_sekolah->nama = $request->nama;
        $master_jenjang_sekolah->ikon_peta = 'images/ikon-peta/'.$ikonPetaName;
        $master_jenjang_sekolah->save();

        return response()->json(['success' => 'Berhasil menambahkan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = MasterJenjangSekolah::find($id);
        $data->ikon_peta = asset($data->ikon_peta);
        return response()->json(['result' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = MasterJenjangSekolah::find($id);
        $data->ikon_peta = asset($data->ikon_peta);
        return response()->json(['result' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $errors = Validator::make($request->all(), [
            'nama' => 'required'
        ]);

        if($errors -> fails())
        {
            return response()->json(['errors' => $errors->errors()->all()]);
        }

        $master_jenjang_sekolah = MasterJenjangSekolah::find($request->hidden_id);
        $master_jenjang_sekolah->nama = $request->nama;
        if($request->ikon_peta)
        {
            File::delete(public_path($master_jenjang_sekolah->ikon_peta));

            $ikonPetaExtension = $request->ikon_peta->extension();
            $ikonPetaName = uniqid().'.'.$ikonPetaExtension;
            $ikonPeta = Image::make($request->ikon_peta);
            $cropSize1 = public_path('images/ikon-peta/'.$ikonPetaName);
            $ikonPeta->save($cropSize1, 60);

            $master_jenjang_sekolah->ikon_peta = 'images/ikon-peta/'.$ikonPetaName;
        }
        $master_jenjang_sekolah->save();

        return response()->json(['success' => 'Berhasil merubah!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = MasterJenjangSekolah::find($id);
        $data->is_active = '0';
        $data->save();
    }
}
