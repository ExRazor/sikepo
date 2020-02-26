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

    //Collaboration
    Route::middleware('role:admin,kaprodi,kajur')->group(function () {
        Route::get('/collaboration','CollaborationController@index')->name('collaboration');
        Route::get('/collaboration/add','CollaborationController@create')->name('collaboration.add');
        Route::get('/collaboration/{id}/edit','CollaborationController@edit')->name('collaboration.edit');
        Route::post('/collaboration','CollaborationController@store')->name('collaboration.store');
        Route::put('/collaboration','CollaborationController@update')->name('collaboration.update');
        Route::delete('/collaboration','CollaborationController@destroy')->name('collaboration.delete');
    });

    //Teacher
    Route::prefix('teacher')->middleware('role:admin,kaprodi,kajur')->group(function () {

        //Teacher List
        Route::get('/',function(){
            return redirect(route('teacher'));
        });
        Route::get('list','TeacherController@index')->name('teacher');
        Route::get('list/add','TeacherController@create')->name('teacher.add');
        Route::get('list/import','TeacherController@import')->name('teacher.import');
        Route::get('list/{id}','TeacherController@profile')->name('teacher.profile');
        Route::get('list/{id}/edit','TeacherController@edit')->name('teacher.edit');
        Route::post('list','TeacherController@store')->name('teacher.store');
        Route::put('list','TeacherController@update')->name('teacher.update');
        Route::delete('list','TeacherController@destroy')->name('teacher.delete');

        //Teacher Achievement
        Route::get('achievement','TeacherAchievementController@index')->name('teacher.achievement');
        Route::get('achievement/{nidn}','TeacherAchievementController@edit')->name('teacher.achievement.edit');
        Route::post('achievement','TeacherAchievementController@store')->name('teacher.achievement.store');
        Route::put('achievement','TeacherAchievementController@update')->name('teacher.achievement.update');
        Route::delete('achievement','TeacherAchievementController@destroy')->name('teacher.achievement.delete');
        Route::get('achievement/file/{nidn}','TeacherAchievementController@delete_file')->name('teacher.achievement.file');
    });



    //EWMP
    Route::get('/teacher/ewmp', 'EwmpController@index')->name('teacher.ewmp');

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

    //Students - Quota
    Route::get('student/quota','StudentQuotaController@index')->name('student.quota');
    Route::get('student/quota/add','StudentQuotaController@create')->name('student.quota.add');
    Route::post('student/quota','StudentQuotaController@store')->name('student.quota.store');
    Route::put('student/quota','StudentQuotaController@update')->name('student.quota.update');
    Route::delete('student/quota','StudentQuotaController@destroy')->name('student.quota.delete');

    //Students - Status
    Route::post('student/status','StudentStatusController@store')->name('student.status.store');
    Route::put('student/status','StudentStatusController@update')->name('student.status.update');
    Route::delete('student/status','StudentStatusController@destroy')->name('student.status.delete');

    //Students - Foreign
    Route::get('student/foreign','StudentForeignController@index')->name('student.foreign');
    Route::get('student/foreign/add','StudentForeignController@create')->name('student.foreign.add');
    Route::get('student/foreign/{id}','StudentForeignController@edit')->name('student.foreign.edit');
    Route::post('student/foreign','StudentForeignController@store')->name('student.foreign.store');
    Route::put('student/foreign','StudentForeignController@update')->name('student.foreign.update');
    Route::delete('student/foreign','StudentForeignController@destroy')->name('student.foreign.delete');

    //Student Achievement
    Route::get('/student/achievement','StudentAchievementController@index')->name('student.achievement');
    Route::get('/student/achievement/{nidn}','StudentAchievementController@edit')->name('student.achievement.edit');
    Route::post('/student/achievement','StudentAchievementController@store')->name('student.achievement.store');
    Route::put('/student/achievement','StudentAchievementController@update')->name('student.achievement.update');
    Route::delete('/student/achievement','StudentAchievementController@destroy')->name('student.achievement.delete');

    //Funding
    Route::get('funding',function(){
        return redirect(route('student'));
    });

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

    //Publication - Teacher
    Route::get('publication/teacher','TeacherPublicationController@index')->name('publication.teacher');
    Route::get('publication/teacher/add','TeacherPublicationController@create')->name('publication.teacher.add');
    Route::get('publication/teacher/{id}','TeacherPublicationController@show')->name('publication.teacher.show');
    Route::get('publication/teacher/{id}/edit','TeacherPublicationController@edit')->name('publication.teacher.edit');
    Route::post('publication/teacher','TeacherPublicationController@store')->name('publication.teacher.store');
    Route::put('publication/teacher','TeacherPublicationController@update')->name('publication.teacher.update');
    Route::delete('publication/teacher','TeacherPublicationController@destroy')->name('publication.teacher.delete');
    Route::get('publication/teacher/delete_member/{id}','TeacherPublicationController@destroy_member')->name('publication.teacher.delete.member');
    Route::get('publication/teacher/delete_student/{id}','TeacherPublicationController@destroy_student')->name('publication.teacher.delete.student');

    //Publication - Student
    Route::get('publication/student','StudentPublicationController@index')->name('publication.student');
    Route::get('publication/student/add','StudentPublicationController@create')->name('publication.student.add');
    Route::get('publication/student/{id}','StudentPublicationController@show')->name('publication.student.show');
    Route::get('publication/student/{id}/edit','StudentPublicationController@edit')->name('publication.student.edit');
    Route::post('publication/student','StudentPublicationController@store')->name('publication.student.store');
    Route::put('publication/student','StudentPublicationController@update')->name('publication.student.update');
    Route::delete('publication/student','StudentPublicationController@destroy')->name('publication.student.delete');
    Route::get('publication/student/delete_member/{id}','StudentPublicationController@destroy_member')->name('publication.student.delete.member');

    //Output Activity - Teacher
    Route::get('output-activity/teacher','TeacherOutputActivityController@index')->name('output-activity.teacher');
    Route::get('output-activity/teacher/add','TeacherOutputActivityController@create')->name('output-activity.teacher.add');
    Route::get('output-activity/teacher/{id}','TeacherOutputActivityController@show')->name('output-activity.teacher.show');
    Route::get('output-activity/teacher/{id}/edit','TeacherOutputActivityController@edit')->name('output-activity.teacher.edit');
    Route::post('output-activity/teacher','TeacherOutputActivityController@store')->name('output-activity.teacher.store');
    Route::put('output-activity/teacher','TeacherOutputActivityController@update')->name('output-activity.teacher.update');
    Route::delete('output-activity/teacher','TeacherOutputActivityController@destroy')->name('output-activity.teacher.delete');
    Route::get('/download/output-activity','TeacherOutputActivityController@download')->name('output-activity.file.download');
    Route::get('/delete_file/output-activity','TeacherOutputActivityController@delete_file')->name('output-activity.file.delete');

    //Output Activity - Student
    Route::get('output-activity/student','StudentOutputActivityController@index')->name('output-activity.student');
    Route::get('output-activity/student/add','StudentOutputActivityController@create')->name('output-activity.student.add');
    Route::get('output-activity/student/{id}','StudentOutputActivityController@show')->name('output-activity.student.show');
    Route::get('output-activity/student/{id}/edit','StudentOutputActivityController@edit')->name('output-activity.student.edit');
    Route::post('output-activity/student','StudentOutputActivityController@store')->name('output-activity.student.store');
    Route::put('output-activity/student','StudentOutputActivityController@update')->name('output-activity.student.update');
    Route::delete('output-activity/student','StudentOutputActivityController@destroy')->name('output-activity.student.delete');

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

    //Academic - Curriculum Integration
    Route::get('academic/integration','CurriculumIntegrationController@index')->name('academic.integration');
    Route::get('academic/integration/add','CurriculumIntegrationController@create')->name('academic.integration.add');
    Route::get('academic/integration/{id}','CurriculumIntegrationController@show')->name('academic.integration.show');
    Route::get('academic/integration/{id}/edit','CurriculumIntegrationController@edit')->name('academic.integration.edit');
    Route::post('academic/integration','CurriculumIntegrationController@store')->name('academic.integration.store');
    Route::put('academic/integration','CurriculumIntegrationController@update')->name('academic.integration.update');
    Route::delete('academic/integration','CurriculumIntegrationController@destroy')->name('academic.integration.delete');

    //Academic - Minithesis
    Route::get('academic/minithesis','MiniThesisController@index')->name('academic.minithesis');
    Route::get('academic/minithesis/add','MiniThesisController@create')->name('academic.minithesis.add');
    Route::get('academic/minithesis/{nidn}','MiniThesisController@show')->name('academic.minithesis.show');
    Route::get('academic/minithesis/{nidn}/edit','MiniThesisController@edit')->name('academic.minithesis.edit');
    Route::post('academic/minithesis','MiniThesisController@store')->name('academic.minithesis.store');
    Route::put('academic/minithesis','MiniThesisController@update')->name('academic.minithesis.update');
    Route::delete('academic/minithesis','MiniThesisController@destroy')->name('academic.minithesis.delete');

    //Academic - Satisfaction
    Route::get('academic/satisfaction','AcademicSatisfactionController@index')->name('academic.satisfaction');
    Route::get('academic/satisfaction/add','AcademicSatisfactionController@create')->name('academic.satisfaction.add');
    Route::get('academic/satisfaction/{id}','AcademicSatisfactionController@show')->name('academic.satisfaction.show');
    Route::get('academic/satisfaction/{id}/edit','AcademicSatisfactionController@edit')->name('academic.satisfaction.edit');
    Route::post('academic/satisfaction','AcademicSatisfactionController@store')->name('academic.satisfaction.store');
    Route::put('academic/satisfaction','AcademicSatisfactionController@update')->name('academic.satisfaction.update');
    Route::delete('academic/satisfaction','AcademicSatisfactionController@destroy')->name('academic.satisfaction.delete');

    //Alumnus - Attainment
    Route::get('alumnus/attainment','AlumnusAttainmentController@attainment')->name('alumnus.attainment');
    Route::get('alumnus/attainment/{id}','AlumnusAttainmentController@attainment_show')->name('alumnus.attainment.show');

    //Alumnus - Idle
    Route::get('alumnus/idle','AlumnusIdleController@index')->name('alumnus.idle');
    Route::get('alumnus/idle/{id}','AlumnusIdleController@show')->name('alumnus.idle.show');
    Route::post('alumnus/idle','AlumnusIdleController@store')->name('alumnus.idle.store');
    Route::put('alumnus/idle','AlumnusIdleController@update')->name('alumnus.idle.update');
    Route::delete('alumnus/idle','AlumnusIdleController@destroy')->name('alumnus.idle.delete');

    //Alumnus - Suitable
    Route::get('alumnus/suitable','AlumnusSuitableController@index')->name('alumnus.suitable');
    Route::get('alumnus/suitable/{id}','AlumnusSuitableController@show')->name('alumnus.suitable.show');
    Route::post('alumnus/suitable','AlumnusSuitableController@store')->name('alumnus.suitable.store');
    Route::put('alumnus/suitable','AlumnusSuitableController@update')->name('alumnus.suitable.update');
    Route::delete('alumnus/suitable','AlumnusSuitableController@destroy')->name('alumnus.suitable.delete');

    //Alumnus - Workplace
    Route::get('alumnus/workplace','AlumnusWorkplaceController@index')->name('alumnus.workplace');
    Route::get('alumnus/workplace/{id}','AlumnusWorkplaceController@show')->name('alumnus.workplace.show');
    Route::post('alumnus/workplace','AlumnusWorkplaceController@store')->name('alumnus.workplace.store');
    Route::put('alumnus/workplace','AlumnusWorkplaceController@update')->name('alumnus.workplace.update');
    Route::delete('alumnus/workplace','AlumnusWorkplaceController@destroy')->name('alumnus.workplace.delete');

    //Alumnus - Satisfaction
    Route::get('alumnus/satisfaction','AlumnusSatisfactionController@index')->name('alumnus.satisfaction');
    Route::get('alumnus/satisfaction/add','AlumnusSatisfactionController@create')->name('alumnus.satisfaction.add');
    Route::get('alumnus/satisfaction/{id}','AlumnusSatisfactionController@show')->name('alumnus.satisfaction.show');
    Route::post('alumnus/satisfaction','AlumnusSatisfactionController@store')->name('alumnus.satisfaction.store');
    Route::put('alumnus/satisfaction','AlumnusSatisfactionController@update')->name('alumnus.satisfaction.update');
    Route::delete('alumnus/satisfaction','AlumnusSatisfactionController@destroy')->name('alumnus.satisfaction.delete');

    //Download
    Route::get('/download/collab/{filename}','CollaborationController@download')->name('collaboration.download');
    Route::get('/download/teacher/{filename}','TeacherController@download')->name('teacher.download');
    Route::get('/download/teacher/achievement/{filename}','TeacherAchievementController@download')->name('teacher.achievement.download');
    Route::get('/download/avatar', 'DownloadController@avatar')->name('download.avatar');
});
