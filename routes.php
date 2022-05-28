<?php

use App\Controllers\LoginController;
use App\Controllers\SmsSendController;
use App\Controllers\SmsStatsController;
use App\Routing\Route;

Route::get('', function(){

});
Route::post('/api/login', [LoginController::class, 'login']);
Route::post('/api/send_sms', [SmsSendController::class, 'send']);
Route::post('/api/sms_stats/history', [SmsStatsController::class, 'history']);
