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

//Profil Dosen
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

//Profil Dosen

Breadcrumbs::for('teacher-ewmp', function ($trail) {
    $trail->parent('teacher');
    $trail->push('Daftar EWMP Dosen', route('teacher.ewmp'));
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
