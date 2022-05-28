<?php

use App\Controllers\ExampleController;
use App\Controllers\LoginController;
use App\Controllers\SmsSendController;
use App\Controllers\SmsStatsController;
use App\Routing\Route;

Route::get('', function(){

});
Route::post('/api/login', [LoginController::class, 'login']);
Route::post('/api/sms/send', [SmsSendController::class, 'send']);
Route::post('/api/sms/stats/history', [SmsStatsController::class, 'history']);

// This is an example route for you to see how you can get parameters in path and use it in ExampleController.
Route::get('/example_route/{example_parameter1}/test/{example_parameter2}', [ExampleController::class, 'sample']);
