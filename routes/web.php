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
    Route::get('academic-year/loadData','AcademicYearController@loadData')->name('ajax.academic-year.load');

    //Fakultas
    Route::post('faculty/edit','FacultyController@edit' );

    //Jurusan
    Route::post('department/edit','DepartmentController@edit' );
    Route::post('department/get_by_faculty','DepartmentController@get_by_faculty' );
    Route::post('department/get_faculty','DepartmentController@get_faculty' );

    //Program Studi
    Route::post('study-program/show','StudyProgramController@show' );
    Route::post('study-program/get_by_department','StudyProgramController@get_by_department')->name('ajax.study-program.filter');
    Route::get('study-program/loadData','StudyProgramController@loadData')->name('ajax.study-program.load');

    //Kerja Sama
    Route::post('collaboration/get_by_filter','CollaborationController@get_by_filter')->name('ajax.collaboration.filter');

    //Teacher
    Route::post('teacher/get_by_filter','TeacherController@get_by_filter')->name('ajax.teacher.filter');
    Route::post('teacher/get_by_studyProgram','TeacherController@get_by_studyProgram')->name('ajax.teacher.studyProgram');
    Route::get('teacher/loadData','TeacherController@loadData')->name('ajax.teacher.loadData');

    //Teacher EWMP
    Route::get('ewmp/countsks','EwmpController@countSKS')->name('ajax.ewmp.countsks');

    //Teacher Achievement
    Route::post('teacher-acv/get_by_filter','TeacherAchievementController@get_by_filter')->name('ajax.teacherAcv.filter');

    //Student Quota
    Route::get('student/quota/{id}','StudentQuotaController@edit')->name('student.quota.edit');

    //Student
    Route::get('student/datatable','StudentController@datatable')->name('ajax.student.datatable');
    Route::get('student/loadData','StudentController@loadData')->name('ajax.student.loadData');
    Route::post('student/get_by_filter','StudentController@get_by_filter')->name('ajax.student.filter');

    //Student - Status
    Route::get('student/status/{id}','StudentStatusController@edit')->name('student.status.edit');

    //Student Foreign
    Route::post('student/foreign/get_by_filter','StudentForeignController@get_by_filter')->name('ajax.student.foreign.filter');

    //Funding Category
    Route::get('funding/category/{id}','FundingCategoryController@edit')->name('funding.category.edit');
    Route::get('funding/category/select/{id}','FundingCategoryController@get_jenis')->name('funding.category.select');

    //Research
    Route::post('research/get_by_filter','ResearchController@get_by_filter')->name('ajax.research.filter');

    //Community Service
    Route::post('community-service/get_by_filter','CommunityServiceController@get_by_filter')->name('ajax.community-service.filter');

    //Publication Category
    Route::get('publication/category/{id}','PublicationCategoryController@edit')->name('publication.category.edit');

    //Publication
    Route::post('publication/get_by_filter','PublicationController@get_by_filter')->name('ajax.publication.filter');

    //Output Activity Category
    Route::get('output-activity/category/{id}','OutputActivityCategoryController@edit')->name('output-activity.category.edit');

    //Output Activity
    Route::post('output-activity/get_by_filter','PublicationController@get_by_filter')->name('ajax.output-activity.filter');

    //Academic - Curriculum
    Route::post('curriculum/get_by_filter','CurriculumController@get_by_filter')->name('ajax.curriculum.filter');
    Route::get('curriculum/loadData','CurriculumController@loadData')->name('ajax.curriculum.loadData');

    //Academic - Schedule
    Route::post('schedule/get_by_filter','CurriculumScheduleController@get_by_filter')->name('ajax.schedule.filter');
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
Route::get('/teacher/list/{id}','TeacherController@profile')->name('teacher.profile');
Route::get('/teacher/list/{id}/edit','TeacherController@edit')->name('teacher.edit');
Route::post('/teacher/list','TeacherController@store')->name('teacher.store');
Route::put('/teacher/list','TeacherController@update')->name('teacher.update');
Route::delete('/teacher/list','TeacherController@destroy')->name('teacher.delete');
Route::get('/download/teacher/{filename}','TeacherController@download')->name('teacher.download');
Route::post('/ajax/teacher/show_by_prodi','TeacherController@show_by_prodi')->name('teacher.show_by_prodi');

//Teacher Achievement
Route::get('/teacher/achievement','TeacherAchievementController@index')->name('teacher.achievement');
Route::get('/teacher-achievement/{nidn}','TeacherAchievementController@edit')->name('teacher.achievement.edit');
Route::post('/teacher-achievement','TeacherAchievementController@store')->name('teacher.achievement.store');
Route::put('/teacher-achievement','TeacherAchievementController@update')->name('teacher.achievement.update');
Route::delete('/teacher-achievement','TeacherAchievementController@destroy')->name('teacher.achievement.delete');
Route::get('/download/teacher-achievement/{filename}','TeacherAchievementController@download')->name('teacher.achievement.download');

