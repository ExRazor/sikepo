@extends('layouts.master')

@section('title', 'Luaran Mahasiswa')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('output-activity-student') as $breadcrumb)
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
        <i class="icon fa fa-boxes"></i>
        <div>
            <h4>Luaran Kegiatan</h4>
            <p class="mg-b-0">Olah data luaran kegiatan mahasiswa</p>
        </div>
    </div>
    @if(!Auth::user()->hasRole('kajur'))
    <div class="ml-auto">
        <a href="{{ route('output-activity.student.create') }}" class="btn btn-teal btn-block mg-b-10" style="color:white"><i class="fa fa-plus mg-r-10"></i> Luaran</a>
    </div>
    @endif
</div>

<div class="br-pagebody">
    <div class="alert alert-info">
        <h5>KATEGORI LUARAN</h5>
        <hr>
        @foreach($category as $c)
        <strong class="d-block d-sm-inline-block-force">{{$loop->iteration.'. '.$c->nama}}</strong><br>
        {{$c->deskripsi}}
        @if (!$loop->last)
            <br><br>
        @endif
        @endforeach
    </div>
    @if (session()->has('flash.message'))
        <div class="alert alert-{{ session('flash.class') }}" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ session('flash.message') }}
        </div>
    @endif
    @if(!Auth::user()->hasRole('kaprodi'))
    <div class="row">
        <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
            <select id="kd_prodi_filter" class="form-control filter-box">
                <option value="">- Pilih Program Studi -</option>
                @foreach($studyProgram as $sp)
                <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endif
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
                <table id="table_outputActivity_student" class="table display responsive" data-order='[[ 3, "desc" ]]' data-page-length="25" url-target="{{route('ajax.output-activity.student.datatable')}}">
                    <thead>
                        <tr>
                            <th class="text-center" width="600">Judul Luaran</th>
                            <th class="text-center" width="300">Pemilik</th>
                            <th class="text-center" width="125">Jenis Luaran</th>
                            <th class="text-center" width="300">Kategori</th>
                            <th class="text-center" width="150">Tahun</th>
                            <th class="text-center" width="50">Aksi</th>
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

@push('custom-js')
<script type="text/javascript">
    var table = $('#table_outputActivity_student');
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
                    d.kd_prodi_filter  = $('#kd_prodi_filter').val();
                    d._token           = $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: 'judul', className: 'min-mobile-p'},
                { data: 'milik', className: 'min-mobile-p'},
                { data: 'jenis_luaran', className: 'desktop'},
                { data: 'kategori', className: 'desktop'},
                { data: 'thn_luaran', className: 'desktop text-center'},
                { data: 'aksi', className: 'desktop text-center', orderable: false}
            ],
            hideEmptyCols: [ 5 ],
            autoWidth: false,
            language: {
                url: "/assets/lib/datatables.net/indonesian.json",
            }
        })
    }
</script>
@endpush
