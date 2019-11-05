<?php

// Dashboard
Breadcrumbs::for('dashboard', function ($trail) {
    $trail->push('Beranda', route('dashboard'));
});

//Collaboration
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

//Dosen
Breadcrumbs::for('teacher', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Data Dosen', route('teacher'));
});

Breadcrumbs::for('teacher-add', function ($trail) {
    $trail->parent('teacher');
    $trail->push('Tambah Data Dosen', route('teacher.add'));
});

Breadcrumbs::for('teacher-edit', function ($trail) {
    $trail->parent('teacher');
    $trail->push('Sunting Data Dosen');
});

//Dosen > EWMP
Breadcrumbs::for('teacher-ewmp', function ($trail) {
    $trail->parent('teacher');
    $trail->push('Daftar EWMP Dosen', route('teacher.ewmp'));
});

//Mahasiswa
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

//Keuangan
Breadcrumbs::for('funding', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Data Keuangan');
});

//Keuangan - Fakultas
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

//Keuangan - Program Studi
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

//Keuangan - Program Studi
Breadcrumbs::for('research', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Data Penelitian', route('research'));
});

Breadcrumbs::for('research-add', function ($trail) {
    $trail->parent('research');
    $trail->push('Tambah Data Penelitian', route('research.add'));
});

Breadcrumbs::for('research-edit', function ($trail,$data) {
    $trail->parent('research-show',$data);
    $trail->push('Sunting Data Penelitian');
});

// Dashboard > Data Master
Breadcrumbs::for('master', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Data Master');
});

// Home > About
Breadcrumbs::for('academic-year', function ($trail) {
    $trail->parent('master');
    $trail->push('Tahun Akademik', route('master.academic-year'));
});

//Program Studi
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

//Jurusan
Breadcrumbs::for('department', function ($trail) {
    $trail->parent('master');
    $trail->push('Jurusan', route('master.department'));
});

//Fakultas
Breadcrumbs::for('faculty', function ($trail) {
    $trail->parent('master');
    $trail->push('Fakultas', route('master.faculty'));
});

Breadcrumbs::for('setting', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Setelan');
});

Breadcrumbs::for('setting-general', function ($trail) {
    $trail->parent('setting');
    $trail->push('Setelan Umum');
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
