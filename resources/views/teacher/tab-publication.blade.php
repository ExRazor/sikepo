<div class="tab-pane fade" id="publication">
    <div class='widget-2'>
        <div class="card card pd-20 pd-xs-30 shadow-base bd-0">
            <div class="row d-flex">
                <div class="pt-1">
                    <h6 class="tx-gray-800 tx-uppercase tx-semibold tx-14 mg-b-30">Daftar Publikasi</h6>
                </div>
            </div>
            <div class="row">
                <div class="bd rounded table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">Judul Pengabdian</th>
                                <th class="text-center align-middle">Tahun Publikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($publication as $pb)
                            <tr>
                                <td>
                                    <a href="{{route('publication.show',encrypt($pb->id))}}">
                                        {{ $pb->judul }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    {{ $pb->academicYear->tahun_akademik.' - '.$pb->academicYear->semester }}
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
