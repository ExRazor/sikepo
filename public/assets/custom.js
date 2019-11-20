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

    //Check Null
    function null_check (value) {
        return (value == null) ? "" : value
    }

    //Datepicker
    $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });

    //Input Money Mask
    $('.rupiah').each(function(){ // function to apply mask on load!
        $(this).maskMoney({
            precision: 0,
            thousands: '.',
            decimal: ',',
        });
        if($(this).val()){
            $(this).maskMoney('mask', $(this).val());
        }
    })

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
        setTimeout(function() {
            $('a.tab-link[href="' + hash + '"]').trigger('click');
        },10);
    } else {
        setTimeout(function() {
            $("a.tab-link").first().trigger('click');
        },10);
    }
    if($('.tab-header').length) {
        $('a.tab-link').click(function(){
            var teks = $(this).text();
            $('.tab-header').find('h4').text(teks);
        });

    }

    //Modal
    $('.modal').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
        $('.alert-danger').hide();
        $('input, select').removeClass('is-invalid');
        $(this).find('input[name^=_id]').removeAttr('value');
    })

    function rupiah(bilangan){
        var	reverse = bilangan.toString().split('').reverse().join(''),
        rupiah 	= reverse.match(/\d{1,3}/g);
        rupiah	= rupiah.join('.').split('').reverse().join('');

        return 'Rp ' + rupiah;
    }

    /********************************* BUTTON / TOMBOL *********************************/

    //Add Button
    $('.btn-add').click(function(e) {
        e.preventDefault();

        $('span.title-action').text('Tambah');
        $('.btn-save').val('post');

        $('.alert-danger').hide();
        $('.is-invalid').removeClass('is-invalid');
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

                cont.html('Tersimpan');

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
                $('.is-invalid').removeClass('is-invalid');
                $.each(json.errors, function(key, value){
                    $('.alert-danger').show();
                    $('.alert-danger').append('<span>'+value+'</span><br>');
                    $('[name='+key+']').addClass('is-invalid');
                    $('[aria-labelledby*=select2-'+key+']').addClass('is-invalid');
                });

                cont.removeClass('disabled');
                $('.btn-cancel').removeClass('disabled');
                cont.html('Simpan');

            },
            statusCode: {
                500: function(state) {
                    Swal.fire({
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat memproses',
                        type: 'error',
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
        var redir= $(this).data('redir');

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
                                    if(redir) {
                                        window.location.replace(redir);
                                    } else {
                                        location.reload();
                                    }
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
                    statusCode: {
                        500: function(state) {
                            Swal.fire({
                                title: 'Gagal',
                                text: 'Terjadi kesalahan saat memproses',
                                type: 'error',
                                timer: 1500,
                            });
                        }
                    },
                });
                // console.log(result.value);
            }
        })
    })

    //btn-delete GET
    $(document).on('click','.btn-delget',function(e){
        e.preventDefault();

        var id   = $(this).data('id');
        var url  = $(this).data('dest');
        var redir= $(this).data('redir');

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
                    data: {id:id},
                    type: 'GET',
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
                                    if(redir) {
                                        window.location.replace(redir);
                                    } else {
                                        location.reload();
                                    }
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
                    statusCode: {
                        500: function(state) {
                            Swal.fire({
                                title: 'Gagal',
                                text: 'Terjadi kesalahan saat memproses',
                                type: 'error',
                                timer: 1500,
                            });
                        }
                    },
                });
            }
        })
    })

    /***********************************************************************************/

    /********************************* DATA TABLE *********************************/
    if($().DataTable) {
        var direction = $('table').data('sort');

        var bahasa = {
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
         };

        var datatable_opt = {
                                order: [[$('th.defaultSort').index(), direction]],
                                responsive: true,
                                autoWidth: false,
                                columnDefs: [ {
                                    "targets"  : 'no-sort',
                                    "orderable": false,
                                }],
                                language: bahasa
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

    $(document).on('input','.number',function(){
        replace = $(this).val().replace(/[^0-9]/g,'');
        $(this).val(replace);
    })
    /******************************************************************************/

    /***************************** SELECT / COMBO BOX ****************************/
    $(document).on('change','.select-jurusan',function(){
        var cont    = $(this);
        var target  = $('.select-prodi');
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

    $('#select-prodi-dsn').change(function(){
        var cont    = $(this);
        var target  = $('select[name=nidn]');
        var id      = cont.val();

        target.find('option').remove();

        $.ajax({
            url: base_url+'/ajax/teacher/get_by_studyProgram',
            data: {kd_prodi:id},
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
                    html += '<option value="">- Pilih Dosen -</option>';
                    $.each(data,function(i){
                        html += '<option value="'+data[i].nidn+'">'+data[i].nama+'</option>';
                    })

                    if(cont.data('type') == 'form') {
                        target.prop('disabled',false);
                        target.prop('required',true);
                        cont.prop('required',false);
                    }

                } else {
                    html = '<option value="">- Pilih Dosen -</option>';
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

    $('.select-prodi').select2({
        language: "id",
        minimumInputLength: 5,
        allowClear: true,
        placeholder: 'Masukkan nama program studi',
        ajax: {
            dataType: 'json',
            url: base_url+'/ajax/study-program/loadData',
            delay: 800,
            data: function(params) {
                return {
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

    function load_select_prodi(selectElementObj) {
        selectElementObj.select2({
            language: "id",
            minimumInputLength: 5,
            allowClear: true,
            placeholder: 'Masukkan nama program studi',
            ajax: {
                dataType: 'json',
                url: base_url+'/ajax/study-program/loadData',
                delay: 800,
                data: function(params) {
                    return {
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

    $('.select-academicYear').select2({
        width: "100%",
        language: "id",
        minimumInputLength: 3,
        allowClear: true,
        placeholder: 'Masukkan tahun akademik',
        ajax: {
            dataType: 'json',
            url: base_url+'/ajax/academic-year/loadData',
            delay: 800,
            data: function(params) {
                return {
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

    $('.select-mhs').select2({
        width: "100%",
        language: "id",
        minimumInputLength: 3,
        allowClear: true,
        placeholder: 'Masukkan nama mahasiswa',
        ajax: {
            dataType: 'json',
            url: base_url+'/ajax/student/loadData',
            delay: 800,
            data: function(params) {
                return {
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

    function load_select_mhs(selectElementObj) {
        selectElementObj.select2({
            width: "100%",
            language: "id",
            minimumInputLength: 3,
            allowClear: true,
            placeholder: 'Masukkan nama mahasiswa',
            ajax: {
                dataType: 'json',
                url: base_url+'/ajax/student/loadData',
                delay: 800,
                data: function(params) {
                    return {
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

    $('.select-dsn').select2({
        width: "100%",
        language: "id",
        minimumInputLength: 3,
        allowClear: true,
        placeholder: 'Masukkan nidn/nama dosen',
        ajax: {
            dataType: 'json',
            url: base_url+'/ajax/teacher/loadData',
            delay: 800,
            data: function(params) {
                return {
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

    function load_select_dsn(selectElementObj) {
        selectElementObj.select2({
            width: "100%",
            language: "id",
            minimumInputLength: 3,
            allowClear: true,
            placeholder: 'Masukkan nidn/nama dosen',
            ajax: {
                dataType: 'json',
                url: base_url+'/ajax/teacher/loadData',
                delay: 800,
                data: function(params) {
                    return {
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

    $('.select-curriculum').select2({
        width: "100%",
        language: "id",
        minimumInputLength: 3,
        allowClear: true,
        placeholder: 'Masukkan kode/nama mata kuliah',
        ajax: {
            dataType: 'json',
            url: base_url+'/ajax/curriculum/loadData',
            delay: 800,
            data: function(params) {
                return {
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

    /****************************************************************************/

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

    /*********************************************************************************/

    /******************************* DATA KERJA SAMA *********************************/
    $('form#filter-collaboration').submit(function(e){
        e.preventDefault();

        var cont    = $(this);
        var btn     = cont.find('button[type=submit]');
        var tabel   = $('#table_collaboration');
        var datacon = cont.serializeArray();
        var url     = cont.attr('action');
        var opsi    = cont.find('select[name=kd_prodi] option:selected');
        var jurusan = cont.find('input#nm_jurusan').val();

        if(opsi.val()) {
            var teks = 'Prodi: '+opsi.text();
            $('h6.card-title').text(teks);
        } else {
            $('h6.card-title').text(jurusan);
        }

        $.ajax({
            url: url,
            data: datacon,
            type: 'POST',
            async: true,
            dataType: 'json',
            beforeSend: function() {
                btn.addClass('disabled');
                btn.html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function (data) {
                var html  = '';

                if(data.length > 0) {
                    $.each(data, function(i,val){
                        var id              = val.id;
                        var tahun_akademik  = val.academic_year.tahun_akademik+' - '+val.academic_year.semester;
                        var prodi           = val.study_program.nama;
                        var lembaga         = val.nama_lembaga;
                        var tingkat         = val.tingkat;
                        var judul           = val.judul_kegiatan;
                        var manfaat         = val.manfaat_kegiatan;
                        var waktu           = val.waktu;
                        var durasi          = val.durasi;

                        html +='<tr>'+
                                    '<td>'+tahun_akademik+'</td>'+
                                    '<td>'+prodi+'</td>'+
                                    '<td>'+lembaga+'</td>'+
                                    '<td class="text-capitalize">'+tingkat+'</td>'+
                                    '<td>'+judul+'</td>'+
                                    '<td>'+manfaat+'</td>'+
                                    '<td>'+waktu+'</td>'+
                                    '<td>'+durasi+'</td>'+
                                    '<td class="text-center" width="75">'+
                                        '<a href="/download/collab/'+encode_id(id)+'" target="_blank"><div><i class="fa fa-download"></i></div></a>'+
                                    '</td>'+
                                    '<td class="text-center" width="50">'+
                                        '<div class="btn-group" role="group">'+
                                            '<button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                                                '<div><span class="fa fa-caret-down"></span></div>'+
                                            '</button>'+
                                            '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">'+
                                                '<a class="dropdown-item" href="/collaboration/'+encode_id(id)+'/edit">Sunting</a>'+
                                                '<form method="POST">'+
                                                    '<input type="hidden" value="'+encode_id(id)+'" name="id">'+
                                                    '<a href="#" class="dropdown-item btn-delete" data-dest="/collaboration">Hapus</a>'+
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

    $('#research_form, #communityService_form, #publication_form')
        .on('change','#prodi_dosen',function(){
            var cont    = $(this);
            var target  = $('#select-dosen');
            var id      = cont.val();

            target.find('option').remove();

            $.ajax({
                url: base_url+'/ajax/teacher/get_by_studyProgram',
                data: {kd_prodi:id},
                type: 'GET',
                dataType: 'json',
                success: function (data) {

                    var html = '';

                    if(data.length > 0) {
                        html += '<option value="">- Pilih Dosen -</option>';
                        $.each(data,function(i){
                            html += '<option value="'+data[i].nidn+'">'+data[i].nama+'</option>';
                        })

                    } else {
                        html = '<option value="">- Pilih Dosen -</option>';
                    }

                    target.append(html);
                }
            })
        })

    /*****************************************************************************/

    /********************************* DATA EWMP DOSEN *********************************/
    $('#modal-teach-ewmp').on('change','select[name=id_ta]',function(e){

        e.preventDefault();
        var cont   = $('#modal-teach-ewmp');
        var nidn   = cont.find('input[name=nidn]').val();
        var id_ta  = $(this).val();
        var url    = base_url+'/ajax/ewmp/countsks';

        $.ajax({
            url: url,
            data: {
                nidn:nidn,
                id_ta:id_ta
            },
            type: 'GET',
            dataType: 'json',
            success: function (count) {
                cont.find('input[name=ps_intra]').val(count.schedule_ps);
                cont.find('input[name=ps_lain]').val(count.schedule_pt);
                cont.find('input[name=penelitian]').val(count.penelitian);
            }
        });
    });

    $('#ewmp').on('click','.btn-edit',function(e){
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

    $('#table_teacherAcv').on('click','.btn-edit',function(e){
        e.preventDefault();

        var id  = $(this).data('id');
        var url = base_url+'/teacher-achievement/'+id;

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

    $('form#filter-teacherAcv').submit(function(e){
        e.preventDefault();

        var cont    = $(this);
        var btn     = cont.find('button[type=submit]');
        var tabel   = $('#table_teacherAcv');
        var datacon = cont.serializeArray();
        var url     = cont.attr('action');
        var opsi    = cont.find('select[name=kd_prodi] option:selected');
        var jurusan = cont.find('input#nm_jurusan').val();

        if(opsi.val()) {
            var teks = 'Prodi: '+opsi.text();
            $('h6.card-title').text(teks);
        } else {
            $('h6.card-title').text(jurusan);
        }

        $.ajax({
            url: url,
            data: datacon,
            type: 'POST',
            async: true,
            dataType: 'json',
            beforeSend: function() {
                btn.addClass('disabled');
                btn.html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function (data) {
                var html          = '';

                if(data.length > 0) {
                    $.each(data, function(i,val){
                        var id          = val.id;
                        var nidn        = val.teacher.nidn;
                        var nama_dsn    = val.teacher.nama;
                        var prodi       = val.teacher.study_program.singkatan;
                        var tanggal     = val.tanggal;
                        var prestasi    = val.prestasi;
                        var tingkat     = val.tingkat_prestasi;
                        var bukti       = bukti_pendukung;

                        html +='<tr>'+
                                '<td>'+tanggal+'</td>'+
                                '<td>'+
                                    nama_dsn+'<br>'+
                                    '<small>NIDN.'+nidn+' / '+prodi+'</small>'+
                                '</td>'+
                                '<td>'+prestasi+'</td>'+
                                '<td>'+tingkat+'</td>'+
                                '<td class="text-center align-middle">'+
                                    '<a href="'+base_url+'/download/teacher-achievement/'+encode_id(bukti)+'" target="_blank"><div><i class="fa fa-download"></i></div></a>'+
                                '</td>'+
                                '<td class="text-center" width="50">'+
                                    '<div class="btn-group" role="group">'+
                                        '<button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                                            '<div><span class="fa fa-caret-down"></span></div>'+
                                        '</button>'+
                                        '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">'+
                                            '<a class="dropdown-item" href="'+base_url+'/'+encode_id(id)+'/edit">Sunting</a>'+
                                            '<form method="POST">'+
                                                '<input type="hidden" value="'+encode_id(id)+'" name="id">'+
                                                '<button class="dropdown-item btn-delete" data-dest="'+base_url+'/community-service">Hapus</button>'+
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

    /***********************************************************************************/

    /********************************* DATA MAHASISWA *********************************/

    $('form#filter-student').submit(function(e){
        e.preventDefault();

        var cont    = $(this);
        var btn     = cont.find('button[type=submit]');
        var tabel   = $('#table_student');
        var datacon = cont.serializeArray();
        var url     = cont.attr('action');

        $('.nm_jurusan').hide();

        $.ajax({
            url: url,
            data: datacon,
            type: 'POST',
            async: true,
            dataType: 'json',
            beforeSend: function() {
                btn.addClass('disabled');
                btn.html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function (data) {
                var html          = '';
                var status_filter = datacon[2].value;

                if(data.length > 0) {
                    $.each(data, function(i,val){
                        if(val.latest_status) {
                            var status      = null_check(val.latest_status.status);
                        } else {
                            var status = 'Aktif';
                        }

                        if(status_filter == status || status_filter == '') {
                            var nim         = null_check(val.nim);
                            var nama        = null_check(val.nama);
                            var tgl_lhr     = null_check(val.tgl_lhr);
                            var prodi       = null_check(val.study_program.nama);
                            var jurusan     = null_check(val.study_program.department.nama);
                            var fakultas    = null_check(val.study_program.department.faculty.singkatan);
                            var angkatan    = null_check(val.angkatan);
                            var kelas       = null_check(val.kelas);
                            var program     = null_check(val.program);


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
                                        '<td class="text-center">'+status+'</td>'+
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
                        }
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

    /********************************* DATA KUOTA MAHASISWA *********************************/
    $('#table_student_quota').on('click','.btn-edit-quota',function(e){
        e.preventDefault();

        var id  = $(this).data('id');
        var url = '/ajax/student/quota/'+id;

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $('#modal-student-quota')
                    .find('input[name=_id]').val(id).end()
                    .find('select[name=kd_prodi]').val(data.kd_prodi).end()
                    .find('select[name=id_ta]').val(data.id_ta).end()
                    .find('input[name=daya_tampung]').val(data.daya_tampung).end()
                    .find('input[name=calon_pendaftar]').val(data.calon_pendaftar).end()
                    .find('input[name=calon_lulus]').val(data.calon_lulus).end()
                    .modal('toggle').end();
            }
        });
    });
    /****************************************************************************************/

    /********************************* DATA STATUS MAHASISWA *********************************/
    $('button.btn-add-studentStatus').on('click', function(){
        $('#modal-student-status .status_mahasiswa').show();
    })

    $('#table_student_status').on('click','.btn-edit',function(e){

        var id  = $(this).data('id');
        var url = '/ajax/student/status/'+id;

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $('#modal-student-status')
                    .find('input[name=_id]').val(id).end()
                    .find('select[name=id_ta]').val(data.id_ta).end()
                    .find('input[name=ipk_terakhir]').val(data.ipk_terakhir).end()
                    .find('.status_mahasiswa').hide().end()
                    .modal('toggle').end();
            }
        });
    });
    /****************************************************************************************/

    /********************************* DATA MAHASISWA ASING *********************************/
    $('#table-studentForeign').on('click','.btn-edit',function(){

        var id  = $(this).data('id');
        var url = base_url+'/student/foreign/'+id;

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                var option = $("<option selected></option>").val(data.nim).text(data.student.nama);

                $('#form-studentForeign')
                    .find('input[name=_id]').val(id).end()
                    .find('select[name=nim]').append(option).trigger('change').end()
                    .find('input[name=asal_negara]').val(data.asal_negara).end()
                    .find('select[name=durasi]').val(data.durasi).end()

            }
        });
    })

    $('#form-studentForeign')
        .on('click','.btn-add',function(e){
            var form  = $('#form-studentForeign')
            form.trigger('reset');
            $('.select-mhs').val(null).trigger('change');
        }).end()

    $('form#filter-studentForeign').submit(function(e){
        e.preventDefault();

        var cont    = $(this);
        var btn     = cont.find('button[type=submit]');
        var tabel   = $('#table-studentForeign');
        var datacon = cont.serializeArray();
        var url     = cont.attr('action');
        var opsi    = cont.find('select[name=kd_prodi] option:selected');
        var jurusan = cont.find('input#nm_jurusan').val();

        if(opsi.val()) {
            var teks = 'Prodi: '+opsi.text();
            $('h6.card-title-table').text(teks);
        } else {
            $('h6.card-title-table').text(jurusan);
        }

        $.ajax({
            url: url,
            data: datacon,
            type: 'POST',
            async: true,
            dataType: 'json',
            beforeSend: function() {
                btn.addClass('disabled');
                btn.html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function (data) {
                var html          = '';

                if(data.length > 0) {
                    $.each(data, function(i,val){
                        var id          = val.id;
                        var nim         = val.nim;
                        var nama        = val.student.nama;
                        var prodi       = val.student.study_program.singkatan;
                        var asal_negara = val.asal_negara;
                        var durasi      = val.durasi;

                        html += '<tr>'+
                                    '<td>'+
                                        '<a href="'+base_url+'/student/list/'+encode_id(nim)+'">'+
                                            nama+'<br>'+
                                            '<small>NIM.'+nim+' / '+prodi+'</small>'+
                                        '</a>'+
                                    '</td>'+
                                    '<td class="text-center">'+asal_negara+'</td>'+
                                    '<td class="text-center">'+durasi+'</td>'+
                                    '<td class="text-center">'+
                                        '<div class="btn-group" role="group">'+
                                            '<button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                                                '<div><span class="fa fa-caret-down"></span></div>'+
                                            '</button>'+
                                            '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">'+
                                                '<button class="dropdown-item btn-edit" data-id="'+encode_id(id)+'">Sunting</button>'+
                                                '<form method="POST">'+
                                                    '<input type="hidden" value="'+encode_id(id)+'" name="_id">'+
                                                    '<button class="dropdown-item btn-delete" data-dest="'+base_url+'/student/foreign">Hapus</button>'+
                                                '</form>'+
                                            '</div>'+
                                        '</div>'+
                                    '</td>'+
                                '</tr>'
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
    /****************************************************************************************/

    /********************************* DATA KATEGORI PENDANAAN *********************************/
    $('#table-fundCat').on('click','.btn-edit',function(){

        var id  = $(this).data('id');
        var url = '/ajax/funding/category/'+id;

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
                    $('#form-fundCat').find('.category-description').show();
                    $('#form-fundCat').find('.category-description').find('textarea').prop('disabled',false);
                    $('#form-fundCat').find('select[name=jenis]').prop('disabled',true).val(data.jenis);
                    $('#form-fundCat').find('input[name=jenis]').prop('disabled',false).val(data.jenis);
                } else {
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
                    url: '/ajax/funding/category/select/'+value,
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
            form.find('select[name=id_parent]').trigger('change')
        }).end()
    /****************************************************************************************/

    /********************************* DATA PENELITIAN DTPS *********************************/

    $('form#filter-research').submit(function(e){
        e.preventDefault();

        var cont    = $(this);
        var btn     = cont.find('button[type=submit]');
        var tabel   = $('#table_research');
        var datacon = cont.serializeArray();
        var url     = cont.attr('action');
        var opsi    = cont.find('select[name=kd_prodi] option:selected');
        var jurusan = cont.find('input#nm_jurusan').val();

        if(opsi.val()) {
            var teks = 'Prodi: '+opsi.text();
            $('h6.card-title').text(teks);
        } else {
            $('h6.card-title').text(jurusan);
        }

        $.ajax({
            url: url,
            data: datacon,
            type: 'POST',
            async: true,
            dataType: 'json',
            beforeSend: function() {
                btn.addClass('disabled');
                btn.html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function (data) {
                var html          = '';

                if(data.length > 0) {
                    $.each(data, function(i,val){
                        var id          = val.id;
                        var tahun       = val.academic_year.tahun_akademik+' - '+val.academic_year.semester;
                        var ketua_nidn  = val.research_ketua.teacher.nidn;
                        var ketua_nm    = val.research_ketua.teacher.nama;
                        var ketua_prodi = val.research_ketua.teacher.study_program.singkatan;
                        var judul       = val.judul_penelitian;
                        var tema        = val.tema_penelitian;
                        var sks         = val.sks_penelitian;
                        var sumber      = val.sumber_biaya;
                        var sumber_nama = val.sumber_biaya_nama;
                        var jumlah_biaya = val.jumlah_biaya;
                        var daftar_dsn  = '';
                        var daftar_mhs  = '';

                        //Daftar Dosen Anggota
                        if(val.research_anggota.length > 0) {
                            $.each(val.research_anggota, function(i,dsn){
                                daftar_dsn += '<li>'+dsn.teacher.nama+'('+dsn.teacher.nidn+') ('+dsn.teacher.study_program.department.nama+' - '+dsn.teacher.study_program.nama+')</li>';
                            })
                        } else {
                            daftar_dsn = '-';
                        }
                        var dosen = '<ol>'+daftar_dsn+'</ol>';

                        //Daftar Mahasiswa Terlibat
                        if(val.research_student.length > 0) {
                            $.each(val.research_student, function(i,mhs){
                                daftar_mhs += '<li>'+mhs.student.nama+'('+mhs.student.nim+') ('+mhs.student.study_program.department.nama+' - '+mhs.student.study_program.nama+')</li>';
                            })
                        } else {
                            daftar_mhs = '-';
                        }
                        var mahasiswa = '<ol>'+daftar_mhs+'</ol>';

                        //Sumber Biaya
                        if(sumber_nama) {
                            var sumber_biaya = sumber+' ('+sumber_nama+' )';
                        } else {
                            var sumber_biaya = sumber;
                        }

                        //Draw tabel
                        html +='<tr>'+
                                '<td>'+id+'</td>'+
                                '<td>'+judul+'</td>'+
                                '<td class="text-center">'+tahun+'</td>'+
                                '<td>'+
                                    ketua_nm+'<br>'+
                                    '<small>NIDN.'+ketua_nidn+' / '+ketua_prodi+'</small>'+
                                '</td>'+
                                '<td>'+tema+'</td>'+
                                '<td class="text-center">'+sks+'</td>'+
                                '<td>'+dosen+'</td>'+
                                '<td>'+mahasiswa+'</td>'+
                                '<td>'+sumber_biaya+'</td>'+
                                '<td>'+rupiah(jumlah_biaya)+'</td>'+
                                '<td class="text-center" width="50">'+
                                    '<div class="btn-group" role="group">'+
                                        '<button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                                            '<div><span class="fa fa-caret-down"></span></div>'+
                                        '</button>'+
                                        '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">'+
                                            '<a class="dropdown-item" href="'+base_url+'/research/'+encode_id(id)+'/edit">Sunting</a>'+
                                            '<form method="POST">'+
                                                '<input type="hidden" value="'+encode_id(id)+'" name="id">'+
                                                '<button class="dropdown-item btn-delete" data-dest="/research">Hapus</button>'+
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


    $('#research_form')
        .on('change','#sumber_biaya_select',function(){
            var value = $(this).val();

            alert

            if(value=='Lembaga Dalam Negeri' || value=='Lembaga Luar Negeri') {
                $('#sumber_biaya_input').prop('disabled',false);
                $('#sumber_biaya_input').prop('required',true);
            } else {
                $('#sumber_biaya_input').val('');
                $('#sumber_biaya_input').prop('required',false);
                $('#sumber_biaya_input').prop('disabled',true);
            }
        })

    /******************* TOMBOL TAMBAH DOSEN *******************/
    $('a.add-dosen').on('click', function (){
        var panel = $('div#panelDosen'); /* target untuk menampilkan form */
        var hitung = panel.attr('data-jumlah');
        var jumlah = parseInt(hitung) + 1;

        /* menampilkan form */
        var sintakshtml = $('<div class="row mb-3 justify-content-center align-items-center row-dosen">'+
                                '<a class="remove-box btn btn-danger btn-sm" href="javascript:void(0)"><i class="fa fa-times"></i></a>'+
                                '<div class="col-7">'+
                                    '<div id="pilihDosen'+jumlah+'" class="parsley-select">'+
                                        '<select class="form-control select-dsn" data-parsley-class-handler="#pilihDosen'+jumlah+'" data-parsley-errors-container="#errorsProdiDsn{{$i}}" name="anggota_nidn[]" required>'+
                                        '</select>'+
                                    '</div>'+
                                    '<div id="errorsPilihDosen'+jumlah+'"></div>'+
                                '</div>'+
                            '</div>')

        sintakshtml.hide();
        panel.append(sintakshtml);
        sintakshtml.fadeIn('slow');
        panel.attr('data-jumlah',jumlah);
        load_select_dsn(panel.find('.select-dsn'));
        return false;
    })

    $('div#panelDosen').on('click', 'a.remove-box', function() {
        var panel  = $('div#panelDosen');
        var hitung = panel.attr('data-jumlah');
        var jumlah = parseInt(hitung) - 1;
        var induk = $(this).parents('div.row-dosen');

        induk.fadeOut('slow', function() {
            $(this).remove();
            panel.attr('data-jumlah',jumlah);
        });
        return false;
    });

    /***********************************************************/
    /************ TOMBOL TAMBAH MAHASISWA ***********/
    $('a.add-mahasiswa').on('click', function (){
        var panel = $('div#panelMahasiswa'); /* target untuk menampilkan form */
        var hitung = panel.attr('data-jumlah');
        var jumlah = parseInt(hitung) + 1;

        /* menampilkan form */
        var sintakshtml = $('<div class="row mb-3 justify-content-center align-items-center row-mahasiswa">'+
                                '<a class="remove-box btn btn-danger btn-sm" href="javascript:void(0)"><i class="fa fa-times"></i></a>'+
                                '<div class="col-7">'+
                                    '<div id="mhs'+jumlah+'" class="parsley-select">'+
                                        '<select class="form-control select-mhs" name="mahasiswa_nim[]" data-parsley-class-handler="#mhs'+jumlah+'" data-parsley-errors-container="#errorsMhs'+jumlah+'" required>'+
                                            '<option value="">- Asal Program Studi -</option>'+
                                        '</select>'+
                                    '</div>'+
                                    '<div id="errorsMhs'+jumlah+'"></div>'+
                                '</div>'+
                            '</div>')

        sintakshtml.hide();
        panel.append(sintakshtml);
        sintakshtml.fadeIn('slow');
        panel.attr('data-jumlah',jumlah);
        load_select_mhs(panel.find('.select-mhs'));
        return false;
    })

    $('div#panelMahasiswa').on('click', 'a.remove-box', function() {
        var panel  = $('div#panelMahasiswa');
        var hitung = panel.attr('data-jumlah');
        var jumlah = parseInt(hitung) - 1;
        var induk = $(this).parents('div.row-mahasiswa');

        induk.fadeOut('slow', function() {
            $(this).remove();
            panel.attr('data-jumlah',jumlah);
        });
        return false;
    });
    /************************************************/

    /****************************************************************************************/

    /************************************ PENGABDIAN ****************************************/
    $('form#filter-communityService').submit(function(e){
        e.preventDefault();

        var cont    = $(this);
        var btn     = cont.find('button[type=submit]');
        var tabel   = $('#table_communityService');
        var datacon = cont.serializeArray();
        var url     = cont.attr('action');
        var opsi    = cont.find('select[name=kd_prodi] option:selected');
        var jurusan = cont.find('input#nm_jurusan').val();

        if(opsi.val()) {
            var teks = 'Prodi: '+opsi.text();
            $('h6.card-title').text(teks);
        } else {
            $('h6.card-title').text(jurusan);
        }

        $.ajax({
            url: url,
            data: datacon,
            type: 'POST',
            async: true,
            dataType: 'json',
            beforeSend: function() {
                btn.addClass('disabled');
                btn.html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function (data) {
                var html          = '';

                if(data.length > 0) {
                    $.each(data, function(i,val){
                        var id          = val.id;
                        var nidn        = val.teacher.nidn;
                        var nama_dsn    = val.teacher.nama;
                        var prodi       = val.teacher.study_program.singkatan;
                        var judul       = val.judul_pengabdian;
                        var tema        = val.tema_pengabdian;
                        var tahun       = val.tahun_pengabdian;
                        var sumber      = val.sumber_biaya;
                        var sumber_nama = val.sumber_biaya_nama;
                        var daftar      = '';

                        if(val.community_service_student.length > 0) {
                            $.each(val.community_service_student, function(i,mhs){

                                daftar += '<li>'+mhs.nama+'('+mhs.nim+') ('+mhs.study_program.department.nama+' - '+mhs.study_program.nama+')</li>';
                            })
                        } else {
                            daftar = '-';
                        }

                        var mahasiswa = '<ol>'+daftar+'</ol>';

                        if(sumber_nama) {
                            var sumber_biaya = sumber+' ('+sumber_nama+' )';
                        } else {
                            var sumber_biaya = sumber;
                        }

                        html +='<tr>'+
                                '<td>'+
                                    nama_dsn+'<br>'+
                                    '<small>NIDN.'+nidn+' / '+prodi+'</small>'+
                                '</td>'+
                                '<td>'+judul+'</td>'+
                                '<td>'+tema+'</td>'+
                                '<td class="text-center">'+tahun+'</td>'+
                                '<td>'+mahasiswa+'</td>'+
                                '<td>'+sumber_biaya+'</td>'+
                                '<td class="text-center" width="50">'+
                                    '<div class="btn-group" role="group">'+
                                        '<button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                                            '<div><span class="fa fa-caret-down"></span></div>'+
                                        '</button>'+
                                        '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">'+
                                            '<a class="dropdown-item" href="/community-service/'+encode_id(id)+'/edit">Sunting</a>'+
                                            '<form method="POST">'+
                                                '<input type="hidden" value="'+encode_id(id)+'" name="id">'+
                                                '<button class="dropdown-item btn-delete" data-dest="/community-service">Hapus</button>'+
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
    /****************************************************************************************/

    /************************************* KURIKULUM ****************************************/
    $('form#filter-curriculum').submit(function(e){
        e.preventDefault();

        var cont    = $(this);
        var btn     = cont.find('button[type=submit]');
        var tabel   = $('#table_curriculum');
        var datacon = cont.serializeArray();
        var url     = cont.attr('action');
        var opsi    = cont.find('select[name=kd_prodi] option:selected');
        var jurusan = cont.find('input#nm_jurusan').val();

        if(opsi.val()) {
            var teks = 'Prodi: '+opsi.text();
            $('h6.card-title').text(teks);
        } else {
            $('h6.card-title').text(jurusan);
        }

        $.ajax({
            url: url,
            data: datacon,
            type: 'POST',
            async: true,
            dataType: 'json',
            beforeSend: function() {
                btn.addClass('disabled');
                btn.html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function (data) {
                var html          = '';

                if(data.length > 0) {
                    $.each(data, function(i,val){
                        var kd_matkul     = val.kd_matkul;
                        var prodi         = val.study_program.nama;
                        var nama          = val.nama;
                        var semester      = val.semester;
                        var jenis         = val.jenis;
                        var versi         = val.versi;
                        var sks_teori     = val.sks_teori;
                        var sks_seminar   = val.sks_seminar;
                        var sks_praktikum = val.sks_praktikum;
                        var capaian       = val.capaian;
                        var dokumen       = val.dokumen_nama;

                        html += '<tr>'+
                                    '<td>'+i+'</td>'+
                                    '<td>'+prodi+'</td>'+
                                    '<td>'+kd_matkul+'</td>'+
                                    '<td>'+nama+'</td>'+
                                    '<td class="text-center">'+semester+'</td>'+
                                    '<td class="text-center">'+jenis+'</td>'+
                                    '<td>'+versi+'</td>'+
                                    '<td>'+sks_teori+'</td>'+
                                    '<td>'+sks_seminar+'</td>'+
                                    '<td>'+sks_praktikum+'</td>'+
                                    '<td>'+capaian.join(', ')+'</td>'+
                                    '<td>'+dokumen+'</td>'+
                                    '<td class="text-center" width="50">'+
                                        '<div class="btn-group" role="group">'+
                                            '<button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                                                '<div><span class="fa fa-caret-down"></span></div>'+
                                            '</button>'+
                                            '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">'+
                                                '<a class="dropdown-item" href="/academic/curriculum/'+encode_id(kd_matkul)+'/edit">Sunting</a>'+
                                                '<form method="POST">'+
                                                    '<input type="hidden" value="'+encode_id(kd_matkul)+'" name="id">'+
                                                    '<button class="dropdown-item btn-delete" data-dest="/academic/curriculum">Hapus</button>'+
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
    /****************************************************************************************/

    /************************************* JADWAL MATA KULIAH ****************************************/
    $('form#filter-schedule').submit(function(e){
        e.preventDefault();

        var cont        = $(this);
        var btn         = cont.find('button[type=submit]');
        var datacon     = cont.serializeArray();
        var url         = cont.attr('action');
        var opsi_prodi  = cont.find('select[name=kd_prodi] option:selected');
        var jurusan = cont.find('input#nm_jurusan').val();

        if(opsi_prodi.val()) {
            var teks = 'Prodi: '+opsi_prodi.text();
            $('h6.card-title').text(teks);
        } else {
            $('h6.card-title').text(jurusan);
        }

        $.ajax({
            url: url,
            data: datacon,
            type: 'POST',
            async: true,
            dataType: 'json',
            beforeSend: function() {
                btn.addClass('disabled');
                btn.html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function (data) {

                var html    = '';
                var tabel   = $('table.datatable');

                if(data.length > 0) {
                    $.each(data, function(indexay,ay) {
                        var tabel   = $('#table-schedule-'+ay.id);
                        html    = '';

                        $.each(ay.curriculum_schedule, function(indexschedule,schedule){
                            // if(schedule.curriculum.kd_prodi == opsi_prodi.val() || opsi_prodi.val() == '') {
                                var id            = schedule.id;
                                var kd_matkul     = schedule.kd_matkul;
                                var nm_matkul     = schedule.curriculum.nama;
                                var prodi_matkul  = schedule.curriculum.study_program.department.nama+' - '+schedule.curriculum.study_program.singkatan;
                                var nm_dosen      = schedule.teacher.nama;
                                var nip_dosen     = schedule.teacher.nip;
                                var nidn_dosen    = schedule.teacher.nidn;
                                var prodi_dosen   = schedule.teacher.study_program.singkatan;
                                var sesuai_prodi  = '';
                                var sesuai_bidang = '';


                                var sks_teori     = parseInt(schedule.curriculum.sks_teori);
                                var sks_seminar   = parseInt(schedule.curriculum.sks_seminar);
                                var sks_praktikum = parseInt(schedule.curriculum.sks_praktikum);
                                var sks           = sks_teori+sks_seminar+sks_praktikum;

                                if(schedule.curriculum.kd_prodi==schedule.teacher.kd_prodi) {
                                    sesuai_prodi = '<i class="fa fa-check"></i>';
                                }

                                if(schedule.sesuai_bidang==1) {
                                    sesuai_bidang = '<i class="fa fa-check"></i>';
                                }

                                html += '<tr>'+
                                            '<td>'+kd_matkul+'</td>'+
                                            '<td>'+
                                                nm_matkul+'<br>'+
                                                '<small>'+prodi_matkul+'</small>'+
                                            '</td>'+
                                            '<td class="text-center">'+sks+'</td>'+
                                            '<td>'+
                                                '<a href="'+base_url+'/teacher/list/'+encode_id('+nip_dosen+')+'">'+
                                                    nm_dosen+'<br>'+
                                                    '<small>NIDN. '+nidn_dosen+' / '+prodi_dosen+'</small>'+
                                                '</a>'+
                                            '</td>'+
                                            '<td class="text-center">'+sesuai_prodi+'</td>'+
                                            '<td class="text-center">'+sesuai_bidang+'</td>'+
                                            '<td class="text-center">'+
                                                '<div class="btn-group" role="group">'+
                                                    '<button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                                                        '<div><span class="fa fa-caret-down"></span></div>'+
                                                    '</button>'+
                                                    '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">'+
                                                        '<a class="dropdown-item" href="'+base_url+'/academic/schedule/'+encode_id(id)+'/edit">Sunting</a>'+
                                                        '<form method="POST">'+
                                                            '<input type="hidden" value="'+encode_id(id)+'" name="id">'+
                                                            '<button class="dropdown-item btn-delete" data-dest="'+base_url+'/academic/schedule">Hapus</button>'+
                                                        '</form>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</td>'+
                                        '</tr>'
                            // }
                        });
                        tabel.DataTable().clear().destroy();
                        tabel.find('tbody').html(html);
                        tabel.DataTable(datatable_opt);
                    });
                } else {
                    tabel.DataTable().clear().destroy();
                    tabel.find('tbody').html(html);
                    tabel.DataTable(datatable_opt);
                }

                btn.removeClass('disabled');
                btn.html('Cari');
            },
            error: function (request) {
                btn.removeClass('disabled');
                btn.html('Cari');
            }
        });
    });

    // JADWAL DI PROFIL DOSEN
    $('#schedule').on('click','.btn-add',function(){
        var form  = $('#modal-teach-schedule form');
        form.trigger('reset');

        form.find('select[name=id_ta] option').remove().trigger('change');
        form.find('select[name=kd_matkul] option').remove().trigger('change');
    })

    $('#schedule').on('click','.btn-edit',function(){

        var id  = $(this).data('id');
        var url = base_url+'/academic/schedule/'+id+'/edit';

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                var cont          = $('#modal-teach-schedule');
                var option_ta     = $("<option selected></option>").val(data.id_ta).text(data.academic_year.tahun_akademik+' - '+data.academic_year.semester);
                var option_matkul = $("<option selected></option>").val(data.kd_matkul).text(data.curriculum.nama);

                cont.find('input[name=id]').val(id);
                cont.find('input[name=nidn]').val(data.nidn);
                cont.find('select[name=id_ta]').append(option_ta).trigger('change');
                cont.find('select[name=kd_matkul]').append(option_matkul).trigger('change');

                if(data.sesuai_bidang) {
                    cont.find('input[name=sesuai_bidang]').prop('checked',true);
                }

                cont.modal('toggle');

            }
        });
    })
    /*************************************************************************************************/

    /********************************* DATA KATEGORI PUBLIKASI *********************************/
    $('#table-publishCat').on('click','.btn-edit',function(){

        var id  = $(this).data('id');
        var url = '/ajax/publication/category/'+id;

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $('#form-publishCat')
                    .find('input[name=_id]').val(id).end()
                    .find('input[name=nama]').val(data.nama).end()
                    .find('textarea[name=deskripsi]').val(data.deskripsi).end()
            }
        });
    })

    $('#form-publishCat')
        .on('click','.btn-add',function(e){
            var form  = $('#form-publishCat')
            form.trigger('reset');
        }).end()
    /****************************************************************************************/

    /************************************* DATA PUBLIKASI ***********************************/
    $('form#filter-publication').submit(function(e){
        e.preventDefault();

        var cont    = $(this);
        var btn     = cont.find('button[type=submit]');
        var tabel   = $('#table_publication');
        var datacon = cont.serializeArray();
        var url     = cont.attr('action');
        var opsi    = cont.find('select[name=kd_prodi] option:selected');
        var jurusan = cont.find('input#nm_jurusan').val();

        if(opsi.val()) {
            var teks = 'Prodi: '+opsi.text();
            $('h6.card-title').text(teks);
        } else {
            $('h6.card-title').text(jurusan);
        }

        $.ajax({
            url: url,
            data: datacon,
            type: 'POST',
            async: true,
            dataType: 'json',
            beforeSend: function() {
                btn.addClass('disabled');
                btn.html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function (data) {
                var html          = '';

                if(data.length > 0) {
                    $.each(data, function(i,val){
                        var id          = val.id;
                        var nidn        = val.teacher.nidn;
                        var nama_dsn    = val.teacher.nama;
                        var prodi       = val.teacher.study_program.singkatan;
                        var judul       = val.judul;
                        var kategori    = val.publication_category.nama;
                        var penerbit    = val.penerbit;
                        var tahun       = val.tahun;
                        var jurnal      = val.jurnal;
                        var sitasi      = val.sitasi;
                        var akreditasi  = val.akreditasi;
                        var tautan      = val.tautan;
                        var daftar      = '';

                        if(val.publication_students.length > 0) {
                            $.each(val.publication_students, function(i,mhs){

                                daftar += '<li>'+mhs.nama+'('+mhs.nim+') ('+mhs.study_program.department.nama+' - '+mhs.study_program.nama+')</li>';
                            })
                        } else {
                            daftar = '-';
                        }
                        var mahasiswa = '<ol>'+daftar+'</ol>';

                        if(val.tautan) {
                            var tautan = '<a href="'+val.tautan+'" target="_blank"><div><i class="fa fa-download"></i></div></a>';
                        } else {
                            var tautan = '-';
                        }


                        html +='<tr>'+
                                '<td>'+
                                    nama_dsn+'<br>'+
                                    '<small>NIDN.'+nidn+' / '+prodi+'</small>'+
                                '</td>'+
                                '<td>'+judul+'</td>'+
                                '<td>'+kategori+'</td>'+
                                '<td class="text-center">'+tahun+'</td>'+
                                '<td>'+penerbit+'</td>'+
                                '<td>'+jurnal+'</td>'+
                                '<td>'+akreditasi+'</td>'+
                                '<td>'+sitasi+'</td>'+
                                '<td>'+mahasiswa+'</td>'+
                                '<td>'+tautan+'</td>'+
                                '<td class="text-center" width="50">'+
                                    '<div class="btn-group" role="group">'+
                                        '<button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                                            '<div><span class="fa fa-caret-down"></span></div>'+
                                        '</button>'+
                                        '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">'+
                                            '<a class="dropdown-item" href="'+base_url+'/publication/list/'+encode_id(id)+'/edit">Sunting</a>'+
                                            '<form method="POST">'+
                                                '<input type="hidden" value="'+encode_id(id)+'" name="id">'+
                                                '<button class="dropdown-item btn-delete" data-dest="'+base_url+'/publication/list">Hapus</button>'+
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
    /***************************************************************************************/

    /********************************* DATA KATEGORI LUARAN *********************************/
    $('#table-outputCat').on('click','.btn-edit',function(){

        var id  = $(this).data('id');
        var url = base_url+'/ajax/output-activity/category/'+id;

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $('#form-outputCat')
                    .find('input[name=_id]').val(id).end()
                    .find('input[name=nama]').val(data.nama).end()
                    .find('textarea[name=deskripsi]').val(data.deskripsi).end()
            }
        });
    })

    $('#form-outputCat')
        .on('click','.btn-add',function(e){
            var form  = $('#form-outputCat')
            form.trigger('reset');
        }).end()
    /****************************************************************************************/

});
