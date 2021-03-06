@extends('layouts.master')

@section('title', 'Detail Data Dosen')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('teacher-profile',$data) as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div>

<div class="br-profile-page">
    <div class="card shadow-base bd-0 rounded-0 widget-4">
        <div class="card-body">
            <div class="card-profile-img" style="background-image: url('{{isset($data->foto) ? route('download.avatar','type=teacher&id='.encrypt($data->foto)): route('download.avatar','type=avatar&id='.encrypt('teacher_avatar.png'))}}')"></div>
            <h4 class="tx-bold tx-roboto tx-white">{{$data->nama}}</h4>
            <p class="mg-b-1">NIDN. {{$data->nidn}}</p>

            @if($data->nip) <p>NIP. {{$data->nip}}</p> @endif

            <p class="wd-md-500 mg-md-l-auto mg-md-r-auto mg-t-25 mg-b-25">
                Status Kerja: <strong>{{ $data->status_kerja}}</strong><br>
                Jabatan Akademik: <strong>{{ $data->jabatan_akademik }}</strong><br>
                Fakultas/Jurusan: <strong>{{ $data->latestStatus->studyProgram->department->faculty->singkatan.' - '.$data->latestStatus->studyProgram->department->nama }}</strong><br>
                Program Studi: <strong>{{ $data->latestStatus->studyProgram->nama }}</strong>
            </p>

            @if(!Auth::user()->hasRole('kajur'))
            <p class="mg-b-0 tx-24">
                <form method="POST">
                    <input type="hidden" value="{{encrypt($data->nidn)}}" name="id">
                    <a href="{{ route('teacher.list.edit',$data->nidn) }}" class="btn btn-sm btn-warning mg-b-10 text-white"><i class="fa fa-pencil-alt mg-r-10"></i> Sunting</a>
                    <button class="btn btn-sm btn-danger mg-b-10 btn-delete" data-dest="{{ route('teacher.list.destroy',encrypt($data->nidn)) }}" data-redir="{{ route('teacher.list.index') }}"><i class="fa fa-trash-alt mg-r-10"></i> Hapus</button>
                </form>
            </p>
            @endif
        </div><!-- card-body -->
    </div>
    <div class="br-profile-tab ht-70 bg-gray-100 shadow-base">
        <ul class="nav nav-outline active-info align-items-center flex-row profile-tab" role="tablist">
            <li class="nav-item"><a class="nav-link tab-link" data-toggle="tab" href="#status" role="tab">Jabatan</a></li>
            <li class="nav-item"><a class="nav-link tab-link" data-toggle="tab" href="#schedule" role="tab">Mata Kuliah</a></li>
            <li class="nav-item"><a class="nav-link tab-link" data-toggle="tab" href="#minithesis" role="tab">Bimbingan</a></li>
            <li class="nav-item"><a class="nav-link tab-link" data-toggle="tab" href="#achievement" role="tab">Prestasi</a></li>
            @if($data->latestStatus->studyProgram->kd_jurusan == setting('app_department_id'))
                @if($data->status_kerja=='Dosen Tetap PS')
                    <li class="nav-item"><a class="nav-link tab-link" data-toggle="tab" href="#ewmp" role="tab">Ekuivalen Waktu Mengajar</a></li>
                @endif
            @endif
            <li class="nav-item"><a class="nav-link tab-link" data-toggle="tab" href="#research" role="tab">Penelitian</a></li>
            <li class="nav-item"><a class="nav-link tab-link" data-toggle="tab" href="#community-service" role="tab">Pengabdian</a></li>
            <li class="nav-item"><a class="nav-link tab-link" data-toggle="tab" href="#publication" role="tab">Publikasi</a></li>
        </ul>
    </div>
    <div class="row br-profile-body">
        <div class="col-lg-9">
            <div class="tab-content">
                @if (session()->has('flash.message'))
                <div class="alert alert-{{ session('flash.class') }}" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ session('flash.message') }}
                </div>
                @endif
                @include('teacher.tab-status')
                @include('teacher.tab-schedule')
                @include('teacher.tab-minithesis')
                @include('teacher.tab-achievement')
                @if($data->latestStatus->studyProgram->kd_jurusan == setting('app_department_id'))
                    @if($data->status_kerja=='Dosen Tetap PS')
                        @include('teacher.tab-ewmp')
                    @endif
                @endif
                @include('teacher.tab-research')
                @include('teacher.tab-service')
                @include('teacher.tab-publication')
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
                <p class="tx-info mg-b-25">{{$data->no_telp ?? '-'}}</p>

                <label class="tx-10 tx-uppercase tx-mont tx-medium tx-spacing-1 mg-b-2">Alamat Email</label>
                <p class="tx-inverse mg-b-25">{{$data->email ?? '-'}}</p>

                <label class="tx-10 tx-uppercase tx-mont tx-medium tx-spacing-1 mg-b-2">Alamat Rumah</label>
                <p class="tx-inverse mg-b-50">{{$data->alamat ?? '-'}}</p>

                <h6 class="tx-gray-800 tx-uppercase tx-semibold tx-13 mg-b-25">Pendidikan</h6>

                <label class="tx-10 tx-uppercase tx-mont tx-medium tx-spacing-1 mg-b-2">No. Sertifikat Pendidik</label>
                <p class="tx-inverse mg-b-25">{{$data->sertifikat_pendidik ?? '-'}}</p>

                <label class="tx-10 tx-uppercase tx-mont tx-medium tx-spacing-1 mg-b-2">Lulusan</label>
                <p class="tx-inverse mg-b-25">{{$data->pend_terakhir_jenjang}} {{'- '.$data->pend_terakhir_jurusan ?? ''}}</p>

                <label class="tx-10 tx-uppercase tx-mont tx-medium tx-spacing-1 mg-b-5">Bidang Keahlian</label>
                <ul class="list-unstyled profile-skills">
                    @foreach($data->bidang_ahli as $ahli)
                        @if($ahli != '')
                            <li><span>{{$ahli}}</span></li>
                        @else
                            -
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-style')

@endsection

@section('js')
@endsection
