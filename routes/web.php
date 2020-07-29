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
Route::get('/tes', 'PageController@tes');
Route::get('login', 'AuthController@login_form')->middleware('guest')->name('login');
Route::post('login', 'AuthController@login_post')->name('login_post');
Route::get('logout', 'AuthController@logout')->name('logout');

Route::middleware('auth')->group(function () {

    //Pages Controller
    Route::get('/', 'PageController@dashboard')->name('dashboard');

    //Account Setting
    Route::prefix('account')->name('account.')->group(function () {
        //Edit Profile
        Route::get('editprofile', 'AuthController@editprofile_form')->name('editprofile');
        Route::post('editprofile', 'AuthController@editprofile_post')->name('editprofile_post');

        //Edit Password
        Route::get('editpassword', 'AuthController@editpassword_form')->name('editpassword');
        Route::post('editpassword', 'AuthController@editpassword_post')->name('editpassword_post');
    });

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
        Route::resource('user', 'UserController');
        // Route::get('user', 'UserController@index')->name('user');
        // Route::post('user', 'UserController@store')->name('user.store');
        // Route::put('user/{id}', 'UserController@update')->name('user.update');
        // Route::delete('user', 'UserController@destroy')->name('user.delete');
        // Route::get('user/{id}', 'UserController@edit')->name('user.edit');
        Route::post('user/resetpass', 'UserController@reset_password')->name('user.resetpass');

        //User
        Route::get('structural', 'TeacherStatusController@index_structural')->name('structural.index');
    });

    //Ajax
    Route::prefix('ajax')->name('ajax.')->group(function () {

        //Page Controller
        Route::get('index/chart','PageController@chart')->name('index.chart');

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

        //Setting - User
        Route::get('user/toggle_active/{id}','UserController@toggle_active')->name('user.toggle_active');
        Route::post('user/datatable_user','UserController@datatable_user')->name('user.datatable.user');
        Route::post('user/datatable_dosen','UserController@datatable_dosen')->name('user.datatable.dosen');

        //Setting - Struktural
        Route::post('structural/datatable','TeacherStatusController@dt_structural')->name('structural.datatable');

        //Satisfaction Category
        Route::get('satisfaction-category/{id}','SatisfactionCategoryController@edit')->name('satisfaction-category.edit');

        //Publication Category
        Route::get('publication/category/{id}','PublicationCategoryController@edit')->name('publication.category.edit');

        //Output Activity Category
        Route::get('output-activity/category/{id}','OutputActivityCategoryController@edit')->name('output-activity.category.edit');

        //Funding Category
        Route::get('funding/category/{id}','FundingCategoryController@edit')->name('funding.category.edit');
        Route::get('funding/category/select/{id}','FundingCategoryController@get_jenis')->name('funding.category.select');

        //Teacher
        Route::post('teacher/loadData','TeacherController@loadData')->name('teacher.loadData');
        Route::post('teacher/datatable','TeacherController@datatable')->name('teacher.datatable');
        Route::post('teacher/get_by_studyProgram','TeacherController@get_by_studyProgram')->name('teacher.studyProgram');

        //Teacher - Status
        Route::get('teacher/status/{id}','TeacherStatusController@edit')->name('teacher.status.edit');

        //Teacher - EWMP
        Route::get('ewmp/countsks','EwmpController@countSKS')->name('ewmp.countsks');
        Route::post('ewmp/list','EwmpController@show_by_filter')->name('ewmp.show_filter');
        Route::get('ewmp/{id}','EwmpController@edit')->name('ewmp.edit');
        Route::post('ewmp','EwmpController@store')->name('ewmp.store');
        Route::put('ewmp','EwmpController@update')->name('ewmp.update');
        Route::delete('ewmp','EwmpController@destroy')->name('ewmp.delete');

        //Teacher - Achievement
        Route::post('teacher/achievement/datatable','TeacherAchievementController@datatable')->name('teacher.achievement.datatable');

        //Student
        Route::get('student/loadData','StudentController@loadData')->name('student.loadData');
        Route::get('student/select_by_studyProgram','StudentController@select_by_studyProgram')->name('student.studyProgram');
        Route::post('student/datatable','StudentController@datatable')->name('student.datatable');
        Route::post('student/get_by_studyProgram','StudentController@get_by_studyProgram');

        //Student - Quota
        Route::post('student/quota/datatable','StudentQuotaController@datatable')->name('student.quota.datatable');

        //Student - Status
        Route::get('student/status/{id}','StudentStatusController@edit')->name('student.status.edit');

        //Student - Foreign
        Route::post('student/foreign/datatable','StudentForeignController@datatable')->name('student.foreign.datatable');

        //Student - Achievement
        Route::post('student/achievement/get_by_filter','StudentAchievementController@get_by_filter')->name('student.achievement.filter');
        Route::post('student/achievement/datatable','StudentAchievementController@datatable')->name('student.achievement.datatable');

        //Collaboration
        Route::post('collaboration/get_by_filter','CollaborationController@get_by_filter')->name('collaboration.filter');
        Route::post('collaboration/datatable','CollaborationController@datatable')->name('collaboration.datatable');

        //Research
        Route::get('research/get_by_department','ResearchController@get_by_department')->name('research.get_by_department');
        Route::post('research/get_by_filter','ResearchController@get_by_filter')->name('research.filter');
        Route::post('research/datatable','ResearchController@datatable')->name('research.datatable');
        Route::get('research/chart','ResearchController@chart')->name('research.chart');

        //Community Service
        Route::get('community-service/get_by_department','CommunityServiceController@get_by_department')->name('community-service.get_by_department');
        Route::post('community-service/get_by_filter','CommunityServiceController@get_by_filter')->name('community-service.filter');
        Route::post('community-service/datatable','CommunityServiceController@datatable')->name('community-service.datatable');

        //Publication - Teacher
        Route::post('publication/teacher/get_by_filter','TeacherPublicationController@get_by_filter')->name('publication.teacher.filter');
        Route::post('publication/teacher/datatable','TeacherPublicationController@datatable')->name('publication.teacher.datatable');

        //Publication - Student
        Route::post('publication/student/get_by_filter','StudentPublicationController@get_by_filter')->name('publication.student.filter');
        Route::post('publication/student/datatable','StudentPublicationController@datatable')->name('publication.student.datatable');

        //Output Activity - Teacher
        Route::post('output-activity/teacher/get_by_filter','TeacherOutputActivityController@get_by_filter')->name('output-activity.teacher.filter');
        Route::post('output-activity/teacher/datatable','TeacherOutputActivityController@datatable')->name('output-activity.teacher.datatable');

        //Output Activity - Student
        Route::post('output-activity/student/get_by_filter','StudentOutputActivityController@get_by_filter')->name('output-activity.student.filter');
        Route::post('output-activity/student/datatable','StudentOutputActivityController@datatable')->name('output-activity.student.datatable');

        //Academic - Curriculum
        Route::get('curriculum/loadData','CurriculumController@loadData')->name('curriculum.loadData');
        Route::post('curriculum/datatable','CurriculumController@datatable')->name('curriculum.datatable');
        Route::post('curriculum/get_by_filter','CurriculumController@get_by_filter')->name('curriculum.filter');

        //Academic - Curriculum Integrations
        Route::post('curriculum-integration/get_by_filter','CurriculumIntegrationController@get_by_filter')->name('curriculum-integration.filter');
        Route::post('curriculum-integration/datatable','CurriculumIntegrationController@datatable')->name('curriculum-integration.datatable');

        //Academic - Schedule
        Route::post('schedule/datatable','CurriculumScheduleController@datatable')->name('schedule.datatable');
        Route::post('schedule/get_by_filter','CurriculumScheduleController@get_by_filter')->name('schedule.filter');

        //Academic - Minithesis
        Route::post('minithesis/get_by_filter','MinithesisController@get_by_filter')->name('minithesis.filter');
        Route::post('minithesis/datatable','MinithesisController@datatable')->name('minithesis.datatable');

        //Academic - Satisfaction
        Route::post('academic-satisfaction/datatable','AcademicSatisfactionController@datatable')->name('academic-satisfaction.datatable');

        //Alumnus
        Route::get('alumnus/get','AlumnusAttainmentController@get_alumnus')->name('alumnus.get_alumnus');

        //Alumnus - Waktu Tunggu Lulusan / Bidang Kerja / Kinerja
        Route::get('alumnus/idle/{id}','AlumnusIdleController@edit')->name('alumnus.idle.edit');
        Route::get('alumnus/suitable/{id}','AlumnusSuitableController@edit')->name('alumnus.suitable.edit');
        Route::get('alumnus/workplace/{id}','AlumnusWorkplaceController@edit')->name('alumnus.workplace.edit');

    });

    //Teacher
    Route::prefix('teacher')->name('teacher.')->middleware('role:admin,kaprodi,kajur')->group(function () {

        Route::get('/',function(){
            return redirect(route('teacher'));
        });

        //Teacher - List
        Route::resource('list', 'TeacherController');
        Route::get('list/import','TeacherController@import')->name('import');

        //Teacher - Status
        Route::post('status','TeacherStatusController@store')->name('status.store');
        Route::put('status/{id}','TeacherStatusController@update')->name('status.update');
        Route::delete('status','TeacherStatusController@destroy')->name('status.destroy');

        //Teacher - Achievement
        Route::resource('achievement', 'TeacherAchievementController');
        Route::get('achievement/file/{nidn}','TeacherAchievementController@delete_file')->name('teacher.achievement.file');

        //EWMP
        Route::get('ewmp', 'EwmpController@index')->name('ewmp');
    });

    //Students
    Route::prefix('student')->name('student.')->middleware('role:admin,kaprodi,kajur')->group(function () {

        Route::get('/',function(){
            return redirect(route('student.list.index'));
        });

        //Students - List
        Route::resource('list', 'StudentController');
        Route::post('list/upload_photo','StudentController@upload_photo')->name('photo');
        Route::post('list/import','StudentController@import')->name('import');

        //Students - Quota
        Route::resource('quota', 'StudentQuotaController');

        //Students - Status
        Route::post('status','StudentStatusController@store')->name('status.store');
        Route::put('status','StudentStatusController@update')->name('status.update');
        Route::delete('status','StudentStatusController@destroy')->name('status.delete');

        //Students - Foreign
        Route::resource('foreign', 'StudentForeignController');

        //Student Achievement
        Route::resource('achievement', 'StudentAchievementController');
    });

    //Academic
    Route::prefix('academic')->name('academic.')->middleware('role:admin,kaprodi,kajur')->group(function () {

        Route::get('/',function(){
            return redirect(route('academic.curriculum.index'));
        });

        //Academic - Curriculum
        Route::resource('curriculum', 'CurriculumController');
        Route::post('curriculum/import','CurriculumController@import')->name('curriculum.import');

        //Academic - Schedule
        Route::resource('schedule', 'CurriculumScheduleController');

        //Academic - Curriculum Integration
        Route::resource('integration', 'CurriculumIntegrationController');

        //Academic - Minithesis
        Route::resource('minithesis', 'MinithesisController');

        //Academic - Satisfaction
        Route::resource('satisfaction', 'AcademicSatisfactionController');
    });

    //Collaboration
    Route::resource('collaboration', 'CollaborationController');

    //Research
    Route::resource('research', 'ResearchController');
    Route::get('research/delete_teacher/{id}','ResearchController@destroy_teacher')->name('research.teacher.delete');
    Route::get('research/delete_student/{id}','ResearchController@destroy_students')->name('research.students.delete');

    //Community Service
    Route::resource('community-service', 'CommunityServiceController');
    Route::get('community-service/delete_teacher/{id}','CommunityServiceController@destroy_teacher')->name('community-service.teacher.delete');
    Route::get('community-service/delete_student/{id}','CommunityServiceController@destroy_students')->name('community-service.students.delete');

    //Publication
    Route::prefix('publication')->name('publication.')->middleware('role:admin,kaprodi,kajur')->group(function () {
        //Publication - Teacher
        Route::resource('teacher', 'TeacherPublicationController');
        Route::get('teacher/delete_member/{id}','TeacherPublicationController@destroy_member')->name('teacher.delete.member');
        Route::get('teacher/delete_student/{id}','TeacherPublicationController@destroy_student')->name('teacher.delete.student');

        //Publication - Student
        Route::resource('student', 'StudentPublicationController');
        Route::get('student/delete_member/{id}','StudentPublicationController@destroy_member')->name('student.delete.member');
    });

    Route::prefix('output-activity')->name('output-activity.')->middleware('role:admin,kaprodi,kajur')->group(function () {

        //Output Activity - Teacher
        Route::resource('teacher', 'TeacherOutputActivityController');
        Route::get('/download/output-activity','TeacherOutputActivityController@download')->name('file.download');
        Route::get('/delete_file/output-activity','TeacherOutputActivityController@delete_file')->name('file.delete');

        //Output Activity - Student
        Route::resource('student', 'StudentOutputActivityController');
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
        Route::get('biodata','Teacher\BiodataController@index')->name('biodata');
        Route::put('biodata','Teacher\BiodataController@update')->name('biodata.update');

        //Achievement
        Route::get('achievement','Teacher\AchievementController@index')->name('achievement');
        Route::get('achievement/{id}','Teacher\AchievementController@edit')->name('achievement.edit');
        Route::post('achievement','Teacher\AchievementController@store')->name('achievement.store');
        Route::put('achievement','Teacher\AchievementController@update')->name('achievement.update');
        Route::delete('achievement','Teacher\AchievementController@destroy')->name('achievement.delete');

        //EWMP
        Route::get('ewmp','Teacher\EwmpController@index')->name('ewmp');
        Route::post('ewmp','Teacher\EwmpController@store')->name('ewmp.store');
        Route::put('ewmp','Teacher\EwmpController@update')->name('ewmp.update');

        //Research
        Route::get('research','Teacher\ResearchController@index')->name('research');
        Route::get('research/add','Teacher\ResearchController@create')->name('research.add');
        Route::get('research/{id}','Teacher\ResearchController@show')->name('research.show');
        Route::get('research/{id}/edit','Teacher\ResearchController@edit')->name('research.edit');
        Route::post('research','Teacher\ResearchController@store')->name('research.store');
        Route::put('research','Teacher\ResearchController@update')->name('research.update');
        Route::delete('research','Teacher\ResearchController@destroy')->name('research.delete');
        Route::get('research/delete_teacher/{id}','Teacher\ResearchController@destroy_teacher')->name('research.teacher.delete');
        Route::get('research/delete_student/{id}','Teacher\ResearchController@destroy_students')->name('research.students.delete');

        //Community Service
        Route::get('community-service','Teacher\CommunityServiceController@index')->name('community-service');
        Route::get('community-service/add','Teacher\CommunityServiceController@create')->name('community-service.add');
        Route::get('community-service/{id}','Teacher\CommunityServiceController@show')->name('community-service.show');
        Route::get('community-service/{id}/edit','Teacher\CommunityServiceController@edit')->name('community-service.edit');
        Route::post('community-service','Teacher\CommunityServiceController@store')->name('community-service.store');
        Route::put('community-service','Teacher\CommunityServiceController@update')->name('community-service.update');
        Route::delete('community-service','Teacher\CommunityServiceController@destroy')->name('community-service.delete');
        Route::get('community-service/delete_teacher/{id}','Teacher\CommunityServiceController@destroy_teacher')->name('community-service.teacher.delete');
        Route::get('community-service/delete_student/{id}','Teacher\CommunityServiceController@destroy_students')->name('community-service.students.delete');

        //Publication
        Route::get('publication','Teacher\PublicationController@index')->name('publication');
        Route::get('publication/add','Teacher\PublicationController@create')->name('publication.add');
        Route::get('publication/{id}','Teacher\PublicationController@show')->name('publication.show');
        Route::get('publication/{id}/edit','Teacher\PublicationController@edit')->name('publication.edit');
        Route::post('publication','Teacher\PublicationController@store')->name('publication.store');
        Route::put('publication','Teacher\PublicationController@update')->name('publication.update');
        Route::delete('publication','Teacher\PublicationController@destroy')->name('publication.delete');
        Route::get('publication/delete_member/{id}','Teacher\PublicationController@destroy_member')->name('publication.delete.member');
        Route::get('publication/delete_student/{id}','Teacher\PublicationController@destroy_student')->name('publication.delete.student');
    });

    //Download
    Route::get('/download/collab/{filename}','CollaborationController@download')->name('collaboration.download');
    Route::get('/download/teacher/{filename}','TeacherController@download')->name('teacher.download');
    Route::get('/download/teacher/achievement/{filename}','TeacherAchievementController@download')->name('teacher.achievement.download');
    Route::get('/download/avatar', 'DownloadController@avatar')->name('download.avatar');

    //Penilaian
    Route::prefix('assessment')->name('assessment.')->group(function () {
        //Kerja Sama
        Route::get('collaboration', 'Perhitungan\KerjaSamaController@index')->name('collaboration');
        Route::post('collaboration', 'Perhitungan\KerjaSamaController@kerjasama');

        //Mahasiswa
        Route::get('student', 'Perhitungan\MahasiswaController@index')->name('student');
        Route::post('student/seleksi', 'Perhitungan\MahasiswaController@mahasiswa_seleksi');
        Route::post('student/asing', 'Perhitungan\MahasiswaController@mahasiswa_asing');

        //Sumber Daya Manusia
        Route::get('resource', 'Perhitungan\SdmController@index')->name('resource');
        Route::post('resource/kecukupan_dosen', 'Perhitungan\SdmController@kecukupan_dosen');
        Route::post('resource/persentase_dtps_s3', 'Perhitungan\SdmController@persentase_dtps_s3');
        Route::post('resource/persentase_dtps_jabatan', 'Perhitungan\SdmController@persentase_dtps_jabatan');
        Route::post('resource/persentase_dtps_sertifikat', 'Perhitungan\SdmController@persentase_dtps_sertifikat');
        Route::post('resource/persentase_dtps_dtt', 'Perhitungan\SdmController@persentase_dtps_dtt');
        Route::post('resource/rasio_mahasiswa_dtps', 'Perhitungan\SdmController@rasio_mahasiswa_dtps');
        Route::post('resource/beban_bimbingan', 'Perhitungan\SdmController@beban_bimbingan');
        Route::post('resource/waktu_mengajar', 'Perhitungan\SdmController@waktu_mengajar');
        Route::post('resource/prestasi_dtps', 'Perhitungan\SdmController@prestasi_dtps');
        Route::post('resource/publikasi_jurnal', 'Perhitungan\SdmController@publikasi_jurnal');
        Route::post('resource/publikasi_seminar', 'Perhitungan\SdmController@publikasi_seminar');
        Route::post('resource/publikasi_tersitasi', 'Perhitungan\SdmController@publikasi_tersitasi');
        Route::post('resource/luaran_pkm', 'Perhitungan\SdmController@luaran_pkm');

        //Penelitian
        Route::get('research', 'Perhitungan\PenelitianController@index')->name('research');
        Route::post('research', 'Perhitungan\PenelitianController@penelitian');

        //Capaian Tridharma
        Route::get('tridharma', 'Perhitungan\TridharmaController@index')->name('tridharma');
        Route::post('tridharma/ipk', 'Perhitungan\TridharmaController@capaian_ipk');
        Route::post('tridharma/prestasi', 'Perhitungan\TridharmaController@prestasi_mahasiswa');
        Route::post('tridharma/tempat_lulusan', 'Perhitungan\TridharmaController@tempat_kerja_lulusan');
    });

    //Report
    Route::prefix('report')->name('report.')->group(function () {
        //Research
        Route::get('research', 'ReportController@research_index')->name('research.index');
        Route::post('collaboration', 'Perhitungan\KerjaSamaController@kerjasama');
    });
});
