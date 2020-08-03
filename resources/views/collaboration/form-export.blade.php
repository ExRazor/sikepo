<div id="modal-export-collaboration" class="modal fade effect-slide-in-right">
    <form action="{{route('collaboration.export')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold">Ekspor Data Pengabdian</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <div class="alert alert-danger" style="display:none">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label">Periode:<span class="tx-danger">*</span></label>
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <select name="periode_awal" class="form-control" required>
                                        <option value="">- Periode Awal -</option>
                                        @foreach($periodeTahun as $pt)
                                        <option value="{{$pt->tahun_akademik}}">{{$pt->tahun_akademik}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select name="periode_akhir" class="form-control">
                                        <option value="">- Periode Akhir -</option>
                                        @foreach($periodeTahun as $pt)
                                        <option value="{{$pt->tahun_akademik}}">{{$pt->tahun_akademik}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label">Program Studi: <span class="tx-danger">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-control" name="kd_prodi">
                                <option value="">- Semua -</option>
                                @foreach ($studyProgram as $sp)
                                <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">
                        Ekspor
                    </button>
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium btn-cancel" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </form>
</div><!-- modal -->
@push('custom-js')

@endpush
