@extends('layouts.master')

@section('title', 'Detail Data Dosen')

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
        <span class="breadcrumb-item">{{$data->nidn}} : {{$data->nama}}</span>
    </nav>
</div>

<div class="br-profile-page">
        <div class="card shadow-base bd-0 rounded-0 widget-4">
            <div class="card-body">
                <div class="card-profile-img" style="background-image: url('{{isset($data->foto) ? route('teacher.download',encrypt($data->foto)): route('teacher.download',encrypt('avatar.png'))}}')"></div>
                <h4 class="tx-normal tx-roboto tx-white">{{$data->nama}}</h4>
                <p class="mg-b-1">NIDN. {{$data->nidn}}</p>
                <p class="mg-b-25">NIP. {{$data->nip}}</p>

                <p class="wd-md-500 mg-md-l-auto mg-md-r-auto mg-b-25">
                    Ikatan Kerja: {{ $data->ikatan_kerja}}<br>
                    Jabatan Akademik: {{ $data->jabatan_akademik }}<br>
                    Program Studi: {{ $data->studyProgram->nama }}
                </p>

                <p class="mg-b-0 tx-24">
                    <a href="{{ route('teacher.edit',encode_id($data->nip)) }}" class="btn btn-sm btn-warning mg-b-10" style="color:white"><i class="fa fa-pencil-alt mg-r-10"></i> Ubah Data</a>
                </p>
            </div><!-- card-body -->
        </div>
        <div class="ht-70 bg-gray-100 pd-x-20 d-flex align-items-center justify-content-center shadow-base">
            <ul class="nav nav-outline active-info align-items-center flex-row profile-tab" role="tablist">
                <li class="nav-item"><a class="nav-link tab-link" data-toggle="tab" href="#achievement" role="tab">Prestasi</a></li>
                @if($data->ikatan_kerja=='Dosen Tetap PS')
                <li class="nav-item"><a class="nav-link tab-link" data-toggle="tab" href="#ewmp" role="tab">Ekuivalen Waktu Mengajar</a></li>
                @endif
                <li class="nav-item"><a class="nav-link tab-link" data-toggle="tab" href="#" role="tab">Favorites</a></li>
                <li class="nav-item hidden-xs-down"><a class="nav-link" data-toggle="tab" href="#" role="tab">Settings</a></li>
            </ul>
        </div>
        <div class="row br-profile-body">
            <div class="col-lg-9">
                <div class="tab-content">
                    @include('teacher.tab-achievement')
                    @if($data->ikatan_kerja=='Dosen Tetap PS')
                        @include('teacher.tab-ewmp')
                    @endif
                </div>
            </div>
            <div class="col-lg-3 mg-t-30 mg-lg-t-0">
                <div class="card pd-20 pd-xs-30 shadow-base bd-0">
                    <h6 class="tx-gray-800 tx-uppercase tx-semibold tx-13 mg-b-25">Profil Diri</h6>

                    <label class="tx-10 tx-uppercase tx-mont tx-medium tx-spacing-1 mg-b-2">Jenis Kelamin</label>
                    <p class="tx-inverse mg-b-25">{{$data->jk}}</p>

                    <label class="tx-10 tx-uppercase tx-mont tx-medium tx-spacing-1 mg-b-2">Agama</label>
                    <p class="tx-inverse mg-b-25">{{$data->agama}}</p>

                    <label class="tx-10 tx-uppercase tx-mont tx-medium tx-spacing-1 mg-b-2">Tempat/Tgl Lahir</label>
                    <p class="tx-inverse mg-b-50">{{$data->tpt_lhr}}, {{ date('d-m-Y', strtotime($data->tgl_lhr))}}</p>

                    <h6 class="tx-gray-800 tx-uppercase tx-semibold tx-13 mg-b-25">Informasi Kontak</h6>

                    <label class="tx-10 tx-uppercase tx-mont tx-medium tx-spacing-1 mg-b-2">No. Telp</label>
                    <p class="tx-info mg-b-25">{{$data->no_telp}}</p>

                    <label class="tx-10 tx-uppercase tx-mont tx-medium tx-spacing-1 mg-b-2">Alamat Email</label>
                    <p class="tx-inverse mg-b-25">{{$data->email}}</p>

                    <label class="tx-10 tx-uppercase tx-mont tx-medium tx-spacing-1 mg-b-2">Alamat Rumah</label>
                <p class="tx-inverse mg-b-50">{{$data->alamat}}</p>

                    <h6 class="tx-gray-800 tx-uppercase tx-semibold tx-13 mg-b-25">Pendidikan</h6>

                    <label class="tx-10 tx-uppercase tx-mont tx-medium tx-spacing-1 mg-b-2">Lulusan</label>
                    <p class="tx-inverse mg-b-25">{{$data->pend_terakhir_jenjang}} - {{$data->pend_terakhir_jurusan}}</p>

                    <label class="tx-10 tx-uppercase tx-mont tx-medium tx-spacing-1 mg-b-5">Bidang Keahlian</label>
                    <ul class="list-unstyled profile-skills">
                        @foreach($data->bidang_ahli as $ahli)
                        <li><span>{{$ahli}}</span></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
</div>
@endsection

@section('js')
@endsection
