@extends('layouts.master')

@section('title', 'Kepuasan Akademik')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('academic-satisfaction') as $breadcrumb)
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
        <i class="icon fa fa-percentage"></i>
        <div>
            <h4>Kepuasan Akademik</h4>
            <p class="mg-b-0">Olah Data Kepuasan Akademik</p>
        </div>
    </div>
    @if (!Auth::user()->hasRole('kajur'))
    <div class="ml-auto">
        <a href="{{ route('academic.satisfaction.create') }}" class="btn btn-teal btn-block mg-b-10" style="color:white"><i class="fa fa-plus mg-r-10"></i> Tambah Data</a>
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
            <select id="kd_prodi_filter" class="form-control filter-box" >
                <option value="">- Program Studi -</option>
                @foreach($studyProgram as $sp)
                <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
            <select id="tahun_filter" class="form-control filter-box">
                <option value="">- Tahun -</option>
                @foreach($academicYear as $ay)
                <option value="{{$ay->tahun_akademik}}">{{$ay->tahun_akademik}}</option>
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
                <table id="table_academic_satisfaction" class="table display responsive nowrap" data-order='[[ 1, "desc" ]]' url-target="{{route('ajax.academic-satisfaction.datatable')}}">
                    <thead>
                        <tr>
                            <th class="text-center">Program Studi</th>
                            <th class="text-center">Tahun</th>
                            <th class="text-center">Sangat Baik</th>
                            <th class="text-center">Baik</th>
                            <th class="text-center">Cukup</th>
                            <th class="text-center">Kurang</th>
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
<script src="{{asset('assets/lib')}}/datatables.net/js/dataTables.hideEmptyColumns.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection

@section('custom-js')
<script type="text/javascript">
    var table = $('#table_academic_satisfaction');
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
                    d.kd_prodi_filter = $('#kd_prodi_filter').val();
                    d.tahun_filter    = $('#tahun_filter').val();
                    d._token          = $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: 'prodi', },
                { data: 'tahun', },
                { data: 'sangat_baik', },
                { data: 'baik', },
                { data: 'cukup', },
                { data: 'kurang', },
                { data: 'aksi', }
            ],
            columnDefs: [
                {
                    targets: [6],
                    orderable: false,
                    className: 'text-center'
                },
                {
                    targets: [1,2,3,4,5,6],
                    className: 'text-center'
                },
            ],
            hideEmptyCols: [ 0 ],
            autoWidth: false,
            language: {
                url: "/assets/lib/datatables.net/indonesian.json",
            }
        })
    }
</script>
@endsection
