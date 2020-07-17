<div class="tab-pane fade" id="research">
    <div class="bd rounded table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th class="text-center align-middle">Judul Penelitian</th>
                    <th class="text-center align-middle">Tahun Penelitian</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($research as $rs)
                <tr>
                    <td>
                        <a href="{{route('research.show',encode_id($rs->id))}}">
                            {{ $rs->judul_penelitian }}
                        </a>
                    </td>
                    <td class="text-center">
                        {{ $rs->academicYear->tahun_akademik.' - '.$rs->academicYear->semester }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center">
                        BELUM ADA DATA PENELITIAN
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
