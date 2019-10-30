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

    Route::post('academic-year','AcademicYearController@store' )->name('academic-year.store');
    Route::put('academic-year','AcademicYearController@update' )->name('academic-year.update');
    Route::delete('academic-year','AcademicYearController@destroy')->name('academic-year.delete');

    //Fakultas
    Route::get('faculty', 'FacultyController@index')->name('faculty');
    Route::get('faculty/add', 'FacultyController@create')->name('faculty.add');
    Route::get('faculty/{id}', 'FacultyController@show')->name('faculty.show');
    Route::post('faculty', 'FacultyController@store')->name('faculty.store');
    Route::put('faculty','FacultyController@update')->name('faculty.update');
    Route::delete('faculty','FacultyController@destroy')->name('faculty.delete');

    //Jurusan
    Route::get('department', 'DepartmentController@index')->name('department');
    Route::get('department/add', 'DepartmentController@create')->name('department.add');
    Route::post('department/show', 'DepartmentController@show')->name('department.show');
    Route::post('department', 'DepartmentController@store')->name('department.store');
    Route::put('department','DepartmentController@update')->name('department.update');
    Route::delete('department','DepartmentController@destroy')->name('department.delete');

    //Program Studi
    Route::get('study-program', 'StudyProgramController@index')->name('study-program');
    Route::get('study-program/add', 'StudyProgramController@create')->name('study-program.add');
    Route::get('study-program/{id}/edit', 'StudyProgramController@edit')->name('study-program.edit');
    Route::post('study-program', 'StudyProgramController@store')->name('study-program.store');
    Route::put('study-program','StudyProgramController@update')->name('study-program.update');
    Route::delete('study-program','StudyProgramController@destroy')->name('study-program.delete');
});

//Setting
Route::prefix('setting')->name('setting.')->group(function () {

    //Umum
    Route::get('general', 'SettingController@index')->name('general');
    Route::put('general', 'SettingController@update')->name('general.update');
});

//Fungsi Ajax
Route::prefix('ajax')->group(function () {
    //Tahun Akademik
    Route::post('academic-year/edit', 'AcademicYearController@edit');
    Route::post('academic-year/status','AcademicYearController@setStatus');

    //Fakultas
    Route::post('faculty/edit','FacultyController@edit' );

    //Jurusan
    Route::post('department/edit','DepartmentController@edit' );
    Route::post('department/get_by_faculty','DepartmentController@get_by_faculty' );
    Route::post('department/get_faculty','DepartmentController@get_faculty' );

    //Program Studi
    Route::post('study-program/show','StudyProgramController@show' );
    Route::post('study-program/get_by_department','StudyProgramController@get_by_department')->name('ajax.study-program.filter');

    //Teacher
    Route::post('teacher/get_by_filter','TeacherController@get_by_filter')->name('ajax.teacher.filter');

    //Student
    Route::post('student/get_by_filter','StudentController@get_by_filter')->name('ajax.student.filter');

    //Student Quota
    Route::get('student/quota/{id}','StudentQuotaController@edit')->name('student.quota.edit');

    //Funding Category
    Route::get('funding/category/{id}','FundingCategoryController@edit')->name('funding.category.edit');
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
Route::get('/teacher/list/{id}','TeacherController@show')->name('teacher.show');
Route::get('/teacher/list/{id}/edit','TeacherController@edit')->name('teacher.edit');
Route::post('/teacher/list','TeacherController@store')->name('teacher.store');
Route::put('/teacher/list','TeacherController@update')->name('teacher.update');
Route::delete('/teacher/list','TeacherController@destroy')->name('teacher.delete');
Route::get('/download/teacher/{filename}','TeacherController@download')->name('teacher.download');
Route::post('/ajax/teacher/show_by_prodi','TeacherController@show_by_prodi')->name('teacher.show_by_prodi');

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

//Students
Route::get('student',function(){
    return redirect(route('student'));
});
Route::get('student/list','StudentController@index')->name('student');
Route::get('student/list/add','StudentController@create')->name('student.add');
Route::get('student/list/{id}','StudentController@profile')->name('student.profile');
Route::get('student/list/{id}/edit','StudentController@edit')->name('student.edit');
Route::post('student/list/upload_photo','StudentController@upload_photo')->name('student.photo');
Route::post('student/list','StudentController@store')->name('student.store');
Route::put('student/list','StudentController@update')->name('student.update');
Route::delete('student/list','StudentController@destroy')->name('student.delete');

//Students - Quota
Route::get('student/quota','StudentQuotaController@index')->name('student.quota');
Route::get('student/quota/add','StudentQuotaController@create')->name('student.quota.add');
Route::post('student/quota','StudentQuotaController@store')->name('student.quota.store');
Route::put('student/quota','StudentQuotaController@update')->name('student.quota.update');
Route::delete('student/quota','StudentQuotaController@destroy')->name('student.quota.delete');

//Funding
Route::get('funding',function(){
    return redirect(route('student'));
});
Route::get('funding/category','FundingCategoryController@index')->name('funding.category');
Route::post('funding/category','FundingCategoryController@store')->name('funding.category.store');
Route::put('funding/category','FundingCategoryController@update')->name('funding.category.update');
Route::delete('funding/category','FundingCategoryController@destroy')->name('funding.category.delete');

//Funding
Route::get('funding/study-program','FundingStudyProgramController@index')->name('funding.study-program');
Route::post('funding/study-program','FundingStudyProgramController@store')->name('funding.study-program.store');
Route::put('funding/study-program','FundingStudyProgramController@update')->name('funding.study-program.update');
Route::delete('funding/study-program','FundingStudyProgramController@destroy')->name('funding.study-program.delete');


Route::get('/home', 'HomeController@index')->name('home');
Route::get('/download/avatar', 'DownloadController@avatar')->name('download.avatar');
