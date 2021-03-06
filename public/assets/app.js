// URL
var base_url    = window.location.origin;
var host        = window.location.host;
var pathArray   = window.location.pathname.split( '/' );

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
    $(this).find('input[name^=_id]').removeAttr('value');
    $(this).find('input[name^=id]').removeAttr('value');
    $(this).find('select').val(null).trigger('change');
    $(this).find('button').attr('data-id',null);
    $('.is-invalid').removeClass('is-invalid');
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
    var modal   = cont.closest('.modal');

    if(action=='put') {
        var url     = cont.data('dest')+'/'+cont.data('id');
    } else {
        var url     = cont.data('dest');
    }

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
            cont.prop('disabled',true);
            $('.btn-cancel').prop('disabled',true);
            cont.html('<i class="fa fa-spinner fa-spin"></i>');
        },
        success: function (state) {

            cont.html('Tersimpan');

            modal.modal('toggle');

            Swal.fire({
                title: state.title,
                text: state.message,
                type: state.type,
                // timer: 1500,
                onClose: () => {
                    location.reload();
                }
            });

        },
        error: function (request) {
            //Button
            cont.prop('disabled',false);
            $('.btn-cancel').prop('disabled',false);
            cont.html('Simpan');

            //Parse Message
            json = $.parseJSON(request.responseText);

            //Init alert variable
            var alert_cont = $('.alert-danger');
            alert_cont.hide();

            //Clear exist message and error
            alert_cont.find('span.error').html('');
            alert_cont.find('ul').html(null)
            $('.is-invalid').removeClass('is-invalid');

            //Repopulate message
            if(!json.errors) {
                alert_cont.find('span.error').html(json.message);
            }

            //Repopulate error
            $.each(json.errors, function(key, value){
                alert_cont.find('ul').append('<li>'+value+'</li>');
                $('#'+key).addClass('is-invalid');
                $('[name='+key+']').addClass('is-invalid');
                $('[name='+key+']').parents('div.radio').addClass('is-invalid');
                $('[aria-labelledby*=select2-'+key+']').addClass('is-invalid');
            });

            //Show Error
            alert_cont.show();

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
                            // timer: 2000,
                            onClose: () => {
                                if(redir) {
                                    window.location.replace(redir);
                                } else {
                                    location.reload();
                                }
                            }
                        });
                    }
                },
                error: function(request) {
                    //Parse Message
                    json = $.parseJSON(request.responseText);

                    //Error Swal
                    Swal.fire({
                        title: 'Gagal',
                        text: json.message,
                        type: 'error',
                    });
                }
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

