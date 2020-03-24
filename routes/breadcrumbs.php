<?php

// Beranda
Breadcrumbs::for('dashboard', function ($trail) {
    $trail->push('Beranda', route('dashboard'));
});

// Kerja Sama
Breadcrumbs::for('collaboration', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Kerja Sama', route('collaboration'));
});

Breadcrumbs::for('collaboration-add', function ($trail) {
    $trail->parent('collaboration');
    $trail->push('Tambah Kerja Sama', route('collaboration.add'));
});

Breadcrumbs::for('collaboration-edit', function ($trail) {
    $trail->parent('collaboration');
    $trail->push('Sunting Kerja Sama');
});

// Dosen
Breadcrumbs::for('teacher', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Data Dosen', route('teacher'));
});

Breadcrumbs::for('teacher-add', function ($trail) {
    $trail->parent('teacher');
    $trail->push('Tambah Data Dosen', route('teacher.add'));
});

Breadcrumbs::for('teacher-profile', function ($trail,$data) {
    $trail->parent('teacher');
    $trail->push($data->nidn.' : '.$data->nama,route('teacher.show',encode_id($data->nidn)));
});

Breadcrumbs::for('teacher-edit', function ($trail,$data) {
    $trail->parent('teacher-profile',$data);
    $trail->push('Sunting Data Dosen');
});

//Dosen > EWMP
Breadcrumbs::for('teacher-ewmp', function ($trail) {
    $trail->parent('teacher');
    $trail->push('Daftar EWMP Dosen', route('teacher.ewmp'));
});

//Dosen > Prestasi
Breadcrumbs::for('teacher-achievement', function ($trail) {
    $trail->parent('teacher');
    $trail->push('Daftar Prestasi Dosen', route('teacher.achievement'));
});

// Mahasiswa
Breadcrumbs::for('student', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Data Mahasiswa', route('student'));
});

Breadcrumbs::for('student-add', function ($trail) {
    $trail->parent('student');
    $trail->push('Tambah Data Mahasiswa', route('student.add'));
});

Breadcrumbs::for('student-profile', function ($trail,$data) {
    $trail->parent('student');
    $trail->push($data->nim.' : '.$data->nama,route('student.profile',encode_id($data->nim)));
});

Breadcrumbs::for('student-edit', function ($trail,$data) {
    $trail->parent('student-profile',$data);
    $trail->push('Sunting Data Mahasiswa');
});

// Mahasiswa > Mahasiswa Asing
Breadcrumbs::for('student-quota', function ($trail) {
    $trail->parent('student');
    $trail->push('Data Kuota Mahasiswa', route('student.quota'));
});

// Mahasiswa > Mahasiswa Asing
Breadcrumbs::for('student-foreign', function ($trail) {
    $trail->parent('student');
    $trail->push('Data Mahasiswa Asing', route('student.foreign'));
});

// Mahasiswa > Prestasi
Breadcrumbs::for('student-achievement', function ($trail) {
    $trail->parent('student');
    $trail->push('Daftar Prestasi Mahasiswa', route('student.achievement'));
});

// Keuangan
Breadcrumbs::for('funding', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Data Keuangan');
});

// Keuangan > Fakultas
Breadcrumbs::for('funding-faculty', function ($trail) {
    $trail->parent('funding');
    $trail->push('Keuangan Fakultas', route('funding.faculty'));
});

Breadcrumbs::for('funding-faculty-add', function ($trail) {
    $trail->parent('funding-faculty');
    $trail->push('Tambah Keuangan Fakultas', route('funding.faculty.add'));
});

Breadcrumbs::for('funding-faculty-show', function ($trail,$data) {
    $trail->parent('funding-faculty');
    $trail->push(
        'Rincian Keuangan: '.$data->faculty->singkatan.' - '.$data->academicYear->tahun_akademik,
        route('funding.faculty.show',encrypt($data->kd_dana))
    );
});

Breadcrumbs::for('funding-faculty-edit', function ($trail,$data) {
    $trail->parent('funding-faculty-show',$data);
    $trail->push('Sunting Keuangan Fakultas');
});

// Keuangan > Program Studi
Breadcrumbs::for('funding-studyProgram', function ($trail) {
    $trail->parent('funding');
    $trail->push('Keuangan Program Studi', route('funding.study-program'));
});

Breadcrumbs::for('funding-studyProgram-add', function ($trail) {
    $trail->parent('funding-studyProgram');
    $trail->push('Tambah Keuangan Program Studi', route('funding.study-program.add'));
});

