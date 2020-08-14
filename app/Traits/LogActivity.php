<?php
namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait LogActivity
{
    public function log($action,$target,$properties,$model=null) {
        $activity = activity();
        $name = Auth::user()->name;

        switch($action) {
            case 'created':
                $message = 'menambahkan data '.$target;
            break;
            case 'updated':
                $message = 'menyunting data '.$target;
            break;
            case 'deleted':
                $message = 'menghapus data '.$target;
            break;
        }

        if($model) {
            $activity->performedOn($model);
        }
        $activity->withProperties($properties)
                ->log($message);

    }
}
