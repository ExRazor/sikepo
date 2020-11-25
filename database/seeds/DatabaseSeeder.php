<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(AcademicYearSeeder::class);
        $this->call(FacultySeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(StudyProgramSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(UserSeeder::class);

        $this->call(FundingCategorySeeder::class);
        $this->call(PublicationCategoriesSeeder::class);
        $this->call(OutputActivityCategorySeeder::class);
        $this->call(SatisfactionCategorySeeder::class);

        $this->call(TeacherSeeder::class);
        $this->call(CurriculumSeeder::class);
        $this->call(StudentSeeder::class);

        // $this->call(StudentStatusSeeder::class);
        // $this->call(CollaborationSeeder::class);
        // $this->call(TeacherAchievementSeeder::class);
        // $this->call(CurriculumScheduleSeeder::class);
        // $this->call(StudentQuotaSeeder::class);
        // $this->call(StudentForeignSeeder::class);
        // $this->call(StudentAchievementSeeder::class);
        // $this->call(FundFacultySeeder::class);
        // $this->call(FundStudyProgramSeeder::class);
        // $this->call(ResearchSeeder::class);
        // $this->call(ResearchTeacherSeeder::class);
        // $this->call(ResearchStudentsSeeder::class);
        // $this->call(CommunityServiceSeeder::class);
        // $this->call(CommunityServiceTeacherSeeder::class);
        // $this->call(CommunityServiceStudentsSeeder::class);
        // $this->call(EwmpSeeder::class);
        // $this->call(TeacherPublicationSeeder::class);
        // $this->call(TeacherPublicationMemberSeeder::class);
        // $this->call(TeacherPublicationStudentSeeder::class);
        // $this->call(StudentPublicationSeeder::class);
        // $this->call(StudentPublicationMemberSeeder::class);
        // $this->call(TeacherOutputActivity::class);
        // $this->call(StudentOutputActivity::class);
        // $this->call(MinithesisSeeder::class);
        // $this->call(CurriculumIntegrationSeeder::class);
        // $this->call(AcademicSatisfactionSeeder::class);
        // $this->call(AlumnusSatisfactionSeeder::class);
        // $this->call(AlumnusIdleSeeder::class);
        // $this->call(AlumnusSuitableSeeder::class);
        // $this->call(AlumnusWorkplaceSeeder::class);
    }
}
