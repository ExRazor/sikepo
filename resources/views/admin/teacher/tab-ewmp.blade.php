<div class="tab-pane fade" id="ewmp">
    <div class='widget-2'>
        <div class="card card pd-20 pd-xs-30 shadow-base bd-0">
            <div class="row d-flex">
                <div class="pt-1">
                    <h6 class="tx-gray-800 tx-uppercase tx-semibold tx-14 mg-b-30">EWMP Per Semester Akademik</h6>
                </div>
                <div class="ml-auto">
                    <button href="#" class="btn btn-sm btn-primary mg-b-10 btn-add" data-toggle="modal" data-target="#modal-teach-ewmp" style="color:white"><i class="fa fa-plus mg-r-10"></i> Tambah</button>
                </div>
            </div>
            <div class="row">
                <div class="bd rounded table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th rowspan="3" class="text-center align-middle">Tahun Akademik</th>
                                <th colspan="6" class="text-center align-middle">Ekuivalen Waktu Mengajar Penuh (EWMP)<br>dalam satuan kredit semester (sks)</th>
                                <th rowspan="3" class="text-center align-middle">Jumlah<br>(sks)</th>
                                <th rowspan="3" class="text-center align-middle">Rata-rata<br>(sks)</th>
                                <th rowspan="3" class="text-center align-middle">Aksi</th>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-center align-middle" style="border-left-width: 1px;">Pendidikan</th>
                                <th rowspan="2" class="text-center align-middle" width="100">Penelitian</th>
                                <th rowspan="2" class="text-center align-middle">PKM</th>
                                <th rowspan="2" class="text-center align-middle" width="100">Tugas Tambahan/<br>Penunjang</th>
                            </tr>
                            <tr>
                                <th class="text-center align-middle" style="border-left-width: 1px;" width="70">PS</th>
                                <th class="text-center align-middle" width="70">PS Luar</th>
                                <th class="text-center align-middle" width="70">Luar PT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ewmp as $e)
                            <tr>
                                <td>{{ $e->academicYear->tahun_akademik.' - '.$e->academicYear->semester }}</td>
                                <td class="text-center">{{ $e->ps_intra }}</td>
                                <td class="text-center">{{ $e->ps_lain }}</td>
                                <td class="text-center">{{ $e->ps_luar }}</td>
                                <td class="text-center">{{ $e->penelitian }}</td>
                                <td class="text-center">{{ $e->pkm }}</td>
                                <td class="text-center">{{ $e->tugas_tambahan }}</td>
                                <td class="text-center">{{ $total = $e->ps_intra+$e->ps_lain+$e->ps_luar+$e->penelitian+$e->pkm+$e->tugas_tambahan}}</td>
                                <td class="text-center">{{ number_format($total/6, 1, ',', ' ') }}</td>
                                <td width="50">
                                    <div class="btn-group" role="group">
                                        <button id="btn-action" type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <div><span class="fa fa-caret-down"></span></div>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                            <a class="dropdown-item btn-edit btn-edit-ewmp" href="#" data-id="{{encrypt($e->id)}}">Sunting</a>
                                            <form method="POST">
                                                @method('delete')
                                                @csrf
                                                <input type="hidden" value="{{encrypt($e->id)}}" name="_id">
                                                <a href="#" class="dropdown-item btn-delete" data-dest="{{ route('ewmp.delete') }}">Hapus</a>
                                            </form>
                                        </div>
                                    </div>
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
@include('admin.teacher.form-ewmp')
