@extends('layouts.master')

@section('title', 'Data Kurikulum')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('academic-curriculum') as $breadcrumb)
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
        <i class="icon fa fa-atom"></i>
        <div>
            <h4>Data Kurikulum</h4>
            <p class="mg-b-0">Olah Data Kurikulum atau Mata Kuliah</p>
        </div>
    </div>
    @if (!Auth::user()->hasRole('kajur'))
    <div class="ml-auto">
        <div class="row">
            <div class="col-6 pr-1">
                <a href="{{ route('academic.curriculum.create') }}" class="btn btn-teal btn-block mg-b-10" style="color:white"><i class="fa fa-plus mg-r-10"></i> Matkul</a>
            </div>
            <div class="col-6 pl-1">
                <button class="btn btn-primary btn-block mg-b-10 text-white" data-toggle="modal" data-target="#modal-import-curriculum"><i class="fa fa-file-import mg-r-10"></i> Impor</button>
            </div>
        </div>
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
        @if (!Auth::user()->hasRole('kaprodi'))
        <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
            <input id="nm_jurusan" type="hidden" value="{{setting('app_department_name')}}">
            <select id="kd_prodi_filter" class="form-control filter-box">
                <option value="">- Program Studi -</option>
                @foreach($studyProgram as $sp)
                <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
            <select id="versi_filter" class="form-control filter-box">
                <option value="">- Tahun Kurikulum -</option>
                @foreach($thn_kurikulum as $tk)
                <option>{{$tk->versi}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
            <select id="semester_filter" class="form-control filter-box">
                <option value="">- Semester -</option>
                @for($i=1;$i<=8;$i++)
                <option>{{$i}}</option>
                @endfor
            </select>
        </div>
        <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
            <select id="jenis_filter" class="form-control filter-box">
                <option value="">- Jenis -</option>
                <option>Wajib</option>
                <option>Pilihan</option>
            </select>
        </div>
    </div>
    <div class="widget-2">
        <div class="card shadow-base mb-3">
            <div class="card-header nm_jurusan">
                <h6 class="card-title">
                    <span class="nm_jurusan">
                    @if(Auth::user()->hasRole('kaprodi'))
                    {{ Auth::user()->studyProgram->nama }}

                    @else
                    {{ setting('app_department_name') }}
                    @endif
                     </span>
                </h6>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <table id="table_curriculum" class="table display responsive nowrap" data-order='[[ 1, "asc" ]]' data-page-length="25" url-target="{{route('ajax.curriculum.datatable')}}">
                    <thead>
                        <tr>
                            <th class="text-center">Mata Kuliah</th>
                            <th class="text-center">Program Studi</th>
                            <th class="text-center">Semester</th>
                            <th class="text-center">Jenis</th>
                            <th class="text-center">Tahun<br>Kurikulum</th>
                            <th class="text-center no-sort" width="50">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div><!-- card-body -->
        </div>
    </div>
</div>
@if (!Auth::user()->hasRole('kajur'))
@include('academic.curriculum.form-import')
@endif
@endsection

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection

@section('custom-js')
<script>
    var table = $('#table_curriculum');
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
                    d.kd_prodi  = $('#kd_prodi_filter').val();
                    d.versi     = $('#versi_filter').val();
                    d.semester  = $('#semester_filter').val();
                    d.jenis     = $('#jenis_filter').val();
                    d._token    = $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: 'matkul', },
                { data: 'prodi', },
                { data: 'semester', },
                { data: 'jenis', },
                { data: 'versi', },
                { data: 'aksi', }
            ],
            columnDefs: [
                {
                    targets: 5,
                    orderable: false,
                    className: 'text-center'
                },
                {
                    targets: [2,3,4],
                    className: 'text-center'
                },
            ],
            hideEmptyCols: [ 1, 5 ],
            autoWidth: false,
            language: {
                url: "/assets/lib/datatables.net/indonesian.json",
            }
        })
    }
</script>
@endsection
