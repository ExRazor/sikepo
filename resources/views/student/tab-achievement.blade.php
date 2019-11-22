<div class="tab-pane fade" id="achievement">
        <div class="profil-judul d-flex align-items-center">
            <div class="ml-auto">
                <button class="btn btn-primary mg-b-10 text-white btn-add" data-toggle="modal" data-target="#modal-student-acv"><i class="fa fa-plus mg-r-10"></i> Tambah</button>
            </div>
        </div>
        <div class="bd rounded table-responsive">
            <table id="table-studentAcv" class="table datatable table-bordered mb-0">
                <thead>
                    <tr>
                        <th class="text-center align-middle" width="115">Tahun</th>
                        <th class="text-center align-middle" width="300">Nama Kegiatan</th>
                        <th class="text-center align-middle">Tingkat</th>
                        <th class="text-center align-middle" width="300">Prestasi</th>
                        <th class="text-center align-middle" width="125">Jenis</th>
                        <th class="text-center align-middle" width="50">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($achievement as $acv)
                    <tr>
                        <td class="text-center">{{ $acv->academicYear->tahun_akademik }} - {{ $acv->academicYear->semester }}</td>
                        <td>{{ $acv->kegiatan_nama }}</td>
                        <td>{{ $acv->kegiatan_tingkat }}</td>
                        <td>{{ $acv->prestasi }}</td>
                        <td>{{ $acv->prestasi_jenis }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button id="btn-action" type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div><span class="fa fa-caret-down"></span></div>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                    <button class="dropdown-item btn-edit" data-id="{{ encode_id($acv->id) }}">Sunting</button>
                                    <form method="POST">
                                        <input type="hidden" value="{{encode_id($acv->id)}}" name="_id">
                                        <button class="dropdown-item btn-delete" data-dest="{{ route('student.achievement.delete') }}">Hapus</button>
                                    </form>
                                </div>
                            </div>
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