Breadcrumbs::for('funding-studyProgram-show', function ($trail,$data) {
    $trail->parent('funding-studyProgram');
    $trail->push(
        'Rincian Keuangan: '.$data->studyProgram->singkatan.' - '.$data->academicYear->tahun_akademik,
        route('funding.study-program.show',encrypt($data->kd_dana))
    );
});

Breadcrumbs::for('funding-studyProgram-edit', function ($trail,$data) {
    $trail->parent('funding-studyProgram-show',$data);
    $trail->push('Sunting Keuangan Program Studi');
});

//Penelitian Dosen
Breadcrumbs::for('research', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Data Penelitian', route('research'));
});

Breadcrumbs::for('research-add', function ($trail) {
    $trail->parent('research');
    $trail->push('Tambah Data Penelitian', route('research.add'));
});

Breadcrumbs::for('research-show', function ($trail,$data) {
    $trail->parent('research');
    $trail->push('Data Penelitian: '.$data->judul_penelitian, route('research.show',encode_id($data->id)));
});

Breadcrumbs::for('research-edit', function ($trail,$data) {
    $trail->parent('research-show',$data);
    $trail->push('Sunting Data Penelitian');
});

//Pengabdian Dosen
Breadcrumbs::for('community-service', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Data Pengabdian', route('community-service'));
});

Breadcrumbs::for('community-service-add', function ($trail) {
    $trail->parent('community-service');
    $trail->push('Tambah Data Pengabdian', route('community-service.add'));
});

Breadcrumbs::for('community-service-show', function ($trail,$data) {
    $trail->parent('community-service');
    $trail->push('Data Pengabdian: '.$data->judul_pengabdian, route('community-service.show',encode_id($data->id)));
});

Breadcrumbs::for('community-service-edit', function ($trail,$data) {
    $trail->parent('community-service-show',$data);
    $trail->push('Sunting Data Pengabdian');
});

//Publikasi > Dosen
Breadcrumbs::for('publication', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Data Publikasi');
});

//Publikasi > Dosen
Breadcrumbs::for('publication-teacher', function ($trail) {
    $trail->parent('publication');
    $trail->push('Publikasi Dosen', route('publication.teacher'));
});

Breadcrumbs::for('publication-teacher-add', function ($trail) {
    $trail->parent('publication-teacher');
    $trail->push('Tambah Publikasi Dosen', route('publication.teacher.add'));
});

Breadcrumbs::for('publication-teacher-show', function ($trail,$data) {
    $trail->parent('publication-teacher');
    $trail->push('Data Publikasi: '.$data->judul, route('publication.teacher.show',encode_id($data->id)));
});

Breadcrumbs::for('publication-teacher-edit', function ($trail,$data) {
    $trail->parent('publication-teacher');
    $trail->push('Sunting Publikasi Dosen');
});

//Publikasi > Mahasiswa
Breadcrumbs::for('publication-student', function ($trail) {
    $trail->parent('publication');
    $trail->push('Publikasi Mahasiswa', route('publication.student'));
});

Breadcrumbs::for('publication-student-add', function ($trail) {
    $trail->parent('publication-student');
    $trail->push('Tambah Publikasi Mahasiswa', route('publication.student.add'));
});

Breadcrumbs::for('publication-student-show', function ($trail,$data) {
    $trail->parent('publication-student');
    $trail->push('Data Publikasi: '.$data->judul, route('publication.student.show',encode_id($data->id)));
});

Breadcrumbs::for('publication-student-edit', function ($trail,$data) {
    $trail->parent('publication-student');
    $trail->push('Sunting Publikasi Mahasiswa');
});

// Luaran - Dosen
Breadcrumbs::for('output-activity', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Data Luaran');
});

Breadcrumbs::for('output-activity-teacher', function ($trail) {
    $trail->parent('output-activity');
    $trail->push('Luaran Dosen', route('output-activity.teacher'));
});

Breadcrumbs::for('output-activity-teacher-add', function ($trail) {
    $trail->parent('output-activity-teacher');
    $trail->push('Tambah Data Luaran', route('output-activity.teacher.add'));
});

Breadcrumbs::for('output-activity-teacher-show', function ($trail,$data) {
    $trail->parent('output-activity-teacher');
    $trail->push('Luaran Penelitian: '.$data->judul_luaran, route('output-activity.teacher.show',encode_id($data->id)));
});

Breadcrumbs::for('output-activity-teacher-edit', function ($trail,$data) {
    $trail->parent('output-activity-teacher-show',$data);
    $trail->push('Sunting Data Luaran');
});

