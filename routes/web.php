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

// Login Routes...
Route::get('login', 'Auth\LoginController@form')->middleware('guest')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login_post');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::middleware('auth')->group(function () {

    //Pages Controller
    Route::get('/', 'PageController@dashboard')->name('dashboard');

    //Master Data
    Route::prefix('master')->name('master.')->middleware('role:admin')->group(function () {

        //Academic Year
        Route::get('academic-year', 'AcademicYearController@index')->name('academic-year');
        Route::post('academic-year','AcademicYearController@store' )->name('academic-year.store');
        Route::put('academic-year','AcademicYearController@update' )->name('academic-year.update');
        Route::delete('academic-year','AcademicYearController@destroy')->name('academic-year.delete');

        //Faculty
        Route::get('faculty', 'FacultyController@index')->name('faculty');
        Route::get('faculty/add', 'FacultyController@create')->name('faculty.add');
        Route::get('faculty/{id}', 'FacultyController@show')->name('faculty.show');
        Route::post('faculty', 'FacultyController@store')->name('faculty.store');
        Route::put('faculty','FacultyController@update')->name('faculty.update');
        Route::delete('faculty','FacultyController@destroy')->name('faculty.delete');

        //Department
        Route::get('department', 'DepartmentController@index')->name('department');
        Route::get('department/add', 'DepartmentController@create')->name('department.add');
        Route::post('department/show', 'DepartmentController@show')->name('department.show');
        Route::post('department', 'DepartmentController@store')->name('department.store');
        Route::put('department','DepartmentController@update')->name('department.update');
        Route::delete('department','DepartmentController@destroy')->name('department.delete');

        //Study Program
        Route::get('study-program', 'StudyProgramController@index')->name('study-program');
        Route::get('study-program/add', 'StudyProgramController@create')->name('study-program.add');
        Route::get('study-program/{id}/edit', 'StudyProgramController@edit')->name('study-program.edit');
        Route::post('study-program', 'StudyProgramController@store')->name('study-program.store');
        Route::put('study-program','StudyProgramController@update')->name('study-program.update');
        Route::delete('study-program','StudyProgramController@destroy')->name('study-program.delete');

        //Satisfaction Category
        Route::get('satisfaction-category', 'SatisfactionCategoryController@index')->name('satisfaction-category');
        Route::post('satisfaction-category', 'SatisfactionCategoryController@store')->name('satisfaction-category.store');
        Route::put('satisfaction-category','SatisfactionCategoryController@update')->name('satisfaction-category.update');
        Route::delete('satisfaction-category','SatisfactionCategoryController@destroy')->name('satisfaction-category.delete');

        //Publication Category
        Route::get('publication-category','PublicationCategoryController@index')->name('publication-category');
        Route::post('publication-category','PublicationCategoryController@store')->name('publication-category.store');
        Route::put('publication-category','PublicationCategoryController@update')->name('publication-category.update');
        Route::delete('publication-category','PublicationCategoryController@destroy')->name('publication-category.delete');

        //Output Activity Category
        Route::get('outputactivity-category','OutputActivityCategoryController@index')->name('outputactivity-category');
        Route::post('outputactivity-category','OutputActivityCategoryController@store')->name('outputactivity-category.store');
        Route::put('outputactivity-category','OutputActivityCategoryController@update')->name('outputactivity-category.update');
        Route::delete('outputactivity-category','OutputActivityCategoryController@destroy')->name('outputactivity-category.delete');

        //Funding Category
        Route::get('funding-category','FundingCategoryController@index')->name('funding-category');
        Route::post('funding-category','FundingCategoryController@store')->name('funding-category.store');
        Route::put('funding-category','FundingCategoryController@update')->name('funding-category.update');
        Route::delete('funding-category','FundingCategoryController@destroy')->name('funding-category.delete');
    });

    //Setting
    Route::prefix('setting')->name('setting.')->middleware('role:admin')->group(function () {
        //General
        Route::get('general', 'SettingController@index')->name('general');
        Route::put('general', 'SettingController@update')->name('general.update');

        //User
        Route::get('user', 'UserController@index')->name('user');
        Route::post('user', 'UserController@store')->name('user.store');
        Route::put('user', 'UserController@update')->name('user.update');
        Route::delete('user', 'UserController@destroy')->name('user.delete');
        Route::post('user/resetpass', 'UserController@reset_password')->name('user.resetpass');
        Route::get('user/{id}', 'UserController@edit')->name('user.edit');
    });

    //Ajax
    Route::prefix('ajax')->name('ajax.')->group(function () {
        //Academic Year
        Route::post('academic-year/edit', 'AcademicYearController@edit');
        Route::post('academic-year/status','AcademicYearController@setStatus');
        Route::get('academic-year/loadData','AcademicYearController@loadData')->name('academic-year.load');

        //Faculty
        Route::post('faculty/edit','FacultyController@edit' );

        //Department
        Route::post('department/edit','DepartmentController@edit' );
        Route::post('department/get_by_faculty','DepartmentController@get_by_faculty' );
        Route::post('department/get_faculty','DepartmentController@get_faculty' );

        //Study Program
        Route::post('study-program/show','StudyProgramController@show' );
        Route::post('study-program/get_by_department','StudyProgramController@get_by_department')->name('study-program.filter');
        Route::get('study-program/loadData','StudyProgramController@loadData')->name('study-program.load');

        //Satisfaction Category
        Route::get('satisfaction-category/{id}','SatisfactionCategoryController@edit')->name('satisfaction-category.edit');

        //Publication Category
        Route::get('publication/category/{id}','PublicationCategoryController@edit')->name('publication.category.edit');

        //Output Activity Category
        Route::get('output-activity/category/{id}','OutputActivityCategoryController@edit')->name('output-activity.category.edit');

        //Funding Category
        Route::get('funding/category/{id}','FundingCategoryController@edit')->name('funding.category.edit');
        Route::get('funding/category/select/{id}','FundingCategoryController@get_jenis')->name('funding.category.select');

        //Collaboration
        Route::post('collaboration/get_by_filter','CollaborationController@get_by_filter')->name('collaboration.filter');

        //Teacher
        Route::post('teacher/get_by_filter','TeacherController@get_by_filter')->name('teacher.filter');
        Route::post('teacher/get_by_studyProgram','TeacherController@get_by_studyProgram')->name('teacher.studyProgram');
        Route::get('teacher/loadData','TeacherController@loadData')->name('teacher.loadData');

        //Teacher - EWMP
        Route::get('ewmp/countsks','EwmpController@countSKS')->name('ewmp.countsks');
        Route::post('ewmp/list','EwmpController@show_by_filter')->name('ewmp.show_filter');
        Route::get('ewmp/{id}','EwmpController@edit')->name('ewmp.edit');
        Route::post('ewmp','EwmpController@store')->name('ewmp.store');
        Route::put('ewmp','EwmpController@update')->name('ewmp.update');
        Route::delete('ewmp','EwmpController@destroy')->name('ewmp.delete');

        //Teacher - Achievement
        Route::post('teacher/achievement/get_by_filter','TeacherAchievementController@get_by_filter')->name('teacher.achievement.filter');

        //Student
        Route::get('student/datatable','StudentController@datatable')->name('student.datatable');
        Route::get('student/loadData','StudentController@loadData')->name('student.loadData');
        Route::get('student/select_by_studyProgram','StudentController@select_by_studyProgram')->name('student.studyProgram');
        Route::post('student/get_by_studyProgram','StudentController@get_by_studyProgram');
        Route::post('student/get_by_filter','StudentController@get_by_filter')->name('student.filter');

        //Student - Quota
        Route::get('student/quota/{id}','StudentQuotaController@edit')->name('student.quota.edit');

        //Student - Status
        Route::get('student/status/{id}','StudentStatusController@edit')->name('student.status.edit');

        //Student - Foreign
        Route::post('student/foreign/get_by_filter','StudentForeignController@get_by_filter')->name('student.foreign.filter');

        //Student - Achievement
        Route::post('student/achievement/get_by_filter','StudentAchievementController@get_by_filter')->name('student.achievement.filter');

        //Research
        Route::get('research/get_by_department','ResearchController@get_by_department')->name('research.get_by_department');
        Route::post('research/get_by_filter','ResearchController@get_by_filter')->name('research.filter');

        //Community Service
        Route::get('community-service/get_by_department','CommunityServiceController@get_by_department')->name('community-service.get_by_department');
        Route::post('community-service/get_by_filter','CommunityServiceController@get_by_filter')->name('community-service.filter');

        //Publication - Teacher
        Route::post('publication/teacher/get_by_filter','TeacherPublicationController@get_by_filter')->name('publication.teacher.filter');

        //Publication - Student
        Route::post('publication/student/get_by_filter','StudentPublicationController@get_by_filter')->name('publication.student.filter');

        //Output Activity - Teacher
        Route::post('output-activity/teacher/get_by_filter','TeacherOutputActivityController@get_by_filter')->name('output-activity.teacher.filter');

        //Output Activity - Student
        Route::post('output-activity/student/get_by_filter','StudentOutputActivityController@get_by_filter')->name('output-activity.student.filter');

        //Academic - Curriculum
        Route::post('curriculum/get_by_filter','CurriculumController@get_by_filter')->name('curriculum.filter');
        Route::get('curriculum/loadData','CurriculumController@loadData')->name('curriculum.loadData');

        //Academic - Curriculum Integrations
        Route::post('curriculum-integration/get_by_filter','CurriculumIntegrationController@get_by_filter')->name('curriculum-integration.filter');

        //Academic - Schedule
        Route::post('schedule/get_by_filter','CurriculumScheduleController@get_by_filter')->name('schedule.filter');

        //Academic - Minithesis
        Route::post('minithesis/get_by_filter','MinithesisController@get_by_filter')->name('minithesis.filter');

        //Alumnus
        Route::get('alumnus/get','AlumnusAttainmentController@get_alumnus')->name('alumnus.get_alumnus');

        //Alumnus - Waktu Tunggu Lulusan / Bidang Kerja / Kinerja
        Route::get('alumnus/idle/{id}','AlumnusIdleController@edit')->name('alumnus.idle.edit');
        Route::get('alumnus/suitable/{id}','AlumnusSuitableController@edit')->name('alumnus.suitable.edit');
        Route::get('alumnus/workplace/{id}','AlumnusWorkplaceController@edit')->name('alumnus.workplace.edit');

    });

    //Teacher
    Route::prefix('teacher')->middleware('role:admin,kaprodi,kajur')->group(function () {

        Route::get('/',function(){
            return redirect(route('teacher'));
        });

        //Teacher - List
        Route::get('list','TeacherController@index')->name('teacher');
        Route::get('list/add','TeacherController@create')->name('teacher.add');
        Route::get('list/import','TeacherController@import')->name('teacher.import');
        Route::get('list/{id}','TeacherController@show')->name('teacher.show');
        Route::get('list/{id}/edit','TeacherController@edit')->name('teacher.edit');
        Route::post('list','TeacherController@store')->name('teacher.store');
        Route::put('list','TeacherController@update')->name('teacher.update');
        Route::delete('list','TeacherController@destroy')->name('teacher.delete');

        //Teacher - Achievement
        Route::get('achievement','TeacherAchievementController@index')->name('teacher.achievement');
        Route::get('achievement/{nidn}','TeacherAchievementController@edit')->name('teacher.achievement.edit');
        Route::post('achievement','TeacherAchievementController@store')->name('teacher.achievement.store');
        Route::put('achievement','TeacherAchievementController@update')->name('teacher.achievement.update');
        Route::delete('achievement','TeacherAchievementController@destroy')->name('teacher.achievement.delete');
        Route::get('achievement/file/{nidn}','TeacherAchievementController@delete_file')->name('teacher.achievement.file');

        //EWMP
        Route::get('ewmp', 'EwmpController@index')->name('teacher.ewmp');
    });

    //Students
    Route::prefix('student')->middleware('role:admin,kaprodi,kajur')->group(function () {

        Route::get('/',function(){
            return redirect(route('student'));
        });

        //Students - List
        Route::get('list','StudentController@index')->name('student');
        Route::get('list/add','StudentController@create')->name('student.add');
        Route::get('list/{id}','StudentController@profile')->name('student.profile');
        Route::get('list/{id}/edit','StudentController@edit')->name('student.edit');
        Route::post('list/upload_photo','StudentController@upload_photo')->name('student.photo');
        Route::post('list/import','StudentController@import')->name('student.import');
        Route::post('list','StudentController@store')->name('student.store');
        Route::put('list','StudentController@update')->name('student.update');
        Route::delete('list','StudentController@destroy')->name('student.delete');

        //Students - Quota
        Route::get('quota','StudentQuotaController@index')->name('student.quota');
        Route::get('quota/add','StudentQuotaController@create')->name('student.quota.add');
        Route::post('quota','StudentQuotaController@store')->name('student.quota.store');
        Route::put('quota','StudentQuotaController@update')->name('student.quota.update');
        Route::delete('quota','StudentQuotaController@destroy')->name('student.quota.delete');

        //Students - Status
        Route::post('status','StudentStatusController@store')->name('student.status.store');
        Route::put('status','StudentStatusController@update')->name('student.status.update');
        Route::delete('status','StudentStatusController@destroy')->name('student.status.delete');

        //Students - Foreign
        Route::get('foreign','StudentForeignController@index')->name('student.foreign');
        Route::get('foreign/add','StudentForeignController@create')->name('student.foreign.add');
        Route::get('foreign/{id}','StudentForeignController@edit')->name('student.foreign.edit');
        Route::post('foreign','StudentForeignController@store')->name('student.foreign.store');
        Route::put('foreign','StudentForeignController@update')->name('student.foreign.update');
        Route::delete('foreign','StudentForeignController@destroy')->name('student.foreign.delete');

        //Student Achievement
        Route::get('achievement','StudentAchievementController@index')->name('student.achievement');
        Route::get('achievement/{nidn}','StudentAchievementController@edit')->name('student.achievement.edit');
        Route::post('achievement','StudentAchievementController@store')->name('student.achievement.store');
        Route::put('achievement','StudentAchievementController@update')->name('student.achievement.update');
        Route::delete('achievement','StudentAchievementController@destroy')->name('student.achievement.delete');
    });

    //Academic
    Route::prefix('academic')->middleware('role:admin,kaprodi,kajur')->group(function () {

        //Academic - Curriculum
        Route::get('curriculum','CurriculumController@index')->name('academic.curriculum');
        Route::get('curriculum/add','CurriculumController@create')->name('academic.curriculum.add');
        Route::get('curriculum/{id}','CurriculumController@show')->name('academic.curriculum.show');
        Route::get('curriculum/{id}/edit','CurriculumController@edit')->name('academic.curriculum.edit');
        Route::post('curriculum_import','CurriculumController@import')->name('academic.curriculum.import');
        Route::post('curriculum','CurriculumController@store')->name('academic.curriculum.store');
        Route::put('curriculum','CurriculumController@update')->name('academic.curriculum.update');
        Route::delete('curriculum','CurriculumController@destroy')->name('academic.curriculum.delete');

        //Academic - Schedule
        Route::get('schedule','CurriculumScheduleController@index')->name('academic.schedule');
        Route::get('schedule/add','CurriculumScheduleController@create')->name('academic.schedule.add');
        Route::get('schedule/{nidn}/edit','CurriculumScheduleController@edit')->name('academic.schedule.edit');
        Route::post('schedule','CurriculumScheduleController@store')->name('academic.schedule.store');
        Route::put('schedule','CurriculumScheduleController@update')->name('academic.schedule.update');
        Route::delete('schedule','CurriculumScheduleController@destroy')->name('academic.schedule.delete');

        //Academic - Curriculum Integration
        Route::get('integration','CurriculumIntegrationController@index')->name('academic.integration');
        Route::get('integration/add','CurriculumIntegrationController@create')->name('academic.integration.add');
        Route::get('integration/{id}','CurriculumIntegrationController@show')->name('academic.integration.show');
        Route::get('integration/{id}/edit','CurriculumIntegrationController@edit')->name('academic.integration.edit');
        Route::post('integration','CurriculumIntegrationController@store')->name('academic.integration.store');
        Route::put('integration','CurriculumIntegrationController@update')->name('academic.integration.update');
        Route::delete('integration','CurriculumIntegrationController@destroy')->name('academic.integration.delete');

        //Academic - Minithesis
        Route::get('minithesis','MiniThesisController@index')->name('academic.minithesis');
        Route::get('minithesis/add','MiniThesisController@create')->name('academic.minithesis.add');
        Route::get('minithesis/{nidn}','MiniThesisController@show')->name('academic.minithesis.show');
        Route::get('minithesis/{nidn}/edit','MiniThesisController@edit')->name('academic.minithesis.edit');
        Route::post('minithesis','MiniThesisController@store')->name('academic.minithesis.store');
        Route::put('minithesis','MiniThesisController@update')->name('academic.minithesis.update');
        Route::delete('minithesis','MiniThesisController@destroy')->name('academic.minithesis.delete');

        //Academic - Satisfaction
        Route::get('satisfaction','AcademicSatisfactionController@index')->name('academic.satisfaction');
        Route::get('satisfaction/add','AcademicSatisfactionController@create')->name('academic.satisfaction.add');
        Route::get('satisfaction/{id}','AcademicSatisfactionController@show')->name('academic.satisfaction.show');
        Route::get('satisfaction/{id}/edit','AcademicSatisfactionController@edit')->name('academic.satisfaction.edit');
        Route::post('satisfaction','AcademicSatisfactionController@store')->name('academic.satisfaction.store');
        Route::put('satisfaction','AcademicSatisfactionController@update')->name('academic.satisfaction.update');
        Route::delete('satisfaction','AcademicSatisfactionController@destroy')->name('academic.satisfaction.delete');
    });

    //Collaboration
    Route::middleware('role:admin,kaprodi,kajur')->group(function () {
        Route::get('/collaboration','CollaborationController@index')->name('collaboration');
        Route::get('/collaboration/add','CollaborationController@create')->name('collaboration.add');
        Route::get('/collaboration/{id}/edit','CollaborationController@edit')->name('collaboration.edit');
        Route::post('/collaboration','CollaborationController@store')->name('collaboration.store');
        Route::put('/collaboration','CollaborationController@update')->name('collaboration.update');
        Route::delete('/collaboration','CollaborationController@destroy')->name('collaboration.delete');
    });

    //Research
    Route::get('research','ResearchController@index')->name('research');
    Route::get('research/add','ResearchController@create')->name('research.add');
    Route::get('research/{id}','ResearchController@show')->name('research.show');
    Route::get('research/{id}/edit','ResearchController@edit')->name('research.edit');
    Route::post('research','ResearchController@store')->name('research.store');
    Route::put('research','ResearchController@update')->name('research.update');
    Route::delete('research','ResearchController@destroy')->name('research.delete');
    Route::get('research/delete_teacher/{id}','ResearchController@destroy_teacher')->name('research.teacher.delete');
    Route::get('research/delete_student/{id}','ResearchController@destroy_students')->name('research.students.delete');

    //Community Service
    Route::get('community-service','CommunityServiceController@index')->name('community-service');
    Route::get('community-service/add','CommunityServiceController@create')->name('community-service.add');
    Route::get('community-service/{id}','CommunityServiceController@show')->name('community-service.show');
    Route::get('community-service/{id}/edit','CommunityServiceController@edit')->name('community-service.edit');
    Route::post('community-service','CommunityServiceController@store')->name('community-service.store');
    Route::put('community-service','CommunityServiceController@update')->name('community-service.update');
    Route::delete('community-service','CommunityServiceController@destroy')->name('community-service.delete');
    Route::get('community-service/delete_teacher/{id}','CommunityServiceController@destroy_teacher')->name('community-service.teacher.delete');
    Route::get('community-service/delete_student/{id}','CommunityServiceController@destroy_students')->name('community-service.students.delete');

    //Publication
    Route::prefix('publication')->middleware('role:admin,kaprodi,kajur')->group(function () {
        //Publication - Teacher
        Route::get('teacher','TeacherPublicationController@index')->name('publication.teacher');
        Route::get('teacher/add','TeacherPublicationController@create')->name('publication.teacher.add');
        Route::get('teacher/{id}','TeacherPublicationController@show')->name('publication.teacher.show');
        Route::get('teacher/{id}/edit','TeacherPublicationController@edit')->name('publication.teacher.edit');
        Route::post('teacher','TeacherPublicationController@store')->name('publication.teacher.store');
        Route::put('teacher','TeacherPublicationController@update')->name('publication.teacher.update');
        Route::delete('teacher','TeacherPublicationController@destroy')->name('publication.teacher.delete');
        Route::get('teacher/delete_member/{id}','TeacherPublicationController@destroy_member')->name('publication.teacher.delete.member');
        Route::get('teacher/delete_student/{id}','TeacherPublicationController@destroy_student')->name('publication.teacher.delete.student');

        //Publication - Student
        Route::get('student','StudentPublicationController@index')->name('publication.student');
        Route::get('student/add','StudentPublicationController@create')->name('publication.student.add');
        Route::get('student/{id}','StudentPublicationController@show')->name('publication.student.show');
        Route::get('student/{id}/edit','StudentPublicationController@edit')->name('publication.student.edit');
        Route::post('student','StudentPublicationController@store')->name('publication.student.store');
        Route::put('student','StudentPublicationController@update')->name('publication.student.update');
        Route::delete('student','StudentPublicationController@destroy')->name('publication.student.delete');
        Route::get('student/delete_member/{id}','StudentPublicationController@destroy_member')->name('publication.student.delete.member');
    });

    Route::prefix('output-activity')->middleware('role:admin,kaprodi,kajur')->group(function () {

        //Output Activity - Teacher
        Route::get('teacher','TeacherOutputActivityController@index')->name('output-activity.teacher');
        Route::get('teacher/add','TeacherOutputActivityController@create')->name('output-activity.teacher.add');
        Route::get('teacher/{id}','TeacherOutputActivityController@show')->name('output-activity.teacher.show');
        Route::get('teacher/{id}/edit','TeacherOutputActivityController@edit')->name('output-activity.teacher.edit');
        Route::post('teacher','TeacherOutputActivityController@store')->name('output-activity.teacher.store');
        Route::put('teacher','TeacherOutputActivityController@update')->name('output-activity.teacher.update');
        Route::delete('teacher','TeacherOutputActivityController@destroy')->name('output-activity.teacher.delete');
        Route::get('/download/output-activity','TeacherOutputActivityController@download')->name('output-activity.file.download');
        Route::get('/delete_file/output-activity','TeacherOutputActivityController@delete_file')->name('output-activity.file.delete');

        //Output Activity - Student
        Route::get('student','StudentOutputActivityController@index')->name('output-activity.student');
        Route::get('student/add','StudentOutputActivityController@create')->name('output-activity.student.add');
        Route::get('student/{id}','StudentOutputActivityController@show')->name('output-activity.student.show');
        Route::get('student/{id}/edit','StudentOutputActivityController@edit')->name('output-activity.student.edit');
        Route::post('student','StudentOutputActivityController@store')->name('output-activity.student.store');
        Route::put('student','StudentOutputActivityController@update')->name('output-activity.student.update');
        Route::delete('student','StudentOutputActivityController@destroy')->name('output-activity.student.delete');
    });

    //Funding
    Route::prefix('funding')->group(function () {

        //Index
        Route::get('/',function(){
            return redirect(route('dashboard'));
        });

        //Funding - Faculty
        Route::middleware('role:admin,kajur')->group(function () {
            Route::get('faculty','FundingFacultyController@index')->name('funding.faculty');
            Route::get('faculty/add','FundingFacultyController@create')->name('funding.faculty.add');
            Route::get('faculty/{id}','FundingFacultyController@show')->name('funding.faculty.show');
            Route::get('faculty/{id}/edit','FundingFacultyController@edit')->name('funding.faculty.edit');
            Route::post('faculty','FundingFacultyController@store')->name('funding.faculty.store');
            Route::put('faculty','FundingFacultyController@update')->name('funding.faculty.update');
            Route::delete('faculty','FundingFacultyController@destroy')->name('funding.faculty.delete');
        });

        //Funding - Study Program
        Route::middleware('role:admin,kaprodi,kajur')->group(function () {
            Route::get('study-program','FundingStudyProgramController@index')->name('funding.study-program');
            Route::get('study-program/add','FundingStudyProgramController@create')->name('funding.study-program.add');
            Route::get('study-program/{id}','FundingStudyProgramController@show')->name('funding.study-program.show');
            Route::get('study-program/{id}/edit','FundingStudyProgramController@edit')->name('funding.study-program.edit');
            Route::post('study-program','FundingStudyProgramController@store')->name('funding.study-program.store');
            Route::put('study-program','FundingStudyProgramController@update')->name('funding.study-program.update');
            Route::delete('study-program','FundingStudyProgramController@destroy')->name('funding.study-program.delete');
        });
    });

    //Alumnus
    Route::prefix('alumnus')->middleware('role:admin,kaprodi,kajur')->group(function () {

        //Alumnus - Attainment
        Route::get('attainment','AlumnusAttainmentController@attainment')->name('alumnus.attainment');
        Route::get('attainment/{id}','AlumnusAttainmentController@attainment_show')->name('alumnus.attainment.show');

        //Alumnus - Idle
        Route::get('idle','AlumnusIdleController@index')->name('alumnus.idle');
        Route::get('idle/{id}','AlumnusIdleController@show')->name('alumnus.idle.show');
        Route::post('idle','AlumnusIdleController@store')->name('alumnus.idle.store');
        Route::put('idle','AlumnusIdleController@update')->name('alumnus.idle.update');
        Route::delete('idle','AlumnusIdleController@destroy')->name('alumnus.idle.delete');

        //Alumnus - Suitable
        Route::get('suitable','AlumnusSuitableController@index')->name('alumnus.suitable');
        Route::get('suitable/{id}','AlumnusSuitableController@show')->name('alumnus.suitable.show');
        Route::post('suitable','AlumnusSuitableController@store')->name('alumnus.suitable.store');
        Route::put('suitable','AlumnusSuitableController@update')->name('alumnus.suitable.update');
        Route::delete('suitable','AlumnusSuitableController@destroy')->name('alumnus.suitable.delete');

        //Alumnus - Workplace
        Route::get('workplace','AlumnusWorkplaceController@index')->name('alumnus.workplace');
        Route::get('workplace/{id}','AlumnusWorkplaceController@show')->name('alumnus.workplace.show');
        Route::post('workplace','AlumnusWorkplaceController@store')->name('alumnus.workplace.store');
        Route::put('workplace','AlumnusWorkplaceController@update')->name('alumnus.workplace.update');
        Route::delete('workplace','AlumnusWorkplaceController@destroy')->name('alumnus.workplace.delete');

        //Alumnus - Satisfaction
        Route::get('satisfaction','AlumnusSatisfactionController@index')->name('alumnus.satisfaction');
        Route::get('satisfaction/add','AlumnusSatisfactionController@create')->name('alumnus.satisfaction.add');
        Route::get('satisfaction/{id}','AlumnusSatisfactionController@show')->name('alumnus.satisfaction.show');
        Route::get('satisfaction/{id}/edit','AlumnusSatisfactionController@edit')->name('alumnus.satisfaction.edit');
        Route::post('satisfaction','AlumnusSatisfactionController@store')->name('alumnus.satisfaction.store');
        Route::put('satisfaction','AlumnusSatisfactionController@update')->name('alumnus.satisfaction.update');
        Route::delete('satisfaction','AlumnusSatisfactionController@destroy')->name('alumnus.satisfaction.delete');
    });

    //Teacher Profile
    Route::prefix('profile')->name('profile.')->middleware('role:dosen')->group(function () {
        //Biodata
        Route::get('biodata','TeacherProfileController@biodata')->name('biodata');
        Route::put('biodata','TeacherProfileController@update_biodata')->name('biodata.update');

        //Achievement
        Route::get('achievement','TeacherProfileController@achievement')->name('achievement');
        Route::get('achievement/{id}','TeacherProfileController@achievement_edit')->name('achievement.edit');
        Route::post('achievement','TeacherProfileController@store_achievement')->name('achievement.store');
        Route::put('achievement','TeacherProfileController@update_achievement')->name('achievement.update');
        Route::delete('achievement','TeacherProfileController@destroy_achievement')->name('achievement.delete');

        Route::get('ewmp','TeacherProfileController@create')->name('ewmp');

        //Research
        Route::get('research','TeacherProfileController@research')->name('research');
        Route::get('research/add','TeacherProfileController@research_create')->name('research.add');
        Route::get('research/{id}','TeacherProfileController@research_show')->name('research.show');
        Route::get('research/{id}/edit','TeacherProfileController@research_edit')->name('research.edit');
        Route::post('research','TeacherProfileController@research_store')->name('research.store');
        Route::put('research','TeacherProfileController@research_update')->name('research.update');
        Route::delete('research','TeacherProfileController@research_destroy')->name('research.delete');
        Route::get('research/delete_teacher/{id}','TeacherProfileController@research_destroy_teacher')->name('research.teacher.delete');
        Route::get('research/delete_student/{id}','TeacherProfileController@research_destroy_students')->name('research.students.delete');

        //Community Service
        Route::get('community-service','TeacherProfileController@commuService')->name('community-service');
        Route::get('community-service/add','TeacherProfileController@commuService_create')->name('community-service.add');
        Route::get('community-service/{id}','TeacherProfileController@commuService_show')->name('community-service.show');
        Route::get('community-service/{id}/edit','TeacherProfileController@commuService_edit')->name('community-service.edit');
        Route::post('community-service','TeacherProfileController@commuService_store')->name('community-service.store');
        Route::put('community-service','TeacherProfileController@commuService_update')->name('community-service.update');
        Route::delete('community-service','TeacherProfileController@commuService_destroy')->name('community-service.delete');
        Route::get('community-service/delete_teacher/{id}','TeacherProfileController@commuService_destroy_teacher')->name('community-service.teacher.delete');
        Route::get('community-service/delete_student/{id}','TeacherProfileController@commuService_destroy_students')->name('community-service.students.delete');

        Route::get('publication','TeacherProfileController@edit')->name('publication');
    });

    //Download
    Route::get('/download/collab/{filename}','CollaborationController@download')->name('collaboration.download');
    Route::get('/download/teacher/{filename}','TeacherController@download')->name('teacher.download');
    Route::get('/download/teacher/achievement/{filename}','TeacherAchievementController@download')->name('teacher.achievement.download');
    Route::get('/download/avatar', 'DownloadController@avatar')->name('download.avatar');
});
