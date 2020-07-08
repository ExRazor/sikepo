<td class="text-center">
    <div class="btn-group" role="group">
        <button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div><span class="fa fa-caret-down"></span></div>
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
            <button class="dropdown-item btn-edit" data-id="{{ $d->id }}" url-target="{{route('student.foreign.show',$d->id)}}">Sunting</button>
            <form method="POST">
                <input type="hidden" value="{{encode_id($d->id)}}" name="_id">
                <button class="dropdown-item btn-delete" data-dest="{{ route('student.foreign.destroy',$d->id) }}">Hapus</button>
            </form>
        </div>
    </div>
</td>
