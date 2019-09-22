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
Route::get('/setpro/{prodi}', 'PageController@set_prodi');

//Academic Year
Route::get('/master/academic-year', 'AcademicYearController@index')->name('master.academic-year');
Route::get('/master/academic-year/{id}', 'AcademicYearController@edit');
Route::post('/master/academic-year','AcademicYearController@store' );
Route::put('/master/academic-year','AcademicYearController@update' );
Route::delete('/master/academic-year','AcademicYearController@destroy' );

Route::get('/ajax/academic-year/get_all','AcademicYearController@get_all' );
Route::post('/ajax/academic-year/status','AcademicYearController@setStatus' );

//Study Program
Route::get('/master/study-program', 'StudyProgramController@index')->name('master.study-program');
Route::get('/master/study-program/add', 'StudyProgramController@create')->name('master.study-program.add');
Route::get('/master/study-program/{id}', 'StudyProgramController@show')->name('master.study-program.show');
Route::get('/master/study-program/{id}/edit', 'StudyProgramController@edit')->name('master.study-program.edit');
Route::post('/master/study-program/', 'StudyProgramController@store')->name('master.study-program.store');
Route::put('/master/study-program','StudyProgramController@update')->name('master.study-program.update');
Route::delete('/master/study-program','StudyProgramController@destroy')->name('master.study-program.delete');

//Collaboration
Route::get('/collaboration','CollaborationController@index')->name('collaboration');
Route::get('/collaboration/add','CollaborationController@create')->name('collaboration.add');
Route::get('/collaboration/{id}/edit','CollaborationController@edit')->name('collaboration.edit');
Route::post('/collaboration','CollaborationController@store')->name('collaboration.store');
Route::put('/collaboration','CollaborationController@update')->name('collaboration.update');
Route::delete('/collaboration','CollaborationController@destroy')->name('collaboration.delete');
Route::get('/download/collab/{filename}','CollaborationController@download')->name('collaboration.download');

//Teacher
Route::get('/teacher','TeacherController@index')->name('teacher');
Route::get('/teacher/add','TeacherController@create')->name('teacher.add');
Route::get('/teacher/import','TeacherController@import')->name('teacher.import');
Route::get('/teacher/{id}/edit','TeacherController@edit')->name('teacher.edit');
Route::post('/teacher','TeacherController@store')->name('teacher.store');
Route::put('/teacher','TeacherController@update')->name('teacher.update');
Route::delete('/teacher','TeacherController@destroy')->name('teacher.delete');