// Luaran - Mahasiswa
Breadcrumbs::for('output-activity-student', function ($trail) {
    $trail->parent('output-activity');
    $trail->push('Luaran Mahasiswa', route('output-activity.student'));
});

Breadcrumbs::for('output-activity-student-add', function ($trail) {
    $trail->parent('output-activity-student');
    $trail->push('Tambah Data Luaran', route('output-activity.student.add'));
});

Breadcrumbs::for('output-activity-student-show', function ($trail,$data) {
    $trail->parent('output-activity-student');
    $trail->push('Luaran Penelitian: '.$data->judul_luaran, route('output-activity.student.show',encode_id($data->id)));
});

Breadcrumbs::for('output-activity-student-edit', function ($trail,$data) {
    $trail->parent('output-activity-student-show',$data);
    $trail->push('Sunting Data Luaran');
});

// Akademik
Breadcrumbs::for('academic', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Akademik');
});

// Akademik > Kurikulum
Breadcrumbs::for('academic-curriculum', function ($trail) {
    $trail->parent('academic');
    $trail->push('Data Kurikulum', route('academic.curriculum'));
});

Breadcrumbs::for('academic-curriculum-add', function ($trail) {
    $trail->parent('academic-curriculum');
    $trail->push('Tambah Mata Kuliah', route('academic.curriculum.add'));
});

Breadcrumbs::for('academic-curriculum-edit', function ($trail,$data) {
    $trail->push('Sunting Mata Kuliah');
});

// Akademik > Jadwal
Breadcrumbs::for('academic-schedule', function ($trail) {
    $trail->parent('academic');
    $trail->push('Jadwal Kurikulum', route('academic.schedule'));
});

Breadcrumbs::for('academic-schedule-add', function ($trail) {
    $trail->parent('academic-schedule');
    $trail->push('Tambah Jadwal Kurikulum', route('academic.schedule.add'));
});

Breadcrumbs::for('academic-schedule-edit', function ($trail,$data) {
    $trail->push('Sunting Jadwal Kurikulum');
});

// Akademik > Integrasi Kurikulum
Breadcrumbs::for('academic-integration', function ($trail) {
    $trail->parent('academic');
    $trail->push('Integrasi Kurikulum', route('academic.integration'));
});

Breadcrumbs::for('academic-integration-add', function ($trail) {
    $trail->parent('academic-integration');
    $trail->push('Tambah Integrasi Kurikulum', route('academic.integration.add'));
});

Breadcrumbs::for('academic-integration-edit', function ($trail,$data) {
    $trail->push('Sunting Integrasi Kurikulum');
});

// Akademik > Skripsi
Breadcrumbs::for('academic-minithesis', function ($trail) {
    $trail->parent('academic');
    $trail->push('Tugas Akhir', route('academic.minithesis'));
});

Breadcrumbs::for('academic-minithesis-add', function ($trail) {
    $trail->parent('academic-minithesis');
    $trail->push('Tambah Tugas Akhir', route('academic.minithesis.add'));
});

Breadcrumbs::for('academic-minithesis-edit', function ($trail,$data) {
    $trail->push('Sunting Tugas Akhir');
});

// Akademik > Kepuasan
Breadcrumbs::for('academic-satisfaction', function ($trail) {
    $trail->parent('academic');
    $trail->push('Kepuasan Akademik', route('academic.satisfaction'));
});

Breadcrumbs::for('academic-satisfaction-add', function ($trail) {
    $trail->parent('academic-satisfaction');
    $trail->push('Tambah', route('academic.satisfaction.add'));
});

Breadcrumbs::for('academic-satisfaction-show', function ($trail,$data) {
    $trail->parent('academic-satisfaction');
    $trail->push(
        'Kepuasan Akademik: '.$data->studyProgram->singkatan.' - '.$data->academicYear->tahun_akademik,
        route('academic.satisfaction.show',encrypt($data->id))
    );
});

Breadcrumbs::for('academic-satisfaction-edit', function ($trail,$data) {
    $trail->parent('academic-satisfaction-show',$data);
    $trail->push('Sunting');
});

// Alumnus
Breadcrumbs::for('alumnus', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Lulusan');
});

// Alumnus > Capaian
Breadcrumbs::for('alumnus-attainment', function ($trail) {
    $trail->parent('alumnus');
    $trail->push('Capaian Pembelajaran Lulusan', route('alumnus.attainment'));
});

// Alumnus > Waktu Tunggu
Breadcrumbs::for('alumnus-idle', function ($trail) {
    $trail->parent('alumnus');
    $trail->push('Waktu Tunggu Lulusan', route('alumnus.idle'));
});

