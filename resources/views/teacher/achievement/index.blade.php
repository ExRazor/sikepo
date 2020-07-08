@extends('layouts.master')

@section('title', 'Prestasi Dosen')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('teacher-achievement') as $breadcrumb)
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
    @if (Auth::user()->role!='kajur')
    <div class="ml-auto">
        <button class="btn btn-teal btn-block btn-add" data-toggle="modal" data-target="#modal-teach-acv" style="color:white"><i class="fa fa-plus mg-r-10"></i> Prestasi Dosen</button>
    </div>
    @endif
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
    @if (Auth::user()->role!='kaprodi')
    <div class="row">
        <div class="col-12">
            <form action="{{route('ajax.teacher.achievement.filter')}}" id="filter-teacherAcv" data-token="{{encode_id(Auth::user()->role)}}" method="POST">
                <div class="row">
                    <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
                        <select class="form-control filter-box" name="kd_prodi">
                            <option value="">- Semua Program Studi -</option>
                            @foreach($studyProgram as $sp)
                            <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
    <div class="widget-2">
        <div class="card shadow-base mb-3">
            <div class="card-header">
                <h6 class="card-title">
                    <span class="nm_jurusan">
                    @if(Auth::user()->hasRole('kaprodi'))
                    {{ Auth::user()->studyProgram->nama }}

                    @else
                    {{ setting('app_department_name') }}
                    @endif
                     </span>
                </h6>
            </div>
            <div class="card-body bd-color-gray-lighter">
                <table id="table_teacher_acv" class="table display responsive nowrap" data-order='[[ 1, "desc" ]]' data-page-length='25' url-target="{{route('ajax.teacher.achievement.datatable')}}">
                    <thead>
                        <tr>
                            <th class="text-center align-middle ">Nama Dosen</th>
                            <th class="text-center align-middle" width="100">Tahun Diperoleh</th>
                            <th class="text-center align-middle">Prestasi</th>
                            <th class="text-center align-middle">Tingkat</th>
                            <th class="text-center align-middle no-sort">Bukti<br>Pendukung</th>
                            @if(Auth::user()->role!='kajur')
                            <th class="text-center align-middle no-sort">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div><!-- card-body -->
        </div>
    </div>
</div>
@include('teacher.achievement.form')
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net/js/dataTables.hideEmptyColumns.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('custom-js')
<script>
    var bahasa = {
            "sProcessing":   '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>',
            "sLengthMenu":   "Tampilan _MENU_ entri",
            "sZeroRecords":  "Tidak ditemukan data",
            "sInfo":         "Tampilan _START_ sampai _END_ dari _TOTAL_ entri",
            "sInfoEmpty":    "Tampilan 0 hingga 0 dari 0 entri",
            "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
            "sInfoPostFix":  "",
            'searchPlaceholder': 'Cari...',
            'sSearch': '',
            "sUrl":          "",
            "oPaginate": {
                "sFirst":    "Awal",
                "sPrevious": "Balik",
                "sNext":     "Lanjut",
                "sLast":     "Akhir"
            }
    };

    var table = $('#table_teacher_acv').DataTable({
                    autoWidth: false,
                    columnDefs: [ {
                        "targets"  : 'no-sort',
                        "orderable": false,
                    }],
                    language: bahasa,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: $('#table_teacher_acv').attr('url-target'),
                        type: "post",
                        data: function(d){
                            d.prodi  = $('select.filter-box[name=kd_prodi]').val();
                            d._token = $('meta[name="csrf-token"]').attr('content')
                        }
                    },
                    columns: [
                        { data: 'nama', },
                        { data: 'tahun', },
                        { data: 'prestasi', },
                        { data: 'tingkat_prestasi', },
                        { data: 'bukti', },
                        { data: 'aksi', }
                    ],
                    hideEmptyCols: [ 5 ],
                });

    $('.filter-box').bind("keyup change", function(){
        table.ajax.reload();
    });

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

                $('#modal-teach-acv')
                    .find('input[name=_id]').val(id).end()
                    .find('input[name=prestasi]').val(data.prestasi).end()
                    .find('select[name=nidn]').append(option_nidn).trigger('change').end()
                    .find('select[name=id_ta]').append(option_ta).trigger('change').end()
                    .find('input[name=bukti_nama]').val(data.bukti_nama).end()
                    .find('button[type=submit]').attr('data-id',id);

                if($('#modal-teach-acv').find('select[name=nidn]').length) {
                    $('#selectProdi').attr('data-nidn',data.nidn);
                    $('#selectProdi').val(data.teacher.study_program.kd_prodi).change();
                }

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
@endsection
