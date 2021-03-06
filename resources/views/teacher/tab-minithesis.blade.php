<div class="tab-pane fade" id="minithesis">
        <div class='widget-2'>
            <div class="card card pd-20 pd-xs-30 shadow-base bd-0">
                <div class="row d-flex">
                    <div class="pt-1">
                        <h6 class="tx-gray-800 tx-uppercase tx-semibold tx-14 mg-b-30">Daftar Tugas Akhir</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="bd rounded table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">Judul Tugas Akhir</th>
                                    <th class="text-center align-middle">Tahun Diangkat</th>
                                    <th class="text-center align-middle">Nama Mahasiswa</th>
                                    <th class="text-center align-middle">Pembimbing</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($minithesis as $m)
                                <tr>
                                    <td>{{ $m->judul }}</td>
                                    <td class="text-center">{{ $m->academicYear->tahun_akademik.' - '.$m->academicYear->semester }}</td>
                                    <td>
                                        <a href="{{route('student.list.show',encode_id($m->nim))}}">
                                            {{ $m->student->nama.' ('.$m->nim.')' }}<br>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        @if($m->pembimbingUtama->nidn == $data->nidn)
                                        Utama
                                        @else
                                        Pendamping
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center align-middle">BELUM ADA DATA</td>
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
