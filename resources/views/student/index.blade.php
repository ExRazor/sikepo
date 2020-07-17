@extends('layouts.master')

@section('title', 'Data Mahasiswa')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('student') as $breadcrumb)
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
        <i class="icon fa fa-user-graduate"></i>
        <div>
            <h4>Data Mahasiswa</h4>
            <p class="mg-b-0">Olah Data Mahasiswa</p>
        </div>
    </div>
    @if(!Auth::user()->hasRole('kajur'))
    <div class="ml-auto">
        <div class="row">
            <div class="col-6 pr-2">
                <a href="{{ route('student.list.create') }}" class="btn btn-teal btn-block text-white"><i class="fa fa-plus mg-r-10"></i> Tambah</a>
            </div>
            <div class="col-6 pl-1">
                <button class="btn btn-primary btn-block text-white" data-toggle="modal" data-target="#modal-import-student"><i class="fa fa-file-import mg-r-10"></i> Impor</button>
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
        @if(Auth::user()->hasRole('admin'))
        {{-- <div class="mg-r-10">
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
        @if(!Auth::user()->hasRole('kaprodi'))
        <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
            <select class="form-control filter-box" name="kd_prodi">
                <option value="">- Semua Program Studi -</option>
                @foreach($studyProgram as $sp)
                <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
            <select class="form-control filter-box" name="angkatan">
                <option value="">- Semua Angkatan -</option>
                @foreach($angkatan as $a)
                <option value="{{$a->tahun_akademik}}">{{$a->tahun_akademik}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
            <select class="form-control filter-box" name="status">
                <option value="">- Pilih Status -</option>
                @foreach($status as $s)
                    <option value="{{$s->status}}">{{$s->status}}</option>
                @endforeach
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
                <table id="table_student" class="table display responsive nowrap" data-order='[[ 3, "desc" ]]' data-page-length='50' url-target="{{route('ajax.student.datatable')}}">
                    <thead>
                        <tr>
                            <th class="text-center" data-priority="1">Nama / NIM</th>
                            <th class="text-center">Tanggal Lahir</th>
                            <th class="text-center" data-priority="2">Program Studi</th>
                            <th class="text-center" data-priority="3">Angkatan</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Program</th>
                            <th class="text-center" data-priority="4">Status</th>
                            <th class="text-center no-sort" data-priority="5">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div><!-- card-body -->
        </div>
    </div>
</div>
@include('student.form-import')
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

    var table = $('#table_student').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: $('#table_student').attr('url-target'),
            type: "post",
            data: function(d){
                d.prodi     = $('select.filter-box[name=kd_prodi]').val();
                d.angkatan  = $('select.filter-box[name=angkatan]').val();
                d.status    = $('select.filter-box[name=status]').val();
                d._token = $('meta[name="csrf-token"]').attr('content')
            }
        },
        columns: [
                    { data: 'nama', },
                    { data: 'tgl_lhr', },
                    { data: 'study_program', },
                    { data: 'angkatan', },
                    { data: 'kelas', },
                    { data: 'program', },
                    { data: 'status', },
                    { data: 'aksi', }
                ],
        columnDefs: [
            {
                "targets": 7,
                "orderable": false
            }
        ],
        hideEmptyCols: [ 7 ],
        autoWidth: false,
        language: bahasa
    })

    $('.filter-box').bind("keyup change", function(){
        table.ajax.reload();
    });
</script>
@endpush