//EWMP
Route::get('/teacher/ewmp', 'EwmpController@index')->name('teacher.ewmp');
Route::post('/ajax/ewmp/list','EwmpController@show_by_filter')->name('ewmp.show_filter');
Route::get('/ajax/ewmp/{id}','EwmpController@edit')->name('ewmp.edit');
Route::post('/ajax/ewmp','EwmpController@store')->name('ewmp.store');
Route::put('/ajax/ewmp','EwmpController@update')->name('ewmp.update');
Route::delete('/ajax/ewmp','EwmpController@destroy')->name('ewmp.delete');

//Students - Quota
Route::get('student/quota','StudentQuotaController@index')->name('student.quota');
Route::get('student/quota/add','StudentQuotaController@create')->name('student.quota.add');
Route::post('student/quota','StudentQuotaController@store')->name('student.quota.store');
Route::put('student/quota','StudentQuotaController@update')->name('student.quota.update');
Route::delete('student/quota','StudentQuotaController@destroy')->name('student.quota.delete');

//Students
Route::get('student',function(){
    return redirect(route('student'));
});
Route::get('student/list','StudentController@index')->name('student');
Route::get('student/list/add','StudentController@create')->name('student.add');
Route::get('student/list/{id}','StudentController@profile')->name('student.profile');
Route::get('student/list/{id}/edit','StudentController@edit')->name('student.edit');
Route::post('student/list/upload_photo','StudentController@upload_photo')->name('student.photo');
Route::post('student/list/import','StudentController@import')->name('student.import');
Route::post('student/list','StudentController@store')->name('student.store');
Route::put('student/list','StudentController@update')->name('student.update');
Route::delete('student/list','StudentController@destroy')->name('student.delete');

//Students - Status
Route::post('student/status','StudentStatusController@store')->name('student.status.store');
Route::put('student/status','StudentStatusController@update')->name('student.status.update');
Route::delete('student/status','StudentStatusController@destroy')->name('student.status.delete');

//Students Foreign
Route::get('student/foreign','StudentForeignController@index')->name('student.foreign');
Route::get('student/foreign/add','StudentForeignController@create')->name('student.foreign.add');
Route::get('student/foreign/{id}','StudentForeignController@edit')->name('student.foreign.edit');
Route::post('student/foreign','StudentForeignController@store')->name('student.foreign.store');
Route::put('student/foreign','StudentForeignController@update')->name('student.foreign.update');
Route::delete('student/foreign','StudentForeignController@destroy')->name('student.foreign.delete');

//Funding
Route::get('funding',function(){
    return redirect(route('student'));
});

//Funding - Category
Route::get('funding/category','FundingCategoryController@index')->name('funding.category');
Route::post('funding/category','FundingCategoryController@store')->name('funding.category.store');
Route::put('funding/category','FundingCategoryController@update')->name('funding.category.update');
Route::delete('funding/category','FundingCategoryController@destroy')->name('funding.category.delete');

//Funding - Faculty
Route::get('funding/faculty','FundingFacultyController@index')->name('funding.faculty');
Route::get('funding/faculty/add','FundingFacultyController@create')->name('funding.faculty.add');
Route::get('funding/faculty/{id}','FundingFacultyController@show')->name('funding.faculty.show');
Route::get('funding/faculty/{id}/edit','FundingFacultyController@edit')->name('funding.faculty.edit');
Route::post('funding/faculty','FundingFacultyController@store')->name('funding.faculty.store');
Route::put('funding/faculty','FundingFacultyController@update')->name('funding.faculty.update');
Route::delete('funding/faculty','FundingFacultyController@destroy')->name('funding.faculty.delete');

//Funding - Study Program
Route::get('funding/study-program','FundingStudyProgramController@index')->name('funding.study-program');
Route::get('funding/study-program/add','FundingStudyProgramController@create')->name('funding.study-program.add');
Route::get('funding/study-program/{id}','FundingStudyProgramController@show')->name('funding.study-program.show');
Route::get('funding/study-program/{id}/edit','FundingStudyProgramController@edit')->name('funding.study-program.edit');
Route::post('funding/study-program','FundingStudyProgramController@store')->name('funding.study-program.store');
Route::put('funding/study-program','FundingStudyProgramController@update')->name('funding.study-program.update');
Route::delete('funding/study-program','FundingStudyProgramController@destroy')->name('funding.study-program.delete');

