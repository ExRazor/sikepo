<div class="tab-pane fade" id="publication">
    <div class="bd rounded table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th class="text-center align-middle">Judul Publikasi</th>
                    <th class="text-center align-middle">Tahun Publikasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($publication as $pb)
                <tr>
                    <td>
                        <a href="{{route('publication.student.show',encode_id($pb->id))}}">
                            {{ $pb->judul }}
                        </a>
                    </td>
                    <td class="text-center">
                        {{ $pb->academicYear->tahun_akademik.' - '.$pb->academicYear->semester }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center">
                        BELUM ADA DATA PUBLIKASI
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
