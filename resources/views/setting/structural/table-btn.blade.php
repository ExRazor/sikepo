<div class="btn-group" role="group">
    <button id="btn-action" type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <div><span class="fa fa-caret-down"></span></div>
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
        <button class="dropdown-item btn-edit" data-id="{{encrypt($d->id)}}" data-url="{{ route('ajax.teacher.status.edit',encrypt($d->id))}}'">Sunting</button>
        <form method="POST">
            <input type="hidden" value="{{encrypt($d->id)}}" name="id">
            <button class="dropdown-item btn-delete" data-dest="{{ route('teacher.status.destroy',$d->id) }}">Hapus</button>
        </form>
    </div>
</div>
