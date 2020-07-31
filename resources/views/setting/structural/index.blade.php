@extends('layouts.master')

@section('title', 'Jabatan Struktural')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('setting-structural') as $breadcrumb)
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
        <i class="icon fa fa-sitemap"></i>
        <div>
            <h4>Jabatan Struktural</h4>
            <p class="mg-b-0">Daftar riwayat jabatan struktural jurusan</p>
        </div>
    </div>
    <div class="ml-auto">
        <button class="btn btn-teal btn-block mg-b-10 btn-add" data-toggle="modal" data-target="#modal-teach-status" style="color:white"><i class="fa fa-plus mg-r-10"></i> Tambah</button>
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
        <div class="col-md-10">
            <div class="card shadow-base mb-3">
                <div class="card-header">
                    <h6 class="card-title">
                        Riwayat Kepala Jurusan
                    </h6>
                </div>
                <div class="card-body bd-color-gray-lighter">
                    <table id="table-kajur" class="table display responsive table-status" url-target="{{route('ajax.structural.datatable')}}" url-type="Kajur">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">Nama Dosen</th>
                                <th class="text-center align-middle">Periode</th>
                                <th class="text-center align-middle">Homebase</th>
                                <th class="text-center align-middle no-sort">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div><!-- card-body -->
            </div>
            <div class="card shadow-base mb-3">
                <div class="card-header">
                    <h6 class="card-title">
                        Riwayat Kepala Program Studi
                    </h6>
                </div>
                <div class="card-body bd-color-gray-lighter">
                    <table id="table-kaprodi" class="table display responsive nowrap table-status" url-target="{{route('ajax.structural.datatable')}}" url-type="Kaprodi">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">Nama Dosen</th>
                                <th class="text-center align-middle">Periode</th>
                                <th class="text-center align-middle">Homebase</th>
                                <th class="text-center align-middle no-sort">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div><!-- card-body -->
            </div>
        </div>
    </div>
</div>
@include('setting.structural.form')
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection

@push('custom-js')
    <script>
        var dt_kajur = $('#table-kajur');
        var dt_kaprodi = $('#table-kaprodi');
        datatable(dt_kajur);
        datatable(dt_kaprodi);
        select2_dosen($('.select2-dosen'));

        //DataTable
        function datatable(table)
        {
            var url  = table.attr('url-target');
            var type = table.attr('url-type');

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

            table.DataTable({
                autoWidth: false,
                language: bahasa,
                processing: true,
                serverSide: true,
                ajax: {
                    url: url,
                    type: "post",
                    data: function(d){
                        d.type = type;
                        d._token = $('meta[name="csrf-token"]').attr('content');
                    }
                },
                columns: [
                    { data: 'nama', className: "min-mobile-p"},
                    { data: 'periode', className: "desktop"},
                    { data: 'study_program', className: "desktop"},
                    { data: 'aksi', className: "desktop text-center", orderable: false}
                ],
                order: [[ 1, "asc" ]]
            });
        }
        $('.table-status').on('click','.btn-edit',function(e){
            var cont = $('#modal-teach-status');
            var id  = $(this).data('id');
            var url = $(this).data('url');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    var option  = $("<option selected></option>").val(data.teacher.nidn).text(data.teacher.nama+' ('+data.teacher.nidn+')');
                    cont.find('select[name=_nidn]').val(null).html(option).trigger('change');

                    cont.find('input[name=_id]').val(id).end()
                    cont.find('select[name=jabatan]').val(data.jabatan).end()
                    cont.find('input[name=periode]').val(data.periode).end()
                    cont.find('select[name=kd_prodi]').val(data.kd_prodi).end()
                    cont.find('button[type=submit]').attr('data-id',id).end()
                    cont.modal('toggle').end();

                }
            });
        });

    </script>
@endpush
