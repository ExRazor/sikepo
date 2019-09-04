@extends('layouts.master')

@section('title', 'Data Program Studi')

@section('content')
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        @foreach (Breadcrumbs::generate( isset($data) ? 'collaboration-edit' : 'collaboration-add' ) as $breadcrumb)
        <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
        @endforeach
    </nav>
</div>

<div class="br-pagetitle">
        <i class="icon ion-calendar"></i>
        @if(isset($data))
        <div>
            <h4>Sunting</h4>
            <p class="mg-b-0">Sunting Data Kerja Sama Prodi</p>
        </div>
        @else
        <div>
                <h4>Tambah</h4>
                <p class="mg-b-0">Tambah Data Kerja Sama Prodi</p>
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
    <div class="card bd-0">
        <div class="card-body bd bd-t-0 rounded-bottom">
            <div class="form-layout form-layout-1">
                <div class="row mg-b-25">
                    <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Firstname: <span class="tx-danger">*</span></label>
                        <input class="form-control" type="text" name="firstname" value="John Paul" placeholder="Enter firstname">
                    </div>
                    </div><!-- col-4 -->
                    <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Lastname: <span class="tx-danger">*</span></label>
                        <input class="form-control" type="text" name="lastname" value="McDoe" placeholder="Enter lastname">
                    </div>
                    </div><!-- col-4 -->
                    <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Email address: <span class="tx-danger">*</span></label>
                        <input class="form-control" type="text" name="email" value="johnpaul@yourdomain.com" placeholder="Enter email address">
                    </div>
                    </div><!-- col-4 -->
                    <div class="col-lg-8">
                    <div class="form-group mg-b-10-force">
                        <label class="form-control-label">Mail Address: <span class="tx-danger">*</span></label>
                        <input class="form-control" type="text" name="address" value="Market St. San Francisco" placeholder="Enter address">
                    </div>
                    </div><!-- col-8 -->
                    <div class="col-lg-4">
                    <div class="form-group mg-b-10-force">
                        <label class="form-control-label">Country: <span class="tx-danger">*</span></label>
                        <select class="form-control select2 select2-hidden-accessible" data-placeholder="Choose country" data-select2-id="1" tabindex="-1" aria-hidden="true">
                        <option label="Choose country" data-select2-id="3"></option>
                        <option value="USA">United States of America</option>
                        <option value="UK">United Kingdom</option>
                        <option value="China">China</option>
                        <option value="Japan">Japan</option>
                        </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="2" style="width: 272.328px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-hj84-container"><span class="select2-selection__rendered" id="select2-hj84-container" role="textbox" aria-readonly="true"><span class="select2-selection__placeholder">Choose one</span></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                    </div>
                    </div><!-- col-4 -->
                </div><!-- row -->

                <div class="form-layout-footer">
                    <button class="btn btn-info">Submit Form</button>
                    <button class="btn btn-secondary">Cancel</button>
                </div><!-- form-layout-footer -->
            </div>
        </div><!-- card-body -->
    </div>
    @include('admin.academic-year.form');
</div>
@endsection

@section('js')
@endsection
