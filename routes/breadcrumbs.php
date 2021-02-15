<?php

// Beranda
Breadcrumbs::for('dashboard', function ($trail) {
    $trail->push('Beranda', route('dashboard'));
});

// Dosen
Breadcrumbs::for('teacher', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Data Dosen', route('teacher.list.index'));
});

Breadcrumbs::for('teacher-create', function ($trail) {
    $trail->parent('teacher');
    $trail->push('Tambah Data Dosen', route('teacher.list.create'));
});

Breadcrumbs::for('teacher-profile', function ($trail, $data) {
    $trail->parent('teacher');
    $trail->push($data->nama, route('teacher.list.show', $data->nidn));
});

Breadcrumbs::for('teacher-edit', function ($trail, $data) {
    $trail->parent('teacher-profile', $data);
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
    $trail->push('Daftar Prestasi Dosen', route('teacher.achievement.index'));
});

// Mahasiswa
Breadcrumbs::for('student', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Data Mahasiswa', route('student.list.index'));
});

Breadcrumbs::for('student-add', function ($trail) {
    $trail->parent('student');
    $trail->push('Tambah Data Mahasiswa', route('student.list.create'));
});

Breadcrumbs::for('student-profile', function ($trail, $data) {
    $trail->parent('student');
    $trail->push($data->nim . ' : ' . $data->nama, route('student.list.show', encode_id($data->nim)));
});

Breadcrumbs::for('student-edit', function ($trail, $data) {
    $trail->parent('student-profile', $data);
    $trail->push('Sunting Data Mahasiswa');
});

// Mahasiswa > Mahasiswa Asing
Breadcrumbs::for('student-quota', function ($trail) {
    $trail->parent('student');
    $trail->push('Data Kuota Mahasiswa', route('student.quota.index'));
});

// Mahasiswa > Mahasiswa Asing
Breadcrumbs::for('student-foreign', function ($trail) {
    $trail->parent('student');
    $trail->push('Data Mahasiswa Asing', route('student.foreign.index'));
});

// Mahasiswa > Prestasi
Breadcrumbs::for('student-achievement', function ($trail) {
    $trail->parent('student');
    $trail->push('Daftar Prestasi Mahasiswa', route('student.achievement.index'));
});

// Akademik
Breadcrumbs::for('academic', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Akademik');
});

// Akademik > Kurikulum
Breadcrumbs::for('academic-curriculum', function ($trail) {
    $trail->parent('academic');
    $trail->push('Data Kurikulum', route('academic.curriculum.index'));
});

Breadcrumbs::for('academic-curriculum-show', function ($trail, $data) {
    $trail->parent('academic-curriculum');
    $trail->push($data->nama, route('academic.curriculum.show', $data->id));
});

Breadcrumbs::for('academic-curriculum-create', function ($trail) {
    $trail->parent('academic-curriculum');
    $trail->push('Tambah Mata Kuliah', route('academic.curriculum.create'));
});

Breadcrumbs::for('academic-curriculum-edit', function ($trail, $data) {
    $trail->parent('academic-curriculum');
    $trail->push('Sunting Mata Kuliah');
});

// Akademik > Jadwal
Breadcrumbs::for('academic-schedule', function ($trail) {
    $trail->parent('academic');
    $trail->push('Pengampu Mata Kuliah', route('academic.schedule.index'));
});

Breadcrumbs::for('academic-schedule-create', function ($trail) {
    $trail->parent('academic-schedule');
    $trail->push('Tambah Pengampu Mata Kuliah', route('academic.schedule.create'));
});

Breadcrumbs::for('academic-schedule-edit', function ($trail, $data) {
    $trail->parent('academic-schedule');
    $trail->push('Sunting Pengampu Mata Kuliah');
});

// Akademik > Integrasi Kurikulum
Breadcrumbs::for('academic-integration', function ($trail) {
    $trail->parent('academic');
    $trail->push('Integrasi Kurikulum', route('academic.integration.index'));
});

