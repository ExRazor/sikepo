<div class="tab-pane fade" id="research">
    <div class='widget-2'>
        <div class="card card pd-20 pd-xs-30 shadow-base bd-0">
            <div class="row d-flex">
                <div class="pt-1">
                    <h6 class="tx-gray-800 tx-uppercase tx-semibold tx-14 mg-b-30">Daftar Penelitian</h6>
                </div>
            </div>
            <div class="row">
                <div class="bd rounded table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">Judul Penelitian</th>
                                <th class="text-center align-middle">Tahun Penelitian</th>
                                <th class="text-center align-middle">SKS<br>Penelitian</th>
                                <th class="text-center align-middle">Status<br>Anggota</th>
                                <th class="text-center align-middle">Jumlah<br>SKS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($research as $rs)
                            <tr>
                                <td>
                                    <a href="{{route('research.show',encode_id($rs->id))}}">
                                        {{ $rs->judul_penelitian }}
                                    </a>
                                </td>
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
