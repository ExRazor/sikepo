<div class="tab-pane fade" id="community-service">
    <div class='widget-2'>
        <div class="card card pd-20 pd-xs-30 shadow-base bd-0">
            <div class="row d-flex">
                <div class="pt-1">
                    <h6 class="tx-gray-800 tx-uppercase tx-semibold tx-14 mg-b-30">Daftar Pengabdian</h6>
                </div>
            </div>
            <div class="row">
                <div class="bd rounded table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">Judul Pengabdian</th>
                                <th class="text-center align-middle">Tahun Pengabdian</th>
                                <th class="text-center align-middle">SKS<br>Pengabdian</th>
                                <th class="text-center align-middle">Status<br>Anggota</th>
                                <th class="text-center align-middle">Jumlah<br>SKS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($service as $s)
                            <tr>
                                <td>
                                    <a href="{{route('community-service.show',encode_id($s->id))}}">
                                        {{ $s->judul_pengabdian }}
                                    </a>
                                </td>
                                <td class="text-center">{{ $s->academicYear->tahun_akademik.' - '.$s->academicYear->semester }}</td>
                                <td class="text-center">
                                    {{ $s->sks_pengabdian }}
                                </td>
                                <td class="text-center">
                                    @foreach($s->serviceTeacher as $st)
                                    {{ $st->status }}
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    @foreach($s->serviceTeacher as $st)
                                    {{ $st->sks }}
                                    @endforeach
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
    {{-- @include('teacher.form-schedule') --}}