//btn-delete File
$(document).on('click','.btn-delfile',function(e){
    e.preventDefault();

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
if($('.datatable').length) {
    var direction = $('.datatable').data('sort');

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

var prodi = $('input[name=kd_prodi]').val();
var prodi_mhs = $('input[name=prodi_mhs]').val();
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

$('.select-mhs-prodi').select2({
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
                prodi: prodi_mhs,
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

function select2_dosen(cont,prodi=null) {

    cont.select2({
        width: "100%",
        language: "id",
        allowClear: true,
        placeholder: 'Masukkan nidn/nama dosen',
        ajax: {
            dataType: 'json',
            type: 'POST',
            url: base_url+'/ajax/teacher/loadData',
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

function load_select_dsn(selectElementObj) {
    selectElementObj.select2({
        width: "100%",
        language: "id",
        minimumInputLength: 3,
        allowClear: true,
        placeholder: 'Masukkan nidn/nama dosen',
        ajax: {
            dataType: 'json',
            type: 'POST',
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


/****************************************************************************/

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
                .modal('toggle');
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

                    html += '<tr>'+
                                '<td>'+kd_jurusan+'</td>'+
                                '<td>'+nama+'</td>'+
                                '<td>'+fakultas+'</td>'+
                                '<td class="text-center">'+
                                    '<div class="btn-group">'+
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
                                '<td class="text-center">'+
                                    '<div class="btn-group hidden-xs-down">'+
                                        '<button class="btn btn-success btn-sm btn-icon rounded-circle mg-r-5 mg-b-10 btn-show-sp" data-id="'+encode_id(kd_prodi)+'" ><div><i class="fa fa-search-plus"></i></div></button>'+
                                        '<a href="'+base_url+'/master/study-program/'+encode_id(kd_prodi)+'/edit" class="btn btn-primary btn-sm btn-icon rounded-circle mg-r-5 mg-b-10"><div><i class="fa fa-pencil-alt"></i></div></a>'+
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
    var role    = decode_id(cont.data('token'));
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
                    var jenis           = val.jenis;
                    var lembaga         = val.nama_lembaga;
                    var tingkat         = val.tingkat;
                    var judul           = val.judul_kegiatan;
                    var manfaat         = val.manfaat_kegiatan;
                    var waktu           = val.waktu;
                    var durasi          = val.durasi;
                    var aksi;

                    if(role!='kajur') {
                        aksi = '<td class="text-center" width="50">'+
                                    '<div class="btn-group" role="group">'+
                                        '<button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                                            '<div><span class="fa fa-caret-down"></span></div>'+
                                        '</button>'+
                                        '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">'+
                                            '<a class="dropdown-item" href="'+base_url+'/collaboration/'+encode_id(id)+'/edit">Sunting</a>'+
                                            '<form method="POST">'+
                                                '<input type="hidden" value="'+encode_id(id)+'" name="id">'+
                                                '<a href="#" class="dropdown-item btn-delete" data-dest="/collaboration">Hapus</a>'+
                                            '</form>'+
                                        '</div>'+
                                    '</div>'+
                                '</td>';
                    }

                    html +='<tr>'+
                                '<td>'+tahun_akademik+'</td>'+
                                '<td>'+prodi+'</td>'+
                                '<td>'+jenis+'</td>'+
                                '<td>'+lembaga+'</td>'+
                                '<td class="text-capitalize">'+tingkat+'</td>'+
                                '<td>'+judul+'</td>'+
                                '<td>'+manfaat+'</td>'+
                                '<td>'+waktu+'</td>'+
                                '<td>'+durasi+'</td>'+
                                '<td class="text-center" width="75">'+
                                    '<a href="'+base_url+'/download/collab/'+encode_id(id)+'" target="_blank"><div><i class="fa fa-download"></i></div></a>'+
                                '</td>'+
                                aksi+
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



$('#research_form, #communityService_form, #publication_form')
    .on('change','#prodi_dosen',function(){
        var cont    = $(this);
        var target  = $('#select-dosen');
        var id      = cont.val();

        target.find('option').remove();

        $.ajax({
            url: base_url+'/ajax/teacher/loadData',
            data: {prodi:id},
            type: 'POST',
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
    .on('change','#prodi_mahasiswa',function(){
        var cont    = $(this);
        var target  = $('#select-mahasiswa');
        var id      = cont.val();

        target.find('option').remove();

        $.ajax({
            url: base_url+'/ajax/student/get_by_studyProgram',
            data: {kd_prodi:id},
            type: 'POST',
            dataType: 'json',
            success: function (data) {

                var html = '';

                if(data.length > 0) {
                    html += '<option value="">- Pilih Mahasiswa -</option>';
                    $.each(data,function(i){
                        html += '<option value="'+data[i].nim+'">'+data[i].nama+'</option>';
                    })

                } else {
                    html = '<option value="">- Pilih Mahasiswa -</option>';
                }

                target.append(html);
            }
        })
    })

/*****************************************************************************/

/********************************* DATA PRESTASI DOSEN *********************************/

$('#modal-teach-acv').on('shown.bs.modal', function () {
    $(this).find('select#selectProdi').trigger('change');
})

$('#modal-teach-acv').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
    // $(this).find('select[name=nidn]').children('option:not(:first)').remove();
    $(this).find('select[name=nidn] option').remove().trigger('change');
    $(this).find('select[name=nidn]').prop('disabled',true);
    $(this).find('select[name=id_ta] option').remove().trigger('change');
})

$('#modal-teach-acv').on('change','select#selectProdi', function() {
    var cont   = $('#modal-teach-acv');
    var prodi  = $(this).val();
    var target = $('.select2-dosen');

    if(prodi=='' || prodi==null) {
        target.prop('disabled',true);
        target.prop('required',false);
        target.val(null).trigger('change');
    } else {
        target.prop('disabled',false);
        target.prop('required',true);
        select2_dosen(target,prodi);
    }
})
/***********************************************************************************/

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
            // cont.find('input[name=ps_lain]').val(count.schedule_pt);
            cont.find('input[name=penelitian]').val(count.penelitian);
            cont.find('input[name=pkm]').val(count.pengabdian);
        }
    });
});

$('#ewmp').on('click','.btn-edit',function(e){
    e.preventDefault();
    var id  = $(this).data('id');
    var url = base_url+'/ajax/ewmp/'+id;

    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            var option_ta     = $("<option selected></option>").val(data.id_ta).text(data.academic_year.tahun_akademik+' - '+data.academic_year.semester);

            $('#modal-teach-ewmp')
                .find('input[name=_id]').val(id).end()
                // .find('select[name=id_ta]').val(data.id_ta).end()
                .find('select[name=id_ta]').append(option_ta).trigger('change').end()
                .find('input[name=tahun_akademik]').val(data.tahun_akademik).end()
                .find('input[name=ps_intra]').val(data.ps_intra).end()
                .find('input[name=ps_lain]').val(data.ps_lain).end()
                .find('input[name=ps_luar]').val(data.ps_luar).end()
                .find('input[name=penelitian]').val(data.penelitian).end()
                .find('input[name=pkm]').val(data.pkm).end()
                .find('input[name=tugas_tambahan]').val(data.tugas_tambahan);

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

/********************************* DATA MAHASISWA *********************************/

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

/********************************* DATA STATUS MAHASISWA *********************************/

/****************************************************************************************/

/********************************* DATA KATEGORI PENDANAAN *********************************/

/****************************************************************************************/

/********************************* DATA PENELITIAN DTPS *********************************/

$('form#filter-research').submit(function(e){
    e.preventDefault();

    var cont    = $(this);
    var btn     = cont.find('button[type=submit]');
    var tabel   = $('#table_research');
    var datacon = cont.serializeArray();
    var url     = cont.attr('action');
    var role    = decode_id(cont.data('token'));
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
                    var ketua_nip   = val.research_ketua.teacher.nip;
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
                    var sesuai_prodi = '';
                    var aksi;

                    if(val.sesuai_prodi!=null) {
                        sesuai_prodi = '<i class="fa fa-check"></i>';
                    }

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

                    //Tombol Aksi
                    if(role!='kajur') {
                        aksi = '<td class="text-center" width="50">'+
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
                                '</td>'
                    }

                    //Draw tabel
                    html +='<tr>'+
                            '<td>'+judul+'</td>'+
                            '<td class="text-center">'+tahun+'</td>'+
                            '<td>'+
                                '<a href="'+base_url+'/teacher/list/'+encode_id(ketua_nip)+'#research">'+
                                    ketua_nm+'<br>'+
                                    '<small>NIDN.'+ketua_nidn+' / '+ketua_prodi+'</small>'+
                                '</a>'+
                            '</td>'+
                            '<td class="text-center">'+sesuai_prodi+'</td>'+
                            aksi+
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


$('#research_form, #communityService_form')
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

if('#research_form, #communityService_form') {
    $('#sumber_biaya_select').change();
}

/******************* TOMBOL TAMBAH DOSEN *******************/
$('button.add-dosen').on('click', function (e){
    e.preventDefault();
    var panel = $('div#panelDosen'); /* target untuk menampilkan form */
    var hitung = panel.attr('data-jumlah');
    var jumlah = parseInt(hitung) + 1;
    var jenis  = $(this).data('jenis');

    /* menampilkan form */
    if(jenis == 'publikasi') {
        var sintakshtml = $('<div class="row mb-3 justify-content-center align-items-center row-dosen">'+
                                '<button class="remove-box btn btn-danger btn-sm"><i class="fa fa-times"></i></button>'+
                                '<div class="col-2">'+
                                    '<input class="form-control number" type="text" name="anggota_nidn[]" placeholder="NIDN" maxlength="9">'+
                                '</div>'+
                                '<div class="col-5">'+
                                    '<input class="form-control" type="text" name="anggota_nama[]" placeholder="Nama Dosen" required>'+
                                '</div>'+
                                '<div class="col-4">'+
                                    '<div id="prodiDsn'+jumlah+'" class="parsley-select">'+
                                        '<select class="form-control select-prodi" data-parsley-class-handler="#prodiDsn'+jumlah+'" data-parsley-errors-container="#errorsProdiDsn'+jumlah+'" name="anggota_prodi[]" required>'+
                                        '</select>'+
                                    '</div>'+
                                    '<div id="errorsProdiDsn'+jumlah+'"></div>'+
                                '</div>'+
                            '</div>');
    } else {
        var sintakshtml = $('<div class="row mb-3 justify-content-center align-items-center row-dosen">'+
                                '<button class="remove-box btn btn-danger btn-sm"><i class="fa fa-times"></i></button>'+
                                '<div class="col-7">'+
                                    '<div id="pilihDosen'+jumlah+'" class="parsley-select">'+
                                        '<select class="form-control select2-dosen-anggota" data-parsley-class-handler="#pilihDosen'+jumlah+'" data-parsley-errors-container="#errorsProdiDsn{{$i}}" name="anggota_nidn[]" required>'+
                                        '</select>'+
                                    '</div>'+
                                    '<div id="errorsPilihDosen'+jumlah+'"></div>'+
                                '</div>'+
                            '</div>');
    }

    sintakshtml.hide();
    panel.append(sintakshtml);
    sintakshtml.fadeIn('slow');
    panel.attr('data-jumlah',jumlah);

    //Select2
    var select2_cont = $('.select2-dosen-anggota');
    select2_dosen(select2_cont);
    // load_select_dsn(panel.find('.select2-dosen'));
    load_select_prodi(panel.find('.select-prodi'));
    return false;
})

$('div#panelDosen').on('click', 'button.remove-box', function() {
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
$('button.add-mahasiswa').on('click', function (){
    var panel = $('div#panelMahasiswa'); /* target untuk menampilkan form */
    var hitung = panel.attr('data-jumlah');
    var jumlah = parseInt(hitung) + 1;
    var jenis  = $(this).data('jenis');

    /* menampilkan form */
    if(jenis == 'publikasi') {
        var sintakshtml = $('<div class="row mb-3 justify-content-center align-items-center row-mahasiswa">'+
                                '<button class="remove-box btn btn-danger btn-sm"><i class="fa fa-times"></i></button>'+
                                '<div class="col-2">'+
                                    '<input class="form-control number" type="text" name="mahasiswa_nim[]" placeholder="NIM" maxlength="9">'+
                                '</div>'+
                                '<div class="col-5">'+
                                    '<input class="form-control" type="text" name="mahasiswa_nama[]" placeholder="Nama Mahasiswa" required>'+
                                '</div>'+
                                '<div class="col-4">'+
                                    '<div id="prodiMhs'+jumlah+'" class="parsley-select">'+
                                        '<select class="form-control select-prodi" data-parsley-class-handler="#prodiMhs'+jumlah+'" data-parsley-errors-container="#errorsProdiMhs'+jumlah+'" name="mahasiswa_prodi[]" required>'+
                                        '</select>'+
                                    '</div>'+
                                    '<div id="errorsProdiMhs'+jumlah+'"></div>'+
                                '</div>'+
                            '</div>')
    } else {
        var sintakshtml = $('<div class="row mb-3 justify-content-center align-items-center row-mahasiswa">'+
                                '<button class="remove-box btn btn-danger btn-sm"><i class="fa fa-times"></i></button>'+
                                '<div class="col-7">'+
                                    '<div id="mhs'+jumlah+'" class="parsley-select">'+
                                        '<select class="form-control select-mhs" name="mahasiswa_nim[]" data-parsley-class-handler="#mhs'+jumlah+'" data-parsley-errors-container="#errorsMhs'+jumlah+'" required>'+
                                        '</select>'+
                                    '</div>'+
                                    '<div id="errorsMhs'+jumlah+'"></div>'+
                                '</div>'+
                            '</div>')
    }
    sintakshtml.hide();
    panel.append(sintakshtml);
    sintakshtml.fadeIn('slow');
    panel.attr('data-jumlah',jumlah);
    load_select_mhs(panel.find('.select-mhs'));
    load_select_prodi(panel.find('.select-prodi'));
    return false;
})

$('div#panelMahasiswa').on('click', 'button.remove-box', function() {
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
    var role    = decode_id(cont.data('token'));
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
                    var ketua_nidn  = val.service_ketua.teacher.nidn;
                    var ketua_nip   = val.service_ketua.teacher.nip;
                    var ketua_nm    = val.service_ketua.teacher.nama;
                    var ketua_prodi = val.service_ketua.teacher.study_program.singkatan;
                    var judul       = val.judul_pengabdian;
                    var tema        = val.tema_pengabdian;
                    var sks         = val.sks_pengabdian;
                    var sumber      = val.sumber_biaya;
                    var sumber_nama = val.sumber_biaya_nama;
                    var jumlah_biaya = val.jumlah_biaya;
                    var daftar_dsn  = '';
                    var daftar_mhs  = '';
                    var sesuai_prodi = '';
                    var aksi;

                    if(val.sesuai_prodi!=null) {
                        sesuai_prodi = '<i class="fa fa-check"></i>';
                    }

                    //Daftar Dosen Anggota
                    if(val.service_anggota.length > 0) {
                        $.each(val.service_anggota, function(i,dsn){
                            daftar_dsn += '<li>'+dsn.teacher.nama+'('+dsn.teacher.nidn+') ('+dsn.teacher.study_program.department.nama+' - '+dsn.teacher.study_program.nama+')</li>';
                        })
                    } else {
                        daftar_dsn = '-';
                    }
                    var dosen = '<ol>'+daftar_dsn+'</ol>';

                    //Daftar Mahasiswa Terlibat
                    if(val.service_student.length > 0) {
                        $.each(val.service_student, function(i,mhs){
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

                    //Tombol Aksi
                    if(role!='kajur') {
                        aksi = '<td class="text-center" width="50">'+
                                    '<div class="btn-group" role="group">'+
                                        '<button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                                            '<div><span class="fa fa-caret-down"></span></div>'+
                                        '</button>'+
                                        '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">'+
                                            '<a class="dropdown-item" href="'+base_url+'/community-service/'+encode_id(id)+'/edit">Sunting</a>'+
                                            '<form method="POST">'+
                                                '<input type="hidden" value="'+encode_id(id)+'" name="id">'+
                                                '<button class="dropdown-item btn-delete" data-dest="/community-service">Hapus</button>'+
                                            '</form>'+
                                        '</div>'+
                                    '</div>'+
                                '</td>'
                    }

                    //Draw tabel
                    html +='<tr>'+
                            '<td>'+judul+'</td>'+
                            '<td class="text-center">'+tahun+'</td>'+
                            '<td>'+
                                '<a href="'+base_url+'/teacher/list/'+encode_id(ketua_nip)+'#community-service">'+
                                    ketua_nm+'<br>'+
                                    '<small>NIDN.'+ketua_nidn+' / '+ketua_prodi+'</small>'+
                                '</a>'+
                            '</td>'+
                            '<td class="text-center">'+sesuai_prodi+'</td>'+
                            aksi+
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
    var role    = decode_id(cont.data('token'));
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
                    var unit          = val.unit_penyelenggara;
                    var kompetensi_prodi = '';
                    var aksi;

                    if(val.kompetensi_prodi!=null) {
                        kompetensi_prodi = '<i class="fa fa-check"></i>';
                    }

                    if(role!='kajur') {
                        aksi = '<td class="text-center" width="50">'+
                                    '<div class="btn-group" role="group">'+
                                        '<button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                                            '<div><span class="fa fa-caret-down"></span></div>'+
                                        '</button>'+
                                        '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">'+
                                            '<a class="dropdown-item" href="'+base_url+'/academic/curriculum/'+encode_id(kd_matkul)+'/edit">Sunting</a>'+
                                            '<form method="POST">'+
                                                '<input type="hidden" value="'+encode_id(kd_matkul)+'" name="id">'+
                                                '<button class="dropdown-item btn-delete" data-dest="/academic/curriculum">Hapus</button>'+
                                            '</form>'+
                                        '</div>'+
                                    '</div>'+
                                '</td>'
                    }

                    html += '<tr>'+
                                '<td>'+i+'</td>'+
                                '<td>'+prodi+'</td>'+
                                '<td>'+kd_matkul+'</td>'+
                                '<td>'+nama+'</td>'+
                                '<td class="text-center">'+semester+'</td>'+
                                '<td class="text-center">'+jenis+'</td>'+
                                '<td class="text-center">'+kompetensi_prodi+'</td>'+
                                '<td>'+versi+'</td>'+
                                '<td>'+sks_teori+'</td>'+
                                '<td>'+sks_seminar+'</td>'+
                                '<td>'+sks_praktikum+'</td>'+
                                '<td>'+capaian.join(', ')+'</td>'+
                                '<td>'+dokumen+'</td>'+
                                '<td>'+unit+'</td>'+
                                aksi+
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
    var role        = decode_id(cont.data('token'));
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
                            var nidn_dosen    = schedule.teacher.nidn;
                            var prodi_dosen   = schedule.teacher.study_program.singkatan;
                            var sesuai_prodi  = '';
                            var sesuai_bidang = '';
                            var aksi;


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

                            if(role!='kajur') {
                                aksi = '<td class="text-center">'+
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
                                        '</td>'
                            }

                            html += '<tr>'+
                                        '<td>'+kd_matkul+'</td>'+
                                        '<td>'+
                                            nm_matkul+'<br>'+
                                            '<small>'+prodi_matkul+'</small>'+
                                        '</td>'+
                                        '<td class="text-center">'+sks+'</td>'+
                                        '<td>'+
                                            '<a href="'+base_url+'/teacher/list/'+encode_id(nidn_dosen)+'">'+
                                                nm_dosen+'<br>'+
                                                '<small>NIDN. '+nidn_dosen+' / '+prodi_dosen+'</small>'+
                                            '</a>'+
                                        '</td>'+
                                        '<td class="text-center">'+sesuai_prodi+'</td>'+
                                        '<td class="text-center">'+sesuai_bidang+'</td>'+
                                        aksi+
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

$('#curriculumSchedule_form').on('change','select#selectProdiDsn', function() {
    var cont   = $('#curriculumSchedule_form');
    var prodi  = $(this).val();
    var target = $('.select2-dosen');

    if(prodi=='' || prodi==null) {
        target.prop('disabled',true);
        target.prop('required',false);
        target.val(null).trigger('change');
    } else {
        target.prop('disabled',false);
        target.prop('required',true);
        target.val(null).trigger('change');
        select2_dosen(target,prodi);
    }
})

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

/************************************* TUGAS AKHIR ****************************************/
$('form#filter-minithesis').submit(function(e){
    e.preventDefault();

    var cont        = $(this);
    var btn         = cont.find('button[type=submit]');
    var tabel       = $('#table-minithesis');
    var datacon     = cont.serializeArray();
    var url         = cont.attr('action');
    var role        = decode_id(cont.data('token'));
    var opsi_mahasiswa  = cont.find('select[name=prodi_mahasiswa] option:selected');
    var opsi_pembimbing = cont.find('select[name=prodi_pembimbing] option:selected');
    var jurusan = cont.find('input#nm_jurusan').val();

    if(opsi_mahasiswa.val() && opsi_pembimbing.val()) {
        var teks = 'Prodi Mahasiswa: '+opsi_mahasiswa.text()+ ' || Prodi Pembimbing: '+opsi_pembimbing.text();
        $('h6.card-title').text(teks);
    } else if(opsi_mahasiswa.val()) {
        var teks = 'Prodi Mahasiswa: '+opsi_mahasiswa.text();
        $('h6.card-title').text(teks);
    } else if(opsi_pembimbing.val()) {
        var teks = 'Prodi Pembimbing Utama: '+opsi_pembimbing.text();
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
                    var id               = val.id;
                    var judul            = val.judul;
                    var nama_mhs         = val.student.nama;
                    var nim_mhs          = val.student.nim;
                    var prodi_mhs        = val.student.study_program.singkatan;
                    var tahun            = val.academic_year.tahun_akademik+' - '+val.academic_year.semester;
                    var nama_utama       = val.pembimbing_utama.nama;
                    var nidn_utama       = val.pembimbing_utama.nidn;
                    var nama_pendamping  = val.pembimbing_pendamping.nama;
                    var nidn_pendamping  = val.pembimbing_pendamping.nidn;
                    var aksi;

                    if(role!='kajur') {
                        aksi = '<td class="text-center" width="50">'+
                                    '<div class="btn-group" role="group">'+
                                        '<button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                                            '<div><span class="fa fa-caret-down"></span></div>'+
                                        '</button>'+
                                        '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">'+
                                            '<a class="dropdown-item" href="'+base_url+'/academic/minithesis/'+encode_id(id)+'/edit">Sunting</a>'+
                                            '<form method="POST">'+
                                                '<input type="hidden" value="encode_id(id)" name="id">'+
                                                '<button class="dropdown-item btn-delete" data-dest="'+base_url+'/academic/minithesis">Hapus</button>'+
                                            '</form>'+
                                        '</div>'+
                                    '</div>'+
                                '</td>'
                    }

                    html += '<tr>'+
                                '<td>'+judul+'</td>'+
                                '<td>'+
                                    '<a href="'+base_url+'/student/list/'+encode_id(nim_mhs)+'">'+
                                        nama_mhs+'<br>'+
                                        '<small>NIM. '+nim_mhs+' / '+prodi_mhs+'</small>'+
                                    '</a>'+
                                '</td>'+
                                '<td class="text-center">'+tahun+'</td>'+
                                '<td>'+
                                    '<a href="'+base_url+'/teacher/list/'+encode_id(nidn_utama)+'">'+
                                        nama_utama+' ('+nidn_utama+')'+
                                    '</a>'+
                                '</td>'+
                                '<td>'+
                                    '<a href="'+base_url+'/teacher/list/'+encode_id(nidn_pendamping)+'">'+
                                        nama_pendamping+' ('+nidn_pendamping+')'+
                                    '</a>'+
                                '</td>'+
                                aksi+
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
/******************************************************************************************/

/********************************* INTEGRASI KEGIATAN - KURIKULUM *********************************/
if($('#curriculumIntegration_form select[name=kegiatan]').length) {
    load_select_activity();
}

$('#curriculumIntegration_form').on('change','select[name=kegiatan]', function(){
    load_select_activity();
});

function load_select_activity() {
    var select_src  = $('select[name=kegiatan]');
    var select      = $('.select-activity');
    var value       = select_src.val();

    if(value=="Penelitian") {
        var url_select2 = base_url+'/ajax/research/get_by_department';
        var placeholder = 'Masukkan nama penelitian';

        select.attr('name','id_penelitian');
        select.prop('disabled',false)
    } else if(value=="Pengabdian") {
        var url_select2 = base_url+'/ajax/community-service/get_by_department';
        var placeholder = 'Masukkan nama pengabdian';

        select.attr('name','id_pengabdian');
        select.prop('disabled',false)
    } else {
        select.removeAttr('name');
        select.prop('disabled',true)
    }

    if(value!='' || value!=null) {
        select.select2({
            width: '100%',
            language: "id",
            minimumInputLength: 5,
            allowClear: true,
            placeholder: placeholder,
            ajax: {
                dataType: 'json',
                url: url_select2,
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
}

/**************************************************************************************************/

/********************************* DATA KATEGORI PUBLIKASI *********************************/
$('#table-publishCat').on('click','.btn-edit',function(){

    var id  = $(this).data('id');
    var url = base_url+'/ajax/publication/category/'+id;

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
    var role    = decode_id(cont.data('token'));
    var opsi    = cont.find('select[name=kd_prodi] option:selected');
    var jurusan = cont.find('input#nm_jurusan').val();
    var type    = cont.data('type');

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

                    if(type=='teacher') {
                        var id_unik     = val.teacher.nidn;
                        var nama        = val.teacher.nama;
                        var prodi       = val.teacher.study_program.singkatan;
                        var id_cat      = 'NIDN.'
                    } else {
                        var id_unik     = val.student.nim;
                        var nama        = val.student.nama;
                        var prodi       = val.student.study_program.singkatan;
                        var id_cat = 'NIM.'
                    }

                    var id          = val.id;
                    var judul       = val.judul;
                    var kategori    = val.publication_category.nama;
                    var tahun       = val.tahun;
                    var sesuai_prodi = '';
                    var aksi;

                    if(val.sesuai_prodi!=null) {
                        sesuai_prodi = '<i class="fa fa-check"></i>';
                    }

                    if(role!='kajur') {
                        aksi = '<td class="text-center" width="50">'+
                                    '<div class="btn-group" role="group">'+
                                        '<button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                                            '<div><span class="fa fa-caret-down"></span></div>'+
                                        '</button>'+
                                        '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">'+
                                            '<a class="dropdown-item" href="'+base_url+'/publication/'+type+'/'+encode_id(id)+'/edit">Sunting</a>'+
                                            '<form method="POST">'+
                                                '<input type="hidden" value="'+encode_id(id)+'" name="id">'+
                                                '<button class="dropdown-item btn-delete" data-dest="'+base_url+'/publication/'+type+'">Hapus</button>'+
                                            '</form>'+
                                        '</div>'+
                                    '</div>'+
                                '</td>'
                    }

                    html +='<tr>'+
                            '<td>'+
                                nama+'<br>'+
                                '<small>'+id_cat+id_unik+' / '+prodi+'</small>'+
                            '</td>'+
                            '<td>'+
                                '<a href="'+base_url+'/publication/teacher/'+encode_id(id)+'">'+judul+'</a>'+
                            '</td>'+
                            '<td>'+kategori+'</td>'+
                            '<td class="text-center">'+tahun+'</td>'+
                            '<td class="text-center">'+sesuai_prodi+'</td>'+
                            aksi+
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

/********************************* DATA LUARAN *********************************/
$(document).on('change','select[name=kegiatan]',function(){

    var input_kegiatan = $('#outputActivity_form').find('input[name=nm_kegiatan]');

    var kegiatan = $(this).val();

    if(kegiatan != '') {
        input_kegiatan.prop('disabled',false);
        input_kegiatan.prop('required',true);
    } else {
        input_kegiatan.prop('disabled',true);
        input_kegiatan.prop('required',false);
    }
})

$('#outputActivity_form').on('change','select#jenis',function(){

    var jenis = $(this).val();
    var karya = $('#outputActivity_form').find('div#karya');

    karya.find('div.row.mb-3, div.row.mg-t-20').addClass('d-none');
    karya.find('input').prop('disabled',true);
    karya.find('input').prop('required',false);
    karya.find('select').prop('disabled',true);
    karya.find('select').prop('required',false);

    if(jenis == 'Buku')
    {
        $('#nama_karya').removeClass('d-none');
        $('#nama_karya').find('label').html('Nama Buku: <span class="tx-danger">*</span>');
        $('#nama_karya').find('input').prop('disabled',false);
        $('#nama_karya').find('input').prop('required',true);

        $('#issn').removeClass('d-none');
        $('#issn').find('input').prop('disabled',false);
        $('#issn').find('input').prop('required',true);

        $('#penerbit').removeClass('d-none');
        $('#penerbit').find('input').prop('disabled',false);
        $('#penerbit').find('input').prop('required',true);

        $('#url').removeClass('d-none');
        $('#url').find('input').prop('disabled',false);

        $('#keterangan').removeClass('d-none');
        $('#keterangan').find('input').prop('disabled',false);

        $('#file').removeClass('d-none');
        $('#file').find('input').prop('disabled',false);


    }
    else if(jenis == 'Jurnal')
    {
        $('#nama_karya').removeClass('d-none');
        $('#nama_karya').find('label').html('Nama Jurnal: <span class="tx-danger">*</span>');
        $('#nama_karya').find('input').prop('disabled',false);
        $('#nama_karya').find('input').prop('required',true);

        $('#issn').removeClass('d-none');
        $('#issn').find('input').prop('disabled',false);
        $('#issn').find('input').prop('required',true);

        $('#penyelenggara').removeClass('d-none');
        $('#penyelenggara').find('input').prop('disabled',false);
        $('#penyelenggara').find('input').prop('required',true);

        $('#url').removeClass('d-none');
        $('#url').find('input').prop('disabled',false);

        $('#keterangan').removeClass('d-none');
        $('#keterangan').find('input').prop('disabled',false);

        $('#file').removeClass('d-none');
        $('#file').find('input').prop('disabled',false);


    }
    else if(jenis == 'HKI')
    {
        $('#nama_karya').removeClass('d-none');
        $('#nama_karya').find('label').html('Nama Karya: <span class="tx-danger">*</span>');
        $('#nama_karya').find('input').prop('disabled',false);
        $('#nama_karya').find('input').prop('required',true);

        $('#jenis_karya').removeClass('d-none');
        $('#jenis_karya').find('input').prop('disabled',false);
        $('#jenis_karya').find('input').prop('required',true);

        $('#pencipta_karya').removeClass('d-none');
        $('#pencipta_karya').find('input').prop('disabled',false);
        $('#pencipta_karya').find('input').prop('required',true);

        $('#no_permohonan').removeClass('d-none');
        $('#no_permohonan').find('input').prop('disabled',false);
        $('#no_permohonan').find('input').prop('required',true);

        $('#tgl_permohonan').removeClass('d-none');
        $('#tgl_permohonan').find('input').prop('disabled',false);
        $('#tgl_permohonan').find('input').prop('required',true);

        $('#keterangan').removeClass('d-none');
        $('#keterangan').find('input').prop('disabled',false);

        $('#file').removeClass('d-none');
        $('#file').find('input').prop('disabled',false);

    }
    else if(jenis == 'Paten')
    {
        $('#nama_karya').removeClass('d-none');
        $('#nama_karya').find('label').html('Nama Karya: <span class="tx-danger">*</span>');
        $('#nama_karya').find('input').prop('disabled',false);
        $('#nama_karya').find('input').prop('required',true);

        $('#jenis_karya').removeClass('d-none');
        $('#jenis_karya').find('input').prop('disabled',false);
        $('#jenis_karya').find('input').prop('required',true);

        $('#pencipta_karya').removeClass('d-none');
        $('#pencipta_karya').find('input').prop('disabled',false);
        $('#pencipta_karya').find('input').prop('required',true);

        $('#no_paten').removeClass('d-none');
        $('#no_paten').find('input').prop('disabled',false);
        $('#no_paten').find('input').prop('required',true);

        $('#tgl_sah').removeClass('d-none');
        $('#tgl_sah').find('input').prop('disabled',false);
        $('#tgl_sah').find('input').prop('required',true);

        $('#keterangan').removeClass('d-none');
        $('#keterangan').find('input').prop('disabled',false);

        $('#file').removeClass('d-none');
        $('#file').find('input').prop('disabled',false);
    }
    else if(jenis == 'Lainnya')
    {
        $('#nama_karya').removeClass('d-none');
        $('#nama_karya').find('label').html('Nama Karya: <span class="tx-danger">*</span>');
        $('#nama_karya').find('input').prop('disabled',false);
        $('#nama_karya').find('input').prop('required',true);

        $('#keterangan').removeClass('d-none');
        $('#keterangan').find('input').prop('disabled',false);

        $('#file').removeClass('d-none');
        $('#file').find('input').prop('disabled',false);
    }
})

$('form#filter-outputActivity').submit(function(e){
    e.preventDefault();

    var cont    = $(this);
    var btn     = cont.find('button[type=submit]');
    var tabel   = $('#table_outputActivity');
    var datacon = cont.serializeArray();
    var url     = cont.attr('action');
    var role    = decode_id(cont.data('token'));
    var opsi    = cont.find('select[name=kd_prodi] option:selected');
    var jurusan = cont.find('input#nm_jurusan').val();
    var type    = cont.data('type');

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

                    if(type=='teacher') {
                        var id_unik     = val.teacher.nidn;
                        var nama        = val.teacher.nama;
                        var prodi       = val.teacher.study_program.singkatan;
                        var id_cat      = 'NIDN.'
                    } else {
                        var id_unik     = val.student.nim;
                        var nama        = val.student.nama;
                        var prodi       = val.student.study_program.singkatan;
                        var id_cat = 'NIM.'
                    }

                    var id          = val.id;
                    var judul       = val.judul_luaran;
                    var jenis       = val.jenis_luaran;
                    var kategori    = val.output_activity_category.nama;
                    var tahun       = val.thn_luaran;
                    var kegiatan    = val.kegiatan;
                    var aksi;

                    if(role!='kajur') {
                        aksi = '<td class="text-center" width="50">'+
                                    '<div class="btn-group" role="group">'+
                                        '<button id="btn-action" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                                            '<div><span class="fa fa-caret-down"></span></div>'+
                                        '</button>'+
                                        '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">'+
                                            '<a class="dropdown-item" href="'+base_url+'/output-activity/'+type+'/'+encode_id(id)+'/edit">Sunting</a>'+
                                            '<form method="POST">'+
                                                '<input type="hidden" value="'+encode_id(id)+'" name="id">'+
                                                '<button class="dropdown-item btn-delete" data-dest="'+base_url+'/output-activity/'+type+'">Hapus</button>'+
                                            '</form>'+
                                        '</div>'+
                                    '</div>'+
                                '</td>'
                    }

                    html +='<tr>'+
                            '<td>'+
                                '<a href="'+base_url+'/output-activity/teacher/'+encode_id(id)+'">'+judul+'</a><br>'+
                                '<small>'+nama+' ('+id_unik+') / '+prodi+'</small>'+
                            '</td>'+
                            '<td class="text-center">'+jenis+'</td>'+
                            '<td class="text-center">'+kategori+'</td>'+
                            '<td class="text-center">'+tahun+'</td>'+
                            '<td class="text-center">'+kegiatan+'</td>'+
                            aksi+
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

/********************************* DATA KATEGORI KEPUASAN *********************************/
$('#table-satisfactionCategory').on('click','.btn-edit',function(){

    var id  = $(this).data('id');
    var url = base_url+'/ajax/satisfaction-category/'+id;

    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#form-satisfactionCategory')
                .find('input[name=id]').val(id).end()
                .find('select[name=jenis]').val(data.jenis).end()
                .find('input[name=nama]').val(data.nama).end()
                .find('input[name=alias]').val(data.alias).end()
                .find('textarea[name=deskripsi]').val(data.deskripsi).end()
        }
    });
})

$('#form-satisfactionCategory')
    .on('click','.btn-add',function(e){
        var form  = $('#form-satisfactionCategory')
        form.trigger('reset');
    }).end()
/****************************************************************************************/

/********************************* DATA WAKTU TUNGGU LULUSAN *********************************/

$('#modal-alumnus-idle, #modal-alumnus-suitable, #modal-alumnus-workplace').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
    $(this).find('input[name=tahun_lulus]').removeAttr('value').end()
    $(this).find('select[name=tahun_lulus]').show().end()
    $(this).find('input[name=tahun_lulus]').hide().end()
})

$('#modal-alumnus-idle, #modal-alumnus-suitable, #modal-alumnus-workplace').on('change','#manual',function(){
    var kd_prodi = $('input[name=kd_prodi]').val();
    var tahun    = $('[name=tahun_lulus]').val();

    var url = base_url+'/ajax/alumnus/get';

    if ($('#manual').is(':checked') == true){
        $('input[name=jumlah_lulusan]').val('').prop('readonly', false);
    } else {
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                tahun:tahun,
                prodi:kd_prodi
            },
            dataType: 'json',
            success: function (data) {
                $('input[name=jumlah_lulusan]').val(data).prop('readonly', true);
            }
        });
    }
})

$('#modal-alumnus-idle, #modal-alumnus-suitable, #modal-alumnus-workplace').on('change','select[name=tahun_lulus]',function(){
    var kd_prodi = $('input[name=kd_prodi]').val();
    var tahun    = $(this).val();

    var url = base_url+'/ajax/alumnus/get';

    $.ajax({
        url: url,
        type: 'GET',
        data: {
            tahun:tahun,
            prodi:kd_prodi
        },
        dataType: 'json',
        success: function (data) {
            $('input[name=jumlah_lulusan]').val(data).prop('readonly', true);
        }
    });
})
/****************************************************************************************/

/********************************* DATA KESESUAIAN BIDANG KERJA LULUSAN *********************************/
$('#table-alumnusSuitable').on('click','.btn-edit',function(){

    var id  = $(this).data('id');
    var url = base_url+'/ajax/alumnus/suitable/'+id;

    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#modal-alumnus-suitable')
                .find('input[name=id]').val(id).end()
                .find('input[name=tahun_lulus]').val(data.tahun_lulus).end()
                .find('select[name=tahun_lulus]').hide().end()
                .find('input[name=tahun_lulus]').show().end()
                .find('input#manual').prop('checked',true).end()
                .find('input[name=jumlah_lulusan]').val(data.jumlah_lulusan).prop('readonly',false).end()
                .find('input[name=lulusan_terlacak]').val(data.lulusan_terlacak).end()
                .find('input[name=sesuai_rendah]').val(data.sesuai_rendah).end()
                .find('input[name=sesuai_sedang]').val(data.sesuai_sedang).end()
                .find('input[name=sesuai_tinggi]').val(data.sesuai_tinggi).end()
                .modal('toggle').end();

        }
    });
})
/********************************************************************************************************/

/*************************************** DATA TEMPAT KERJA LULUSAN ***********************************/

$('#table-alumnusWorkplace').on('click','.btn-edit',function(){

    var id  = $(this).data('id');
    var url = base_url+'/ajax/alumnus/workplace/'+id;

    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#modal-alumnus-workplace')
                .find('input[name=id]').val(id).end()
                .find('input[name=tahun_lulus]').val(data.tahun_lulus).end()
                .find('select[name=tahun_lulus]').hide().end()
                .find('input[name=tahun_lulus]').show().end()
                .find('input#manual').prop('checked',true).end()
                .find('input[name=jumlah_lulusan]').val(data.jumlah_lulusan).prop('readonly',false).end()
                .find('input[name=lulusan_bekerja]').val(data.lulusan_bekerja).end()
                .find('input[name=kerja_lokal]').val(data.kerja_lokal).end()
                .find('input[name=kerja_nasional]').val(data.kerja_nasional).end()
                .find('input[name=kerja_internasional]').val(data.kerja_internasional).end()
                .modal('toggle').end();

        }
    });
})
/****************************************************************************************************/

/******************************************* DATA USER *********************************************/


/****************************************************************************************************/
