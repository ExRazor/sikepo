<td class="text-center" width="50">
    <div class="btn-group" role="group">
        <button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div><span class="fa fa-caret-down"></span></div>
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
            <a class="dropdown-item" href="{{ route('collaboration.edit',$d->id) }}">Sunting</a>
            <form method="POST">
                <input type="hidden" value="{{encrypt($d->id)}}" name="id">
                <button class="dropdown-item btn-delete" data-dest="{{ route('collaboration.destroy',$d->id) }}">Hapus</button>
            </form>
        </div>
    </div>
</td>
