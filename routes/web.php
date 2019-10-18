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

//Master
Route::prefix('master')->name('master.')->group(function () {
    //Tahun Akademik
    Route::get('academic-year', 'AcademicYearController@index')->name('academic-year');
    Route::get('academic-year/{id}', 'AcademicYearController@edit')->name('academic-year.edit');
    Route::post('academic-year','AcademicYearController@store' )->name('academic-year.store');
    Route::put('academic-year','AcademicYearController@update' )->name('academic-year.update');
    Route::delete('academic-year','AcademicYearController@destroy')->name('academic-year.delete');

    //Fakultas
    Route::get('faculty', 'FacultyController@index')->name('faculty');
    Route::get('faculty/add', 'FacultyController@create')->name('faculty.add');
    Route::get('faculty/{id}', 'FacultyController@show')->name('faculty.show');
    Route::post('faculty/', 'FacultyController@store')->name('faculty.store');
    Route::put('faculty','FacultyController@update')->name('faculty.update');
    Route::delete('faculty','FacultyController@destroy')->name('faculty.delete');

    //Program Studi
    Route::get('study-program', 'StudyProgramController@index')->name('study-program');
    Route::get('study-program/add', 'StudyProgramController@create')->name('study-program.add');
    Route::get('study-program/edit/{id}', 'StudyProgramController@edit')->name('study-program.edit');
    Route::post('study-program/', 'StudyProgramController@store')->name('study-program.store');
    Route::put('study-program','StudyProgramController@update')->name('study-program.update');
    Route::delete('study-program','StudyProgramController@destroy')->name('study-program.delete');


});

//Fungsi Ajax
Route::prefix('ajax')->group(function () {
    //Tahun Akademik
    Route::get('academic-year/get_all','AcademicYearController@get_all' );
    Route::post('academic-year/status','AcademicYearController@setStatus' );

    //Fakultas
    Route::get('faculty/{id}','FacultyController@edit' );
});

//Collaboration
Route::get('/collaboration','CollaborationController@index')->name('collaboration');
Route::get('/collaboration/add','CollaborationController@create')->name('collaboration.add');
Route::get('/collaboration/{id}/edit','CollaborationController@edit')->name('collaboration.edit');
Route::post('/collaboration','CollaborationController@store')->name('collaboration.store');
Route::put('/collaboration','CollaborationController@update')->name('collaboration.update');
Route::delete('/collaboration','CollaborationController@destroy')->name('collaboration.delete');
Route::get('/download/collab/{filename}','CollaborationController@download')->name('collaboration.download');

//Teacher
Route::get('/teacher',function(){
    return redirect(route('teacher'));
});
Route::get('/teacher/list','TeacherController@index')->name('teacher');
Route::get('/teacher/list/add','TeacherController@create')->name('teacher.add');
Route::get('/teacher/list/import','TeacherController@import')->name('teacher.import');
Route::get('/teacher/list/detail/{id}','TeacherController@show')->name('teacher.show');
Route::get('/teacher/list/edit/{id}','TeacherController@edit')->name('teacher.edit');
Route::post('/teacher/list','TeacherController@store')->name('teacher.store');
Route::put('/teacher/list','TeacherController@update')->name('teacher.update');
Route::delete('/teacher/list','TeacherController@destroy')->name('teacher.delete');
Route::get('/download/teacher/{filename}','TeacherController@download')->name('teacher.download');
Route::post('/ajax/teacher/show_by_prodi','TeacherController@show_by_prodi')->name('teacher.show_by_prodi');

Route::get('/student',function(){
    return redirect(route('student.registrant'));
});
Route::get('/student/list','StudentController@index')->name('student');
Route::get('/student/registrant/add','StudentRegistrantController@create')->name('student.registrant.add');
Route::get('/student/registrant/detail/{id}','TeacherController@show')->name('teacher.show');
Route::get('/student/registrant/edit/{id}','StudentRegistrantController@edit')->name('student.registrant.edit');
Route::post('/student/registrant','StudentRegistrantController@store')->name('student.registrant.store');
Route::put('/student/registrant','StudentRegistrantController@update')->name('student.registrant.update');
Route::delete('/student/registrant','StudentRegistrantController@destroy')->name('student.registrant.delete');

//Teacher Achievement
Route::get('/teacher/achievement','TeacherAchievementController@index')->name('teacher.achievement');
Route::get('/ajax/teacher-achievement/{nidn}','TeacherAchievementController@edit')->name('teacher.achievement.edit');
Route::post('/ajax/teacher-achievement','TeacherAchievementController@store')->name('teacher.achievement.store');
Route::put('/ajax/teacher-achievement','TeacherAchievementController@update')->name('teacher.achievement.update');
Route::delete('/ajax/teacher-achievement','TeacherAchievementController@destroy')->name('teacher.achievement.delete');
Route::get('/download/teacher-achievement/{filename}','TeacherAchievementController@download')->name('teacher.achievement.download');

//EWMP
Route::get('/teacher/ewmp', 'EwmpController@index')->name('teacher.ewmp');
Route::post('/ajax/ewmp/list','EwmpController@show_by_filter')->name('ewmp.show_filter');
Route::get('/ajax/ewmp/{id}','EwmpController@edit')->name('ewmp.edit');
Route::post('/ajax/ewmp','EwmpController@store')->name('ewmp.store');
Route::put('/ajax/ewmp','EwmpController@update')->name('ewmp.update');
Route::delete('/ajax/ewmp','EwmpController@destroy')->name('ewmp.delete');

