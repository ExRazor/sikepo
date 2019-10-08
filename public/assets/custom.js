// URL
var base_url = window.location.origin;
var host = window.location.host;
var pathArray = window.location.pathname.split( '/' );

//Ajax Setup
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('.datepicker').datepicker({
    dateFormat: 'yy-mm-dd'
});

$('.select2').select2();

//Tab
$(document).ready(function() {
    // your existing tabs click listener
    $('a.tab-link').click(function() {
        location.hash = $(this).attr('href');
    })
    // get hash from url
    var hash = location.hash;
    if (hash) {
      // now trigger click on appropriate tab
      $('a.tab-link[href="' + hash + '"]').click();
    } else {
      $("a.tab-link").first().click();
    }
})

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
$('.btn-edit').click(function(e){
    e.preventDefault();

    $('span.title-action').text('Sunting');
    $('.btn-save').val('put');
});

$('.btn-save').click(function(e) {
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
                type: "success",
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
        }
    });

});

//Delete Button
$('.btn-delete').click(function(e){
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
                    Swal.fire({
                        title: state.title,
                        text: state.message,
                        type: "success",
                        timer: 2000,
                        onClose: () => {
                            location.reload();
                        }
                    });

                }
            });
            // console.log(result.value);
        }
    })
})

/***********************************************************************************/

/********************************* DATA TABLE *********************************/
if($().DataTable) {
    $('.table-kerjasama').DataTable({
        order: [[1, 'asc']],
        responsive: true,
        language: {
        searchPlaceholder: 'Cari...',
        sSearch: '',
        lengthMenu: '_MENU_ items/page',
        },
        columnDefs: [
            { "orderable": false, "targets": 'no-sort' }
        ]
    });
    $('.table-ewmp').DataTable({
        order: [[1, 'desc']],
        responsive: true,
        language: {
        searchPlaceholder: 'Cari...',
        sSearch: '',
        lengthMenu: '_MENU_ items/page',
        },
        columnDefs: [
            { "orderable": false, "targets": 'no-sort' }
        ]
    });
};
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

$('.btn-add-ay').click(function(e) {
    $('h6.title-ay').text('Tambah Tahun Akademik');
    $('#btn-save-ay').val('post');
});

$('#btn-save-ay').click(function(e) {
    e.preventDefault();

    var action = $(this).val();
    var data = $('#form-academicYear').serialize();
    var cont = $(this);

    $.ajax({
        url: base_url+'/master/academic-year',
        data: data,
        type: action,
        dataType: 'json',
        beforeSend: function() {
            cont.addClass('disabled');
            $('.btn-cancel').addClass('disabled');
            cont.html('<i class="fa fa-spinner fa-spin"></i>');
        },
        success: function (state) {

            $('#academicYear-form').modal('toggle');

            Swal.fire({
                title: state.title,
                text: state.message,
                type: "success",
                timer: 1500,
                onClose: () => {
                    window.location = "/master/academic-year";
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
        }
    });

})

$('.btn-delete-ay').click(function(e){
    e.preventDefault();

    var form = $(this).closest('form');
    var data = form.serialize();

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
                url: base_url+'/master/academic-year',
                data: data,
                type: 'DELETE',
                dataType: 'json',
                beforeSend: function() {
                    Swal.showLoading()
                },
                success: function (state) {
                    Swal.fire({
                        title: state.title,
                        text: state.message,
                        type: "success",
                        timer: 2000,
                        onClose: () => {
                            window.location = "/master/academic-year";
                        }
                    });

                }
            });
        // console.log(result.value);
        }
      })
})

$('.btn-edit-ay').click(function(e){
    e.preventDefault();

    $('#btn-save-ay').val('put');

    var id = $(this).attr('href');
    $.ajax({
        url: base_url+'/master/academic-year/'+id,
        data: {id:id},
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            $('input[name=id]').val(id);
            $('input[name=tahun_akademik]').val(data.tahun_akademik);
            $('select[name=semester]').val(data.semester);
            $('h6.title-ay').text('Sunting Tahun Akademik ');
            $('#academicYear-form').modal('toggle');
        }
    });
});

$('.custom-file-input').change(function(){
    var fileName = $(this).val();
    // removing the fake path (Chrome)
    fileName = fileName.replace("C:\\fakepath\\", "");
    //replace the "Choose a file" label
    $(this).next('.custom-file-label').html(fileName);
});
/*********************************************************************************/

