@extends('layouts.master')

@section('title', 'Data Mahasiswa')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

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
    <div class="ml-auto d-inline-flex">
        <a href="{{ route('student.add') }}" class="btn btn-teal btn-block mg-y-10 mg-r-10" style="color:white"><i class="fa fa-plus mg-r-10"></i> Mahasiswa</a>
        <button class="btn btn-primary btn-block mg-y-10 text-white" data-toggle="modal" data-target="#modal-import-student"><i class="fa fa-file-import mg-r-10"></i> Impor</button>
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
        <div class="col-12">
            <form action="{{route('ajax.student.filter')}}" id="filter-student" method="POST">
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
                        <select class="form-control" name="kd_prodi">
                            <option value="">- Semua Program Studi -</option>
                            @foreach($studyProgram as $sp)
                            <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
                        <select class="form-control" name="angkatan">
                            <option value="">- Semua Angkatan -</option>
                            @foreach($angkatan as $a)
                            <option value="{{$a->tahun_akademik}}">{{$a->tahun_akademik}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
                        <div class="input-group">
                            <select class="form-control mr-3" id="status_mahasiswa" name="status">
                                <option value="">- Pilih Status -</option>
                                @foreach($status as $s)
                                    <option value="{{$s->status}}">{{$s->status}}</option>
                                @endforeach
                            </select>
                            <div>
                                <button type="submit" class="btn btn-purple btn-block " style="color:white">Cari</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
                <table id="table_student" class="table display responsive" data-sort="desc">
                    <thead>
                        <tr>
                            <th class="text-center">Nama / NIM</th>
                            <th class="text-center none">Tanggal Lahir</th>
                            <th class="text-center">Program Studi</th>
                            <th class="text-center defaultSort none">Angkatan</th>
                            <th class="text-center none">Kelas</th>
                            <th class="text-center none">Program</th>
                            <th class="text-center none">Status</th>
                            <th class="text-center no-sort none">
                                @if(!Auth::user()->hasRole('kajur'))
                                Aksi
                                @endif
                            </th>
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

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection

@section('custom-js')
<script>
    $('#table_student').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('ajax.student.datatable') }}",
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
        order: [[3, 'desc']],
        columnDefs: [
                        {
                            "targets": 7,
                            "orderable": false
                        }
                    ],
        pageLength: 25,
        responsive: true,
        autoWidth: false,
        language: {
            "sProcessing":   "Sedang proses...",
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
        }
    })
</script>
@endsection
