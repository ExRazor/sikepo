@extends('layouts.master')

@section('title', 'Integrasi Kurikulum')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('academic-integration') as $breadcrumb)
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
        <i class="icon fa fa-link"></i>
        <div>
            <h4>Integrasi Kurikulum</h4>
            <p class="mg-b-0">Integrasi Kegiatan Penelitian/Pengabdian dalam Pembelajaran</p>
        </div>
    </div>
    @if (!Auth::user()->hasRole('kajur'))
    <div class="ml-auto">
        <a href="{{ route('academic.integration.create') }}" class="btn btn-teal btn-block mg-b-10" style="color:white"><i class="fa fa-plus mg-r-10"></i> Integrasi</a>
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
        <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
            <select id="kd_prodi_dosen_filter" class="form-control filter-box">
                <option value="">- Program Studi Dosen -</option>
                @foreach($studyProgram as $sp)
                <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
            <select id="kd_prodi_matkul_filter" class="form-control filter-box">
                <option value="">- Program Studi Matkul -</option>
                @foreach($studyProgram as $sp)
                <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="widget-2">
        <div class="card shadow-base mb-3">
            <div class="card-body bd-color-gray-lighter">
                <table id="table_curriculum_integration" class="table display responsive nowrap" data-order='[[ 0, "desc" ]]' data-page-length="25" url-target="{{route('ajax.curriculum-integration.datatable')}}">
                    <thead>
                        <tr>
                            <th class="text-center">Tahun Akademik</th>
                            <th class="text-center">Nama Dosen</th>
                            <th class="text-center">Mata Kuliah</th>
                            <th class="text-center">Judul Kegiatan</th>
                            <th class="text-center">Bentuk Integrasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div><!-- card-body -->
        </div>
    </div>
</div>
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
<script type="text/javascript">
    var table = $('#table_curriculum_integration');
    datatable(table);

    $('.filter-box').bind("keyup change", function(){
        table.DataTable().clear().destroy();
        datatable(table);
    });

    function datatable(table_ehm)
    {
        table_ehm.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: table_ehm.attr('url-target'),
                type: "post",
                data: function(d){
                    d.kd_prodi_dosen    = $('#kd_prodi_dosen_filter').val();
                    d.kd_prodi_matkul   = $('#kd_prodi_matkul_filter').val();
                    d._token            = $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: 'akademik', },
                { data: 'dosen', },
                { data: 'matkul', },
                { data: 'kegiatan', },
                { data: 'bentuk_integrasi', },
                { data: 'aksi', }
            ],
            columnDefs: [
                {
                    targets: [5],
                    orderable: false,
                    className: 'text-center'
                },
                {
                    targets: [0],
                    className: 'text-center'
                },
            ],
            hideEmptyCols: [ 5 ],
            autoWidth: false,
            language: {
                url: "/assets/lib/datatables.net/indonesian.json",
            }
        })
    }
</script>
@endsection