//Research
Route::get('research','ResearchController@index')->name('research');
Route::get('research/add','ResearchController@create')->name('research.add');
Route::get('research/{id}','ResearchController@show')->name('research.show');
Route::get('research/{id}/edit','ResearchController@edit')->name('research.edit');
Route::post('research','ResearchController@store')->name('research.store');
Route::put('research','ResearchController@update')->name('research.update');
Route::delete('research','ResearchController@destroy')->name('research.delete');
Route::get('research/delete_student/{id}','ResearchController@destroy_students')->name('research.students.delete');
Route::get('research/delete_teacher/{id}','ResearchController@destroy_teacher')->name('research.teacher.delete');

//Community Service
Route::get('community-service','CommunityServiceController@index')->name('community-service');
Route::get('community-service/add','CommunityServiceController@create')->name('community-service.add');
Route::get('community-service/{id}','CommunityServiceController@show')->name('community-service.show');
Route::get('community-service/{id}/edit','CommunityServiceController@edit')->name('community-service.edit');
Route::post('community-service','CommunityServiceController@store')->name('community-service.store');
Route::put('community-service','CommunityServiceController@update')->name('community-service.update');
Route::delete('community-service','CommunityServiceController@destroy')->name('community-service.delete');
Route::get('community-service/delete_student/{id}','CommunityServiceController@destroy_students')->name('community-service.students.delete');

//Publication Category
Route::get('publication/category','PublicationCategoryController@index')->name('publication.category');
Route::post('publication/category','PublicationCategoryController@store')->name('publication.category.store');
Route::put('publication/category','PublicationCategoryController@update')->name('publication.category.update');
Route::delete('publication/category','PublicationCategoryController@destroy')->name('publication.category.delete');

//Publication List
Route::get('publication/list','PublicationController@index')->name('publication');
Route::get('publication/list/add','PublicationController@create')->name('publication.add');
Route::get('publication/list/{id}','PublicationController@show')->name('publication.show');
Route::get('publication/list/{id}/edit','PublicationController@edit')->name('publication.edit');
Route::post('publication/list','PublicationController@store')->name('publication.store');
Route::put('publication/list','PublicationController@update')->name('publication.update');
Route::delete('publication/list','PublicationController@destroy')->name('publication.delete');
Route::get('publication/list/delete_student/{id}','PublicationController@destroy_students')->name('publication.students.delete');

//Output Activity Category
Route::get('output-activity/category','OutputActivityCategoryController@index')->name('output-activity.category');
Route::post('output-activity/category','OutputActivityCategoryController@store')->name('output-activity.category.store');
Route::put('output-activity/category','OutputActivityCategoryController@update')->name('output-activity.category.update');
Route::delete('output-activity/category','OutputActivityCategoryController@destroy')->name('output-activity.category.delete');

//Output Activity
Route::get('output-activity/list','OutputActivityController@index')->name('output-activity');
Route::get('output-activity/list/add','OutputActivityController@create')->name('output-activity.add');
Route::get('output-activity/list/{id}','OutputActivityController@show')->name('output-activity.show');
Route::get('output-activity/list/{id}/edit','OutputActivityController@edit')->name('output-activity.edit');
Route::post('output-activity/list','OutputActivityController@store')->name('output-activity.store');
Route::put('output-activity/list','OutputActivityController@update')->name('output-activity.update');
Route::delete('output-activity/list','OutputActivityController@destroy')->name('output-activity.delete');

//Academic - Curriculum
Route::get('academic/curriculum','CurriculumController@index')->name('academic.curriculum');
Route::get('academic/curriculum/add','CurriculumController@create')->name('academic.curriculum.add');
Route::get('academic/curriculum/{id}','CurriculumController@show')->name('academic.curriculum.show');
Route::get('academic/curriculum/{id}/edit','CurriculumController@edit')->name('academic.curriculum.edit');
Route::post('academic/curriculum_import','CurriculumController@import')->name('academic.curriculum.import');
Route::post('academic/curriculum','CurriculumController@store')->name('academic.curriculum.store');
Route::put('academic/curriculum','CurriculumController@update')->name('academic.curriculum.update');
Route::delete('academic/curriculum','CurriculumController@destroy')->name('academic.curriculum.delete');

//Academic - Schedule
Route::get('academic/schedule','CurriculumScheduleController@index')->name('academic.schedule');
Route::get('academic/schedule/add','CurriculumScheduleController@create')->name('academic.schedule.add');
Route::get('academic/schedule/{nidn}/edit','CurriculumScheduleController@edit')->name('academic.schedule.edit');
Route::post('academic/schedule','CurriculumScheduleController@store')->name('academic.schedule.store');
Route::put('academic/schedule','CurriculumScheduleController@update')->name('academic.schedule.update');
Route::delete('academic/schedule','CurriculumScheduleController@destroy')->name('academic.schedule.delete');


Route::get('/home', 'HomeController@index')->name('home');
Route::get('/download/avatar', 'DownloadController@avatar')->name('download.avatar');