Breadcrumbs::for('academic-integration-create', function ($trail) {
    $trail->parent('academic-integration');
    $trail->push('Tambah Integrasi Kurikulum', route('academic.integration.create'));
});

Breadcrumbs::for('academic-integration-edit', function ($trail, $data) {
    $trail->parent('academic-curriculum');
    $trail->push('Sunting Integrasi Kurikulum');
});

// Akademik > Skripsi
Breadcrumbs::for('academic-minithesis', function ($trail) {
    $trail->parent('academic');
    $trail->push('Tugas Akhir', route('academic.minithesis.index'));
});

Breadcrumbs::for('academic-minithesis-create', function ($trail) {
    $trail->parent('academic-minithesis');
    $trail->push('Tambah Tugas Akhir', route('academic.minithesis.create'));
});

Breadcrumbs::for('academic-minithesis-edit', function ($trail, $data) {
    $trail->parent('academic-curriculum');
    $trail->push('Sunting Tugas Akhir');
});

// Akademik > Kepuasan
Breadcrumbs::for('academic-satisfaction', function ($trail) {
    $trail->parent('academic');
    $trail->push('Kepuasan Akademik', route('academic.satisfaction.index'));
});

Breadcrumbs::for('academic-satisfaction-create', function ($trail) {
    $trail->parent('academic-satisfaction');
    $trail->push('Tambah', route('academic.satisfaction.create'));
});

Breadcrumbs::for('academic-satisfaction-show', function ($trail, $data) {
    $trail->parent('academic-satisfaction');
    $trail->push(
        'Kepuasan Akademik: ' . $data->studyProgram->singkatan . ' - ' . $data->academicYear->tahun_akademik,
        route('academic.satisfaction.show', encrypt($data->id))
    );
});

Breadcrumbs::for('academic-satisfaction-edit', function ($trail, $data) {
    $trail->parent('academic-satisfaction-show', $data);
    $trail->push('Sunting');
});

// Kerja Sama
Breadcrumbs::for('collaboration', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Kerja Sama', route('collaboration.index'));
});

Breadcrumbs::for('collaboration-create', function ($trail) {
    $trail->parent('collaboration');
    $trail->push('Tambah Kerja Sama', route('collaboration.create'));
});

Breadcrumbs::for('collaboration-show', function ($trail, $data) {
    $trail->parent('collaboration');
    $trail->push('Rincian Kerja Sama', route('collaboration.show', $data->id));
});

Breadcrumbs::for('collaboration-edit', function ($trail) {
    $trail->parent('collaboration');
    $trail->push('Sunting Kerja Sama');
});

//Penelitian Dosen
Breadcrumbs::for('research', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Data Penelitian', route('research.index'));
});

Breadcrumbs::for('research-create', function ($trail) {
    $trail->parent('research');
    $trail->push('Tambah Data Penelitian', route('research.create'));
});

Breadcrumbs::for('research-show', function ($trail, $data) {
    $trail->parent('research');
    $trail->push($data->judul_penelitian, route('research.show', encode_id($data->id)));
});

Breadcrumbs::for('research-edit', function ($trail, $data) {
    $trail->parent('research-show', $data);
    $trail->push('Sunting Data Penelitian');
});

//Pengabdian Dosen
Breadcrumbs::for('community-service', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Data Pengabdian', route('community-service.index'));
});

Breadcrumbs::for('community-service-create', function ($trail) {
    $trail->parent('community-service');
    $trail->push('Tambah Data Pengabdian', route('community-service.create'));
});

Breadcrumbs::for('community-service-show', function ($trail, $data) {
    $trail->parent('community-service');
    $trail->push($data->judul_pengabdian, route('community-service.show', encode_id($data->id)));
});

Breadcrumbs::for('community-service-edit', function ($trail, $data) {
    $trail->parent('community-service-show', $data);
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
    $trail->push('Publikasi Dosen', route('publication.teacher.index'));
});

