<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Pages Controller
Route::get('/dashboard', 'PageController@dashboard')->name('dashboard');

//Academic Year
Route::get('/master/academic-year', 'AcademicYearController@index')->name('master.academic-year');
Route::get('/master/academic-year/{id}', 'AcademicYearController@edit');
Route::post('/master/academic-year','AcademicYearController@store' );
Route::put('/master/academic-year','AcademicYearController@update' );

Route::get('/ajax/academic-year/get_all','AcademicYearController@get_all' );
Route::post('/ajax/academic-year/status','AcademicYearController@setStatus' );
Route::delete('/master/academic-year','AcademicYearController@destroy' );
