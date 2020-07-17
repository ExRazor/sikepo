@extends('layouts.master')

@section('title', 'Jadwal Kurikulum')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('academic-schedule') as $breadcrumb)
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
            <h4>Jadwal Kurikulum</h4>
            <p class="mg-b-0">Olah Data Jadwal Kurikulum</p>
        </div>
    </div>
    @if (!Auth::user()->hasRole('kajur'))
    <div class="ml-auto">
        <a href="{{ route('academic.schedule.create') }}" class="btn btn-teal btn-block mg-b-10" style="color:white"><i class="fa fa-plus mg-r-10"></i> Jadwal Matkul</a>
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
    @if (!Auth::user()->hasRole('kaprodi'))
    <div class="row">
        <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
            <div class="input-group">
                <select id="tahun_filter" class="form-control filter-box mr-3">
                    <option value="">- Tahun -</option>
                    @foreach($ay_year as $year)
                    <option value="{{$year->tahun_akademik}}" @if($year->tahun_akademik == current_academic()->tahun_akademik) selected @endif>{{$year->tahun_akademik}}</option>
                    @endforeach
                </select>
                <select id="semester_filter" class="form-control filter-box">
                    <option value="">- Semester -</option>
                    @foreach($ay_semester as $semester)
                    <option value="{{$semester->semester}}" @if($semester->semester == current_academic()->semester) selected @endif>{{$semester->semester}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
            <select id="kd_prodi_filter" class="form-control filter-box">
                <option value="">- Program Studi -</option>
                @foreach($studyProgram as $sp)
                <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endif
    <div class="widget-2">
        <div class="card">
            <div class="card-body">
                <div class="card-block pd-20">
                    <table id="table_curriculum_schedule" class="table display responsive nowrap" data-order='[[ 1, "asc" ]]' data-page-length="25" url-target="{{route('ajax.schedule.datatable')}}">
                        <thead>
                            <tr>
                                <th class="text-center">Matkul</th>
                                <th class="text-center">Akademik</th>
                                <th class="text-center">Jumlah SKS</th>
                                <th class="text-center">Nama Dosen</th>
                                <th class="text-center">Sesuai Prodi</th>
                                <th class="text-center">Sesuai Bidang</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
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
<script src="{{asset('assets/lib')}}/datatables.net/js/dataTables.hideEmptyColumns.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection

@push('custom-js')
<script type="text/javascript">
    var table = $('#table_curriculum_schedule');
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
                    d.tahun     = $('#tahun_filter').val();
                    d.semester  = $('#semester_filter').val();
                    d.kd_prodi  = $('#kd_prodi_filter').val();
                    d._token    = $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: 'matkul', },
                { data: 'akademik', },
                { data: 'sks', },
                { data: 'dosen', },
                { data: 'sesuai_prodi', },
                { data: 'sesuai_bidang', },
                { data: 'aksi', }
            ],
            columnDefs: [
                {
                    targets: [4,5,6],
                    orderable: false,
                    className: 'text-center'
                },
                {
                    targets: [2],
                    className: 'text-center'
                },
            ],
            hideEmptyCols: [ 6 ],
            autoWidth: false,
            language: {
                url: "/assets/lib/datatables.net/indonesian.json",
            }
        })
    }
</script>
@endpush
