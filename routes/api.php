<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


/**
 * ------------------
 * payfort routes
 * ------------------
 */
Route::match(['get', 'post'], '/api/{provider}/response', 'ZakariaTlilani\PayFort\Http\Controllers\Payment\TransactionController@paymentResponse');
Route::match(['get', 'post'], '/api/{provider}/process-response', 'ZakariaTlilani\PayFort\Http\Controllers\Payment\TransactionController@processPaymentPresponse');
Route::match(['get', 'post'], '/api/{provider}/web-hook', 'ZakariaTlilani\PayFort\Http\Controllers\Payment\TransactionController@webHookNotify');
