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

Route::get('/', 'IndexController@index')->name('index');

Auth::routes(['verify' => true]);

Route::group(['namespace' => 'Settings'], function(){
    Route::get('roles/json', 'RolesController@index');
});

Route::group(['middleware' => ['auth', 'verified']], function(){

    Route::get('home', 'HomeController@index')->name('home');

    Route::group(['namespace' => 'Appointments'], function(){

        Route::group([
            'prefix' => 'appointments', 'middleware' => ['auth.patient']
        ], function(){
            Route::get('/', 'AppointmentsController@index')->name('appointments.index');
            Route::post('/', 'AppointmentsController@store')->name('appointments.store');
            Route::get('create', 'AppointmentsController@create')->name('appointments.create');
            Route::get('edit/{appointment}', 'AppointmentsController@edit')->name('appointments.edit');
            Route::patch('update', 'AppointmentsController@update')->name('appointments.update');
        });

        Route::group([
            'prefix' => 'schedules', 'middleware' => ['auth.doctor']
        ], function(){
            Route::get('/', 'SchedulesController@index')->name('schedules.index');
            Route::get('show/{appointment}', 'SchedulesController@show')->name('schedules.show');
            Route::get('edit/{appointment}', 'SchedulesController@edit')->name('schedules.edit');
            Route::patch('update', 'SchedulesController@update')->name('schedules.update');
        });

    });

});

