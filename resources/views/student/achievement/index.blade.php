@extends('layouts.master')

@section('title', 'Prestasi Mahasiswa')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('student-achievement') as $breadcrumb)
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
            <h4>Prestasi Mahasiswa</h4>
            <p class="mg-b-0">Daftar Prestasi Mahasiswa</p>
        </div>
    </div>
    @if (!Auth::user()->hasRole('kajur'))
    <div class="ml-auto">
        <button class="btn btn-teal btn-block mg-b-10 btn-add" data-toggle="modal" data-target="#modal_student_acv" style="color:white"><i class="fa fa-plus mg-r-10"></i> Prestasi</button>
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
    <div class="row">
        @if(!Auth::user()->hasRole('kaprodi'))
        <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
            <input id="nm_jurusan" type="hidden" value="{{setting('app_department_name')}}">
            <select id="kd_prodi_filter" class="form-control filter-box">
                <option value="">- Pilih Program Studi -</option>
                @foreach($studyProgram as $sp)
                <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
            <select id="kegiatan_tingkat_filter" class="form-control filter-box">
                <option value="">- Pilih Tingkat Kegiatan -</option>
                <option value="Wilayah">Wilayah</option>
                <option value="Nasional">Nasional</option>
                <option value="Internasional">Internasional</option>
            </select>
        </div>
        <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
            <select id="prestasi_jenis_filter" class="form-control filter-box">
                <option value="">- Pilih Jenis Prestasi -</option>
                <option value="Akademik">Akademik</option>
                <option value="Non Akademik">Non Akademik</option>
            </select>
        </div>
    </div>
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
                <table id="table_student_acv" class="table display responsive nowrap" data-order='[[ 0, "desc" ]]' url-target="{{route('ajax.student.achievement.datatable')}}">
                    <thead>
                        <tr>
                            <th class="text-center align-middle defaultSort" width="100">Tahun Diperoleh</th>
                            <th class="text-center align-middle">Nama Mahasiswa</th>
                            <th class="text-center align-middle">Nama Kegiatan</th>
                            <th class="text-center align-middle">Tingkat Kegiatan</th>
                            <th class="text-center align-middle no-sort">Prestasi yang Diraih</th>
                            <th class="text-center align-middle no-sort">Jenis Prestasi</th>
                            <th class="text-center align-middle no-sort">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div><!-- card-body -->
        </div>
    </div>
</div>
@if (!Auth::user()->hasRole('kajur'))
    @include('student.achievement.form')
@endif
@endsection

@section('style')
<link href="{{ asset ('assets/lib') }}/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{ asset ('assets/lib') }}/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('js')
<script src="{{asset('assets/lib')}}/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net/js/dataTables.hideEmptyColumns.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('assets/lib')}}/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
@endsection

@push('custom-js')
<script>
    var table = $('#table_student_acv');
    var modal = $('#modal_student_acv');
    datatable(table);

    $('.filter-box').bind("keyup change", function(){
        table.DataTable().clear().destroy();
        datatable(table);
    });

    function datatable(table_ehm)
    {
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

        table_ehm.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: table_ehm.attr('url-target'),
                type: "post",
                data: function(d){
                    d.kd_prodi          = $('select#kd_prodi_filter').val();
                    d.kegiatan_tingkat  = $('select#kegiatan_tingkat_filter').val();
                    d.prestasi_jenis    = $('select#prestasi_jenis_filter').val();
                    d._token            = $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                        { data: 'tahun', },
                        { data: 'mahasiswa', },
                        { data: 'kegiatan_nama', },
                        { data: 'kegiatan_tingkat', },
                        { data: 'prestasi', },
                        { data: 'prestasi_jenis', },
                        { data: 'aksi', }
                    ],
            columnDefs: [
                {
                    targets: 3,
                    orderable: false,
                    className: 'text-center'
                },
            ],
            hideEmptyCols: [ 3 ],
            autoWidth: false,
            language: bahasa
        })
    }

    modal.on('shown.bs.modal', function () {
        $(this).find('select#selectProdi').trigger('change');
    })

    modal.on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
        $(this).find('select[name=nidn]').children('option:not(:first)').remove();
        $(this).find('select[name=nidn]').prop('disabled',true);
        $(this).find('select[name=id_ta] option').remove().trigger('change');
        $(this).find('select[name=nim] option').remove().trigger('change');
    })

    modal.on('change','select#selectProdi', function() {
        var cont    = modal;
        var target  = cont.find('.select-mhs-prodi');
        var prodi   = $(this).val();

        if(prodi=='' || prodi==null) {
            target.prop('disabled',true);
            target.prop('required',false);
        } else {
            target.prop('disabled',false);
            target.prop('required',true);

            target.select2({
                language: "id",
                width: '100%',
                allowClear: true,
                placeholder: "Masukkan nim/nama mahasiswa",
                ajax: {
                    dataType: 'json',
                    url: base_url+'/ajax/student/select_by_studyProgram',
                    delay: 800,
                    data: function(params) {
                        return {
                            prodi: prodi,
                            cari: params.term
                        }
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                }
            });
        }

    })

    table.on('click','.btn-edit',function(e){
        e.preventDefault();

        var id  = $(this).data('id');
        var url = $(this).attr('url-target');

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                var option_ta     = $("<option selected></option>").val(data.id_ta).text(data.academic_year.tahun_akademik+' - '+data.academic_year.semester);
                var option_nim    = $("<option selected></option>").val(data.nim).text(data.student.nama+' ('+data.student.nim+')');

                modal
                    .find('input[name=_id]').val(id).end()
                    .find('select#selectProdi').val(data.student.kd_prodi).trigger('change').end()
                    .find('select[name=nim]').append(option_nim).trigger('change').end()
                    .find('select[name=id_ta]').append(option_ta).trigger('change').end()
                    .find('input[name=kegiatan_nama]').val(data.kegiatan_nama).end()
                    .find('input[name=prestasi]').val(data.prestasi).end()

                switch(data.kegiatan_tingkat) {
                    case 'Wilayah':
                        $('input:radio[name=kegiatan_tingkat][value="Wilayah"]').prop('checked',true);
                    break;
                    case 'Nasional':
                        $('input:radio[name=kegiatan_tingkat][value="Nasional"]').prop('checked',true);
                    break;
                    case 'Internasional':
                        $('input:radio[name=kegiatan_tingkat][value="Internasional"]').prop('checked',true);
                    break;
                }
                switch(data.prestasi_jenis) {
                    case 'Akademik':
                        $('input:radio[name=prestasi_jenis][value="Akademik"]').prop('checked',true);
                    break;
                    case 'Non Akademik':
                        $('input:radio[name=prestasi_jenis][value="Non Akademik"]').prop('checked',true);
                    break;
                }

                modal.modal('toggle');
            }
        });
    });

</script>
@endpush
