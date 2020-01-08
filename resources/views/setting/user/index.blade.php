@extends('layouts.master')

@section('title', 'Manajemen User')

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('setting-user') as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div>

<div class="br-pagetitle">
    <i class="icon fa fa-users"></i>
    <div>
        <h4>Manajemen User</h4>
        <p class="mg-b-0">Daftar Manajemen User</p>
    </div>
    <div class="ml-auto">
        <button class="btn btn-teal btn-block mg-b-10 btn-add" data-toggle="modal" data-target="#modal-setting-user" style="color:white"><i class="fa fa-plus mg-r-10"></i> User</button>
    </div>
</div>

<div class="br-pagebody">
    @if($errors->any())
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
        </button>
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
    @endif
    <div class="row widget-2">
        <div class="col-9">
            <div class="card shadow-base mb-3">
                <div class="card-header">
                    <h6 class="card-title">
                        Daftar User
                    </h6>
                </div>
                <div class="card-body bd-color-gray-lighter">
                    <table id="table-user" class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">#</th>
                                <th class="text-center align-middle">Nama User</th>
                                <th class="text-center align-middle">Username</th>
                                <th class="text-center align-middle">Role</th>
                                <th class="text-center align-middle no-sort">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user as $u)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $u->name }}</td>
                                <td>{{ $u->username }}</td>
                                <td>
                                    <span class="badge badge-{{$u->badge}} tx-13">{{ $u->role }} {{isset($u->kd_prodi) ? ' - '.$u->studyProgram->nama : ''}}</span>
                                </td>
                                <td width="50" class="text-center">
                                    <div class="btn-group" role="group">
                                        <button id="btn-action" type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <div><span class="fa fa-caret-down"></span></div>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                            <button class="dropdown-item btn-edit" data-id="{{ encrypt($u->id) }}">Sunting</button>
                                            <button class="dropdown-item reset-password" data-id="{{ encrypt($u->id) }}">Reset Password</button>
                                            <form method="POST">
                                                @method('delete')
                                                @csrf
                                                <input type="hidden" value="{{ encrypt($u->id) }}" name="id">
                                                <button class="dropdown-item btn-delete" data-dest="{{ route('setting.user.delete') }}">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div><!-- card-body -->
            </div>
        </div>
        <div class="col-3">
            <div class="alert alert-info">
                <h5>ROLE UNTUK USER</h5>
                <hr>
                <ul class="pd-l-12">
                    <li>
                        <strong>Admin</strong><br>
                        Hak akses tertinggi yang dapat mengelola seluruh data dalam aplikasi, termasuk setelan dan manajemen user
                    </li>
                    <li>
                        <strong>Kajur</strong><br>
                        Hak akses untuk kepala jurusan. Hanya dapat melihat data untuk seluruh program studi, serta mengelola data keuangan Fakultas dan Program Studi.
                    </li>
                    <li>
                        <strong>Kaprodi</strong><br>
                        Hak akses untuk kepala proram studi. Hak akses ini seperti namanya yaitu memberikan hak untuk mengelola data berdasarkan program studi yang diampu.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@include('setting.user.form')
@endsection

@section('custom-js')

@endsection
