<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ************* Admin User Auth *******************

Route::get('/admin/login', 'Auth\AdminLoginController@showLoginForm');
Route::post('/admin/login', 'Auth\AdminLoginController@login')->name('admin.login');
Route::post('/admin/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');

// ************* User Auth *******************

Auth::routes();

Route::middleware('auth')->namespace('Frontend')->group(function()
{
    Route::get('/', 'PageController@home')->name('home');
    Route::get('/profile', 'PageController@profile')->name('profile');
    Route::get('/wallet', 'PageController@wallet')->name('wallet');

    Route::get('/transation', 'PageController@transation')->name('transation');
    Route::get('/transation/{trx_id}', 'PageController@transationDetail');
    
    Route::get('/receive_qr', 'PageController@receiverQR');
    Route::get('/scan_and_pay', 'PageController@scanAndPay');
    Route::get('/scan_and_pay_form', 'PageController@scanAndPayForm');
    Route::get('/scan_and_pay/confirm', 'PageController@scanAndPayConfirm');
    Route::post('/scan_and_pay/complete', 'PageController@scanAndPayComplete');
    
    Route::get('/transfer_hash', 'PageController@transferHash');

    Route::get('/transfer', 'PageController@transfer')->name('transfer');
    Route::get('/to-account-verify', 'PageController@toAccountVerify');
    Route::get('/transfer/confirm', 'PageController@transferConfirm');
    Route::post('/transfer/complete', 'PageController@transferComplete');
    Route::get('/account_confirm_password', 'PageController@passwordCheck');

    Route::get('/update-password', 'PageController@updatePassword')->name('update-password');
    Route::post('/update-password', 'PageController@updatePasswordStore')->name('update-password.store');

    Route::get('/notification', 'NotificationController@index');
    Route::get('/notification/{id}', 'NotificationController@show');


});
