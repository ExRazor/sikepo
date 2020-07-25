<div class="btn-group" role="group">
    <button id="btn-action" type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <div><span class="fa fa-caret-down"></span></div>
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
        <button class="dropdown-item reset-password" data-id="{{ encrypt($d->id) }}">Reset Password</button>
    </div>
</div>
