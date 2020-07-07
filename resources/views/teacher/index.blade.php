@extends('layouts.master')

@section('title', 'Data Dosen')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('teacher') as $breadcrumb)
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
        <i class="icon fa fa-chalkboard-teacher"></i>
        <div>
            <h4>Dosen Program Studi</h4>
            <p class="mg-b-0">Olah Data Dosen</p>
        </div>
    </div>
    <div class="ml-auto">
        @if(!Auth::user()->hasRole('kajur'))
        <a href="{{ route('teacher.list.create') }}" class="btn btn-teal btn-block" style="color:white"><i class="fa fa-plus mg-r-10"></i> Data Dosen</a>
        {{-- <a href="{{ route('teacher.import') }}" class="btn btn-primary btn-block mg-y-10" style="color:white"><i class="fa fa-file-import mg-r-10"></i> Import Data</a> --}}
        @endif
    </div>
</div>
<div class="br-pagebody">
    @if (session()->has('flash.message'))
        <div class="alert alert-{{ session('flash.class') }}" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ session('flash.message') }}
        </div>
    @endif
    @if(Auth::user()->role!='kaprodi')
    <div class="row mb-3">
        <div class="col-md-12">
            <form action="{{route('ajax.teacher.filter')}}" id="filter-teacher" data-token="{{encode_id(Auth::user()->role)}}" method="POST">
                <div class="row">
                    @if(Auth::user()->role!='kajur')
                    {{-- <div class="col-md mb-2">
                        <select id="fakultas" class="form-control" name="kd_jurusan" data-placeholder="Pilih Jurusan" required>
                            <option value="0">- Semua Jurusan -</option>
                            @foreach($faculty as $f)
                                @if($f->department->count())
                                <optgroup label="{{$f->nama}}">
                                    @foreach($f->department as $d)
                                    <option value="{{$d->kd_jurusan}}" {{ $d->kd_jurusan == setting('app_department_id') ? 'selected' : ''}}>{{$d->nama}}</option>
                                    @endforeach
                                </optgroup>
                                @endif
                            @endforeach
                        </select>
                    </div> --}}
                    @endif
                    <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
                        <div class="input-group">
                            <select class="form-control mr-3 filter-box" name="kd_prodi">
                                <option value="">- Semua Program Studi -</option>
                                @foreach($studyProgram as $sp)
                                <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                                @endforeach
                            </select>
                            <div>
                                {{-- <button type="submit" class="btn btn-purple btn-block " style="color:white">Cari</a> --}}
                            </div>
                        </div>
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
                <div class="table-responsive">

                </div>
                <table id="table_teacher" class="table" data-order='[[ 0, "asc" ]]' data-page-length='25' data-ajax="{{route('ajax.teacher.datatable')}}">
                    <thead>
                        <tr>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Program Studi</th>
                            <th class="text-center">Ikatan Kerja</th>
                            @if(!Auth::user()->hasRole('kajur'))
                            <th class="text-center no-sort">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div><!-- card-body -->
        </div>
    </div>
</div>
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

    var table = $('#table_teacher').DataTable({
                    responsive: true,
                    autoWidth: false,
                    columnDefs: [ {
                        "targets"  : 'no-sort',
                        "orderable": false,
                    }],
                    language: bahasa,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{route('ajax.teacher.datatable')}}?prodi=57201',
                        type: "GET",
                        data: function(d){
                            var prodi = $('select.filter-box[name=kd_prodi]').val();

                            d.prodi = prodi;
                        }
                    },
                    columns: [
                                { data: 'nama', },
                                { data: 'study_program', },
                                { data: 'ikatan_kerja', },
                                { data: 'aksi', }
                            ],
                });

    $('.filter-box').bind("keyup change", function(){
        // hehe = $('select.filter-box[name=kd_prodi]').val();
        // alert(hehe);
        table.draw();
    });



// $('form#filter-teacher').submit(function(e){
//     e.preventDefault();

//     var cont = $(this);
//     var btn  = cont.find('button[type=submit]');
//     var data = cont.serialize();
//     var url  = cont.attr('action');
//     var role = decode_id(cont.data('token'));
//     var opsi = cont.find('select[name=kd_jurusan] option:selected').text();

//     $.ajax({
//         url: url,
//         data: data,
//         type: 'POST',
//         async: true,
//         dataType: 'json',
//         beforeSend: function() {
//             btn.addClass('disabled');
//             btn.html('<i class="fa fa-spinner fa-spin"></i>');
//         },
//         success: function (data) {
//             $('span.nm_jurusan').text(opsi);

//             var tabel = $('#table_teacher');
//             var html = '';

//             tabel.show();

//             if(data.length > 0) {
//                 $.each(data, function(i){

//                     var nidn        = data[i].nidn;
//                     var nama        = data[i].nama;
//                     var nip         = data[i].nip;
//                     var prodi       = data[i].study_program.nama;
//                     var jurusan     = data[i].study_program.department.nama;
//                     var fakultas    = data[i].study_program.department.faculty.singkatan;
//                     var ikatan      = data[i].ikatan_kerja;
//                     var jabatan     = data[i].jabatan_akademik;
//                     var aksi;

//                     if(role!='kajur') {
//                         aksi = '<td class="text-center no-sort" width="50">'+
//                                     '<div class="btn-group" role="group">'+
//                                         '<button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
//                                             '<div><span class="fa fa-caret-down"></span></div>'+
//                                         '</button>'+
//                                         '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">'+
//                                             '<a class="dropdown-item" href="'+base_url+'/teacher/list/'+encode_id(nidn)+'/edit">Sunting</a>'+
//                                             '<form method="POST">'+
//                                                 '<input type="hidden" value="'+encode_id(nidn)+'" name="id">'+
//                                                 '<button type="submit" class="dropdown-item btn-delete" data-dest="/teacher/list">Hapus</button>'+
//                                             '</form>'+
//                                         '</div>'+
//                                     '</div>'+
//                                 '</td>'
//                     }

//                     html += '<tr>'+
//                                 '<td><a href="'+base_url+'/teacher/list/'+encode_id(nidn)+'">'+nidn+'</a></td>'+
//                                 '<td>'+
//                                     nama+'<br>'+
//                                     '<small>NIP. '+nip+'</small>'+
//                                 '</td>'+
//                                 '<td>'+
//                                     prodi+'<br>'+
//                                     '<small>'+fakultas+' - '+jurusan+'</small>'+
//                                 '</td>'+
//                                 '<td>'+ikatan+'</td>'+
//                                 '<td>'+jabatan+'</td>'+
//                                 aksi+
//                             '</tr>';

//                 })
//             }
//             // tabel.dataTable().fnDestroy();
//             tabel.DataTable().clear().destroy();
//             tabel.find('tbody').html(html);
//             tabel.DataTable(datatable_opt);

//             btn.removeClass('disabled');
//             btn.html('Cari');
//         },
//         error: function (request) {
//             btn.removeClass('disabled');
//             btn.html('Cari');
//         }
//     });
// });
</script>
@endsection
