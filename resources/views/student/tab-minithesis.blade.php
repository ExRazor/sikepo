<div class="tab-pane fade" id="minithesis">
        <div class="bd rounded table-responsive">
            <table id="table-studentAcv" class="table datatable table-bordered mb-0">
                <thead>
                    <tr>
                        <th class="text-center align-middle">Judul Tugas Akhir</th>
                        <th class="text-center align-middle" width="150">Tahun Diangkat</th>
                        <th class="text-center align-middle" width="300">Pembimbing</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($minithesis as $m)
                    <tr>
                        <td>{{ $m->judul }}</td>
                        <td class="text-center">{{ $m->academicYear->tahun_akademik }} - {{ $m->academicYear->semester }}</td>
                        <td class="text-center">
                            <a href="{{route('teacher.show',encode_id($m->pembimbingUtama->nidn))}}">
                                {{ $m->pembimbingUtama->nama}} (Utama)
                            </a><br>
                            <a href="{{route('teacher.show',encode_id($m->pembimbingPendamping->nidn))}}">
                                {{ $m->pembimbingPendamping->nama}} (Pendamping)
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center align-middle">BELUM ADA DATA</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