/********************************* PROGRAM STUDI *********************************/
$('.btn-show-sp').click(function(e){

    e.preventDefault();
    var id = $(this).data('id');

    $.ajax({
        url: base_url+'/master/study-program/'+id,
        data: {id:id},
        type: 'GET',
        dataType: 'json',
        beforeSend: function() {
            $('span[name*=prodi]').text('');
        },
        success: function (data) {
            $('span[name=kd_prodi]').text(data.kd_prodi);
            $('span[name=nama_prodi]').text(data.nama);
            $('span[name=jenjang_prodi]').text(data.jenjang);
            $('span[name=no_sk_prodi]').text(data.no_sk);
            $('span[name=tgl_sk_prodi]').text(data.tgl_sk);
            $('span[name=pejabat_sk_prodi]').text(data.pejabat_sk);
            $('span[name=thn_menerima_prodi]').text(data.thn_menerima);
            $('span[name=singkatan_prodi]').text(data.singkatan);

            $('#studyProgram-show').modal('show')
        }
    })
});

$('.btn-delete-sp').click(function(e){
    e.preventDefault();

    var form = $(this).closest('form');
    var data = form.serialize();

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
                url: base_url+'/master/study-program',
                data: data,
                type: 'DELETE',
                dataType: 'json',
                beforeSend: function() {
                    Swal.showLoading()
                },
                success: function (state) {
                    Swal.fire({
                        title: state.title,
                        text: state.message,
                        type: "success",
                        timer: 2000,
                        onClose: () => {
                            window.location = "/master/study-program";
                        }
                    });

                }
            });
            // console.log(result.value);
        }
    })
})

/*********************************************************************************/

/********************************* DATA DOSEN *********************************/
$('.btn-submit-teacher').click(function(e){
    e.preventDefault();

    $('form[name=teacher_form]').submit();
    // alert('hehe');
})

$('#foto_profil').change(function(){
    var fileName = $(this).val();
    // removing the fake path (Chrome)
    fileName = fileName.replace("C:\\fakepath\\", "");
    //replace the "Choose a file" label
    $(this).next('.custom-file-label').html(fileName);
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

$('#filter-ewmpsss').submit(function(e){
    e.preventDefault();

    var data = $(this).serialize();
    var url  = $(this).attr('action');

    $.ajax({
        url: url,
        data: data,
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            if(data.length > 0) {
                var html = ''
                for (i = 0; i < data.length; i++) {

                    var ps_intra        = parseInt(data[i].ps_intra);
                    var ps_lain         = parseInt(data[i].ps_lain);
                    var ps_luar         = parseInt(data[i].ps_luar);
                    var penelitian      = parseInt(data[i].penelitian);
                    var pkm             = parseInt(data[i].pkm);
                    var tugas_tambahan  = parseInt(data[i].tugas_tambahan);
                    var total = parseInt(ps_intra+ps_lain+ps_luar+penelitian+pkm+tugas_tambahan);
                    var rata  = parseFloat(total/6).toFixed(2);
                    html += '<tr>'+
                                '<td>'+data[i].nama+'</td>'+
                                '<td>'+data[i].tahun_akademik+' - '+data[i].semester+'</td>'+
                                '<td>'+data[i].ps_intra+'</td>'+
                                '<td>'+data[i].ps_lain+'</td>'+
                                '<td>'+data[i].ps_luar+'</td>'+
                                '<td>'+data[i].penelitian+'</td>'+
                                '<td>'+data[i].pkm+'</td>'+
                                '<td>'+data[i].tugas_tambahan+'</td>'+
                                '<td>'+total+'</td>'+
                                '<td>'+rata+'</td>'+
                                '<td class="text-center" width="50">'+
                                    '<div class="btn-group" role="group">'+
                                        '<button id="btn-action" type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                                            '<div><span class="fa fa-caret-down"></span></div>'+
                                        '</button>'+
                                        '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn-action">'+
                                            '<a class="dropdown-item btn-edit btn-edit-ewmp" href="#" data-id="{{encrypt($e->id)}}">Sunting</a>'+
                                            '<form method="POST">'+
                                                '@method("delete")'+
                                                '@csrf'+
                                                '<input type="hidden" value="{{encrypt($e->id)}}" name="_id">'+
                                                '<a href="#" class="dropdown-item btn-delete" data-dest="{{ route("ewmp.delete") }}">Hapus</a>'+
                                            '</form>'+
                                        '</div>'+
                                    '</div>'+
                                '</td>'+
                            '<tr>'
                }

                $('.table-ewmp tbody').html(html);

                console.log(data);






            }



        }
    });
    console.log(data);
})
/***********************************************************************************/

/********************************* DATA PRESTASI DOSEN *********************************/
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
            $('input[name=tanggal_dicapai]').val(data.tanggal);

            $('#modal-teach-acv').modal('toggle');
        }
    });
});
/***********************************************************************************/
