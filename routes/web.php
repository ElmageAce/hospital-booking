<?php

use Illuminate\Support\Facades\Auth;
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
Auth::routes();

Route::group(['middleware' => ['auth']], function(){

    Route::group(['namespace' => 'Appointments'], function(){

        Route::group([
            'prefix' => 'appointments',
            'middleware' => ['auth.patient']
        ], function(){
            Route::get('/', 'AppointmentsController@index')->name('appointments.index');
            Route::post('/', 'AppointmentsController@store')->name('appointments.store');
            Route::get('create', 'AppointmentsController@create')->name('appointments.create');
            Route::get('edit/{id}', 'AppointmentsController@edit')->name('appointments.edit');
            Route::patch('update', 'AppointmentsController@update')->name('appointments.update');
        });

    });

});

