<div class="tab-pane fade" id="schedule">
    <div class='widget-2'>
        <div class="card card pd-20 pd-xs-30 shadow-base bd-0">
            <div class="row d-flex">
                <div class="pt-1">
                    <h6 class="tx-gray-800 tx-uppercase tx-semibold tx-14 mg-b-30">Prestasi/Pengakuan/Rekognisi Dosen</h6>
                </div>
                @if(!Auth::user()->hasRole('kajur'))
                <div class="ml-auto">
                    <button class="btn btn-sm btn-primary mg-b-10 btn-add" data-toggle="modal" data-target="#modal-teach-schedule"><i class="fa fa-plus mg-r-10"></i> Tambah</button>
                </div>
                @endif
            </div>
            <div class="row">
                <div class="bd rounded table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">Tahun Akademik</th>
                                <th class="text-center align-middle">Mata Kuliah</th>
                                <th class="text-center align-middle">SKS</th>
                                <th class="text-center align-middle">Sesuai Prodi</th>
                                <th class="text-center align-middle">Sesuai Bidang</th>
                                @if(!Auth::user()->hasRole('kajur'))
                                <th class="text-center align-middle">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($schedule as $s)
                            <tr>
                                <td>{{ $s->academicYear->tahun_akademik.' - '.$s->academicYear->semester }}</td>
                                <td>{{ $s->curriculum->nama.' - '.$s->curriculum->studyProgram->singkatan.' ('.$s->curriculum->versi.')' }}</td>
                                <td class="text-center">
                                    {{ $s->curriculum->sks_teori + $s->curriculum->sks_seminar + $s->curriculum->sks_praktikum }}
                                </td>
                                <td class="text-center">
                                    @isset($s->sesuai_prodi)
                                    <i class="fa fa-check"></i>
                                    @endisset
                                </td>
                                <td class="text-center">
                                    @isset($s->sesuai_bidang)
                                    <i class="fa fa-check"></i>
                                    @endisset
                                </td>
                                @if(!Auth::user()->hasRole('kajur'))
                                <td width="50">
                                    <div class="btn-group" role="group">
                                        <button id="btn-action" type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <div><span class="fa fa-caret-down"></span></div>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                            <button class="dropdown-item btn-edit" data-id="{{encode_id($s->id)}}">Sunting</button>
                                            <form method="POST">
                                                <input type="hidden" value="{{encode_id($s->id)}}" name="id">
                                                <a href="#" class="dropdown-item btn-delete" data-dest="{{ route('academic.schedule.delete') }}">Hapus</a>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan=5 class="text-center align-middle">BELUM ADA DATA</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('teacher.form-schedule')
