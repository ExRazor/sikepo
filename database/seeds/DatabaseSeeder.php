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
        $this->call(StudentSeeder::class);
        $this->call(StudentQuotaSeeder::class);
    }
}
