@extends('layouts.master')

@section('title', 'Manajemen User')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('setting-user') as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div>

<div class="br-pagetitle">
    <div class="d-flex pl-0 mb-3">
        <i class="icon fa fa-users"></i>
        <div>
            <h4>Manajemen User</h4>
            <p class="mg-b-0">Daftar Manajemen User</p>
        </div>
    </div>
    <div class="ml-auto">
        <button class="btn btn-teal btn-block mg-b-10 btn-add" data-toggle="modal" data-target="#modal-setting-user" style="color:white"><i class="fa fa-plus mg-r-10"></i> User</button>
    </div>
</div>

<div class="br-pagebody">
    @if($errors->any())
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
        </button>
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
    @endif
    <div class="row widget-2">
        <div class="col-md-9 order-2 order-md-1">
            <div class="card shadow-base mb-3">
                <div class="card-header">
                    <h6 class="card-title">
                        Daftar User
                    </h6>
                </div>
                <div class="card-body bd-color-gray-lighter">
                    <table id="table-user" class="table display responsive" url-target="{{route('ajax.user.datatable.user')}}">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">Username</th>
                                <th class="text-center align-middle">Nama User</th>
                                <th class="text-center align-middle">Role</th>
                                <th class="text-center align-middle">Status</th>
                                <th class="text-center align-middle">Password</th>
                                <th class="text-center align-middle no-sort">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div><!-- card-body -->
            </div>
            <div class="card shadow-base mb-3">
                <div class="card-header">
                    <h6 class="card-title">
                        Daftar User
                    </h6>
                </div>
                <div class="card-body bd-color-gray-lighter">
                    <table id="table-dosen" class="table display responsive nowrap" url-target="{{route('ajax.user.datatable.dosen')}}">
                        <thead>
                            <tr>
                                <th class="text-center">Username</th>
                                <th class="text-center">Nama User</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Password</th>
                                <th class="text-center no-sort">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div><!-- card-body -->
            </div>
        </div>
        <div class="col-md-3 order-1 order-md-2">
            <div class="alert alert-info">
                <h5>ROLE UNTUK USER</h5>
                <hr>
                <ul class="pd-l-12">
                    <li>
                        <strong>Admin</strong><br>
                        Hak akses tertinggi yang dapat mengelola seluruh data dalam aplikasi, termasuk setelan dan manajemen user
                    </li>
                    <li>
                        <strong>Kajur</strong><br>
                        Hak akses untuk kepala jurusan. Hanya dapat melihat data untuk seluruh program studi, serta mengelola data keuangan Fakultas dan Program Studi.
                    </li>
                    <li>
                        <strong>Kaprodi</strong><br>
                        Hak akses untuk kepala proram studi. Hak akses ini seperti namanya yaitu memberikan hak untuk mengelola data berdasarkan program studi yang diampu.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@include('setting.user.form')
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection

