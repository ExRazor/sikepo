@extends('layouts.master')

@section('title', 'Data Fakultas')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('department') as $breadcrumb)
            @isset($breadcrumb->url)
                <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            @else
                <span class="breadcrumb-item">{{ $breadcrumb->title }}</span>
            @endisset
        @endforeach
    </nav>
</div>
<div class="br-pagetitle">
    <i class="icon ion-calendar"></i>
    <div>
        <h4>Data Jurusan</h4>
        <p class="mg-b-0">Olah Data Jurusan</p>
    </div>
    <div class="ml-auto">
        <button class="btn btn-teal btn-block mg-b-10 btn-add btn-add-department" style="color:white" data-toggle="modal" data-target="#modal-master-department"><i class="fa fa-plus mg-r-10"></i> Jurusan</button>
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
    <div class="widget-2">
        <div class="card mb-3">
            <div class="card-header bd rounded-top bd-color-gray-lighter">
                <h6 class="card-title">Tampilkan</h6>
            </div>
            <div class="card-body bd bd-t-0 rounded-bottom bd-color-gray-lighter">
                <div class="row">
                    <div class="col-md-10">
                        <form action="{{route('master.department.show')}}" id="filter-department" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('post')
                            <div class="form-group row mb-3">
                                <label class="col-3 form-control-label">Fakultas:</label>
                                <div class="col-6">
                                    <select id="fakultas" class="form-control" name="id_fakultas" data-placeholder="Pilih Prodi" required>
                                        <option value="0">Semua Fakultas</option>
                                        @foreach ($faculty as $f)
                                        <option value="{{$f->id}}">{{$f->nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-6 offset-3">
                                    <button type="submit" class="btn btn-info">Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- card-body -->
        </div>
        <div class="card mb-3">
            <div class="card-header bd rounded-top bd-color-gray-lighter">
                <h6 class="card-title">
                    <span class="nm_fakultas">-</span>  ||  Total: <span class="tot_jurusan">-</span> Jurusan
                </h6>
            </div>
            <div class="card-body bd bd-t-0 rounded-bottom bd-color-gray-lighter">
                <table id="table_department" class="table table-striped">
                    <thead>
                        <tr>
                            <th width="25">Kode</th>
                            <th width="250">Nama Jurusan</th>
                            <th width="300">Asal Fakultas</th>
                            <th>Nama Kajur</th>
                            <th class="text-center" width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan=5 class="text-center">PILIH FAKULTAS</td>
                        </tr>
                    </tbody>
                </table>
            </div><!-- card-body -->
        </div>
    </div>
</div>
@include('department.form');
<script>
    $('#filter-department').submit(function(e){
        e.preventDefault();
        $('#table_department tbody').empty();

        var btn  = $(this).find('button.btn-info');
        var data = $(this).serialize();
        var url  = $(this).attr('action');
        var opsi = $(this).find('select[name=id_fakultas] option:selected').text();

        $.ajax({
            url: url,
            data: data,
            type: 'POST',
            dataType: 'json',
            beforeSend: function() {
                btn.addClass('disabled');
                btn.html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function (data) {
                $('span.nm_fakultas').text(opsi);
                $('span.tot_jurusan').text(data.length);
                var html = '';

                if(data.length > 0) {
                    $.each(data, function(i){

                        var kd_jurusan   = data[i].kd_jurusan;
                        var nama         = data[i].nama;
                        var fakultas     = data[i].faculty.nama;
                        var nip_kajur    = data[i].nip_kajur;
                        var nm_kajur     = data[i].nm_kajur;

                        html += '<tr>'+
                                    '<td>'+kd_jurusan+'</td>'+
                                    '<td>'+nama+'</td>'+
                                    '<td>'+fakultas+'</td>'+
                                    '<td>'+
                                        nm_kajur+'<br>'+
                                        '<small>'+nip_kajur+'</small>'+
                                    '</td>'+
                                    '<td class="text-center">'+
                                        '<div class="btn-group hidden-xs-down">'+
                                            '<button class="btn btn-primary btn-sm btn-icon rounded-circle mg-r-5 mg-b-10 btn-edit btn-edit-department" data-id="'+kd_jurusan+'"><div><i class="fa fa-pencil-alt"></i></div></button>'+
                                            '<form method="POST">'+
                                                '<input type="hidden" value="'+kd_jurusan+'" name="_id">'+
                                                '<button type="submit" class="btn btn-danger btn-sm btn-icon rounded-circle mg-r-5 mg-b-10 btn-delete" data-dest="{{ route("master.department.delete") }}">'+
                                                    '<div><i class="fa fa-trash"></i></div>'+
                                                '</button>'+
                                            '</form>'+
                                        '</div>'+
                                    '</td>'+
                                '</tr>'
                    })
                    $('#table_department tbody').html(html);
                } else {
                    html = '<tr><td colspan="5" class="text-center">BELUM ADA DATA</td></tr>';

                    $('#table_department tbody').html(html);
                }
                btn.removeClass('disabled');
                btn.html('Cari');
            },
            error: function (request) {
                btn.removeClass('disabled');
                btn.html('Cari');
            }
        });
    })
</script>
@endsection

@section('js')
@endsection
