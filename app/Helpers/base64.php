<?php

if (!function_exists('encode_base64')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function encode_base64($string) {
        return str_replace(['+','/','='], ['-','_',''], base64_encode($string));
    }
}

if (!function_exists('decode_base64')) {

    /**
     * description
     *
     * @param
     * @return
     */    
    function decode_base64($string) {
        return base64_decode(str_replace(['-','_'], ['+','/'], $string));
    }
}
