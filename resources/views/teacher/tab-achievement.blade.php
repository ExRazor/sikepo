<div class="tab-pane fade" id="achievement">
    <div class='widget-2'>
        <div class="card card pd-20 pd-xs-30 shadow-base bd-0">
            <div class="row d-flex">
                <div class="pt-1">
                    <h6 class="tx-gray-800 tx-uppercase tx-semibold tx-14 mg-b-30">Prestasi/Pengakuan/Rekognisi Dosen</h6>
                </div>
                <div class="ml-auto">
                    <button href="#" class="btn btn-sm btn-primary mg-b-10 btn-add" data-toggle="modal" data-target="#modal-teach-acv" style="color:white"><i class="fa fa-plus mg-r-10"></i> Tambah</button>
                </div>
            </div>
            <div class="row">
                <div class="bd rounded table-responsive">
                    <table id="table-teacherAcv" class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">Prestasi</th>
                                <th class="text-center align-middle">Tingkat Prestasi</th>
                                <th class="text-center align-middle">Tahun Diperoleh</th>
                                <th class="text-center align-middle">Bukti Pendukung</th>
                                <th class="text-center align-middle">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($achievement as $acv)
                            <tr>
                                <td>{{ $acv->prestasi }}</td>
                                <td>{{ $acv->tingkat_prestasi }}</td>
                                <td>{{ $acv->academicYear->tahun_akademik.' - '.$acv->academicYear->semester }}</td>
                                <td class="text-center align-middle">
                                    <a href="{{route('teacher.achievement.download',encode_id($acv->bukti_file))}}" target="_blank">
                                        {{$acv->bukti_nama}}
                                    </a>
                                </td>
                                <td width="50">
                                    <div class="btn-group" role="group">
                                        <button id="btn-action" type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <div><span class="fa fa-caret-down"></span></div>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                            <button class="dropdown-item btn-edit" href="#" data-id="{{ encode_id($acv->id) }}">Sunting</button>
                                            <form method="POST">
                                                <input type="hidden" value="{{encode_id($acv->id)}}" name="_id">
                                                <a href="#" class="dropdown-item btn-delete" data-dest="{{ route('teacher.achievement.delete') }}">Hapus</a>
                                            </form>
                                        </div>
                                    </div>
                                </td>
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
@include('teacher.form-achievement')
