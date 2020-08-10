<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;

class BaseModel extends Model
{
    use Userstamps;
    use LogsActivity;

    protected static $logFillable = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        switch($eventName) {
            case 'created':
                $aksi = 'menambahkan data baru';
            break;
            case 'updated':
                $aksi = 'menyunting data';
            break;
            case 'deleted':
                $aksi = 'menghapus data';
            break;
        }
        $user = Auth::user()->name;

        return ":causer.name telah {$aksi}";
    }
}