Breadcrumbs::for('publication-teacher-create', function ($trail) {
    $trail->parent('publication-teacher');
    $trail->push('Tambah Publikasi Dosen', route('publication.teacher.create'));
});

Breadcrumbs::for('publication-teacher-show', function ($trail, $data) {
    $trail->parent('publication-teacher');
    $trail->push($data->judul, route('publication.teacher.show', encode_id($data->id)));
});

Breadcrumbs::for('publication-teacher-edit', function ($trail, $data) {
    $trail->parent('publication-teacher');
    $trail->push('Sunting Publikasi Dosen');
});

//Publikasi > Mahasiswa
Breadcrumbs::for('publication-student', function ($trail) {
    $trail->parent('publication');
    $trail->push('Publikasi Mahasiswa', route('publication.student.index'));
});

Breadcrumbs::for('publication-student-create', function ($trail) {
    $trail->parent('publication-student');
    $trail->push('Tambah Publikasi Mahasiswa', route('publication.student.create'));
});

Breadcrumbs::for('publication-student-show', function ($trail, $data) {
    $trail->parent('publication-student');
    $trail->push($data->judul, route('publication.student.show', encode_id($data->id)));
});

Breadcrumbs::for('publication-student-edit', function ($trail, $data) {
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
    $trail->push('Luaran Dosen', route('output-activity.teacher.index'));
});

Breadcrumbs::for('output-activity-teacher-create', function ($trail) {
    $trail->parent('output-activity-teacher');
    $trail->push('Tambah Data Luaran', route('output-activity.teacher.create'));
});

Breadcrumbs::for('output-activity-teacher-show', function ($trail, $data) {
    $trail->parent('output-activity-teacher');
    $trail->push($data->judul_luaran, route('output-activity.teacher.show', encode_id($data->id)));
});

Breadcrumbs::for('output-activity-teacher-edit', function ($trail, $data) {
    $trail->parent('output-activity-teacher-show', $data);
    $trail->push('Sunting Data Luaran');
});

// Luaran - Mahasiswa
Breadcrumbs::for('output-activity-student', function ($trail) {
    $trail->parent('output-activity');
    $trail->push('Luaran Mahasiswa', route('output-activity.student.index'));
});

Breadcrumbs::for('output-activity-student-create', function ($trail) {
    $trail->parent('output-activity-student');
    $trail->push('Tambah Data Luaran', route('output-activity.student.create'));
});

Breadcrumbs::for('output-activity-student-show', function ($trail, $data) {
    $trail->parent('output-activity-student');
    $trail->push($data->judul_luaran, route('output-activity.student.show', encode_id($data->id)));
});

