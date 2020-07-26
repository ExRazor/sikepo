@extends('layouts.master')

@section('title', 'Kategori Dana')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate('funding-category') as $breadcrumb)
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
        <i class="icon fa fa-paperclip"></i>
        <div>
            <h4>Kategori Pendanaan</h4>
            <p class="mg-b-0">Olah Kategori Pendanaan</p>
        </div>
    </div>
</div>

<div class="br-pagebody">
    <div class="alert alert-warning" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong class="d-block d-sm-inline-block-force">Hati-Hati!</strong><br>
        Data Kategori yang disunting atau pun dihapus akan berdampak langung pada Data Keuangan Program Studi dan Fakultas.<br>
        Jika data kategori dihapus, maka data keuangan yang berkaitan dengan kategori tersebut akan ikut terhapus.<br>
    </div>
    @if (session()->has('flash.message'))
        <div class="alert alert-{{ session('flash.class') }}" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ session('flash.message') }}
        </div>
    @endif
    <div class="row widget-2">
        <div class="col-md-8 order-2 order-md-1 mb-3">
            <div class="card shadow-base">
                <div class="card-header">
                        <h6 class="card-title">Daftar Kategori</span></h6>
                    </div>
                <div class="card-body bd-color-gray-lighter">
                    <table id="table-fundCat" class="table table-striped">
                        <thead>
                            <tr>
                                <th width="25">#</th>
                                <th width="350">Nama Kategori</th>
                                <th>Jenis & Deskripsi</th>
                                <th class="text-center" width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($category as $c)
                                <tr>
                                    <th scope="row" style="vertical-align:middle">{{ $loop->iteration }}</th>
                                    <td>{{isset($c->id_parent) ? '——' : ''}} {{$c->nama}}</td>
                                    <td>{{$c->jenis}}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button class="btn btn-primary btn-sm btn-icon rounded-circle mg-r-5 mg-b-10 btn-edit" data-id="{{ encrypt($c->id) }}"><div><i class="fa fa-pencil-alt"></i></div></button>
                                            <form method="POST">
                                                <input type="hidden" value="{{encrypt($c->id)}}" name="_id">
                                                <button type="submit" class="btn btn-danger btn-sm btn-icon rounded-circle mg-r-5 mg-b-10 btn-delete" data-dest="{{ route('master.funding-category.delete') }}">
                                                    <div><i class="fa fa-trash"></i></div>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @if($c->children)
                                    @foreach($c->children as $child)
                                    <tr>
                                        <th scope="row" style="vertical-align:middle"></th>
                                        <td colspan="2">
                                            —— {{$child->nama}} <br>
                                            <small>( {{$child->deskripsi}} )</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button class="btn btn-primary btn-sm btn-icon rounded-circle mg-r-5 mg-b-10 btn-edit" data-id="{{ encrypt($child->id) }}"><div><i class="fa fa-pencil-alt"></i></div></button>
                                                <form method="POST">
                                                    <input type="hidden" value="{{encrypt($child->id)}}" name="_id">
                                                    <button type="submit" class="btn btn-danger btn-sm btn-icon rounded-circle mg-r-5 mg-b-10 btn-delete" data-dest="{{ route('master.funding-category.delete') }}">
                                                        <div><i class="fa fa-trash"></i></div>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div><!-- card-body -->
            </div>
        </div>
        <div class="col-md-4 order-1 order-md-2 mb-3 widget-2">
            <div class="card shadow-base">
                <div class="card-header">
                    <h6 class="card-title"><span class="title-action">Tambah</span> Kategori</h6>
                </div>
                <div class="card-body bd-color-gray-lighter">
                    <form id="form-fundCat" enctype="multipart/form-data">
                        <div class="alert alert-danger" style="display:none">
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                        <div class="row mg-t-20 select_parent">
                            <label class="col-sm-3 form-control-label">Induk:</label>
                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                <input type="hidden" name="_id">
                                <select class="form-control" name="id_parent">
                                    <option value="">- Pilih Induk Kategori -</option>
                                    @foreach($category as $c)
                                        <option value="{{ $c->id }}">{{ $c->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mg-t-20 category-type">
                            <label class="col-sm-3 form-control-label">Jenis: <span class="tx-danger">*</span></label>
                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                <select class="form-control" name="jenis" required>
                                    <option value="">- Pilih Jenis Dana -</option>
                                    <option>Operasional</option>
                                    <option>Penelitian dan Pengabdian</option>
                                    <option>Investasi dan Sarana</option>
                                </select>
                                <input type="hidden" name="jenis" disabled>
                            </div>
                        </div>
                        <div class="row mg-t-20">
                            <label class="col-sm-3 form-control-label">Nama: <span class="tx-danger">*</span></label>
                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                <input type="text" name="nama" class="form-control" placeholder="Isikan nama kategori" required>
                            </div>
                        </div>
                        <div class="row mg-t-20 category-description" style="display:none">
                            <label class="col-sm-3 form-control-label">Deskripsi:</label>
                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                <textarea rows="5" name="deskripsi" class="form-control" placeholder="Isikan deskripsi kategori"></textarea>
                            </div>
                        </div>
                        <div class="row mg-t-20">
                            <div class="offset-sm-3 col-sm-9 mg-t-10 mg-sm-t-0">
                                <button type="submit" class="btn btn-info mr-2 btn-save" value="post" data-dest="{{route('master.funding-category.store')}}">Simpan</button>
                                <button class="btn btn-secondary btn-add">Reset</button>
                            </div>
                        </div>
                    </form>
                </div><!-- card-body -->
            </div>
        </div>
    </div>

</div>
{{-- @include('funding.category.form'); --}}
@endsection

@push('custom-js')
    <script>
        $('#table-fundCat').on('click','.btn-edit',function(){
            var id  = $(this).data('id');
            var url = base_url+'/ajax/funding/category/'+id;

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#form-fundCat')
                        .find('input[name=_id]').val(id).end()
                        .find('select[name=id_parent]').val(data.id_parent).end()
                        .find('input[name=nama]').val(data.nama).end()
                        .find('textarea[name=deskripsi]').val(data.deskripsi).end()

                    if(data.id_parent) {
                        $('#form-fundCat').find('.select_parent').show()
                        $('#form-fundCat').find('.category-description').show();
                        $('#form-fundCat').find('.category-description').find('textarea').prop('disabled',false);
                        $('#form-fundCat').find('select[name=jenis]').prop('disabled',true).val(data.jenis);
                        $('#form-fundCat').find('input[name=jenis]').prop('disabled',false).val(data.jenis);
                    } else {
                        $('#form-fundCat').find('.select_parent').hide()
                        $('#form-fundCat').find('.category-description').hide();
                        $('#form-fundCat').find('.category-description').find('textarea').prop('disabled',true);
                        $('#form-fundCat').find('input[name=jenis]').prop('disabled',true).removeAttr('value');
                        $('#form-fundCat').find('select[name=jenis]').prop('disabled',false).val(data.jenis);
                    }

                }
            });
            })

            $('#form-fundCat')
            .on('change','select[name=id_parent]',function(){
                var form  = $('#form-fundCat')
                var value = $(this).val();

                if(value) {
                    $.ajax({
                        url: base_url+'/ajax/funding/category/select/'+value,
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            form.find('.category-description').show();
                            form.find('.category-description').find('textarea').prop('disabled',false);
                            form.find('.category-type').find('select').prop('disabled',true);
                            form.find('.category-type').find('select').val(data);
                            form.find('input[name=jenis]').prop('disabled',false);
                            form.find('input[name=jenis]').val(data);
                        }
                    });
                } else {
                    form.find('.category-description').hide();
                    form.find('.category-description').find('textarea').prop('disabled',true);
                    form.find('input[name=jenis]').prop('disabled',true)
                    form.find('input[name=jenis]').removeAttr("value");
                    form.find('.category-type').find('select').prop('disabled',false);
                    form.find('.category-type').find('select').val("");

                }
            })
            .on('click','.btn-add',function(e){
                var form  = $('#form-fundCat')
                form.trigger('reset');
                form.find('.select_parent').show()
                form.find('select[name=id_parent]').trigger('change')

            }).end()
    </script>
@endpush
