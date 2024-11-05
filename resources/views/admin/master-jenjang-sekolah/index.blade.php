@extends('admin.layouts.app')
@section('title', 'Admin | Master Jenjang Sekolah')
@section('subheader', 'Master Jenjang Sekolah')

@section('css')
<link href="{{ asset('/adminto/assets/libs/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/adminto/assets/libs/datatables/responsive.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/adminto/assets/libs/datatables/buttons.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/adminto/assets/libs/datatables/select.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/adminto/assets/libs/custombox/custombox.min.css') }}" rel="stylesheet">
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

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #000000; /* Black background */
        color: #FFFFFF; /* White text for contrast */
        border-color: #000000; /* Black border */
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
                        <button class="btn btn-icon waves-effect waves-light btn-primary" data-toggle="modal" data-target="#createModal" id="create" name="create">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <table id="master_jenjang_sekolah_table" class="table table-bordered table-bordered dt-responsive nowrap">
                    <thead>
                        <tr>
                            <th width="10%">No</th>
                            <th>Nama</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div> <!-- end row -->
    <div id="createModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="createModalLabel">Tambah Data</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <span id="form_result"></span>
                    <form class="form-horizontal" id="master_jenjang_sekolah_form" method="POST" data-parsley-validate novalidate>
                        @csrf
                        <div class="form-group">
                            <label for="nama" class="control-label">Nama<span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="nama" parsley-trigger="change" required
                            placeholder="Masukan nama ..." class="form-control">
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

    <div id="detail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="detailModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="detail-title">Detail Data</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="detail_nama" class="control-label col-md-4">Nama </label>
                        <div class="col-md-8">
                            <span id="detail_nama"></span>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirm" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirm">Konfirmasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 class="text-center" style="margin: 0;">Apakah anda yakin menghapus?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" name="ok_button" id="ok_button" class="btn btn-danger waves-effect width-md waves-light">Ok</button>
                    <button class="btn btn-primary waves-effect width-md waves-light" type="button" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
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
    <!-- Validation js (Parsleyjs) -->
    <script src="{{ asset('/adminto/assets/libs/parsleyjs/parsley.min.js') }}"></script>

    <!-- validation init -->
    <script src="{{ asset('/adminto/assets/js/pages/form-validation.init.js') }}"></script>
    <script src="{{ asset('js/sweetalert.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            var dataTables = $('#master_jenjang_sekolah_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.master-jenjang-sekolah.index') }}",
                },
                columns:[
                    {
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false
                    },

                ]
            });
        });

        $(document).on('click', '.detail', function(){
            var id = $(this).attr('id');
            var url = "{{ route('admin.master-jenjang-sekolah.detail', ['id' => ":id"]) }}";
            url = url.replace(":id", id);
            $.ajax({
                url: url,
                dataType: "json",
                success: function(data)
                {
                    $('#detail-title').text('Detail Data');
                    $('#detail_nama').text(data.result.nama);
                    $('#detail').modal('show');
                }
            });
        });
        $('#create').click(function(){
            $('#master_jenjang_sekolah_form')[0].reset();
            $('#aksi_button').text('Save');
            $('#aksi_button').prop('disabled', false);
            $('.modal-title').text('Add Data');
            $('#aksi_button').val('Save');
            $('#aksi').val('Save');
            $('#form_result').html('');
        });
        $('#master_jenjang_sekolah_form').on('submit', function(e){
            e.preventDefault();
            if($('#aksi').val() == 'Save')
            {
                $.ajax({
                    url: "{{ route('admin.master-jenjang-sekolah.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
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
                            $('#aksi_button').prop('disabled', false);
                            $('#master_jenjang_sekolah_form')[0].reset();
                            $('#aksi_button').text('Save');
                            $('#master_jenjang_sekolah_table').DataTable().ajax.reload();
                        }
                        if(data.success)
                        {
                            html = '<div class="alert alert-success">'+data.success+'</div>';
                            $('#aksi_button').prop('disabled', false);
                            $('#master_jenjang_sekolah_form')[0].reset();
                            $('#aksi_button').text('Save');
                            $('#master_jenjang_sekolah_table').DataTable().ajax.reload();
                        }

                        $('#form_result').html(html);
                    }
                });
            }
            if($('#aksi').val() == 'Edit')
            {
                $.ajax({
                    url: "{{ route('admin.master-jenjang-sekolah.update') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
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
                            $('#aksi_button').text('Save');
                        }
                        if(data.success)
                        {
                            $('#master_jenjang_sekolah_form')[0].reset();
                            $('#aksi_button').prop('disabled', false);
                            $('#aksi_button').text('Save');
                            $('#master_jenjang_sekolah_table').DataTable().ajax.reload();
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
        $(document).on('click', '.edit', function(){
            var id = $(this).attr('id');
            $('#form_result').html('');
            var url = "{{ route('admin.master-jenjang-sekolah.edit', ['id' => ":id"]) }}";
            url = url.replace(":id", id);
            $.ajax({
                url: url,
                dataType: "json",
                success: function(data)
                {
                    $('#nama').val(data.result.nama);
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
        var user_id;
        $(document).on('click', '.delete', function(){
            user_id = $(this).attr('id');
            $('.modal-title').text('Konfirmasi');
            $('#ok_button').prop('disabled', false);
            $('#confirmModal').modal('show');
            $('#ok_button').text('Ok');
        });

        $('#ok_button').click(function(){
            var url = "{{ route('admin.master-jenjang-sekolah.destroy', ['id' => ":id"]) }}";
            url = url.replace(":id", user_id);
            $.ajax({
                url: url,
                beforeSend: function(){
                    $('#ok_button').text('Deleting....');
                    $('#ok_button').prop('disabled', true);
                },
                success: function(data)
                {
                    $('#ok_button').prop('disabled', false);
                    $('#confirmModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil di hapus',
                        showConfirmButton: true
                    });
                    $('#master_jenjang_sekolah_table').DataTable().ajax.reload();
                }
            });
        });
    </script>
@endsection
