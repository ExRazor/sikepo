@extends('layouts.master')

@section('title', 'Prestasi')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('profile-achievement') as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </nav>
</div>

<div class="br-pagetitle">
    <div class="d-flex pl-0 mb-3">
        <i class="icon fa fa-medal"></i>
        <div>
            <h4>Prestasi Dosen</h4>
            <p class="mg-b-0">Daftar Prestasi Dosen</p>
        </div>
    </div>
    <div class="ml-auto">
        <button class="btn btn-teal btn-block mg-b-10 btn-add" data-toggle="modal" data-target="#modal-teach-acv" style="color:white"><i class="fa fa-plus mg-r-10"></i> Prestasi Dosen</button>
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
    <div class="widget-2">
        <div class="card shadow-base mb-3">
            <div class="card-body bd-color-gray-lighter">
                <table id="table_teacher_acv" class="table table-bordered mb-0 datatable" data-sort="desc">
                    <thead>
                        <tr>
                            <th class="text-center align-middle defaultSort" width="100">Tanggal Diperoleh</th>
                            <th class="text-center align-middle">Prestasi</th>
                            <th class="text-center align-middle">Tingkat</th>
                            <th class="text-center align-middle no-sort">Bukti<br>Pendukung</th>
                            <th class="text-center align-middle no-sort">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($achievement as $acv)
                        <tr>
                            <td class="text-center">{{ $acv->academicYear->tahun_akademik.' - '.$acv->academicYear->semester }}</td>
                            <td>{{ $acv->prestasi }}</td>
                            <td>{{ $acv->tingkat_prestasi }}</td>
                            <td class="text-center align-middle">
                                <a href="{{route('teacher.achievement.download',encode_id($acv->bukti_file))}}" target="_blank">
                                    {{$acv->bukti_nama}}
                                </a>
                            </td>
                            <td width="50" class="text-center">
                                <div class="btn-group" role="group">
                                    <button id="btn-action" type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div><span class="fa fa-caret-down"></span></div>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">
                                        <a class="dropdown-item btn-edit" href="#" data-id="{{ $acv->id }}" data-dest="{{ route('profile.achievement') }}">Sunting</a>
                                        <form method="POST">
                                            @method('delete')
                                            @csrf
                                            <input type="hidden" value="{{encrypt($acv->id)}}" name="_id">
                                            <a href="#" class="dropdown-item btn-delete" data-dest="{{ route('profile.achievement.delete') }}">Hapus</a>
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
</div>
@include('teacher-view.achievement.form')
@endsection

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection

@push('custom-js')
<script>
    $('#table_teacher_acv').on('click','.btn-edit',function(e){
        e.preventDefault();

        var id   = $(this).data('id');
        var url  = $(this).data('dest')+'/'+id+'/edit';
        // var url = base_url+'/teacher/achievement/'+id;

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                var option_ta     = $("<option selected></option>").val(data.id_ta).text(data.academic_year.tahun_akademik+' - '+data.academic_year.semester);
                var option_nidn   = $("<option selected></option>").val(data.nidn).text(data.teacher.nama+' ('+data.teacher.nidn+')');

                if($('#modal-teach-acv').find('select[name=nidn]').length) {
                    $('#selectProdi').attr('data-nidn',data.nidn);
                    $('#selectProdi').val(data.teacher.latest_status.study_program.kd_prodi);
                }

                $('#modal-teach-acv')
                    .find('input[name=_id]').val(id).end()
                    .find('input[name=prestasi]').val(data.prestasi).end()
                    .find('select[name=nidn]').val(null).html(option_nidn).trigger('change').end()
                    .find('select[name=id_ta]').append(option_ta).trigger('change').end()
                    .find('input[name=bukti_nama]').val(data.bukti_nama).end()
                    .find('button[type=submit]').attr('data-id',id);

                switch(data.tingkat_prestasi) {
                    case 'Wilayah':
                        $('input:radio[name=tingkat_prestasi][value="Wilayah"]').prop('checked',true);
                    break;
                    case 'Nasional':
                        $('input:radio[name=tingkat_prestasi][value="Nasional"]').prop('checked',true);
                    break;
                    case 'Internasional':
                        $('input:radio[name=tingkat_prestasi][value="Internasional"]').prop('checked',true);
                    break;
                }

                $('#modal-teach-acv').modal('toggle');
            }
        });
    });
</script>
@endpush