Breadcrumbs::for('alumnus-idle-show', function ($trail,$data) {
    $trail->parent('alumnus-idle');
    $trail->push($data->nama, route('alumnus.idle.show',encrypt($data->kd_prodi)));
});

// Alumnus > Kesesuaian Bidang Kerja
Breadcrumbs::for('alumnus-suitable', function ($trail) {
    $trail->parent('alumnus');
    $trail->push('Bidang Kerja Lulusan', route('alumnus.suitable'));
});

Breadcrumbs::for('alumnus-suitable-show', function ($trail,$data) {
    $trail->parent('alumnus-suitable');
    $trail->push($data->nama, route('alumnus.suitable.show',encrypt($data->kd_prodi)));
});

// Alumnus > Kinerja Lulusan
Breadcrumbs::for('alumnus-workplace', function ($trail) {
    $trail->parent('alumnus');
    $trail->push('Kinerja Lulusan', route('alumnus.workplace'));
});

Breadcrumbs::for('alumnus-workplace-show', function ($trail,$data) {
    $trail->parent('alumnus-workplace');
    $trail->push($data->nama, route('alumnus.workplace.show',encrypt($data->kd_prodi)));
});

// Alumnus > Kepuasan
Breadcrumbs::for('alumnus-satisfaction', function ($trail) {
    $trail->parent('alumnus');
    $trail->push('Kepuasan Pengguna Lulusan', route('alumnus.satisfaction'));
});

Breadcrumbs::for('alumnus-satisfaction-add', function ($trail) {
    $trail->parent('alumnus-satisfaction');
    $trail->push('Tambah', route('alumnus.satisfaction.add'));
});

Breadcrumbs::for('alumnus-satisfaction-show', function ($trail,$data) {
    $trail->parent('alumnus-satisfaction');
    $trail->push(
        'Kepuasan Pengguna Lulusan: '.$data->studyProgram->singkatan.' - '.$data->academicYear->tahun_akademik,
        route('alumnus.satisfaction.show',encrypt($data->id))
    );
});

Breadcrumbs::for('alumnus-satisfaction-edit', function ($trail,$data) {
    $trail->parent('alumnus-satisfaction-show',$data);
    $trail->push('Sunting');
});

// Data Master
Breadcrumbs::for('master', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Data Master');
});

// Data Master > Tahun Akademik
Breadcrumbs::for('academic-year', function ($trail) {
    $trail->parent('master');
    $trail->push('Tahun Akademik', route('master.academic-year'));
});

// Data Master > Kategori Publikasi
Breadcrumbs::for('publication-category', function ($trail) {
    $trail->parent('master');
    $trail->push('Kategori Publikasi', route('master.publication-category'));
});

// Luaran > Kategori
Breadcrumbs::for('output-activity-category', function ($trail) {
    $trail->parent('master');
    $trail->push('Kategori Luaran', route('master.outputactivity-category'));
});

// Luaran > Kategori
Breadcrumbs::for('funding-category', function ($trail) {
    $trail->parent('master');
    $trail->push('Kategori Pendanaan', route('master.funding-category'));
});

// Data Master > Program Studi
Breadcrumbs::for('study-program', function ($trail) {
    $trail->parent('master');
    $trail->push('Program Studi', route('master.study-program'));
});

Breadcrumbs::for('study-program-add', function ($trail) {
    $trail->parent('study-program');
    $trail->push('Tambah Program Studi');
});

Breadcrumbs::for('study-program-edit', function ($trail) {
    $trail->parent('study-program');
    $trail->push('Sunting Program Studi');
});

// Data Master > Jurusan
Breadcrumbs::for('department', function ($trail) {
    $trail->parent('master');
    $trail->push('Jurusan', route('master.department'));
});

// Data Master > Fakultas
Breadcrumbs::for('faculty', function ($trail) {
    $trail->parent('master');
    $trail->push('Fakultas', route('master.faculty'));
});

// Data Master > Kategori Kepuasan
Breadcrumbs::for('satisfaction-category', function ($trail) {
    $trail->parent('master');
    $trail->push('Aspek Kepuasan', route('master.satisfaction-category'));
});

Breadcrumbs::for('satisfaction-category-add', function ($trail) {
    $trail->parent('satisfaction-category');
    $trail->push('Tambah Aspek Kepuasan');
});

Breadcrumbs::for('satisfaction-category-edit', function ($trail) {
    $trail->parent('satisfaction-category');
    $trail->push('Sunting Aspek Kepuasan');
});

// Setelan
Breadcrumbs::for('setting', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Setelan');
});

