<div class="tab-pane fade" id="research">
    <div class='widget-2'>
        <div class="card card pd-20 pd-xs-30 shadow-base bd-0">
            <div class="row d-flex">
                <div class="pt-1">
                    <h6 class="tx-gray-800 tx-uppercase tx-semibold tx-14 mg-b-30">Daftar Penelitian</h6>
                </div>
                <div class="ml-auto">
                    <button class="btn btn-sm btn-primary mg-b-10 btn-add" data-toggle="modal" data-target="#modal-teach-schedule"><i class="fa fa-plus mg-r-10"></i> Tambah</button>
                </div>
            </div>
            <div class="row">
                <div class="bd rounded table-responsive">
                    <table class="table datatable table-bordered mb-0">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">Judul Penelitian</th>
                                <th class="text-center align-middle">Tahun Penelitian</th>
                                <th class="text-center align-middle">SKS<br>Penelitian</th>
                                <th class="text-center align-middle">Status<br>Anggota</th>
                                <th class="text-center align-middle">Jumlah<br>SKS</th>
                                <th class="text-center align-middle">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($research as $rs)
                            <tr>
                                <td>{{ $rs->judul_penelitian }}</td>
                                <td class="text-center">{{ $rs->academicYear->tahun_akademik.' - '.$rs->academicYear->semester }}</td>
                                <td class="text-center">
                                    {{ $rs->sks_penelitian }}
                                </td>
                                <td class="text-center">
                                    {{ $rs->researchTeacher[0]->status }}
                                </td>
                                <td class="text-center">
                                    {{ $rs->researchTeacher[0]->sks }}
                                </td>
                                <td width="50">
                                    <div class="btn-group" role="group">
                                        <button id="btn-action" type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <div><span class="fa fa-caret-down"></span></div>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                            <button class="dropdown-item btn-edit" data-id="{{encode_id($rs->id)}}">Sunting</button>
                                            <form method="POST">
                                                <input type="hidden" value="{{encode_id($rs->id)}}" name="id">
                                                <a href="#" class="dropdown-item btn-delete" data-dest="{{ route('academic.schedule.delete') }}">Hapus</a>
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
</div>
{{-- @include('teacher.form-schedule') --}}
