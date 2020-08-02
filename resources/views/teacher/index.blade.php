@extends('layouts.master')

@section('title', 'Data Dosen')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('teacher') as $breadcrumb)
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
        <i class="icon fa fa-chalkboard-teacher"></i>
        <div>
            <h4>Dosen Program Studi</h4>
            <p class="mg-b-0">Olah Data Dosen</p>
        </div>
    </div>
    <div class="ml-auto">
        <div class="d-flex">
            <div class="mr-2">
                <a href="{{ route('teacher.list.create') }}" class="btn btn-teal btn-block" style="color:white"><i class="fa fa-plus mg-r-10"></i> Data Dosen</a>
            </div>
            <div>
                <button class="btn btn-indigo btn-block text-white" data-toggle="modal" data-target="#modal-export-teacher"><i class="fa fa-file-excel mg-r-10"></i> Ekspor</button>
           </div>
        </div>
    </div>
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
    @if(Auth::user()->role!='kaprodi')
    <div class="row">
        @if(Auth::user()->role!='kajur')
        {{-- <div class="col-md mb-2">
            <select id="fakultas" class="form-control" name="kd_jurusan" data-placeholder="Pilih Jurusan" required>
                <option value="0">- Semua Jurusan -</option>
                @foreach($faculty as $f)
                    @if($f->department->count())
                    <optgroup label="{{$f->nama}}">
                        @foreach($f->department as $d)
                        <option value="{{$d->kd_jurusan}}" {{ $d->kd_jurusan == setting('app_department_id') ? 'selected' : ''}}>{{$d->nama}}</option>
                        @endforeach
                    </optgroup>
                    @endif
                @endforeach
            </select>
        </div> --}}
        @endif
        <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
            <select class="form-control filter-box" name="kd_prodi">
                <option value="">- Semua Program Studi -</option>
                @foreach($studyProgram as $sp)
                <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endif
    <div class="widget-2">
        <div class="card shadow-base mb-3">
            <div class="card-header">
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
                <table id="table_teacher" class="table display responsive nowrap" data-order='[[ 0, "asc" ]]' data-page-length='25' url-target="{{route('ajax.teacher.datatable')}}">
                    <thead>
                        <tr>
                            <th class="text-center" data-priority="1">Nama</th>
                            <th class="text-center" data-priority="3">Program Studi</th>
                            <th class="text-center">Ikatan Kerja</th>
                            @if(!Auth::user()->hasRole('kajur'))
                            <th class="text-center no-sort" data-priority="2">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div><!-- card-body -->
        </div>
    </div>
</div>
@include('teacher.form-export');
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

@push('custom-js')
<script>
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

    var table = $('#table_teacher').DataTable({
                    autoWidth: false,
                    columnDefs: [ {
                        "targets"  : 'no-sort',
                        "orderable": false,
                    }],
                    hideEmptyCols: [ 3 ],
                    language: bahasa,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: $('#table_teacher').attr('url-target'),
                        type: "post",
                        data: function(d){
                            d.prodi = $('select.filter-box[name=kd_prodi]').val();
                            d._token = $('meta[name="csrf-token"]').attr('content')
                        }
                    },
                    columns: [
                        { data: 'nama', },
                        { data: 'study_program', },
                        { data: 'ikatan_kerja', },
                        { data: 'aksi', }
                    ],
                });

    $('.filter-box').bind("keyup change", function(){
        table.ajax.reload();
    });
</script>
@endpush