// Setelan > Umum
Breadcrumbs::for('setting-general', function ($trail) {
    $trail->parent('setting');
    $trail->push('Setelan Umum',route('setting.general'));
});

// Setelan > User
Breadcrumbs::for('setting-user', function ($trail) {
    $trail->parent('setting');
    $trail->push('Manajemen User',route('setting.user'));
});

// Profile
Breadcrumbs::for('profile', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Profil');
});

// Profile - Biodata
Breadcrumbs::for('profile-biodata', function ($trail) {
    $trail->parent('profile');
    $trail->push('Ubah Biodata', route('profile.biodata'));
});

// Profile - Prestasi
Breadcrumbs::for('profile-achievement', function ($trail) {
    $trail->parent('profile');
    $trail->push('Prestasi', route('profile.achievement'));
});

// Profile - Prestasi
Breadcrumbs::for('profile-ewmp', function ($trail) {
    $trail->parent('profile');
    $trail->push('Ekuivalen Waktu Mengajar Penuh', route('profile.ewmp'));
});

// Profile - Penelitian
Breadcrumbs::for('profile-research', function ($trail) {
    $trail->parent('profile');
    $trail->push('Data Penelitian', route('profile.research'));
});

Breadcrumbs::for('profile-research-add', function ($trail) {
    $trail->parent('profile');
    $trail->push('Tambah Data Penelitian', route('profile.research.add'));
});

Breadcrumbs::for('profile-research-show', function ($trail,$data) {
    $trail->parent('profile');
    $trail->push('Data Penelitian: '.$data->judul_penelitian, route('profile.research.show',encode_id($data->id)));
});

Breadcrumbs::for('profile-research-edit', function ($trail,$data) {
    $trail->parent('profile-research-show',$data);
    $trail->push('Sunting Data Penelitian');
});

// Profile - Pengabdian
Breadcrumbs::for('profile-community-service', function ($trail) {
    $trail->parent('profile');
    $trail->push('Data Pengabdian', route('profile.community-service'));
});

Breadcrumbs::for('profile-community-service-add', function ($trail) {
    $trail->parent('profile-community-service');
    $trail->push('Tambah Data Pengabdian', route('profile.community-service.add'));
});

Breadcrumbs::for('profile-community-service-show', function ($trail,$data) {
    $trail->parent('profile-community-service');
    $trail->push('Data Pengabdian: '.$data->judul_pengabdian, route('profile.community-service.show',encode_id($data->id)));
});

Breadcrumbs::for('profile-community-service-edit', function ($trail,$data) {
    $trail->parent('profile-community-service-show',$data);
    $trail->push('Sunting Data Pengabdian');
});

// Profile - Publikasi
Breadcrumbs::for('profile-publication', function ($trail) {
    $trail->parent('profile');
    $trail->push('Data Publikasi', route('profile.publication'));
});

Breadcrumbs::for('profile-publication-add', function ($trail) {
    $trail->parent('profile-publication');
    $trail->push('Tambah Publikasi', route('profile.publication.add'));
});

Breadcrumbs::for('profile-publication-show', function ($trail,$data) {
    $trail->parent('profile-publication');
    $trail->push('Data Publikasi: '.$data->judul, route('profile.publication.show',encode_id($data->id)));
});

Breadcrumbs::for('profile-publication-edit', function ($trail,$data) {
    $trail->parent('profile-publication');
    $trail->push('Sunting Publikasi');
});

// Akun
Breadcrumbs::for('account', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Akun');
});

// Akun > Ganti Profil
Breadcrumbs::for('account-editprofile', function ($trail) {
    $trail->parent('account');
    $trail->push('Ubah Profil',route('account.editprofile'));
});

// Akun > Ganti Kata Sandi
Breadcrumbs::for('account-editpassword', function ($trail) {
    $trail->parent('account');
    $trail->push('Ubah Kata Sandi',route('account.editpassword'));
});


// // Home > About
// Breadcrumbs::for('about', function ($trail) {
//     $trail->parent('home');
//     $trail->push('About', route('about'));
// });

// // Home > Blog
// Breadcrumbs::for('blog', function ($trail) {
//     $trail->parent('home');
//     $trail->push('Blog', route('blog'));
// });

// // Home > Blog > [Category]
// Breadcrumbs::for('category', function ($trail, $category) {
//     $trail->parent('blog');
//     $trail->push($category->title, route('category', $category->id));
// });

// // Home > Blog > [Category] > [Post]
// Breadcrumbs::for('post', function ($trail, $post) {
//     $trail->parent('category', $post->category);
//     $trail->push($post->title, route('post', $post->id));
// });