@push('custom-js')
    <script>
        //DataTable
        var bahasa = {
                "sProcessing":   '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>',
                "sLengthMenu":   "Tampilan _MENU_ entri",
                "sZeroRecords":  "Tidak ditemukan data",
                "sInfo":         "Tampilan _START_ sampai _END_ dari _TOTAL_ entri",
                "sInfoEmpty":    "Tampilan 0 hingga 0 dari 0 entri",
                "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                "sInfoPostFix":  "",
                'searchPlaceholder': 'Cari...',
                'sSearch': '',
                "sUrl":          "",
                "oPaginate": {
                    "sFirst":    "Awal",
                    "sPrevious": "Balik",
                    "sNext":     "Lanjut",
                    "sLast":     "Akhir"
                }
        };
        var dt_user = $('#table-user').DataTable({
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: {
                url: $('#table-user').attr('url-target'),
                type: "post",
                data: function(d){
                    d._token = $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: 'username', className: "min-mobile-p"},
                { data: 'name', className: "desktop"},
                { data: 'role', className: "min-mobile-p text-center"},
                { data: 'status', className: "desktop text-center"},
                { data: 'password', className: "desktop text-center"},
                { data: 'aksi', className: "desktop text-center", orderable: false}
            ],
            order: [[ 2, "asc" ]]
        });
        var dt_dosen = $('#table-dosen').DataTable({
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: {
                url: $('#table-dosen').attr('url-target'),
                type: "post",
                data: function(d){
                    d._token = $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: 'username', className: "min-mobile-p"},
                { data: 'name', className: "min-mobile-p"},
                { data: 'status', className: "min-mobile-p text-center"},
                { data: 'password', className: "desktop text-center"},
                { data: 'aksi', className: "desktop text-center", orderable: false}
            ],
            order: [[ 1, "asc" ]]
        });

        //Button Edit
        $('#table-user').on('click','.btn-edit',function(){

            var id = $(this).data('id');
            var url  = $(this).attr('url-target');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (data) {

                    var cont        = $('#modal-setting-user');
                    var selectProdi = cont.find('select#kd_prodi');

                    switch(data.role) {
                        case 'admin':
                            $('input:radio[name=role][value="admin"]').prop('checked',true).trigger('change');
                        break;
                        case 'kajur':
                            $('input:radio[name=role][value="kajur"]').prop('checked',true).trigger('change');
                        break;
                        case 'kaprodi':
                            $('input:radio[name=role][value="kaprodi"]').prop('checked',true).trigger('change');
                        break;
                    }

                    if(data.role == 'admin') {
                        cont.find('input[name=username]').val(data.username);
                    } else {
                        var option_username  = $("<option selected></option>").val(data.username).text(data.name+' ('+data.username+')');
                        cont.find('select[name=username]').val(null).html(option_username).trigger('change');

                        if (data.role == 'kaprodi') {
                            selectProdi.prop('disabled',false);
                            selectProdi.prop('required',true);
                            selectProdi.val(data.kd_prodi);
                        } else {
                            selectProdi.prop('disabled',true);
                            selectProdi.prop('required',false);
                            selectProdi.val(null);
                        }
                    }



                    $('#modal-setting-user')
                        .find('input[name=id]').val(id).end()
                        .find('input[name=name]').val(data.name).end()
                        // .find('input[name=username]').val(data.username).prop('readonly',false).end()
                        .modal('toggle').end();

                }
            });
        })

        //Reset Password Button
        $('#table-user, #table-dosen').on('click','.reset-password',function(e){
            e.preventDefault();

            var id   = $(this).data('id');
            var url  = base_url+'/setting/user/resetpass';

            Swal.fire({
                title: 'Reset password?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Reset!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: url,
                        data: {
                            id:id
                        },
                        type: 'POST',
                        dataType: 'json',
                        beforeSend: function() {
                            Swal.showLoading()
                        },
                        success: function (state) {
                            if(state.type=='success') {
                                Swal.fire({
                                    title: state.title,
                                    html:
                                        '<input class="swal2-input" value="'+state.password+'" readonly>',
                                    onClose: () => {
                                            location.reload();
                                        }
                                });
                            } else {
                                Swal.fire({
                                    title: state.title,
                                    text: state.message,
                                    type: state.type,
                                    timer: 2000,
                                });
                            }
                        }
                    });
                    // console.log(result.value);
                }
            })
        })

        //Function Set Active
        if (typeof setActive != 'function') {

            function setActive(button) {
            var button  = $(button);
            var route   = button.attr('data-route');
            var dt_id   = button.parent().parent().parent().parent().attr('id');

            $.ajax({
                type: "GET",
                url: route,
                success: function(result){
                    if(result) {
                        // Swal.fire({
                        //     title: result.title,
                        //     text: result.message,
                        //     type: result.type,
                        //     onClose: () => {
                        //         if(dt_id == 'table-user') {
                        //             dt_user.ajax.reload();
                        //         } else {
                        //             dt_dosen.ajax.reload();
                        //         }
                        //     }
                        // });
                        if(dt_id == 'table-user') {
                            dt_user.ajax.reload();
                        } else {
                            dt_dosen.ajax.reload();
                        }
                    } else {
                        Swal.fire({
                            title: "Terjadi Kesalahan",
                            text: "Gagal menyetel status.",
                            type: "error",
                            timer: 4000,
                            onClose: () => {
                                location.reload();
                            }
                        });
                    }
                },
                error: function (request) {
                    // Show an alert with the result
                    Swal.fire({
                        title: "Terjadi Kesalahan",
                        text: "Gagal menyetel status.",
                        type: "error",
                        timer: 4000,
                        onClose: () => {
                            location.reload();
                        }
                    });
                },
            });

            }
        }
    </script>
@endpush
