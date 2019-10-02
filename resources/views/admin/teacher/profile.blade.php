@extends('layouts.master')

@section('title', 'Data Program Studi')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('teacher') as $breadcrumb)
            @isset($breadcrumb->url)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endisset
        @endforeach
        <span class="breadcrumb-item">{{$data->nidn}} : {{$data->nama}}</span>
    </nav>
</div>

<div class="br-profile-page">
        <div class="card shadow-base bd-0 rounded-0 widget-4">
            <div class="card-body">
                <div class="card-profile-img" style="background-image: url('/upload/teacher/{{$data->foto}}')"></div>
                <h4 class="tx-normal tx-roboto tx-white">{{$data->nama}}</h4>
                <p class="mg-b-25">NIDN. {{$data->nidn}}</p>

                <p class="wd-md-500 mg-md-l-auto mg-md-r-auto mg-b-25">
                    {{ $data->status_pengajar=='DT' ? 'Dosen Tetap' : 'Dosen Tidak Tetap' }} Program Studi.<br>
                    Menjabat sebagai
                    {{ $data->jabatan_akademik }}
                    di Program Studi
                    {{ $data->studyProgram->nama }}.
                </p>

                <p class="mg-b-0 tx-24">
                    <a href="/teacher/{{encrypt($data->nidn)}}/edit" class="btn btn-sm btn-warning mg-b-10" style="color:white"><i class="fa fa-pencil-alt mg-r-10"></i> Ubah Data</a>
                </p>
            </div><!-- card-body -->
        </div>
        <div class="ht-70 bg-gray-100 pd-x-20 d-flex align-items-center justify-content-center shadow-base">
            <ul class="nav nav-outline active-info align-items-center flex-row" role="tablist">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#ewmp" role="tab">Ekuivalen Waktu Mengajar</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#photos" role="tab">Photos</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#" role="tab">Favorites</a></li>
                <li class="nav-item hidden-xs-down"><a class="nav-link" data-toggle="tab" href="#" role="tab">Settings</a></li>
            </ul>
        </div>
        <div class="row br-profile-body">
            <div class="col-lg-9">
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="ewmp">
                        <div class='widget-2'>
                            <div class="card card pd-20 pd-xs-30 shadow-base bd-0">
                                <div class="row d-flex">
                                    <div class="pt-1">
                                        <h6 class="tx-gray-800 tx-uppercase tx-semibold tx-14 mg-b-30">EWMP Per Semester Akademik</h6>
                                    </div>
                                    <div class="ml-auto">
                                        <button href="#" class="btn btn-sm btn-primary mg-b-10 btn-add-ewmp" data-toggle="modal" data-target="#ewmp-form" style="color:white"><i class="fa fa-plus mg-r-10"></i> Tambah</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="bd rounded table-responsive">
                                        <table class="table table-bordered mb-0">
                                            <thead>
                                                <tr>
                                                    <th rowspan="3" class="text-center align-middle">Tahun Akademik</th>
                                                    <th colspan="6" class="text-center align-middle">Ekuivalen Waktu Mengajar Penuh (EWMP)<br>dalam satuan kredit semester (sks)</th>
                                                    <th rowspan="3" class="text-center align-middle">Jumlah<br>(sks)</th>
                                                    <th rowspan="3" class="text-center align-middle">Rata-rata<br>(sks)</th>
                                                    <th rowspan="3" class="text-center align-middle">Aksi</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" class="text-center align-middle" style="border-left-width: 1px;">Pendidikan</th>
                                                    <th rowspan="2" class="text-center align-middle" width="100">Penelitian</th>
                                                    <th rowspan="2" class="text-center align-middle">PKM</th>
                                                    <th rowspan="2" class="text-center align-middle" width="100">Tugas Tambahan/<br>Penunjang</th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center align-middle" style="border-left-width: 1px;" width="70">PS</th>
                                                    <th class="text-center align-middle" width="70">PS Luar</th>
                                                    <th class="text-center align-middle" width="70">Luar PT</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($ewmp as $e)
                                                <tr>
                                                    <td>{{ $e->academicYear->tahun_akademik.' - '.$e->academicYear->semester }}</td>
                                                    <td class="text-center">{{ $e->ps_intra }}</td>
                                                    <td class="text-center">{{ $e->ps_lain }}</td>
                                                    <td class="text-center">{{ $e->ps_luar }}</td>
                                                    <td class="text-center">{{ $e->penelitian }}</td>
                                                    <td class="text-center">{{ $e->pkm }}</td>
                                                    <td class="text-center">{{ $e->tugas_tambahan }}</td>
                                                    <td class="text-center">{{ $total = $e->ps_intra+$e->ps_lain+$e->ps_luar+$e->penelitian+$e->pkm+$e->tugas_tambahan}}</td>
                                                    <td class="text-center">{{ number_format($total/6, 1, ',', ' ') }}</td>
                                                    <td width="50">
                                                        <div class="btn-group" role="group">
                                                            <button id="btn-action" type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <div><span class="fa fa-caret-down"></span></div>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                                                <a class="dropdown-item btn-edit-ewmp" href="#" data-id="{{ encrypt($e->id) }}">Sunting</a>
                                                                <form method="POST">
                                                                    @method('delete')
                                                                    @csrf
                                                                    <input type="hidden" value="{{encrypt($e->id)}}" name="_id">
                                                                    <a href="#" class="dropdown-item btn-delete" data-dest="/ajax/ewmp">Hapus</a>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div><!-- tab-pane -->
                    <div class="tab-pane fade" id="photos">
                        <div class="row">
                        <div class="col-lg-8">
                            <div class="card pd-20 pd-xs-30 shadow-base bd-0 mg-t-30">
                            <h6 class="tx-gray-800 tx-uppercase tx-semibold tx-14 mg-b-30">Recent Photos</h6>

                            <div class="row row-xs">
                                <div class="col-6 col-sm-4 col-md-3"><img src="https://via.placeholder.com/800" class="img-fluid" alt=""></div>
                                <div class="col-6 col-sm-4 col-md-3"><img src="https://via.placeholder.com/300" class="img-fluid" alt=""></div>
                                <div class="col-6 col-sm-4 col-md-3 mg-t-10 mg-sm-t-0"><img src="https://via.placeholder.com/600x600" class="img-fluid" alt=""></div>
                                <div class="col-6 col-sm-4 col-md-3 mg-t-10 mg-md-t-0"><img src="https://via.placeholder.com/600x600" class="img-fluid" alt=""></div>
                                <div class="col-6 col-sm-4 col-md-3 mg-t-10"><img src="https://via.placeholder.com/800" class="img-fluid" alt=""></div>
                                <div class="col-6 col-sm-4 col-md-3 mg-t-10"><img src="https://via.placeholder.com/800" class="img-fluid" alt=""></div>
                                <div class="col-6 col-sm-4 col-md-3 mg-t-10"><img src="https://via.placeholder.com/800" class="img-fluid" alt=""></div>
                                <div class="col-6 col-sm-4 col-md-3 mg-t-10"><img src="https://via.placeholder.com/500" class="img-fluid" alt=""></div>
                                <div class="col-6 col-sm-4 col-md-3 mg-t-10"><img src="http://via.placeholder.com/300x300" class="img-fluid" alt=""></div>
                                <div class="col-6 col-sm-4 col-md-3 mg-t-10"><img src="http://via.placeholder.com/300x300" class="img-fluid" alt=""></div>
                                <div class="col-6 col-sm-4 col-md-3 mg-t-10"><img src="http://via.placeholder.com/300x300" class="img-fluid" alt=""></div>
                                <div class="col-6 col-sm-4 col-md-3 mg-t-10"><img src="http://via.placeholder.com/300x300" class="img-fluid" alt=""></div>
                            </div><!-- row -->

                            <p class="mg-t-20 mg-b-0">Loading more photos...</p>

                            </div><!-- card -->
                        </div><!-- col-lg-8 -->
                        <div class="col-lg-4 mg-t-30 mg-lg-t-0">
                            <div class="card pd-20 pd-xs-30 shadow-base bd-0 mg-t-30">
                            <h6 class="tx-gray-800 tx-uppercase tx-semibold tx-14 mg-b-30">Photo Albums</h6>
                            <div class="row row-xs mg-b-15">
                                <div class="col"><img src="https://via.placeholder.com/800" class="img-fluid" alt=""></div>
                                <div class="col"><img src="https://via.placeholder.com/800" class="img-fluid" alt=""></div>
                                <div class="col">
                                <div class="overlay">
                                    <img src="https://via.placeholder.com/800" class="img-fluid" alt="">
                                    <div class="overlay-body bg-black-5 d-flex align-items-center justify-content-center">
                                    <span class="tx-white tx-12">20+ more</span>
                                    </div><!-- overlay-body -->
                                </div><!-- overlay -->
                                </div>
                            </div><!-- row -->
                            <div class="d-flex alig-items-center justify-content-between">
                                <h6 class="tx-inverse tx-14 mg-b-0">Profile Photos</h6>
                                <span class="tx-12">24 Photos</span>
                            </div><!-- d-flex -->

                            <hr>

                            <div class="row row-xs mg-b-15">
                                <div class="col"><img src="https://via.placeholder.com/600x600" class="img-fluid" alt=""></div>
                                <div class="col"><img src="https://via.placeholder.com/600x600" class="img-fluid" alt=""></div>
                                <div class="col">
                                <div class="overlay">
                                    <img src="https://via.placeholder.com/600x600" class="img-fluid" alt="">
                                    <div class="overlay-body bg-black-5 d-flex align-items-center justify-content-center">
                                    <span class="tx-white tx-12">20+ more</span>
                                    </div><!-- overlay-body -->
                                </div><!-- overlay -->
                                </div>
                            </div><!-- row -->
                            <div class="d-flex alig-items-center justify-content-between">
                                <h6 class="tx-inverse tx-14 mg-b-0">Mobile Uploads</h6>
                                <span class="tx-12">24 Photos</span>
                            </div><!-- d-flex -->

                            <hr>

                            <div class="row row-xs mg-b-15">
                                <div class="col"><img src="http://via.placeholder.com/300x300/0866C6/FFF" class="img-fluid" alt=""></div>
                                <div class="col"><img src="http://via.placeholder.com/300x300/DC3545/FFF" class="img-fluid" alt=""></div>
                                <div class="col">
                                <div class="overlay">
                                    <img src="http://via.placeholder.com/300x300/0866C6/FFF" class="img-fluid" alt="">
                                    <div class="overlay-body bg-black-5 d-flex align-items-center justify-content-center">
                                    <span class="tx-white tx-12">20+ more</span>
                                    </div><!-- overlay-body -->
                                </div><!-- overlay -->
                                </div>
                            </div><!-- row -->
                            <div class="d-flex alig-items-center justify-content-between">
                                <h6 class="tx-inverse tx-14 mg-b-0">Mobile Uploads</h6>
                                <span class="tx-12">24 Photos</span>
                            </div><!-- d-flex -->

                            <a href="" class="d-block mg-t-20"><i class="fa fa-angle-down mg-r-5"></i> Show 8 more albums</a>
                            </div><!-- card -->
                        </div><!-- col-lg-4 -->
                        </div><!-- row -->
                    </div><!-- tab-pane -->
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
                    <li><span>html</span></li>
                    <li><span>css</span></li>
                    <li><span>javascript</span></li>
                    <li><span>php</span></li>
                    <li><span>photoshop</span></li>
                    <li><span>java</span></li>
                    <li><span>angular</span></li>
                    <li><span>wordpress</span></li>
                    </ul>
                </div>
            </div>
        </div>
</div>

@include('admin.teacher.ewmp.form');
@endsection

@section('js')
@endsection
