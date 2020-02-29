<div class="tab-pane fade" id="status">
    <div class="profil-judul d-flex align-items-center">
        <div class="alert alert-warning" role="alert">
            <span>Status akademik yang aktif akan diambil berdasarkan status terbaru</span>
        </div>
        @if(!Auth::user()->hasRole('kajur'))
        <div class="ml-auto">
            <button class="btn btn-primary mg-b-10 text-white btn-add btn-add-studentStatus" data-toggle="modal" data-target="#modal-student-status"><i class="fa fa-plus mg-r-10"></i> Tambah</button>
        </div>
        @endif
    </div>
    <div class="bd rounded table-responsive">
        <table id="table_student_status" class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th class="text-center align-middle">Tahun Akademik</th>
                    <th class="text-center align-middle">Status</th>
                    <th class="text-center align-middle">IPK Terakhir</th>
                    @if(!Auth::user()->hasRole('kajur'))
                    <th class="text-center align-middle">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($statusList as $sl)
                <tr>
                    <td>{{ $sl->academicYear->tahun_akademik }} - {{ $sl->academicYear->semester }}</td>
                    <td class="text-center">{{ $sl->status }}</td>
                    <td class="text-center">{{ $sl->ipk_terakhir }}</td>
                    @if(!Auth::user()->hasRole('kajur'))
                    <td width="50">
                        @if(!$loop->first)
                        <div class="btn-group hidden-xs-down">
                            <button class="btn btn-warning btn-sm btn-icon rounded-circle mg-r-5 mg-b-10 btn-edit btn-edit-studentStatus" data-id="{{ encrypt($sl->id) }}"><div><i class="fa fa-pencil-alt"></i></div></button>
                            <form method="POST">
                                <input type="hidden" value="{{encrypt($sl->id)}}" name="id">
                                <button type="submit" class="btn btn-danger btn-sm btn-icon rounded-circle mg-r-5 mg-b-10 btn-delete" data-dest="{{ route('student.status.delete') }}">
                                    <div><i class="fa fa-trash"></i></div>
                                </button>
                            </form>
                        </div>
                        @endif
                    </td>
                    @endif
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
