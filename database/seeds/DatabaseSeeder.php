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
        $this->call(UserSeeder::class);
        $this->call(AcademicYearSeeder::class);
        $this->call(FacultySeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(StudyProgramSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(CollaborationSeeder::class);
        $this->call(TeacherSeeder::class);
        $this->call(EwmpSeeder::class);
        $this->call(TeacherAchievementSeeder::class);
        $this->call(CurriculumSeeder::class);
        $this->call(TeacherScheduleSeeder::class);
        $this->call(StudentQuotaSeeder::class);
        $this->call(StudentSeeder::class);
        $this->call(StudentForeignSeeder::class);
        $this->call(FundingCategorySeeder::class);
        $this->call(FundFacultySeeder::class);
        $this->call(FundStudyProgramSeeder::class);
        $this->call(ResearchSeeder::class);
        $this->call(ResearchStudentsSeeder::class);
        $this->call(CommunityServiceSeeder::class);
        $this->call(CommunityServiceStudentsSeeder::class);
        $this->call(PublicationCategoriesSeeder::class);
        $this->call(PublicationSeeder::class);
        $this->call(PublicationStudentsSeeder::class);
        $this->call(OutputActivityCategorySeeder::class);
        $this->call(OutputActivitySeeder::class);
    }
}
