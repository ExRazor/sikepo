<div class="tab-pane fade" id="community-service">
    <div class="bd rounded table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th class="text-center align-middle">Judul Pengabdian</th>
                    <th class="text-center align-middle">Tahun Pengabdian</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($service as $cs)
                <tr>
                    <td>
                        <a href="{{route('community-service.show',encode_id($cs->id))}}">
                            {{ $cs->judul_pengabdian }}
                        </a>
                    </td>
                    <td class="text-center">
                        {{ $cs->academicYear->tahun_akademik.' - '.$cs->academicYear->semester }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center">
                        BELUM ADA DATA PENGABDIAN
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
