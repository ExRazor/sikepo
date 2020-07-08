@extends('layouts.master')

@section('title', 'Kuota Mahasiswa')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('student-quota') as $breadcrumb)
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
        <i class="icon fa fa-sort-amount-down"></i>
        <div>
            <h4>Kuota Mahasiswa</h4>
            <p class="mg-b-0">Olah Data Kuota Mahasiswa</p>
        </div>
    </div>
    @if(!Auth::user()->hasRole('kajur'))
    <div class="ml-auto">
        <button class="btn btn-teal btn-block mg-b-10 text-white btn-add" data-toggle="modal" data-target="#modal-student-quota"><i class="fa fa-plus mg-r-10"></i> Kuota Mahasiswa</button>
    </div>
    @endif
</div>

<div class="br-pagebody">
    @if (session()->has('flash.message'))
        <div class="alert alert-{{ session('flash.class') }}" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ session('flash.message') }}
        </div>
    @endif
    <div class="row">
        @if(!Auth::user()->hasRole('kaprodi'))
        <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
            <select class="form-control filter-box" name="kd_prodi_filter">
                <option value="">- Semua Program Studi -</option>
                @foreach($studyProgram as $sp)
                <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                @endforeach
            </select>
        </div>
        @else
        <input type="hidden" name="kd_prodi" value="{{Auth::user()->kd_prodi}}">
        @endif
    </div>
    <div class="widget-2">
        <div class="card shadow-base mb-3">
            <div class="card-header nm_jurusan">
                <h6 class="card-title">
                    @if(Auth::user()->hasRole('kaprodi'))
                    {{ Auth::user()->studyProgram->nama }}
                    @else
                    {{ setting('app_department_name') }}
                    @endif
                </h6>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <table id="table_student_quota" class="table display responsive nowrap" data-order='[[ 1, "desc" ]]' url-target="{{route('ajax.student.quota.datatable')}}">
                    <thead>
                        <tr>
                            <th class="text-center">Program Studi</th>
                            <th class="text-center defaultSort">Tahun</th>
                            <th class="text-center">Daya Tampung</th>
                            <th class="text-center">Calon Mahasiswa<br>Pendaftar</th>
                            <th class="text-center">Calon mahasiswa<br>Lulus Seleksi</th>
                            @if(!Auth::user()->hasRole('kajur'))
                            <th class="text-center no-sort">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div><!-- card-body -->
        </div>
    </div>
</div>
@if(!Auth::user()->hasRole('kajur'))
    @include('student.quota.form')
@endif
@endsection

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net/js/dataTables.hideEmptyColumns.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection

@section('custom-js')
<script>
    var table = $('#table_student_quota');
    datatable(table);

    $('.filter-box').bind("keyup change", function(){
        table.DataTable().clear().destroy();
        datatable(table);
    });

    function datatable(table_ehm)
    {
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

        table_ehm.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: table_ehm.attr('url-target'),
                type: "post",
                data: function(d){
                    d.prodi   = $('select.filter-box[name=kd_prodi_filter]').val();
                    d._token = $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                        { data: 'prodi', },
                        { data: 'tahun', },
                        { data: 'daya_tampung', },
                        { data: 'calon_pendaftar', },
                        { data: 'calon_lulus', },
                        { data: 'aksi', }
                    ],
            columnDefs: [
                {
                    targets: 5,
                    orderable: false
                },
                {
                    targets: [1,2,3,4,5],
                    className: 'text-center'
                },
            ],
            hideEmptyCols: [ 0,5 ],
            autoWidth: false,
            language: bahasa
        })
    }

    $('#table_student_quota').on('click','.btn-edit-quota',function(e){
        e.preventDefault();

        var id  = $(this).data('id');
        var url = $(this).attr('url-target');

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $('#modal-student-quota')
                    .find('input[name=_id]').val(id).end()
                    .find('select[name=kd_prodi]').val(data.kd_prodi).end()
                    .find('select[name=id_ta]').val(data.id_ta).end()
                    .find('input[name=daya_tampung]').val(data.daya_tampung).end()
                    .find('input[name=calon_pendaftar]').val(data.calon_pendaftar).end()
                    .find('input[name=calon_lulus]').val(data.calon_lulus).end()
                    .find('button[type=submit]').attr('data-id',id).end()
                    .modal('toggle').end();
            }
        });
    });
</script>
@endsection
