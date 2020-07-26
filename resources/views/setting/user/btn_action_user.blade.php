<div class="btn-group" role="group">
    <button id="btn-action" type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <div><span class="fa fa-caret-down"></span></div>
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
        <button class="dropdown-item btn-edit" data-id="{{encrypt($d->id)}}" url-target="{{ route('setting.user.show',encrypt($d->id)) }}">Sunting</button>
        <button class="dropdown-item reset-password" data-id="{{ encrypt($d->id) }}">Reset Password</button>
        <form method="POST">
            @method('delete')
            @csrf
            <input type="hidden" value="{{ encrypt($d->id) }}" name="id">
            <button class="dropdown-item btn-delete" data-dest="{{ route('setting.user.destroy',encrypt($d->id)) }}">Hapus</button>
        </form>
    </div>
</div>
