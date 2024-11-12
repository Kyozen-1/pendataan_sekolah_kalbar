@extends('admin.layouts.app')
@section('title', 'Admin | Sekolah')
@section('subheader', 'Sekolah')

@section('css')
    <link href="{{ asset('/adminto/assets/libs/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/adminto/assets/libs/datatables/responsive.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/adminto/assets/libs/datatables/buttons.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/adminto/assets/libs/datatables/select.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/adminto/assets/libs/custombox/custombox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/adminto/assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <style>
        .table th {
            text-align: center;
        }
        .table td {
            justify-content: center;
            text-align: center;
        }
        .select2-selection__rendered {
            line-height: 40px !important;
        }
        .select2-container .select2-selection--single {
            height: 41px !important;
        }
        .select2-selection__arrow {
            height: 36px !important;
        }
        #map {
            height: 400px;
            width: 100%;
        }
        #lengkapi_data_map {
            height: 400px;
            width: 100%;
        }
        /* Style for the search input */
        .map-search-container {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 5;
            width: 300px;
        }

        #location-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #d4d4d4;
            border-radius: 4px;
            font-size: 16px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }

        #lengkapi_data_location-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #d4d4d4;
            border-radius: 4px;
            font-size: 16px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }

        /* Autocomplete results */
        .autocomplete-list {
            position: absolute;
            top: 45px;
            left: 0;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            z-index: 10;
            border: 1px solid #d4d4d4;
            background-color: #fff;
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .autocomplete-item {
            padding: 10px;
            cursor: pointer;
            background-color: #fff;
        }

        .autocomplete-item:hover {
            background-color: #e9e9e9;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box table-responsive">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <h4 class="mt-0 header-title">Tabel Data</h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-icon waves-effect waves-light btn-success mr-1" id="btn_sinkronisasi" title="Sinkronisasi Data Sekolah">
                            <i class="fas fa-recycle"></i>
                        </button>
                        <button class="btn btn-icon waves-effect waves-light btn-primary" data-toggle="modal" data-target="#createModal" id="create" name="create" title="Tambah Data Sekolah">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <table id="sekolah_table" class="table table-bordered table-bordered dt-responsive nowrap">
                    <thead>
                        <tr>
                            <th width="15%">Aksi</th>
                            <th width="5%">No</th>
                            <th>Jenjang</th>
                            <th>Nama</th>
                            <th>NPSN</th>
                            <th>Status</th>
                            <th>Kurikulum</th>
                            <th>Logo</th>
                            <th>Total Siswa</th>
                            <th>Provinsi</th>
                            <th>Kabupaten/Kota</th>
                            <th>Kecamatan</th>
                            <th>Kelurahan/Desa</th>
                            <th>Alamat</th>
                            <th>Status Dummy</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div id="createModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="createModalLabel">Tambah Sekolah</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <span id="form_result"></span>
                    <form class="form-horizontal" id="sekolah_form" method="POST" data-parsley-validate novalidate enctype="multipart/form-data">
                        @csrf
                        <h4 class="text-primary">Data Sekolah</h4>
                        <hr>
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="nama" class="control-label">Nama Sekolah<span class="text-danger">*</span></label>
                                    <input type="text" name="nama" id="nama" parsley-trigger="change" required
                                    placeholder="Masukan nama sekolah..." class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-md-4" id="form_npsn">
                                <div class="form-group">
                                    <label for="npsn" class="control-label">NPSN</label>
                                    <input type="text" name="npsn" id="npsn" parsley-trigger="change" required
                                    placeholder="Masukan npsn..." class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="master_jenjang_sekolah_id" class="control-label">Jenjang Sekolah<span class="text-danger">*</span></label>
                                    <select name="master_jenjang_sekolah_id" id="master_jenjang_sekolah_id" class="form-control" parsley-trigger="change" required>
                                        <option value="">--- Pilih Jenjang Sekolah ---</option>
                                        @foreach ($masterJenjangSekolah as $id => $nama)
                                            <option value="{{$id}}">{{$nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="status_sekolah" class="control-label">Status Sekolah<span class="text-danger">*</span></label>
                                    <select name="status_sekolah" id="status_sekolah" class="form-control" required>
                                        <option value="">--- Pilih Status Sekolah ---</option>
                                        <option value="negeri">Negeri</option>
                                        <option value="swasta">Swasta</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="master_kurikulum_id" class="control-label">Kurikulum Yang Digunakan<span class="text-danger">*</span></label>
                                    <select name="master_kurikulum_id" id="master_kurikulum_id" class="form-control" required>
                                        <option value="">--- Pilih Kurikulum ---</option>
                                        @foreach ($masterKurikulum as $id => $nama)
                                            <option value="{{$id}}">{{$nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="akreditasi" class="control-label">Akreditasi</label>
                                    <select name="akreditasi" id="akreditasi" class="form-control" required>
                                        <option value="">--- Pilih ---</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="total_siswa" class="control-label">Total Siswa<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="total_siswa" id="total_siswa" placeholder="Masukan total siswa...">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="nama_kepsek" class="control-label">Nama Kepala Sekolah<span class="text-danger">*</span></label>
                                    <input type="text" name="nama_kepsek" id="nama_kepsek" parsley-trigger="change" required
                                    placeholder="Masukan nama kepala sekolah..." class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="nip_kepsek" class="control-label">NIP Kepala Sekolah<span class="text-danger">*</span></label>
                                    <input type="text" name="nip_kepsek" id="nip_kepsek" parsley-trigger="change" required
                                    placeholder="Masukan nip kepala sekolah..." class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="kode_penerbitan" class="control-label">Kode Penerbitan</label>
                                <select name="kode_penerbitan" id="kode_penerbitan" class="form-control" required>
                                    <option value="">--- Pilih Kode Penerbitan ---</option>
                                    <option value="DN">DN</option>
                                    <option value="LN">LN</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="kode_jenjang_pendidikan" class="control-label">Kode Jenjang Pendidikan</label>
                                <select name="kode_jenjang_pendidikan" id="kode_jenjang_pendidikan" class="form-control" required>
                                    <option value="">--- Pilih Kode Jenjang Pendidikan ---</option>
                                    <option value="D">D</option>
                                    <option value="M">M</option>
                                    <option value="PA">PA</option>
                                    <option value="PB">PB</option>
                                    <option value="PC">PC</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-4" id="col_form_lama_program_belajar_smk">

                            </div>
                        </div>
                        <hr>
                        <h4 class="text-primary">Kontak</h4>
                        <hr>
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="no_hp" class="control-label">Call Center / No. HP / WhatsApp<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="no_hp" id="no_hp" placeholder="Masukan nomor Call Center...">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h4 class="text-primary">Data Lainnya</h4>
                        <hr>
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="status_internet" class="control-label">Status Internet<span class="text-danger">*</span></label>
                                    <select name="status_internet" id="status_internet" class="form-control" required>
                                        <option value="">--- Pilih Status Internet</option>
                                        <option value="tidak">Tidak</option>
                                        <option value="iya">Iya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="jaringan_internet" class="control-label">Bentuk Jaringan Internet<span class="text-warning" style="font-size: 10px;">*(diisi jika status internet = iya)</span></label>
                                    <select name="jaringan_internet" id="jaringan_internet" class="form-control">
                                        <option value="">--- Pilih Jaringan Internet ---</option>
                                        <option value="wifi">Wifi</option>
                                        <option value="data_seluler">Data Seluler</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="master_kecepatan_internet_id" class="control-label">Kategori Kecepatan Internet<span class="text-warning" style="font-size: 10px;">*(diisi jika status internet = iya)</span></label>
                                    <select name="master_kecepatan_internet_id" id="master_kecepatan_internet_id" class="form-control">
                                        <option value="">--- Kategori Kecepatan Internet ---</option>
                                        @foreach ($masterKecepatanInternet as $id => $nama)
                                            <option value="{{$id}}">{{$nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="status_rawan_banjir" class="control-label">Status Rawan Banjir</label>
                                    <select name="status_rawan_banjir" id="status_rawan_banjir" class="form-control" parsley-trigger="change" required>
                                        <option value="">--- Pilih ---</option>
                                        <option value="ya">Ya</option>
                                        <option value="tidak">Tidak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="is_dummy" class="control-label">Status Dummy<span class="text-danger">*</span></label>
                                    <select name="is_dummy" id="is_dummy" class="form-control" parsley-trigger="change"  required>
                                        <option value="">--- Pilih Status Dummy ---</option>
                                        <option value="1">Ya</option>
                                        <option value="0">Tidak</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h4 class="text-primary">Lokasi Sekolah</h4>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="alamat" class="control-label">Alamat<span class="text-danger">*</span></label>
                                    <textarea name="alamat" id="alamat" cols="30" rows="4" parsley-trigger="change" class="form-control" required></textarea>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="provinsi_id" class="control-label">Provinsi<span class="text-danger">*</span></label>
                                    <select name="provinsi_id" id="provinsi_id" class="form-control" parsley-trigger="change" required>
                                        <option value="">--- Pilih Provinsi ---</option>
                                        @foreach ($provinsis as $id => $nama)
                                            <option value="{{$id}}">{{$nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="kabupaten_id" class="control-label">Kabupaten / Kota<span class="text-danger">*</span></label>
                                    <select name="kabupaten_id" id="kabupaten_id" class="form-control" parsley-trigger="change" disabled required>
                                        <option value="">--- Pilih Kabupaten / Kota ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="kecamatan_id" class="control-label">Kecamatan<span class="text-danger">*</span></label>
                                    <select name="kecamatan_id" id="kecamatan_id" class="form-control" parsley-trigger="change" disabled required>
                                        <option value="">--- Pilih Kecamatan ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="kelurahan_id" class="control-label">Kelurahan / Desa<span class="text-danger">*</span></label>
                                    <select name="kelurahan_id" id="kelurahan_id" class="form-control" parsley-trigger="change" disabled  required>
                                        <option value="">--- Pilih Kelurahan / Desa ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="map-search-container">
                                    <input id="location-input" type="text" placeholder="Search for a place" />
                                    <ul id="autocomplete-results" class="autocomplete-list"></ul>
                                </div>
                                <div id="map" class="mb-3"></div>
                                <div class="form-group row">
                                    <div class="col-6">
                                        <label for="lat" class="control-label">Latitude</label>
                                        <input type="text" id="lat" name="lat" class="form-control" placeholder="Latitude" readonly/>
                                    </div>
                                    <div class="col-6">
                                        <label for="lng" class="control-label">Longitude</label>
                                        <input type="text" id="lng" name="lng" class="form-control" placeholder="Longitude" readonly/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h4 class="text-primary">Berkas Sekolah</h4>
                        <hr>
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="logo" class="control-label">Logo Sekolah<span class="text-danger">*</span></label>
                                    <input type="file" name="logo" id="logo" parsley-trigger="change" required
                                    placeholder="Masukan logo sekolah..." class="dropify" data-height="150">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="berkas_sertifikat_akreditasi" class="control-label">Sertifikat Akreditasi<span class="text-danger">*</span></label>
                                    <input type="file" name="berkas_sertifikat_akreditasi" id="berkas_sertifikat_akreditasi" parsley-trigger="change" required
                                    placeholder="Masukan Sertifikat Akreditasi Sekolah..." class="dropify" data-height="150">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="tanda_tangan_kepsek" class="control-label">Tanda Tangan Kepala Sekolah<span class="text-danger">*</span></label>
                                    <input type="file" name="tanda_tangan_kepsek" id="tanda_tangan_kepsek" parsley-trigger="change" required
                                    placeholder="Masukan tanda tangan kepala sekolah..." class="dropify" data-height="150">
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light waves-effect width-md waves-light" data-dismiss="modal">Close</button>
                    <input type="hidden" name="aksi" id="aksi" value="Save">
                    <input type="hidden" name="hidden_id" id="hidden_id">
                    <button type="submit" name="aksi_button" id="aksi_button" class="btn btn-primary waves-effect width-md waves-light">Save</button>
                </div>
            </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <div id="lengkapiDataModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="lengkapiDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="lengkapiDataModalLabel">Lengkapi Data Sekolah</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <span id="form_result"></span>
                    <form class="form-horizontal" id="lengkapi_data_sekolah_form" method="POST" data-parsley-validate novalidate enctype="multipart/form-data">
                        @csrf
                        <h4 class="text-primary">Data Sekolah</h4>
                        <hr>
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="lengkapi_data_master_jenjang_sekolah_id" class="control-label">Jenjang Sekolah<span class="text-danger">*</span></label>
                                    <select name="lengkapi_data_master_jenjang_sekolah_id" id="lengkapi_data_master_jenjang_sekolah_id" class="form-control" parsley-trigger="change" required>
                                        <option value="">--- Pilih Jenjang Sekolah ---</option>
                                        @foreach ($masterJenjangSekolah as $id => $nama)
                                            <option value="{{$id}}">{{$nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="lengkapi_data_master_kurikulum_id" class="control-label">Kurikulum Yang Digunakan<span class="text-danger">*</span></label>
                                    <select name="lengkapi_data_master_kurikulum_id" id="lengkapi_data_master_kurikulum_id" class="form-control" required>
                                        <option value="">--- Pilih Kurikulum ---</option>
                                        @foreach ($masterKurikulum as $id => $nama)
                                            <option value="{{$id}}">{{$nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h4 class="text-primary">Data Lainnya</h4>
                        <hr>
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="lengkapi_data_master_kecepatan_internet_id" class="control-label">Kategori Kecepatan Internet</label>
                                    <select name="lengkapi_data_master_kecepatan_internet_id" id="lengkapi_data_master_kecepatan_internet_id" class="form-control">
                                        <option value="">--- Kategori Kecepatan Internet ---</option>
                                        @foreach ($masterKecepatanInternet as $id => $nama)
                                            <option value="{{$id}}">{{$nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h4 class="text-primary">Lokasi Sekolah</h4>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="lengkapi_data_alamat" class="control-label">Alamat<span class="text-danger">*</span></label>
                                    <textarea id="lengkapi_data_alamat" cols="30" rows="4" parsley-trigger="change" class="form-control" disabled></textarea>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="lengkapi_data_provinsi_id" class="control-label">Provinsi<span class="text-danger">*</span></label>
                                    <select name="lengkapi_data_provinsi_id" id="lengkapi_data_provinsi_id" class="form-control" parsley-trigger="change" required>
                                        <option value="">--- Pilih Provinsi ---</option>
                                        @foreach ($provinsis as $id => $nama)
                                            <option value="{{$id}}">{{$nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="lengkapi_data_kabupaten_id" class="control-label">Kabupaten / Kota<span class="text-danger">*</span></label>
                                    <select name="lengkapi_data_kabupaten_id" id="lengkapi_data_kabupaten_id" class="form-control" parsley-trigger="change" disabled required>
                                        <option value="">--- Pilih Kabupaten / Kota ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="lengkapi_data_kecamatan_id" class="control-label">Kecamatan<span class="text-danger">*</span></label>
                                    <select name="lengkapi_data_kecamatan_id" id="lengkapi_data_kecamatan_id" class="form-control" parsley-trigger="change" disabled required>
                                        <option value="">--- Pilih Kecamatan ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="lengkapi_data_kelurahan_id" class="control-label">Kelurahan / Desa<span class="text-danger">*</span></label>
                                    <select name="lengkapi_data_kelurahan_id" id="lengkapi_data_kelurahan_id" class="form-control" parsley-trigger="change" disabled  required>
                                        <option value="">--- Pilih Kelurahan / Desa ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="map-search-container">
                                    <input id="lengkapi_data_location-input" type="text" placeholder="Search for a place" />
                                    <ul id="lengkapi_data_autocomplete-results" class="autocomplete-list"></ul>
                                </div>
                                <div id="lengkapi_data_map" class="mb-3"></div>
                                <div class="form-group row">
                                    <div class="col-6">
                                        <label for="lengkapi_data_lat" class="control-label">Latitude</label>
                                        <input type="text" id="lengkapi_data_lat" name="lengkapi_data_lat" class="form-control" placeholder="Latitude" readonly/>
                                    </div>
                                    <div class="col-6">
                                        <label for="lengkapi_data_lng" class="control-label">Longitude</label>
                                        <input type="text" id="lengkapi_data_lng" name="lengkapi_data_lng" class="form-control" placeholder="Longitude" readonly/>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light waves-effect width-md waves-light" data-dismiss="modal">Close</button>
                    <input type="hidden" name="lengkapi_data_hidden_id" id="lengkapi_data_hidden_id">
                    <button type="submit" class="btn btn-primary waves-effect width-md waves-light">Simpan Data</button>
                </div>
            </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
@endsection

@section('js')
    <!-- third party js -->
    <script src="{{ asset('/adminto/assets/libs/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/adminto/assets/libs/datatables/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('/adminto/assets/libs/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/adminto/assets/libs/datatables/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/adminto/assets/libs/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('/adminto/assets/libs/datatables/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/adminto/assets/libs/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('/adminto/assets/libs/datatables/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('/adminto/assets/libs/datatables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('/adminto/assets/libs/datatables/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('/adminto/assets/libs/datatables/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('/adminto/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('/adminto/assets/libs/pdfmake/vfs_fonts.js') }}"></script>
    <!-- third party js ends -->

    <!-- Datatables init -->
    <script src="{{ asset('/adminto/assets/js/pages/datatables.init.js') }}"></script>
    <script src="{{ asset('js/sweetalert.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <!-- Validation js (Parsleyjs) -->
    <script src="{{ asset('/adminto/assets/libs/parsleyjs/parsley.min.js') }}"></script>

    <!-- dropify js -->
    <script src="{{ asset('/adminto/assets/libs/dropify/dropify.min.js') }}"></script>

    <!-- form-upload init -->
    <script src="{{ asset('/adminto/assets/js/pages/form-fileupload.init.js') }}"></script>

    <!-- validation init -->
    <script src="{{ asset('/adminto/assets/js/pages/form-validation.init.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('API_KEY_GOOGLEMAPS')}}&libraries=places"></script>
    <script>
        var STATE_KABUPATEN = 0;
        var STATUS_KABUPATEN = false;
        var STATE_KECAMATAN = 0;
        var STATUS_KECAMATAN = false;
        var STATE_KELURAHAN = 0;
        var STATUS_KELURAHAN = false;
        let map;
        let marker;
        let autocompleteService;
        let placesService;

        $(document).ready(function(){
            loadTable();

            $('#master_jenjang_sekolah_id').select2();
            $('#master_kurikulum_id').select2();
            $('#provinsi_id').select2();
            $('#kabupaten_id').select2();
            $('#kecamatan_id').select2();
            $('#kelurahan_id').select2();
        });

        const loadTable = () => {
            if ($.fn.DataTable.isDataTable('#sekolah_table')) {
                $('#sekolah_table').DataTable().clear().destroy();
            }

            var dataTables = $('#sekolah_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.sekolah.index') }}",
                },
                columns: [
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'master_jenjang_sekolah_id',
                        name: 'master_jenjang_sekolah_id'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'npsn',
                        name: 'npsn'
                    },
                    {
                        data: 'status_sekolah',
                        name: 'status_sekolah'
                    },
                    {
                        data: 'master_kurikulum_id',
                        name: 'master_kurikulum_id'
                    },
                    {
                        data: 'logo',
                        name: 'logo'
                    },
                    {
                        data: 'total_siswa',
                        name: 'total_siswa'
                    },
                    {
                        data: 'provinsi_id',
                        name: 'provinsi_id'
                    },
                    {
                        data: 'kabupaten_id',
                        name: 'kabupaten_id'
                    },
                    {
                        data: 'kecamatan_id',
                        name: 'kecamatan_id'
                    },
                    {
                        data: 'kelurahan_id',
                        name: 'kelurahan_id'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
                    },
                    {
                        data: 'is_dummy',
                        name: 'is_dummy'
                    },
                ]
            });
        }

        $('#provinsi_id').on('change', function(){
            $.ajax({
                url: "{{ route('admin.sekolah.get-kabupaten') }}",
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id:$(this).val()
                },
                success: function(response){
                    $('#kabupaten_id').empty();
                    $('#kabupaten_id').append('<option value="">--- Pilih Kabupaten ---</option>');
                    $('#kecamatan_id').empty();
                    $('#kecamatan_id').append('<option value="">--- Pilih Kecamatan ---</option>');
                    $('#kelurahan_id').empty();
                    $('#kelurahan_id').append('<option value="">--- Pilih Kelurahan ---</option>');
                    $('#kabupaten_id').prop('disabled', false);
                    $('#kecamatan_id').prop('disabled', true);
                    $('#kelurahan_id').prop('disabled', true);
                    $.each(response, function(id, nama){
                        $('#kabupaten_id').append(new Option(nama, id));
                    });
                    if(STATE_KABUPATEN != 0 && STATUS_KABUPATEN == true)
                    {
                        $("[name = 'kabupaten_id']").val(STATE_KABUPATEN).trigger('change');
                        STATUS_KABUPATEN = false;
                    }
                }
            });
        });

        $('#kabupaten_id').on('change', function(){
            $.ajax({
                url: "{{ route('admin.sekolah.get-kecamatan') }}",
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id:$(this).val()
                },
                success: function(response){
                    $('#kecamatan_id').empty();
                    $('#kecamatan_id').append('<option value="">--- Pilih Kecamatan ---</option>');
                    $('#kelurahan_id').empty();
                    $('#kelurahan_id').append('<option value="">--- Pilih Kelurahan ---</option>');
                    $('#kecamatan_id').prop('disabled', false);
                    $('#kelurahan_id').prop('disabled', true);
                    $.each(response, function(id, nama){
                        $('#kecamatan_id').append(new Option(nama, id));
                    });

                    if(STATE_KECAMATAN != 0 && STATUS_KECAMATAN == true)
                    {
                        $("[name = 'kecamatan_id']").val(STATE_KECAMATAN).trigger('change');
                        STATUS_KECAMATAN = false;
                    }
                }
            });
        });

        $('#kecamatan_id').on('change', function(){
            $.ajax({
                url: "{{ route('admin.sekolah.get-kelurahan') }}",
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id:$(this).val()
                },
                success: function(response){
                    $('#kelurahan_id').empty();
                    $('#kelurahan_id').prop('disabled', false);
                    $('#kelurahan_id').append('<option value="">--- Pilih Kelurahan ---</option>');
                    $.each(response, function(id, nama){
                        $('#kelurahan_id').append(new Option(nama, id));
                    });

                    if(STATE_KELURAHAN != 0 && STATUS_KELURAHAN == true)
                    {
                        $("[name = 'kelurahan_id']").val(STATE_KELURAHAN).trigger('change');
                        STATUS_KELURAHAN = false;
                    }
                }
            });
        });

        $('#create').click(function(){
            $('#sekolah_form')[0].reset();
            $("#master_jenjang_sekolah_id").val('').trigger('change');
            $("#master_kurikulum_id").val('').trigger('change');
            $("#provinsi_id").val('').trigger('change');
            $('#status_sekolah').val('').trigger('change');
            $("#logo").prop('required',true);
            $("#tanda_tangan_kepsek").prop('required',true);
            $('.dropify-clear').click();
            $('#aksi_button').text('Save');
            $('#aksi_button').prop('disabled', false);
            $('.modal-title').text('Tamba Sekolah');
            $('#aksi_button').val('Save');
            $('#aksi').val('Save');
            $('#form_result').html('');
            $('#location-input').val();
            window.onload = initMap(-2.500000, 118.000000, 5);

            $('#form_email').remove();
            var email = $(`<div class="col-12 col-md-4" id="form_email">
                                <div class="form-group">
                                    <label for="nama" class="control-label">Email Sekolah<span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" parsley-trigger="change" required
                                    placeholder="Masukan email sekolah..." class="form-control">
                                </div>
                            </div>`);
            $('#form_npsn').after(email);
        });

        $('#status_internet').change(function(){
            var value = $(this).val();

            if(value == 'tidak')
            {
                $('#jaringan_internet').prop('required', false);
                $('#master_kecepatan_internet_id').prop('required', false);
                $('#jaringan_internet').prop('disabled', true);
                $('#master_kecepatan_internet_id').prop('disabled', true);
            }
            if(value == 'iya')
            {
                $('#jaringan_internet').prop('required', true);
                $('#master_kecepatan_internet_id').prop('required', true);
                $('#jaringan_internet').prop('disabled', false);
                $('#master_kecepatan_internet_id').prop('disabled', false);
            }
            if(value == '')
            {
                $('#jaringan_internet').prop('required', false);
                $('#master_kecepatan_internet_id').prop('required', false);
                $('#jaringan_internet').prop('disabled', true);
                $('#master_kecepatan_internet_id').prop('disabled', true);
            }
        });

        function initMap(lat, lng, zoom) {
            // Initialize the map
            map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: lat, lng: lng}, // Center to Indonesia
                zoom: zoom
            });

            // Initialize marker
            marker = new google.maps.Marker({
                position: { lat: lat, lng: lng},
                map: map,
                draggable: true
            });

            // Add click event listener on the map
            map.addListener('click', (event) => {
                placeMarker(event.latLng);
            });

            // Drag the marker to change lat/lng
            marker.addListener('dragend', (event) => {
                document.getElementById('lat').value = event.latLng.lat();
                document.getElementById('lng').value = event.latLng.lng();
            });

            // Autocomplete for the location search
            const input = document.getElementById('location-input');
            autocompleteService = new google.maps.places.AutocompleteService();
            placesService = new google.maps.places.PlacesService(map);

            input.addEventListener('keydown', (event) => {
                const searchText = event.target.value;
                if (searchText.length > 0) {
                    fetch(`/dll/get-location-autocomplete?input=${searchText}`)
                    .then(response => response.json())
                    .then(data => {
                        // Display autocomplete results as before
                        displayAutocompleteResults(data.predictions,data.status);
            });
                } else {
                    clearAutocompleteResults();
                }
            });
        }

        function placeMarker(location) {
            marker.setPosition(location);
            document.getElementById('lat').value = location.lat();
            document.getElementById('lng').value = location.lng();
        }

        function displayAutocompleteResults(predictions, status) {
            const resultsContainer = document.getElementById('autocomplete-results');
            clearAutocompleteResults();
            if (status === google.maps.places.PlacesServiceStatus.OK) {
                predictions.forEach(prediction => {
                    const li = document.createElement('li');
                    li.textContent = prediction.description;
                    li.classList.add('autocomplete-item');
                    li.addEventListener('click', () => {
                        selectPlace(prediction.place_id);
                    });
                    resultsContainer.appendChild(li);
                });
            }
        }

        function clearAutocompleteResults() {
            const resultsContainer = document.getElementById('autocomplete-results');
            while (resultsContainer.firstChild) {
                resultsContainer.removeChild(resultsContainer.firstChild);
            }
        }

        function selectPlace(placeId) {
            placesService.getDetails({ placeId }, (place, status) => {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    // Center the map and move the marker to the selected place
                    map.setCenter(place.geometry.location);
                    map.setZoom(15);
                    placeMarker(place.geometry.location);
                    document.getElementById('location-input').value = place.formatted_address;
                    clearAutocompleteResults();
                }
            });
        }

        // Prevent form submission on Enter keypress in search input
        document.getElementById('location-input').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Prevent the form from submitting
            }
        });

        $('#sekolah_form').on('submit', function(e){
            e.preventDefault();
            if($('#aksi').val() == 'Save')
            {
                $.ajax({
                    url: "{{ route('admin.sekolah.store') }}",
                    method: "POST",
                    data: new FormData(this),
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function()
                    {
                        $('#aksi_button').text('Menyimpan...');
                        $('#aksi_button').prop('disabled', true);
                    },
                    success: function(data)
                    {
                        var html = '';
                        if(data.errors)
                        {
                            html = '<div class="alert alert-danger">'+data.errors+'</div>';
                            $("#logo").prop('required',true);
                            $("#tanda_tangan_kepsek").prop('required',true);
                            $('.dropify-clear').click();
                            $('#aksi_button').prop('disabled', false);
                            $('#aksi_button').text('Save');
                        }
                        if(data.success)
                        {
                            html = '<div class="alert alert-success">'+data.success+'</div>';
                            $("#provinsi_id").val('').trigger('change');
                            $("#kabupaten_id").val('').trigger('change');
                            $("#kecamatan_id").val('').trigger('change');
                            $("#kelurahan_id").val('').trigger('change');
                            $('#status_sekolah').val('').trigger('change');
                            $("#logo").prop('required',true);
                            $("#tanda_tangan_kepsek").prop('required',true);
                            $('.dropify-clear').click();
                            $('#aksi_button').prop('disabled', false);
                            $('#sekolah_form')[0].reset();
                            $('#aksi_button').text('Save');
                            $('#sekolah_table').DataTable().ajax.reload();
                        }

                        $('#form_result').html(html);
                    }
                });
            }
            if($('#aksi').val() == 'Edit')
            {
                $.ajax({
                    url: "{{ route('admin.sekolah.update') }}",
                    method: "POST",
                    data: new FormData(this),
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function(){
                        $('#aksi_button').text('Mengubah...');
                        $('#aksi_button').prop('disabled', true);
                    },
                    success: function(data)
                    {
                        var html = '';
                        if(data.errors)
                        {
                            html = '<div class="alert alert-danger">'+data.errors+'</div>';
                            $("#logo").prop('required',false);
                            $("#tanda_tangan_kepsek").prop('required',false);
                            $('.dropify-clear').click();
                            $('#aksi_button').prop('disabled', false);
                            $('#aksi_button').text('Edit');
                        }
                        if(data.success)
                        {
                            $('#sekolah_form')[0].reset();
                            $("#logo").prop('required',false);
                            $("#tanda_tangan_kepsek").prop('required',false);
                            $('.dropify-clear').click();
                            $('#aksi_button').prop('disabled', false);
                            $('#aksi_button').text('Save');
                            $('#sekolah_table').DataTable().ajax.reload();
                            $('#createModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil di ubah',
                                showConfirmButton: true
                            });
                        }

                        $('#form_result').html(html);
                    }
                });
            }
        });

        $('#btn_sinkronisasi').click(function(){
            $.ajax({
                url: "{{ route('admin.sekolah.sinkronisasi-data-sekolah') }}",
                dataType: "json",
                beforeSend: function(){
                    return new swal({
                        title: "Checking...",
                        text: "Harap Menunggu",
                        imageUrl: "{{ asset('/images/preloader.gif') }}",
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });
                },
                success: function(data)
                {
                    if(data.success)
                    {
                        loadTable();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil Sinkronisasi Data Sekolah',
                            showConfirmButton: true
                        });
                    }

                    if(data.errors)
                    {
                        Swal.fire({
                            icon: 'errors',
                            title: data.errors,
                            showConfirmButton: true
                        });
                    }
                }
            });
        });

        $('#master_jenjang_sekolah_id').change(function(){
            var value = $('#master_jenjang_sekolah_id option:selected').text();
            if(value == 'SMK')
            {
                $('#col_form_lama_program_belajar_smk').empty();
                var form_lama_program_belajar_smk = $(`<div id="form_lama_program_belajar_smk">
                                                            <label for="lama_program_belajar_smk" class="control-label">Lama Program Belajar SMK</label>
                                                            <select name="lama_program_belajar_smk" id="lama_program_belajar_smk" class="form-control" required>
                                                                <option value="">--- Pilih Lama Program Belajar SMK ---</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                            </select>
                                                        </div>`);

                $('#col_form_lama_program_belajar_smk').append(form_lama_program_belajar_smk);
            } else {
                $('#col_form_lama_program_belajar_smk').empty();
            }
        });

        $(document).on('click', '.edit', function(){
            var id = $(this).attr('id');
            var url = "{{ route('admin.sekolah.edit', ['id' => ":id"]) }}";
            url = url.replace(':id', id);

            $.ajax({
                url : url,
                dataType: "json",
                success: function(data)
                {
                    $('#nama').val(data.result.nama);
                    $('#npsn').val(data.result.npsn);
                    $('#email').val(data.result.email);
                    $("[name='master_jenjang_sekolah_id']").val(data.result.master_jenjang_sekolah_id).trigger('change');
                    $("[name='status_sekolah']").val(data.result.status_sekolah).trigger('change');
                    $("[name='master_kurikulum_id']").val(data.result.master_kurikulum_id).trigger('change');
                    $("[name='akreditasi']").val(data.result.akreditasi).trigger('change');
                    $('#total_siswa').val(data.result.total_siswa);
                    $('#nama_kepsek').val(data.result.nama_kepsek);
                    $('#nip_kepsek').val(data.result.nip_kepsek);
                    $("[name='kode_penerbitan']").val(data.result.kode_penerbitan).trigger('change');
                    $("[name='kode_jenjang_pendidikan']").val(data.result.kode_jenjang_pendidikan).trigger('change');
                    if(data.result.master_jenjang_sekolah_id == 4)
                    {
                        $("[name='lama_program_belajar_smk']").val(data.result.lama_program_belajar_smk).trigger('change');
                    }
                    $('#no_hp').val(data.result.no_hp);
                    $("[name='status_internet']").val(data.result.status_internet).trigger('change');
                    $("[name='jaringan_internet']").val(data.result.jaringan_internet).trigger('change');
                    $("[name='master_kecepatan_internet_id']").val(data.result.master_kecepatan_internet_id).trigger('change');
                    $("[name='status_rawan_banjir']").val(data.result.status_rawan_banjir).trigger('change');
                    $("[name='is_dummy']").val(data.result.is_dummy).trigger('change');
                    $('#alamat').val(data.result.alamat);
                    $("[name='provinsi_id']").val(data.result.provinsi_id).trigger('change');
                    STATE_KABUPATEN = data.result.kabupaten_id;
                    STATUS_KABUPATEN = true;
                    STATE_KECAMATAN = data.result.kecamatan_id;
                    STATUS_KECAMATAN = true;
                    STATE_KELURAHAN = data.result.kelurahan_id;
                    STATUS_KELURAHAN = true;
                    $('#lat').val(data.result.lat);
                    $('#lng').val(data.result.lng);

                    var lokasi_logo = data.result.logo;
                    var fileDropperLogo = $("#logo").dropify({
                        messages: { default: "Seret dan lepas logo di sini atau klik", replace: "Seret dan lepas logo di sini atau klik", remove: "Remove", error: "Terjadi kesalahan" },
                        error: { fileSize: "Ukuran file gambar terlalu besar (Maksimal 1 MB)" },
                    });

                    fileDropperLogo = fileDropperLogo.data('dropify');
                    fileDropperLogo.resetPreview();
                    fileDropperLogo.clearElement();
                    fileDropperLogo.settings['defaultFile'] = lokasi_logo;
                    fileDropperLogo.destroy();
                    fileDropperLogo.init();

                    var lokasi_tanda_tangan_kepsek = data.result.tanda_tangan_kepsek;
                    var fileDropperTandaTanganKepsek = $("#tanda_tangan_kepsek").dropify({
                        messages: { default: "Seret dan lepas logo di sini atau klik", replace: "Seret dan lepas logo di sini atau klik", remove: "Remove", error: "Terjadi kesalahan" },
                        error: { fileSize: "Ukuran file gambar terlalu besar (Maksimal 1 MB)" },
                    });

                    fileDropperTandaTanganKepsek = fileDropperTandaTanganKepsek.data('dropify');
                    fileDropperTandaTanganKepsek.resetPreview();
                    fileDropperTandaTanganKepsek.clearElement();
                    fileDropperTandaTanganKepsek.settings['defaultFile'] = lokasi_tanda_tangan_kepsek;
                    fileDropperTandaTanganKepsek.destroy();
                    fileDropperTandaTanganKepsek.init();

                    var lokasi_sertifikat_akreditasi = data.result.berkas_sertifikat_akreditasi;
                    var fileDropperSertifikatAkreditasi = $("#berkas_sertifikat_akreditasi").dropify({
                        messages: { default: "Seret dan lepas logo di sini atau klik", replace: "Seret dan lepas logo di sini atau klik", remove: "Remove", error: "Terjadi kesalahan" },
                        error: { fileSize: "Ukuran file gambar terlalu besar (Maksimal 1 MB)" },
                    });

                    fileDropperSertifikatAkreditasi = fileDropperSertifikatAkreditasi.data('dropify');
                    fileDropperSertifikatAkreditasi.resetPreview();
                    fileDropperSertifikatAkreditasi.clearElement();
                    fileDropperSertifikatAkreditasi.settings['defaultFile'] = lokasi_sertifikat_akreditasi;
                    fileDropperSertifikatAkreditasi.destroy();
                    fileDropperSertifikatAkreditasi.init();

                    window.onload = initMap(data.result.lat, data.result.lng, data.result.zoom);
                    $('#form_email').remove();
                    $('#hidden_id').val(id);
                    $('.modal-title').text('Edit Data');
                    $('#aksi_button').text('Edit');
                    $('#aksi_button').prop('disabled', false);
                    $('#aksi_button').val('Edit');
                    $('#aksi').val('Edit');
                    $('#createModal').modal('show');
                }
            });
        });
    </script>

    <script>
        var LENGKAPI_DATA_STATE_KABUPATEN = 0;
        var LENGKAPI_DATA_STATUS_KABUPATEN = false;
        var LENGKAPI_DATA_STATE_KECAMATAN = 0;
        var LENGKAPI_DATA_STATUS_KECAMATAN = false;
        var LENGKAPI_DATA_STATE_KELURAHAN = 0;
        var LENGKAPI_DATA_STATUS_KELURAHAN = false;
        let lengkapi_data_map;
        let lengkapi_data_marker;
        let lengkapi_data_autocompleteService;
        let lengkapi_data_placesService;

        $('#lengkapi_data_master_jenjang_sekolah_id').select2();
        $('#lengkapi_data_master_kurikulum_id').select2();
        $('#lengkapi_data_master_kecepatan_internet_id').select2();
        $('#lengkapi_data_provinsi_id').select2();
        $('#lengkapi_data_kabupaten_id').select2();
        $('#lengkapi_data_kecamatan_id').select2();
        $('#lengkapi_data_kelurahan_id').select2();

        $(document).on('click', '.btn-melengkapi-data', function(){
            var id = $(this).attr('id');

            var url = "{{ route('admin.sekolah.lengkapi-data-sekolah.data', ['id' => ":id"]) }}";
            url = url.replace(':id', id);

            $.ajax({
                url : url,
                dataType: "json",
                success: function(data)
                {
                    $("[name='lengkapi_data_master_jenjang_sekolah_id']").val(data.result.master_jenjang_sekolah_id).trigger('change');
                    $("[name='lengkapi_data_master_kurikulum_id']").val(data.result.master_kurikulum_id).trigger('change');
                    $("[name='lengkapi_data_master_kecepatan_internet_id']").val(data.result.master_kecepatan_internet_id).trigger('change');
                    $("[name='lengkapi_data_provinsi_id']").val(data.result.provinsi_id).trigger('change');
                    $("[name='lengkapi_data_kabupaten_id']").val(data.result.kabupaten_id).trigger('change');
                    $("[name='lengkapi_data_kecepatan_id']").val(data.result.kecepatan_id).trigger('change');
                    $("[name='lengkapi_data_kelurahan_id']").val(data.result.kelurahan_id).trigger('change');
                    $('#lengkapi_data_alamat').val(data.result.alamat);
                    $('#lengkapi_data_hidden_id').val(id);
                    $('#lengkapiDataModal').modal('show');
                    window.onload = lengkapi_data_initMap(-2.500000, 118.000000, 5);
                }
            });
        });

        $('#lengkapi_data_provinsi_id').on('change', function(){
            $.ajax({
                url: "{{ route('admin.sekolah.get-kabupaten') }}",
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id:$(this).val()
                },
                success: function(response){
                    $('#lengkapi_data_kabupaten_id').empty();
                    $('#lengkapi_data_kabupaten_id').append('<option value="">--- Pilih Kabupaten ---</option>');
                    $('#lengkapi_data_kecamatan_id').empty();
                    $('#lengkapi_data_kecamatan_id').append('<option value="">--- Pilih Kecamatan ---</option>');
                    $('#lengkapi_data_kelurahan_id').empty();
                    $('#lengkapi_data_kelurahan_id').append('<option value="">--- Pilih Kelurahan ---</option>');
                    $('#lengkapi_data_kabupaten_id').prop('disabled', false);
                    $('#lengkapi_data_kecamatan_id').prop('disabled', true);
                    $('#lengkapi_data_kelurahan_id').prop('disabled', true);
                    $.each(response, function(id, nama){
                        $('#lengkapi_data_kabupaten_id').append(new Option(nama, id));
                    });
                    if(LENGKAPI_DATA_STATE_KABUPATEN != 0 && LENGKAPI_DATA_STATUS_KABUPATEN == true)
                    {
                        $("[name = 'lengkapi_data_kabupaten_id']").val(LENGKAPI_DATA_STATE_KABUPATEN).trigger('change');
                        LENGKAPI_DATA_STATUS_KABUPATEN = false;
                    }
                }
            });
        });

        $('#lengkapi_data_kabupaten_id').on('change', function(){
            $.ajax({
                url: "{{ route('admin.sekolah.get-kecamatan') }}",
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id:$(this).val()
                },
                success: function(response){
                    $('#lengkapi_data_kecamatan_id').empty();
                    $('#lengkapi_data_kecamatan_id').append('<option value="">--- Pilih Kecamatan ---</option>');
                    $('#lengkapi_data_kelurahan_id').empty();
                    $('#lengkapi_data_kelurahan_id').append('<option value="">--- Pilih Kelurahan ---</option>');
                    $('#lengkapi_data_kecamatan_id').prop('disabled', false);
                    $('#lengkapi_data_kelurahan_id').prop('disabled', true);
                    $.each(response, function(id, nama){
                        $('#lengkapi_data_kecamatan_id').append(new Option(nama, id));
                    });

                    if(LENGKAPI_DATA_STATE_KECAMATAN != 0 && LENGKAPI_DATA_STATUS_KECAMATAN == true)
                    {
                        $("[name = 'lengkapi_data_kecamatan_id']").val(LENGKAPI_DATA_STATE_KECAMATAN).trigger('change');
                        LENGKAPI_DATA_STATUS_KECAMATAN = false;
                    }
                }
            });
        });

        $('#lengkapi_data_kecamatan_id').on('change', function(){
            $.ajax({
                url: "{{ route('admin.sekolah.get-kelurahan') }}",
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id:$(this).val()
                },
                success: function(response){
                    $('#lengkapi_data_kelurahan_id').empty();
                    $('#lengkapi_data_kelurahan_id').prop('disabled', false);
                    $('#lengkapi_data_kelurahan_id').append('<option value="">--- Pilih Kelurahan ---</option>');
                    $.each(response, function(id, nama){
                        $('#lengkapi_data_kelurahan_id').append(new Option(nama, id));
                    });

                    if(LENGKAPI_DATA_STATE_KELURAHAN != 0 && LENGKAPI_DATA_STATUS_KELURAHAN == true)
                    {
                        $("[name = 'lengkapi_data_kelurahan_id']").val(LENGKAPI_DATA_STATE_KELURAHAN).trigger('change');
                        LENGKAPI_DATA_STATUS_KELURAHAN = false;
                    }
                }
            });
        });

        function lengkapi_data_initMap(lat, lng, zoom) {
            // Initialize the map
            lengkapi_data_map = new google.maps.Map(document.getElementById('lengkapi_data_map'), {
                center: { lat: lat, lng: lng}, // Center to Indonesia
                zoom: zoom
            });

            // Initialize marker
            lengkapi_data_marker = new google.maps.Marker({
                position: { lat: lat, lng: lng},
                map: lengkapi_data_map,
                draggable: true
            });

            // Add click event listener on the map
            lengkapi_data_map.addListener('click', (event) => {
                lengkapi_data_placeMarker(event.latLng);
            });

            // Drag the marker to change lat/lng
            lengkapi_data_marker.addListener('dragend', (event) => {
                document.getElementById('lengkapi_data_lat').value = event.latLng.lat();
                document.getElementById('lengkapi_data_lng').value = event.latLng.lng();
            });

            // Autocomplete for the location search
            const lengkapi_data_input = document.getElementById('lengkapi_data_location-input');
            lengkapi_data_autocompleteService = new google.maps.places.AutocompleteService();
            lengkapi_data_placesService = new google.maps.places.PlacesService(lengkapi_data_map);

            lengkapi_data_input.addEventListener('keydown', (event) => {
                const searchText = event.target.value;
                if (searchText.length > 0) {
                    fetch(`/dll/get-location-autocomplete?input=${searchText}`)
                    .then(response => response.json())
                    .then(data => {
                        // Display autocomplete results as before
                        lengkapi_data_displayAutocompleteResults(data.predictions,data.status);
            });
                } else {
                    lengkapi_data_clearAutocompleteResults();
                }
            });
        }

        function lengkapi_data_placeMarker(location) {
            lengkapi_data_marker.setPosition(location);
            document.getElementById('lengkapi_data_lat').value = location.lat();
            document.getElementById('lengkapi_data_lng').value = location.lng();
        }

        function lengkapi_data_displayAutocompleteResults(predictions, status) {
            const lengkapi_data_resultsContainer = document.getElementById('lengkapi_data_autocomplete-results');
            lengkapi_data_clearAutocompleteResults();
            if (status === google.maps.places.PlacesServiceStatus.OK) {
                predictions.forEach(prediction => {
                    const li = document.createElement('li');
                    li.textContent = prediction.description;
                    li.classList.add('autocomplete-item');
                    li.addEventListener('click', () => {
                        lengkapi_data_selectPlace(prediction.place_id);
                    });
                    lengkapi_data_resultsContainer.appendChild(li);
                });
            }
        }

        function lengkapi_data_clearAutocompleteResults() {
            const lengkapi_data_resultsContainer = document.getElementById('lengkapi_data_autocomplete-results');
            while (lengkapi_data_resultsContainer.firstChild) {
                lengkapi_data_resultsContainer.removeChild(lengkapi_data_resultsContainer.firstChild);
            }
        }

        function lengkapi_data_selectPlace(placeId) {
            lengkapi_data_placesService.getDetails({ placeId }, (place, status) => {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    // Center the map and move the marker to the selected place
                    lengkapi_data_map.setCenter(place.geometry.location);
                    lengkapi_data_map.setZoom(15);
                    lengkapi_data_placeMarker(place.geometry.location);
                    document.getElementById('lengkapi_data_location-input').value = place.formatted_address;
                    lengkapi_data_clearAutocompleteResults();
                }
            });
        }

        // Prevent form submission on Enter keypress in search input
        document.getElementById('lengkapi_data_location-input').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Prevent the form from submitting
            }
        });

        $('#lengkapi_data_sekolah_form').on('submit', function(e){
            e.preventDefault();
            return new swal({
                title: "Apakah Anda Yakin?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#1976D2",
                confirmButtonText: "Ya"
            }).then((result)=>{
                if(result.value)
                {
                    $.ajax({
                        url: "{{ route('admin.sekolah.lengkapi-data-sekolah') }}",
                        method: "POST",
                        data: new FormData(this),
                        dataType: "json",
                        contentType: false,
                        cache: false,
                        processData: false,
                        beforeSend: function()
                        {
                            return new swal({
                                title: "Checking...",
                                text: "Harap Menunggu",
                                imageUrl: "{{ asset('/images/preloader.gif') }}",
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });
                        },
                        success: function(data)
                        {
                            var html = '';
                            if(data.errors)
                            {
                                Swal.fire({
                                    icon: 'errors',
                                    title: data.errors,
                                    showConfirmButton: true
                                });
                            }
                            if(data.success)
                            {
                                Swal.fire({
                                    icon: 'success',
                                    title: data.success,
                                    showConfirmButton: true
                                });
                                $('#lengkapiDataModal').modal('hide');
                                $('#sekolah_table').DataTable().ajax.reload();
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
