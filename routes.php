<?php

use App\Controllers\SmsSendController;
use App\Routing\Route;

Route::get('', function(){

});
Route::get('/send_sms', [SmsSendController::class, 'send']);
