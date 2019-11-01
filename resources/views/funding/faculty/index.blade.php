@extends('layouts.master')

@section('title', 'Data Keuangan fakultas')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('funding-faculty') as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div>
<div class="br-pagetitle">
    <i class="icon fa fa-balance-scale"></i>
    <div>
        <h4>Keuangan Fakultas</h4>
        <p class="mg-b-0">Olah Data Keuangan Fakultas</p>
    </div>
    <div class="ml-auto">
        <a href="{{ route('funding.faculty.add') }}" class="btn btn-teal btn-block mg-b-10" style="color:white"><i class="fa fa-plus mg-r-10"></i> Data Keuangan</a>
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
            <div class="card-header nm_jurusan">
                <h6 class="card-title">{{ setting('app_faculty_name') }}</h6>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <table id="table_teacher" class="table display responsive nowrap datatable" data-sort="desc">
                    <thead>
                        <tr>
                            <th class="text-center align-middle no-sort" width="50"></th>
                            <th class="text-center align-middle defaultSort">Tahun Akademik</th>
                            <th class="text-center">Total Biaya<br>Operasional Akademik</th>
                            <th class="text-center">Total Biaya<br>Pengabdian kepada Masyarakat</th>
                            <th class="text-center">Total Biaya<br>Sarana Prasarana</th>
                            <th class="text-center align-middle">Total Semua Biaya</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $d)
                        <tr>
                            <td class="text-center">
                                <a class="btn btn-success btn-sm btn-icon rounded-circle" href="{{ route('funding.faculty.show',encrypt($d->kd_dana)) }}">
                                    <div><i class="fa fa-search-plus"></i></div>
                                </a>
                            </td>
                            <td class="text-center">{{ $d->academicYear->tahun_akademik }}</td>
                            @php
                                $kolom = 0;
                                $total = 0;
                            @endphp
                            @foreach($dana[$d->kd_dana] as $index => $value)
                            <td class="text-center">
                                {{ rupiah($value->total) }}
                                @php
                                    $kolom = $index;
                                    $total += $value->total;
                                @endphp
                            </td>
                            @endforeach
                            @for($i=1;$i<3-$kolom;$i++)
                            <td class="text-center">{{ rupiah('0') }}</td>
                            @endfor
                            <td class="text-center">{{ rupiah($total) }}</td>
                        </tr>
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
