@extends('layouts.master')

@section('title', 'Data Pengabdian')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('community-service') as $breadcrumb)
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
        <i class="icon fa fa-american-sign-language-interpreting"></i>
        <div>
            <h4>Data Pengabdian</h4>
            <p class="mg-b-0">Olah Data Pengabdian</p>
        </div>
    </div>
    @if(!Auth::user()->hasRole('kajur'))
    <div class="ml-auto">
        <a href="{{ route('community-service.create') }}" class="btn btn-teal btn-block mg-b-10" style="color:white"><i class="fa fa-plus mg-r-10"></i> Pengabdian</a>
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
                <table id="table_communityService" class="table display responsive" data-order='[[ 0, "desc" ]]' data-page-length="25" url-target="{{route('ajax.community-service.datatable')}}">
                    <thead>
                        <tr>
                            <th class="text-center">Judul Pengabdian</th>
                            <th class="text-center">Tahun Pengabdian</th>
                            <th class="text-center">Ketua Pelaksana</th>
                            @if(!Auth::user()->hasRole('kajur'))
                            <th class="text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($pengabdian as $p)
                        <tr>
                            <td>
                                <a href="{{route('community-service.show',encode_id($p->id))}}">
                                    {{ $p->judul_pengabdian }}
                                </a>
                            </td>
                            <td class="text-center">{{ $p->academicYear->tahun_akademik.' - '.$p->academicYear->semester }}</td>
                            <td>
                                <a href="{{route('teacher.show',encode_id($p->serviceKetua->teacher->nip))}}#community-service">
                                    {{ $p->serviceKetua->teacher->nama }}<br>
                                    <small>NIDN.{{ $p->serviceKetua->teacher->nidn }} / {{ $p->serviceKetua->teacher->studyProgram->singkatan }}</small>
                                </a>
                            </td>
                            <td class="text-center">
                                @isset($p->sesuai_prodi)
                                    <i class="fa fa-check"></i>
                                @endisset
                            </td>
                            @if(!Auth::user()->hasRole('kajur'))
                            <td class="text-center" width="50">
                                <div class="btn-group" role="group">
                                    <button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div><span class="fa fa-caret-down"></span></div>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                        <a class="dropdown-item" href="{{ route('community-service.edit',encode_id($p->id)) }}">Sunting</a>
                                        <form method="POST">
                                            <input type="hidden" value="{{encode_id($p->id)}}" name="id">
                                            <button class="dropdown-item btn-delete" data-dest="{{ route('community-service.delete') }}">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @endforeach --}}
                    </tbody>
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
    var table = $('#table_communityService');
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
                { data: 'tahun', className: "text-center min-mobile-p" },
                { data: 'pengabdian', className: "min-mobile-p" },
                { data: 'pelaksana', className: "desktop" },
                { data: 'aksi', className: "desktop text-center", orderable: false }
            ],
            hideEmptyCols: [ 3 ],
            autoWidth: false,
            language: {
                url: "/assets/lib/datatables.net/indonesian.json",
            }
        })
    }
</script>
@endsection