Breadcrumbs::for('output-activity-student-edit', function ($trail, $data) {
    $trail->parent('output-activity-student-show', $data);
    $trail->push('Sunting Data Luaran');
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

Breadcrumbs::for('funding-faculty-show', function ($trail, $data) {
    $trail->parent('funding-faculty');
    $trail->push(
        'Rincian Keuangan: ' . $data->faculty->singkatan . ' - ' . $data->academicYear->tahun_akademik,
        route('funding.faculty.show', encrypt($data->kd_dana))
    );
});

Breadcrumbs::for('funding-faculty-edit', function ($trail, $data) {
    $trail->parent('funding-faculty-show', $data);
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

Breadcrumbs::for('funding-studyProgram-show', function ($trail, $data) {
    $trail->parent('funding-studyProgram');
    $trail->push(
        'Rincian Keuangan: ' . $data->studyProgram->singkatan . ' - ' . $data->academicYear->tahun_akademik,
        route('funding.study-program.show', encrypt($data->kd_dana))
    );
});

Breadcrumbs::for('funding-studyProgram-edit', function ($trail, $data) {
    $trail->parent('funding-studyProgram-show', $data);
    $trail->push('Sunting Keuangan Program Studi');
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

Breadcrumbs::for('alumnus-idle-show', function ($trail, $data) {
    $trail->parent('alumnus-idle');
    $trail->push($data->nama, route('alumnus.idle.show', encrypt($data->kd_prodi)));
});

// Alumnus > Kesesuaian Bidang Kerja
Breadcrumbs::for('alumnus-suitable', function ($trail) {
    $trail->parent('alumnus');
    $trail->push('Bidang Kerja Lulusan', route('alumnus.suitable'));
});

Breadcrumbs::for('alumnus-suitable-show', function ($trail, $data) {
    $trail->parent('alumnus-suitable');
    $trail->push($data->nama, route('alumnus.suitable.show', encrypt($data->kd_prodi)));
});

// Alumnus > Kinerja Lulusan
Breadcrumbs::for('alumnus-workplace', function ($trail) {
    $trail->parent('alumnus');
    $trail->push('Kinerja Lulusan', route('alumnus.workplace'));
});

Breadcrumbs::for('alumnus-workplace-show', function ($trail, $data) {
    $trail->parent('alumnus-workplace');
    $trail->push($data->nama, route('alumnus.workplace.show', encrypt($data->kd_prodi)));
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

Breadcrumbs::for('alumnus-satisfaction-show', function ($trail, $data) {
    $trail->parent('alumnus-satisfaction');
    $trail->push(
        'Kepuasan Pengguna Lulusan: ' . $data->studyProgram->singkatan . ' - ' . $data->academicYear->tahun_akademik,
        route('alumnus.satisfaction.show', encrypt($data->id))
    );
});

Breadcrumbs::for('alumnus-satisfaction-edit', function ($trail, $data) {
    $trail->parent('alumnus-satisfaction-show', $data);
    $trail->push('Sunting');
});

// Data Master
Breadcrumbs::for('report', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Laporan');
});

// Data Master > Tahun Akademik
Breadcrumbs::for('report-tridharma', function ($trail) {
    $trail->parent('report');
    $trail->push('Tridharma Dosen', route('report.tridharma.index'));
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
    $trail->push('Setelan Umum', route('setting.general'));
});

// Setelan > Struktural
Breadcrumbs::for('setting-structural', function ($trail) {
    $trail->parent('setting');
    $trail->push('Manajemen Struktural', route('setting.structural.index'));
});

// Setelan > User
Breadcrumbs::for('setting-user', function ($trail) {
    $trail->parent('setting');
    $trail->push('Manajemen User', route('setting.user.index'));
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

Breadcrumbs::for('profile-research-create', function ($trail) {
    $trail->parent('profile');
    $trail->push('Tambah Data Penelitian', route('profile.research.create'));
});

Breadcrumbs::for('profile-research-show', function ($trail, $data) {
    $trail->parent('profile');
    $trail->push('Data Penelitian: ' . $data->judul_penelitian, route('profile.research.show', encode_id($data->id)));
});

Breadcrumbs::for('profile-research-edit', function ($trail, $data) {
    $trail->parent('profile-research-show', $data);
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

Breadcrumbs::for('profile-community-service-show', function ($trail, $data) {
    $trail->parent('profile-community-service');
    $trail->push($data->judul_pengabdian, route('profile.community-service.show', encode_id($data->id)));
});

Breadcrumbs::for('profile-community-service-edit', function ($trail, $data) {
    $trail->parent('profile-community-service-show', $data);
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

Breadcrumbs::for('profile-publication-show', function ($trail, $data) {
    $trail->parent('profile-publication');
    $trail->push('Data Publikasi: ' . $data->judul, route('profile.publication.show', encode_id($data->id)));
});

Breadcrumbs::for('profile-publication-edit', function ($trail, $data) {
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
    $trail->push('Ubah Profil', route('account.editprofile'));
});

// Akun > Ganti Kata Sandi
Breadcrumbs::for('account-editpassword', function ($trail) {
    $trail->parent('account');
    $trail->push('Ubah Kata Sandi', route('account.editpassword'));
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
