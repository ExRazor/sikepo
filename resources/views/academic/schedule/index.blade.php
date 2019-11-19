@extends('layouts.master')

@section('title', 'Jadwal Kurikulum')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

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
    <i class="icon fa fa-chalkboard-teacher"></i>
    <div>
        <h4>Jadwal Kurikulum</h4>
        <p class="mg-b-0">Olah Data Jadwal Kurikulum</p>
    </div>
    <div class="ml-auto">
        <a href="{{ route('academic.schedule.add') }}" class="btn btn-teal btn-block mg-b-10" style="color:white"><i class="fa fa-plus mg-r-10"></i> Jadwal Matkul</a>
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
    <div class="row">
        <div class="col-12">
            <form action="{{route('ajax.schedule.filter')}}" id="filter-schedule" method="POST">
                <div class="filter-box d-flex flex-row bd-highlight mg-b-10">
                    <div class="mg-r-10">
                        <select id="fakultas" class="form-control" name="kd_jurusan" data-placeholder="Pilih Jurusan" required>
                            <option value="0">Semua Jurusan</option>
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
                    </div>
                    <div class="mg-r-10">
                        <select class="form-control" name="kd_prodi">
                            <option value="">- Pilih Program Studi -</option>
                            @foreach($studyProgram as $sp)
                            <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-purple btn-block " style="color:white">Cari</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="accordion_schedule" class="accordion accordion-head-colored accordion-info" role="tablist" aria-multiselectable="false">
        @foreach($academicYear as $ay)
        <div class="card">
            <div class="card-header" role="tab" id="heading_ay_{{$ay->id}}">
                <h6 class="mg-b-0">
                    <a class="collapsed transition" data-toggle="collapse" data-parent="#accordion_schedule" href="#schedule_ay_{{$ay->id}}" aria-expanded="true" aria-controls="schedule_ay_{{$ay->id}}">
                    {{$ay->tahun_akademik.' - '.$ay->semester}}
                    </a>
                </h6>
            </div><!-- card-header -->
            <div id="schedule_ay_{{$ay->id}}" class="collapse" role="tabpanel" aria-labelledby="heading_ay_{{$ay->id}}">
                <div class="card-block pd-20">
                    <table id="table-schedule-{{$ay->id}}" class="table display responsive nowrap datatable" data-sort="asc">
                        <thead>
                            <tr>
                                <th class="text-center">Kode Matkul</th>
                                <th class="text-center defaultSort">Nama Matkul</th>
                                <th class="text-center">Jumlah SKS</th>
                                <th class="text-center">Nama Dosen</th>
                                <th class="text-center">Sesuai Prodi</th>
                                <th class="text-center no-sort">Sesuai Bidang</th>
                                <th class="text-center no-sort">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ay->curriculumSchedule as $schedule)
                            <tr>
                                <td>{{$schedule->kd_matkul}}</td>
                                <td>
                                    {{$schedule->curriculum->nama}}<br>
                                    <small>{{$schedule->curriculum->studyProgram->department->nama.' - '.$schedule->curriculum->studyProgram->singkatan}}</small>
                                </td>
                                <td class="text-center">{{$schedule->curriculum->sks_teori+$schedule->curriculum->sks_seminar+$schedule->curriculum->sks_praktikum}}</td>
                                <td>
                                    <a href="{{ route('teacher.profile',encode_id($schedule->teacher->nip)) }}">
                                        {{$schedule->teacher->nama}}<br>
                                        <small>NIDN. {{$schedule->teacher->nidn.' / '.$schedule->teacher->studyProgram->singkatan}}</small>
                                    </a>
                                </td>
                                <td class="text-center">
                                    @isset($schedule->sesuai_prodi)
                                    <i class="fa fa-check"></i>
                                    @endisset
                                </td>
                                <td class="text-center">
                                    @isset($schedule->sesuai_bidang)
                                    <i class="fa fa-check"></i>
                                    @endisset
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <div><span class="fa fa-caret-down"></span></div>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                            <a class="dropdown-item" href="{{ route('academic.schedule.edit',encode_id($schedule->id)) }}">Sunting</a>
                                            <form method="POST">
                                                <input type="hidden" value="{{encode_id($schedule->id)}}" name="id">
                                                <button class="dropdown-item btn-delete" data-dest="{{ route('academic.schedule.delete') }}">Hapus</button>
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
        @endforeach
    </div><!-- accordion -->

</div>
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection

@section('custom-js')
<script type="text/javascript">
    var datatable_opt = {
                            order: [[$('th.defaultSort').index(), $('table').data('sort')]],
                            responsive: true,
                            autoWidth: false,
                            columnDefs: [ {
                                "targets"  : 'no-sort',
                                "orderable": false,
                            }],
                            language: {
                                url: "/assets/lib/datatables.net/Indonesian.json",
                            }
                        }


</script>
@endsection
