@extends('layouts.master')

@section('title', 'Rincian Keuangan Fakultas')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('funding-faculty-show',$data) as $breadcrumb)
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
        <i class="icon fa fa-balance-scale"></i>
        <div>
            <h4>Dana {{ $data->faculty->nama }} Tahun {{ $data->academicYear->tahun_akademik }}</h4>
            <p class="mg-b-0">Rincian keuangan untuk {{ $data->faculty->nama }} periode tahun {{ $data->academicYear->tahun_akademik }}</p>
        </div>
    </div>
    <div class="d-flex ml-auto">
        <div class="mr-2">
            <form method="POST">
                <input type="hidden" value="{{encrypt($data->kd_dana)}}" name="id">
                <button class="btn btn-danger btn-block btn-delete" data-dest="{{ route('funding.faculty.delete') }}" data-redir="{{ route('funding.faculty') }}"><i class="fa fa-trash mg-r-10"></i> Hapus</button>
            </form>
        </div>
        <div>
            <a href="{{ route('funding.faculty.edit',encrypt($data->kd_dana)) }}" class="btn btn-warning btn-block text-white"><i class="fa fa-pencil-alt mg-r-10"></i>Sunting</a>
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
    <div class="widget-2">
        <div class="card shadow-base mb-3">
            <div class="card-body bd-color-gray-lighter">
                <table id="table_teacher" class="table display responsive nowrap" data-sort="desc">
                    <thead>
                        <tr>
                            <th class="text-center align-middle" width="25">#</th>
                            <th class="text-center align-middle" width="400">Jenis Penggunaan</th>
                            <th class="text-center align-middle defaultSort">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($category as $c)
                        <tr>
                            <td>{{$x = $loop->iteration}}
                            <td>{{ $c->nama }}</td>
                            @if(!$c->children->count())
                            <td class="text-center">{{ rupiah($nominal[$c->id]->nominal) }}</td>
                            @else
                            <td></td>
                            @endif
                        </tr>
                        @if($c->children->count())
                            @foreach($c->children as $child)
                            <tr>
                                <td></td>
                                <td>
                                    — {{ $child->nama }}<br>
                                    <p class="ml-3"><small>( {{$child->deskripsi}} )</small></p>
                                </td>
                                <td class="text-center">{{ rupiah($nominal[$child->id]->nominal) }}</td>
                            </tr>
                            @endforeach
                        @endif
                        @if($loop->last)
                        <tr>
                            <td class="text-center" colspan="2"><h4>TOTAL DANA</h4></td>
                            <td class="text-center" colspan="2"><h4>{{ rupiah($total) }}</h4></td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div><!-- card-body -->
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection
