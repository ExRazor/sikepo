// URL
var base_url = window.location.origin;
var host = window.location.host;
var pathArray = window.location.pathname.split( '/' );

$(document).ready(function() {

    //Ajax Setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //Datepicker
    $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });

    //Select 2
    if($().select2) {
        $('.select2').select2({
          minimumResultsForSearch: Infinity,
          placeholder: 'Pilih satu',
          width: '100%'
        });

        // Select2 by showing the search
        $('.select2-show-search').select2({
          minimumResultsForSearch: ''
        });

        // Select2 with tagging support
        $('.select2-tag').select2({
          tags: true,
          tokenSeparators: [',', ' ']
        });
    }

    //Tab
    $('a.tab-link').click(function() {
        location.hash = $(this).attr('href');
        $(window).scrollTop();
    })
    var hash = location.hash;
    if (hash) {
        $('a.tab-link[href="' + hash + '"]').click();
    } else {
        $("a.tab-link").first().click();

    }

    //Modal
    $('.modal').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
        $('.alert-danger').hide();
        $('input, select').removeClass('is-invalid');
        $(this).find('input[name^=_id]').removeAttr('value');
    })

    /********************************* BUTTON / TOMBOL *********************************/

    //Add Button
    $('.btn-add').click(function(e) {
        e.preventDefault();

        $('span.title-action').text('Tambah');
        $('.btn-save').val('post');
    });

    //Edit Button
    $(document).on('click','.btn-edit',function(e){
        e.preventDefault();

        $('span.title-action').text('Sunting');
        $('.btn-save').val('put');
    });

    //Save Button
    $(document).on('click','.btn-save',function(e) {
        e.preventDefault();

        var cont    = $(this);
        var action  = cont.val();
        var url     = cont.data('dest');
        var modal   = cont.closest('.modal');
        var data    = new FormData(cont.closest('form')[0]);
        data.append('_method', action);

        // for (var pair of data.entries()) {
        //     console.log(pair[0]+ ', ' + pair[1]);
        // }

        $.ajax({
            url: url,
            data: data,
            method: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function() {
                cont.addClass('disabled');
                $('.btn-cancel').addClass('disabled');
                cont.html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function (state) {

                modal.modal('toggle');

                Swal.fire({
                    title: state.title,
                    text: state.message,
                    type: state.type,
                    timer: 1500,
                    onClose: () => {
                        location.reload();
                    }
                });

            },
            error: function (request) {
                json = $.parseJSON(request.responseText);
                $('.alert-danger').html('');
                $.each(json.errors, function(key, value){
                    $('.alert-danger').show();
                    $('.alert-danger').append('<span>'+value+'</span><br>');
                    $('[name='+key+']').addClass('is-invalid');
                });

                cont.removeClass('disabled');
                $('.btn-cancel').removeClass('disabled');
                cont.html('Simpan');
            },
            statusCode: {
                500: function(state) {
                    Swal.fire({
                        title: state.title,
                        text: state.message,
                        type: state.type,
                        timer: 1500,
                    });
                }
            },
        });

    });

    //Submit Button
    $(document).on('click','.btn-submit',function(e){

        // $(this).prop('disabled',true);
        // $(this).html('<i class="fa fa-spinner fa-spin"></i>');

    });

    //Delete Button
    $(document).on('click','.btn-delete',function(e){
        e.preventDefault();

        var form = $(this).closest('form');
        var data = form.serialize();
        var url  = $(this).data('dest');

        Swal.fire({
            title: 'Yakin menghapus?',
            text: "Datanya tidak dapat dikembalikan!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: url,
                    data: data,
                    type: 'DELETE',
                    dataType: 'json',
                    beforeSend: function() {
                        Swal.showLoading()
                    },
                    success: function (state) {
                        if(state.type=='success') {
                            Swal.fire({
                                title: state.title,
                                text: state.message,
                                type: state.type,
                                timer: 2000,
                                onClose: () => {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                title: state.title,
                                text: state.message,
                                type: state.type,
                                timer: 2000,
                            });
                        }
                    },
                });
                // console.log(result.value);
            }
        })
    })

    /***********************************************************************************/

    /********************************* DATA TABLE *********************************/
    if($().DataTable) {
        var direction = $('table').data('sort');

        var datatable_opt = {
                                order: [[$('th.defaultSort').index(), direction]],
                                responsive: true,
                                autoWidth: false,
                                columnDefs: [ {
                                    "targets"  : 'no-sort',
                                    "orderable": false,
                                }],
                                language: {
                                    "sProcessing":   "Sedang proses...",
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
                                 }
                            }
        $('.datatable').DataTable(datatable_opt)
    };
    /******************************************************************************/

    /********************************* FILE INPUT *********************************/
    $('.custom-file-input').change(function(){
        var fileName = $(this).val();
        // removing the fake path (Chrome)
        fileName = fileName.replace("C:\\fakepath\\", "");
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
    });
    /******************************************************************************/

    /********************************* TAHUN AKADEMIK *********************************/
    $('#tahunAkademik').mask('9999');
    $('input#tahunAkademik').keyup(function(){
        var year = parseInt($(this).val());
        $('span.ta_next').text('/ '+(year+1));
    })

    $('.toggle-ay-status').click(function(e){
        e.preventDefault();

        var id     = $(this).data('id');
        var toggle = $(this);
        // $(this).toggleClass('on');
        $.ajax({
            url: base_url+'/ajax/academic-year/status',
            data: {id:id},
            type: 'POST',
            dataType: 'json',
            success: function (state) {

                if(state.warning) {
                    alertify.warning(state.warning);
                } else {
                    $('.toggle-ay-status').removeClass('on');
                    toggle.toggleClass('on');

                    alertify.success(state.message);
                }

            }
        });
    })

    $('.btn-edit-ay').click(function(e){
        e.preventDefault();
        var id = $(this).data('id');
        $.ajax({
            url: base_url+'/ajax/academic-year/edit',
            data: {id:id},
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                $('#academicYear-form')
                    .find('input[name=_id]').val(id).end()
                    .find('input[name=tahun_akademik]').val(data.tahun_akademik).end()
                    .find('select[name=semester]').val(data.semester).end()
                    .modal('toggle').end();
            }
        });
    });
    /*********************************************************************************/

    /********************************* FAKULTAS *********************************/
    $('.btn-edit-faculty').click(function(e){
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: base_url+'/ajax/faculty/edit',
            data: {id:id},
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                $('#modal-master-faculty')
                    .find('input[name=_id]').val(id).end()
                    .find('input[name=nama]').val(data.nama).end()
                    .find('input[name=singkatan]').val(data.singkatan).end()
                    .find('input[name=nip_dekan]').val(data.nip_dekan).end()
                    .find('input[name=nm_dekan]').val(data.nm_dekan).end()
                    .modal('toggle').end();
            }
        });
    });
    /*********************************************************************************/

    /********************************* JURUSAN *********************************/
    $('.btn-add-department').click(function(e){
        $('#modal-master-department')
            .find('input[name=kd_jurusan]').prop('disabled',false).end()
    })

    if($('#table_department').length) {
        load_department_table()
    }

    $('#filter-department').submit(function(e){
        e.preventDefault();

        load_department_table();
    })

    function load_department_table()
    {
        $('#table_department tbody').empty();

        var cont = $('#filter-department');
        var btn  = cont.find('button.btn-cari');
        var data = cont.serialize();
        var url  = cont.attr('action');
        var opsi = cont.find('select[name=id_fakultas] option:selected').text();

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
                                            '<button class="btn btn-primary btn-sm btn-icon rounded-circle mg-r-5 mg-b-10 btn-edit btn-edit-department" data-id="'+encode_id(kd_jurusan)+'"><div><i class="fa fa-pencil-alt"></i></div></button>'+
                                            '<form method="POST">'+
                                                '<input type="hidden" value="'+encode_id(kd_jurusan)+'" name="id">'+
                                                '<button type="submit" class="btn btn-danger btn-sm btn-icon rounded-circle mg-r-5 mg-b-10 btn-delete" data-dest="/master/department">'+
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
    }

    $('#table_department').on('click','.btn-edit-department',function(e){
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: base_url+'/ajax/department/edit',
            data: {id:id},
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                $('#modal-master-department')
                    .find('select[name=id_fakultas]').val(data.id_fakultas).end()
                    .find('input[name=_id]').val(data.kd_jurusan).end()
                    .find('input[name=kd_jurusan]').val(data.kd_jurusan).prop('disabled',true).end()
                    .find('input[name=nama]').val(data.nama).end()
                    .find('input[name=nip_kajur]').val(data.nip_kajur).end()
                    .find('input[name=nm_kajur]').val(data.nm_kajur).end()
                    .modal('toggle').end();
            }
        });
    });

    /*********************************************************************************/

    /********************************* PROGRAM STUDI *********************************/
    if($('#table_studyProgram').length) {
        load_studyProgram_table()
    }

    $('form#filter-study-program').submit(function(e){
        e.preventDefault();
        load_studyProgram_table()
    })

    function load_studyProgram_table()
    {
        var cont    = $('#filter-study-program');
        var tabel   = $('#table_studyProgram');
        var btn     = cont.find('button[type=submit]');
        var data    = cont.serialize();
        var url     = cont.attr('action');
        var opsi    = cont.find('select[name=kd_jurusan] option:selected').text();

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
                $('span.nm_jurusan').text(opsi);
                $('span.tot_prodi').text(data.length);


                var html = '';

                $('#table_studyProgram').show();

                if(data.length > 0) {
                    $.each(data, function(i){

                        var fakultas     = data[i].department.faculty.singkatan;
                        var jurusan      = data[i].department.nama;
                        var kd_prodi     = data[i].kd_prodi;
                        var nama         = data[i].nama;
                        var singkatan    = data[i].singkatan;
                        var jenjang      = data[i].jenjang;
                        var nip_kaprodi  = data[i].nip_kaprodi;
                        var nm_kaprodi   = data[i].nm_kaprodi;

                        html += '<tr>'+
                                    '<td>'+kd_prodi+'</td>'+
                                    '<td>'
                                        +nama+'<br>'+
                                        '<small>'+fakultas+' - '+jurusan+'</small>'+
                                    '</td>'+
                                    '<td>'+singkatan+'</td>'+
                                    '<td>'+jenjang+'</td>'+
                                    '<td>'+
                                        nm_kaprodi+'<br>'+
                                        '<small>'+nip_kaprodi+'</small>'+
                                    '</td>'+
                                    '<td class="text-center">'+
                                        '<div class="btn-group hidden-xs-down">'+
                                            '<button class="btn btn-success btn-sm btn-icon rounded-circle mg-r-5 mg-b-10 btn-show-sp" data-id="'+encode_id(kd_prodi)+'" ><div><i class="fa fa-search-plus"></i></div></button>'+
                                            '<a href="/master/study-program/'+encode_id(kd_prodi)+'/edit" class="btn btn-primary btn-sm btn-icon rounded-circle mg-r-5 mg-b-10"><div><i class="fa fa-pencil-alt"></i></div></a>'+
                                            '<form method="POST">'+
                                                '<input type="hidden" value="'+encode_id(kd_prodi)+'" name="id">'+
                                                '<button type="submit" class="btn btn-danger btn-sm btn-icon rounded-circle mg-r-5 mg-b-10 btn-delete" data-dest="/master/study-program">'+
                                                    '<div><i class="fa fa-trash"></i></div>'+
                                                '</button>'+
                                            '</form>'+
                                        '</div>'+
                                    '</td>'+
                                '</tr>'
                    })
                }

                tabel.dataTable().fnDestroy();
                tabel.find('tbody').html(html);
                tabel.DataTable(datatable_opt);

                btn.removeClass('disabled');
                btn.html('Cari');
            },
            error: function (request) {
                btn.removeClass('disabled');
                btn.html('Cari');
            }
        });
    }

    $('#table_studyProgram').on('click','.btn-show-sp',function(e){

        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: base_url+'/ajax/study-program/show',
            data: {id:id},
            type: 'POST',
            dataType: 'json',
            beforeSend: function() {
                $('span[name*=prodi]').text('');
            },
            success: function (data) {
                $('span[name=fakultas]').text(data.department.faculty.nama);
                $('span[name=jurusan]').text(data.department.nama);
                $('span[name=kd_prodi]').text(data.kd_prodi);
                $('span[name=nama_prodi]').text(data.nama);
                $('span[name=singkatan_prodi]').text(data.singkatan);
                $('span[name=jenjang_prodi]').text(data.jenjang);
                $('span[name=no_sk_prodi]').text(data.no_sk);
                $('span[name=tgl_sk_prodi]').text(data.tgl_sk);
                $('span[name=pejabat_sk_prodi]').text(data.pejabat_sk);
                $('span[name=thn_menerima_prodi]').text(data.thn_menerima);
                $('span[name=nip_kaprodi]').text(data.nip_kaprodi);
                $('span[name=nm_kaprodi]').text(data.nm_kaprodi);

                $('#studyProgram-show').modal('show')
            }
        })
    });

    $('select[name=id_fakultas]').change(function(){
        var target        = $('select[name=kd_jurusan]');
        var id_fakultas   = $(this).val();

        target.find('option').remove();

        $.ajax({
            url: base_url+'/ajax/department/get_by_faculty',
            data: {id:id_fakultas},
            type: 'POST',
            dataType: 'json',
            success: function (data) {

                var html = '';

                if(data.length > 0) {
                    $.each(data,function(i){
                        html += '<option value="'+data[i].kd_jurusan+'">'+data[i].nama+'</option>';
                    })
                } else {
                    html = '<option value="">- BELUM ADA DATA -</option>';
                }

                target.append(html);


            }
        })
    })

    $('select[name=kd_jurusan]').change(function(){
        var cont    = $(this);
        var target  = $('select[name=kd_prodi]');
        var id      = cont.val();

        target.find('option').remove();

        $.ajax({
            url: base_url+'/ajax/study-program/get_by_department',
            data: {kd_jurusan:id},
            type: 'POST',
            dataType: 'json',
            beforeSend: function() {
                if(cont.data('type') == 'form') {
                    target.prop('disabled',true);
                }
            },
            success: function (data) {

                var html = '';

                if(data.length > 0) {
                    html += '<option value="">- Pilih Program Studi -</option>';
                    $.each(data,function(i){
                        html += '<option value="'+data[i].kd_prodi+'">'+data[i].nama+'</option>';
                    })

                    if(cont.data('type') == 'form') {
                        target.prop('disabled',false);
                        target.prop('required',true);
                        cont.prop('required',false);
                    }

                } else {
                    html = '<option value="">- Pilih Program Studi -</option>';
                    if(cont.data('type') == 'form') {
                        target.prop('disabled',true);
                        target.prop('required',false);
                        cont.prop('required',true);
                    }
                }

                target.append(html);
            }
        })
    })

    /*********************************************************************************/

    /********************************* DATA DOSEN *********************************/

    $('#foto_profil').change(function(){
        var fileName = $(this).val();
        // removing the fake path (Chrome)
        fileName = fileName.replace("C:\\fakepath\\", "");
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
    });

    $('form#filter-teacher').submit(function(e){
        e.preventDefault();

        var cont = $(this);
        var btn  = cont.find('button[type=submit]');
        var data = cont.serialize();
        var url  = cont.attr('action');
        var opsi = cont.find('select[name=kd_jurusan] option:selected').text();

        $.ajax({
            url: url,
            data: data,
            type: 'POST',
            async: true,
            dataType: 'json',
            beforeSend: function() {
                btn.addClass('disabled');
                btn.html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function (data) {
                $('span.nm_jurusan').text(opsi);

                var tabel = $('#table_teacher');
                var html = '';

                tabel.show();

                if(data.length > 0) {
                    $.each(data, function(i){

                        var nidn        = data[i].nidn;
                        var nama        = data[i].nama;
                        var nip         = data[i].nip;
                        var prodi       = data[i].study_program.nama;
                        var jurusan     = data[i].study_program.department.nama;
                        var fakultas    = data[i].study_program.department.faculty.singkatan;
                        var ikatan      = data[i].ikatan_kerja;
                        var jabatan     = data[i].jabatan_akademik;
                        var pend        = data[i].pend_terakhir_jenjang;


                        html += '<tr>'+
                                    '<td><a href="/teacher/list/'+encode_id(nip)+'">'+nidn+'</a></td>'+
                                    '<td>'+
                                        nama+'<br>'+
                                        '<small>NIP. '+nip+'</small>'+
                                    '</td>'+
                                    '<td>'+
                                        prodi+'<br>'+
                                        '<small>'+fakultas+' - '+jurusan+'</small>'+
                                    '</td>'+
                                    '<td>'+ikatan+'</td>'+
                                    '<td>'+jabatan+'</td>'+
                                    '<td class="text-center">'+pend+'</td>'+
                                    '<td class="text-center no-sort" width="50">'+
                                        '<div class="btn-group" role="group">'+
                                            '<button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                                                '<div><span class="fa fa-caret-down"></span></div>'+
                                            '</button>'+
                                            '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">'+
                                                '<a class="dropdown-item" href="/teacher/list/'+encode_id(nip)+'/edit">Sunting</a>'+
                                                '<form method="POST">'+
                                                    '<input type="hidden" value="'+encode_id(nidn)+'" name="id">'+
                                                    '<button type="submit" class="dropdown-item btn-delete" data-dest="/teacher/list">Hapus</button>'+
                                                '</form>'+
                                            '</div>'+
                                        '</div>'+
                                    '</td>'+
                                '</tr>';
                    })
                }
                // tabel.dataTable().fnDestroy();
                tabel.DataTable().clear().destroy();
                tabel.find('tbody').html(html);
                tabel.DataTable(datatable_opt);

                btn.removeClass('disabled');
                btn.html('Cari');
            },
            error: function (request) {
                btn.removeClass('disabled');
                btn.html('Cari');
            }
        });
    });

    /*****************************************************************************/

    /********************************* DATA EWMP DOSEN *********************************/
    $('.btn-edit-ewmp').click(function(e){
        e.preventDefault();
        var id  = $(this).data('id');
        var url = '/ajax/ewmp/'+id;

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $('#modal-teach-ewmp')
                    .find('input[name=_id]').val(id).end()
                    .find('select[name=id_ta]').val(data.id_ta).end()
                    .find('input[name=tahun_akademik]').val(data.tahun_akademik).end()
                    .find('input[name=ps_intra]').val(data.ps_intra).end()
                    .find('input[name=ps_lain]').val(data.ps_lain).end()
                    .find('input[name=ps_luar]').val(data.ps_luar).end()
                    .find('input[name=penelitian]').val(data.penelitian).end()
                    .find('input[name=pkm]').val(data.pkm).end()
                    .find('input[name=tugas_tambahan]').val(data.tugas_tambahan).end()

                $('#modal-teach-ewmp').modal('toggle');
            }
        });
    });

    $('#filter-ewmp').submit(function(e){
        e.preventDefault();

        var tabel   = $('#table-ewmp');
        var data    = $(this).serialize();
        var url     = $(this).attr('action');
        var btn     = $(this).find('button[type=submit]');
        var prodi   = $('select[name=program_studi]').val();
        var ta      = $('select[name=tahun_akademik]').val();
        var smt     = $('select[name=semester]').val();

        tabel.find('tbody').empty();

        $.ajax({
            url: url,
            data: data,
            type: 'POST',
            async: true,
            dataType: 'json',
            beforeSend: function() {
                btn.prop('disabled',true);
                btn.html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function (data) {

                $('span.program-studi').text('prodi');
                $('span.tahun_akademik').text(ta+' - '+smt);

                if(data.ewmp.length > 0) {
                    var html = ''
                    $.each(data.ewmp, function(i,val) {
                        var ps_intra        = parseInt(val.ps_intra);
                        var ps_lain         = parseInt(val.ps_lain);
                        var ps_luar         = parseInt(val.ps_luar);
                        var penelitian      = parseInt(val.penelitian);
                        var pkm             = parseInt(val.pkm);
                        var tugas_tambahan  = parseInt(val.tugas_tambahan);
                        var total = parseInt(ps_intra+ps_lain+ps_luar+penelitian+pkm+tugas_tambahan);
                        var rata  = parseFloat(total/6).toFixed(2);
                        html += '<tr>'+
                                    '<td>'+val.teacher.nama+'</td>'+
                                    '<td class="text-center">'+data.tahun_akademik+'</td>'+
                                    '<td class="text-center">'+val.ps_intra+'</td>'+
                                    '<td class="text-center">'+val.ps_lain+'</td>'+
                                    '<td class="text-center">'+val.ps_luar+'</td>'+
                                    '<td class="text-center">'+val.penelitian+'</td>'+
                                    '<td class="text-center">'+val.pkm+'</td>'+
                                    '<td class="text-center">'+val.tugas_tambahan+'</td>'+
                                    '<td class="text-center no-sort">'+total+'</td>'+
                                    '<td class="text-center no-sort">'+rata+'</td>'+
                                '<tr>'
                    });
                } else {
                    html =  '<tr>'+
                                '<td colspan="10" class="text-center align-middle">BELUM ADA DATA</td>'+
                            '</tr>'

                }

                tabel.find('tbody').html(html);

                btn.prop('disabled',false);
                btn.html('Cari');

            },
            error: function (request) {
                btn.prop('disabled',false);
                btn.html('Cari');
            }
        });
    })
    /***********************************************************************************/

    /********************************* DATA PRESTASI DOSEN *********************************/

    $('#modal-teach-acv').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
        $(this).find('select[name=nidn]').children('option:not(:first)').remove();
        $(this).find('select[name=nidn]').prop('disabled',true);
    })

    $('.btn-edit-acv').click(function(e){
        e.preventDefault();

        var id  = $(this).data('id');
        var url = '/ajax/teacher-achievement/'+id;

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $('#modal-teach-acv')
                    .find('input[name=_id]').val(id).end()
                    .find('input[name=prestasi]').val(data.prestasi).end()
                    .find('input[name=tanggal_dicapai]').val(data.tanggal).end();

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

    $('#selectProdi').on('change',function(){
        var prodi = $(this).val();
        var url   = base_url+'/ajax/teacher/show_by_prodi';
        var nidn  = $(this).attr('data-nidn');

        $.ajax({
            url: url,
            type: 'POST',
            data: {kd_prodi:prodi},
            dataType:"json",
            success:function(data){

                var opsi;
                var cont = $('#modal-teach-acv').find('select[name=nidn]');

                cont.prop('disabled',false);
                cont.children('option:not(:first)').remove();

                $.each(data, function(i){
                    cont.append('<option value="'+data[i].nidn+'" '+((data[i].nidn == nidn) ? "selected" : "")+'>'+data[i].nama+'</option>');
                });

            }
        });
    })


    /***********************************************************************************/

    /********************************* DATA MAHASISWA *********************************/
    $('form#filter-student').submit(function(e){
        e.preventDefault();

        var cont    = $(this);
        var btn     = cont.find('button[type=submit]');
        var tabel   = $('#table_teacher');
        var data    = cont.serialize();
        var url     = cont.attr('action');

        $('.nm_jurusan').hide();

        $.ajax({
            url: url,
            data: data,
            type: 'POST',
            async: true,
            dataType: 'json',
            beforeSend: function() {
                btn.addClass('disabled');
                btn.html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function (data) {
                var html = '';

                if(data.length > 0) {
                    $.each(data, function(i){

                        var nim         = data[i].nim;
                        var nama        = data[i].nama;
                        var tgl_lhr     = data[i].tgl_lhr;
                        var prodi       = data[i].study_program.nama;
                        var jurusan     = data[i].study_program.department.nama;
                        var fakultas    = data[i].study_program.department.faculty.singkatan;
                        var angkatan    = data[i].angkatan;
                        var kelas       = data[i].kelas;
                        var program     = data[i].program;

                        html += '<tr>'+
                                    '<td>'+
                                        '<a href="/student/list/'+encode_id(nim)+'">'+
                                            nama+'<br>'+
                                            '<small>NIM. '+nim+'</small>'+
                                        '</a>'+
                                    '</td>'+
                                    '<td>'+tgl_lhr+'</td>'+
                                    '<td>'+
                                        prodi+'<br>'+
                                        '<small>'+fakultas+' - '+jurusan+'</small>'+
                                    '</td>'+
                                    '<td>'+angkatan+'</td>'+
                                    '<td>'+kelas+'</td>'+
                                    '<td class="text-center">'+program+'</td>'+
                                    '<td class="text-center no-sort" width="50">'+
                                        '<div class="btn-group" role="group">'+
                                            '<button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                                                '<div><span class="fa fa-caret-down"></span></div>'+
                                            '</button>'+
                                            '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">'+
                                                '<a class="dropdown-item" href="/student/list/'+encode_id(nim)+'/edit">Sunting</a>'+
                                                '<form method="POST">'+
                                                    '<input type="hidden" value="'+encode_id(nim)+'" name="id">'+
                                                    '<button type="submit" class="dropdown-item btn-delete" data-dest="/student/list">Hapus</button>'+
                                                '</form>'+
                                            '</div>'+
                                        '</div>'+
                                    '</td>'+
                                '</tr>';
                    })
                }

                // tabel.dataTable().fnDestroy();
                tabel.DataTable().clear().destroy();
                tabel.find('tbody').html(html);
                tabel.DataTable(datatable_opt);

                btn.removeClass('disabled');
                btn.html('Cari');
            },
            error: function (request) {
                btn.removeClass('disabled');
                btn.html('Cari');
            }
        });
    });

    $('#student_form').on('change','#tipe_mahasiswa',function(){
        var select      = $(this).find('select');
        var divlain     = $('#student_form').find('#tipe_lainlain');
        var inputlain   = divlain.find('input');
        var val         = select.val();

        if(val=='Lain-Lain') {
            $(this).removeClass('col-12');
            $(this).addClass('col-6');

            select.removeAttr('name');
            select.prop('required',false);
            inputlain.val('');
            inputlain.prop('disabled',false);
            inputlain.prop('required',true);
            divlain.show();

        } else {
            divlain.hide();
            $(this).removeClass('col-6');
            $(this).addClass('col-12');

            inputlain.prop('disabled',true);
            inputlain.prop('required',false);
            inputlain.removeAttr('name')

            select.attr('name','tipe');
            select.prop('required',true);
        }
    })

    $('#student_form').on('change','select[name=seleksi_jenis]',function(){
        load_seleksi_jenis()
    })

    if($('#student_form').length) {
        var student_form  = $('#student_form');
        var val           = student_form.find('select[name=seleksi_jenis]').val();
        var seleksi_jalur = student_form.find('select[name=seleksi_jalur]');

        if(!val) {
            seleksi_jalur.children('option:not(:first)').remove();
        }
        else {
            if(val!='Nasional') {
                var seleksi = ['SNMPTN','SBMPTN'];
            } else if(val!='Lokal') {
                var seleksi = ['Mandiri'];
            }

            $.each(seleksi, function(i,value){
                seleksi_jalur.children('option[value='+value+']').remove();
            });
        }

        ///////////////////////////////////////////////////////

        var divtipe        = student_form.find('#tipe_mahasiswa');
        var divlain        = student_form.find('#tipe_lainlain');
        var select         = divtipe.find('select');
        var inputlain      = divlain.find('input');

        if(select.val() == 'Lain-Lain') {
            divtipe.removeClass('col-12');
            divtipe.addClass('col-6');
            select.prop('required',false);
            select.removeAttr('name');

            inputlain.prop('disabled',false);
            inputlain.prop('required',true);
            divlain.show();
        }


    }

    function load_seleksi_jenis() {
        var student_form  = $('#student_form');
        var seleksi_jalur = student_form.find('select[name=seleksi_jalur]');
        var val           = student_form.find('select[name=seleksi_jenis]').val();

        seleksi_jalur.children('option:not(:first)').remove();

        if(val=='Nasional') {
            var seleksi = ['SNMPTN','SBMPTN'];
        } else if(val=='Lokal') {
            var seleksi = ['Mandiri'];
        }

        $.each(seleksi, function(i,val){
            seleksi_jalur.append('<option>'+val+'</option>');
        });
    }


    /**********************************************************************************/
});
