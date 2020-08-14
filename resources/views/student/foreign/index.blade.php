@extends('layouts.master')

@section('title', 'Mahasiswa Asing')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('student-foreign') as $breadcrumb)
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
        <i class="icon fa fa-flag-usa"></i>
        <div>
            <h4>Mahasiswa Asing</h4>
            <p class="mg-b-0">Olah Data Mahasiswa Asing</p>
        </div>
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
    @if(!Auth::user()->hasRole('kaprodi'))
    <div class="row">
        <div class="col-sm-3 col-md-5 col-lg-3 mb-2">
            <input id="nm_jurusan" type="hidden" value="{{setting('app_department_name')}}">
            <select id="kd_prodi_filter" class="form-control filter-box">
                <option value="">- Pilih Program Studi -</option>
                @foreach($studyProgram as $sp)
                <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endif
    <div class="row widget-2">
        <div class="order-2 order-md-1 {{Auth::user()->hasRole('kajur') ? 'col-md-12' : 'col-md-8' }}">
            <div class="card shadow-base mb-3">
                <div class="card-header nm_jurusan">
                    <h6 class="card-title">
                        @if(Auth::user()->hasRole('kaprodi'))
                        {{ Auth::user()->studyProgram->nama }}
                        @else
                        {{ setting('app_department_name') }}
                        @endif
                    </h6>
                </div>
                <div class="card-body bd-color-gray-lighter">
                    <table id="table_student_foreign" class="table display responsive nowrap" data-order='[[ 0, "asc" ]]' url-target="{{route('ajax.student.foreign.datatable')}}">
                        <thead>
                            <tr>
                                <th class="text-center">Mahasiswa</th>
                                <th class="text-center">Asal Negara</th>
                                <th class="text-center">Durasi</th>
                                <th class="text-center no-sort" width="50">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div><!-- card-body -->
            </div>
        </div>
        @if(!Auth::user()->hasRole('kajur'))
        <div class="col-md-4 order-1 order-md-2 widget-2">
            <div class="card shadow-base mb-3">
                <div class="card-header">
                    <h6 class="card-title"><span class="title-action">Tambah</span> Mahasiswa Asing</h6>
                </div>
                <div class="card-body bd-color-gray-lighter">
                    <form id="form-student-foreign" enctype="multipart/form-data">
                        @include('layouts.alert')
                        @isset(Auth::user()->kd_prodi)
                        <input type="hidden" name="kd_prodi" value="{{Auth::user()->kd_prodi}}">
                        @endisset
                        <div class="row mg-t-20">
                            <label class="col-sm-4 form-control-label">Mahasiswa: <span class="tx-danger">*</span></label>
                            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                                <input type="hidden" name="_id">
                                <select class="form-control select-mhs" name="nim" required></select>
                            </div>
                        </div>
                        <div class="row mg-t-20">
                            <label class="col-sm-4 form-control-label">Asal Negara: <span class="tx-danger">*</span></label>
                            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                                <input type="text" name="asal_negara" class="form-control" placeholder="Isikan asal negara mahasiswa" required>
                            </div>
                        </div>
                        <div class="row mg-t-20 category-description">
                            <label class="col-sm-4 form-control-label">Durasi:</label>
                            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                                <select name="durasi" class="form-control" required>
                                    <option value="">- Pilih Durasi Studi -</option>
                                    <option value="Full-time">Full-time (Penuh Waktu)</option>
                                    <option value="Part-time">Part-time (Paruh Waktu)</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mg-t-20">
                            <div class="offset-sm-4 col-sm-8 mg-t-10 mg-sm-t-0">
                                <button type="submit" class="btn btn-info mr-2 btn-save" value="post" data-dest="{{route('student.foreign.store')}}">Simpan</button>
                                <button class="btn btn-secondary btn-add">Reset</button>
                            </div>
                        </div>
                    </form>
                </div><!-- card-body -->
            </div>
        </div>
        @endif
    </div>
</div>
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
    var table = $('#table_student_foreign');
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
                    d.prodi   = $('select#kd_prodi_filter').val();
                    d._token = $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                        { data: 'nama', },
                        { data: 'asal_negara', },
                        { data: 'durasi', },
                        { data: 'aksi', }
                    ],
            columnDefs: [
                {
                    targets: 3,
                    orderable: false
                },
                {
                    targets: [3],
                    className: 'text-center'
                },
            ],
            hideEmptyCols: [ 3 ],
            autoWidth: false,
            language: bahasa
        })
    }

    table.on('click','.btn-edit',function(){

        var id  = $(this).data('id');
        var url = $(this).attr('url-target');

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                var option = $("<option selected></option>").val(data.nim).text(data.student.nama);

                $('#form-student-foreign')
                    .find('input[name=_id]').val(id).end()
                    .find('select[name=nim]').append(option).trigger('change').end()
                    .find('input[name=asal_negara]').val(data.asal_negara).end()
                    .find('select[name=durasi]').val(data.durasi).end()
                    .find('button[type=submit]').attr('data-id',id).end()

            }
        });
    })

    $('#form-student-foreign').on('click','.btn-add',function(e){
        $('#form-student-foreign').trigger('reset');
        $('.select-mhs').val(null).trigger('change');
    });

</script>
@endpush
