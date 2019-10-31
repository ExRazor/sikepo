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

if (!function_exists('encode_url')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function encode_id($string) {
        return str_replace(['+','/','='], ['-','_',''], base64_encode($string));
    }
}

if (!function_exists('decode_url')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function decode_id($string) {
        return base64_decode(str_replace(['-','_'], ['+','/'], $string));
    }
}

if (!function_exists('decode_url')) {
    /**
     * description
     *
     * @param
     * @return
     */
    function rupiah($angka){

        $hasil_rupiah = "Rp " . number_format($angka,0,',','.');
        return $hasil_rupiah;

    }
}
