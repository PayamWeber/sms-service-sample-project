<?php

if (!function_exists('dump')) {
    /**
     * @param $var
     * @return void
     */
    function dump($var)
    {
        echo "<pre>";
        var_dump($var);
        echo "</pre><br>";
    }
}

if (!function_exists('dd')) {
    /**
     * @param $var
     * @return void
     */
    function dd($var)
    {
        dump($var);
        die();
    }
}