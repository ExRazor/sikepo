<div class="tab-pane fade" id="status">
    <div class='widget-2'>
        <div class="card card pd-20 pd-xs-30 shadow-base bd-0">
            <div class="row d-flex">
                <div class="pt-1">
                    <h6 class="tx-gray-800 tx-uppercase tx-semibold tx-14 mg-b-30">Status Jabatan</h6>
                </div>
                @if(!Auth::user()->hasRole('kajur'))
                <div class="ml-auto">
                    <button class="btn btn-sm btn-primary mg-b-10 btn-add" data-toggle="modal" data-target="#modal-teach-status"><i class="fa fa-plus mg-r-10"></i> Tambah</button>
                </div>
                @endif
            </div>
            <div class="row">
                <div class="bd rounded table-responsive">
                    <table id="table_teacher_status" class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">Jabatan</th>
                                <th class="text-center align-middle">Periode</th>
                                <th class="text-center align-middle">Homebase</th>
                                @if(!Auth::user()->hasRole('kajur'))
                                <th class="text-center align-middle">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($status as $s)
                            <tr>
                                <td>{{ $s->jabatan }}</td>
                                <td class="text-center">{{ $s->periode }}</td>
                                <td>{{ $s->studyProgram->nama }}</td>
                                @if(!Auth::user()->hasRole('kajur'))
                                <td width="50">
                                    <div class="btn-group" role="group">
                                        <button id="btn-action" type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <div><span class="fa fa-caret-down"></span></div>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                            <button class="dropdown-item btn-edit" data-id="{{encrypt($s->id)}}">Sunting</button>
                                            @if(!$loop->first)
                                            <form method="POST">
                                                <input type="hidden" value="{{encrypt($s->id)}}" name="id">
                                                <a href="#" class="dropdown-item btn-delete" data-dest="{{ route('teacher.status.destroy',$s->id) }}">Hapus</a>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan=4 class="text-center align-middle">BELUM ADA DATA</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('teacher.form-status')

@push('custom-js')
<script>
$('#table_teacher_status').on('click','.btn-edit',function(e){
    var id  = $(this).data('id');
    var url = base_url+'/ajax/teacher/status/'+id;

    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#modal-teach-status')
                .find('input[name=_id]').val(id).end()
                .find('select[name=jabatan]').val(data.jabatan).end()
                .find('input[name=periode]').val(data.periode).end()
                .find('select[name=kd_prodi]').val(data.kd_prodi).end()
                .find('button[type=submit]').attr('data-id',id).end()
                .modal('toggle').end();
        }
    });
});
</script>
@endpush
