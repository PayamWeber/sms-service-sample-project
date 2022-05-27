<?php

require_once "../load.php";

try {
    // Here we go through the routes where registered in routes.php and find the right one and print the response of that route
    \App\Routing\Route::detectAndPrintResponseOfCurrentRoute();
} catch (Exception $e) {
    if($e instanceof \App\Exceptions\BaseException){
        $e->print();
    }else{
        throw $e;
    }
}