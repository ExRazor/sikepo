<div id="modal-setting-user" class="modal fade effect-slide-in-right">
    <form method="POST" enctype="multipart/form-data" data-parsley-validate>
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content bd-0 tx-14 modal-form">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-16 mg-b-0 tx-uppercase tx-inverse tx-bold"><span class="title-action"></span> User</h6>
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
                        <label class="col-sm-3 form-control-label">Nama Lengkap: <span class="tx-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="hidden" name="id">
                            <input type="text" class="form-control" name="name" placeholder="Masukkan nama lengkap" required>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label">Username: <span class="tx-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="username" placeholder="Masukkan username" required>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label">Hak Akses: <span class="tx-danger">*</span></label>
                        <div class="col-sm-8">
                            <div id="role">
                                <label class="rdiobox rdiobox-inline mb-0 mg-r-20">
                                    <input name="role" type="radio" value="admin" required>
                                    <span>Admin</span>
                                </label>
                                <label class="rdiobox rdiobox-inline mb-0 mg-r-20">
                                    <input name="role" type="radio" value="kajur" required>
                                    <span>Kajur</span>
                                </label>
                                <label class="rdiobox rdiobox-inline mb-0 mg-r-20">
                                    <input name="role" type="radio" value="kaprodi" required>
                                    <span>Kaprodi</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mg-t-20">
                        <label class="col-sm-3 form-control-label">Program Studi: <span class="tx-danger">*</span></label>
                        <div class="col-sm-8">
                            <select id="kd_prodi" class="form-control" name="kd_prodi" disabled>
                                <option value="">- Pilih Program Studi -</option>
                                @foreach ($studyProgram as $sp)
                                <option value="{{$sp->kd_prodi}}">{{$sp->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium btn-save" value="post" data-dest="{{route('setting.user.store')}}">
                        Simpan
                    </button>
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </form>
</div><!-- modal -->
@push('custom-js')
    <script>
        $('#modal-setting-user').on('click','button#generatePassword',function(e){
            e.preventDefault();

            var formPass = $('#modal-setting-user').find('input[name=password]');
            var password = rand_password();

            formPass.val(password);
        })

        $('#modal-setting-user').on('change','input[name=role]',function(){
            var selectProdi = $('#modal-setting-user').find('select#kd_prodi');

            if ($(this).is(':checked') && $(this).val() == 'kaprodi') {
                selectProdi.prop('disabled',false);
                selectProdi.prop('required',true);
            } else {
                selectProdi.prop('disabled',true);
                selectProdi.prop('required',false);
            }
        })
    </script>
@endpush
