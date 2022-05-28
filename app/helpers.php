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

if (!function_exists('request')) {
    /**
     * @return \App\Services\Request
     */
    function request(): \App\Services\Request
    {
        return \App\Services\Request::getInstance();
    }
}

if (!function_exists('str_random')) {
    /**
     * @param int $length
     * @return string
     */
    function str_random(int $length = 16): string
    {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;

            try {
                $bytes = random_bytes($size);
            } catch (Exception $e) {
                $bytes = '123456789';
            }

            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }
}

if (!function_exists('auth')) {
    /**
     * @return \App\Auth\Auth
     */
    function auth(): \App\Auth\Auth
    {
        return \App\Auth\Auth::getInstance();
    }
}

if (!function_exists('arabic_persian_to_english')) {
    /**
     * @param $string
     * @return string
     */
    function arabic_persian_to_english($string): string
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨','٩'];

        $num = range(0, 9);
        $convertedPersianNums = str_replace($persian, $num, $string);

        return str_replace($arabic, $num, $convertedPersianNums);
    }
}