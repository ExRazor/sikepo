<?php

use App\Setting;

if (!function_exists('setting')) {
    /**
     * description
     *
     * @param
     * @return
     */
    function setting($key) {

        $data = Setting::whereName($key)->first();

        if($data) {
            return $data->value;
        } else {
            return '';
        }
    }
}
