<td width="50" class="text-center">
    <div class="btn-group" role="group">
        <button id="btn-action" type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div><span class="fa fa-caret-down"></span></div>
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
            <a class="dropdown-item btn-edit" href="#" data-id="{{ $d->id }}" data-dest="{{ route('teacher.achievement.index') }}">Sunting</a>
            <form method="POST">
                @method('delete')
                @csrf
                <input type="hidden" value="{{encode_id($d->id)}}" name="_id">
                <a href="#" class="dropdown-item btn-delete" data-dest="{{ route('teacher.achievement.destroy',$d->id) }}">Hapus</a>
            </form>
        </div>
    </div>
</td>
