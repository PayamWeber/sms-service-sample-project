<?php

use App\Controllers\LoginController;
use App\Controllers\SmsSendController;
use App\Routing\Route;

Route::get('', function(){

});
Route::post('/api/login', [LoginController::class, 'login']);
Route::post('/api/send_sms', [SmsSendController::class, 'send']);
