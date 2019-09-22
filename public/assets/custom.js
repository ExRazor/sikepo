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

// Toggles
$('.br-toggle').on('click', function(e){
    // e.preventDefault();
    // $(this).toggleClass('on');
})

//Modal
$('.modal').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
    $('.alert-danger').hide();
    $('input, select').removeClass('is-invalid');
})

//Delete Button
$('.btn-delete').click(function(e){
    e.preventDefault();

    var form = $(this).closest('form');
    var data = form.serialize();
    var url  = base_url+$(this).data('dest');

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
                            window.location = url;
                        }
                    });

                }
            });
            // console.log(result.value);
        }
    })
})

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
            $('#btn-save-ay').val('put');
            $('#academicYear-form').modal('toggle');
        }
    });
})
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

/********************************* KERJA SAMA PROGRAM STUDI *********************************/

$('#bukti_kerjasama').change(function(){
    var fileName = $(this).val();
    // removing the fake path (Chrome)
    fileName = fileName.replace("C:\\fakepath\\", "");
    //replace the "Choose a file" label
    $(this).next('.custom-file-label').html(fileName);
});

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
};

/********************************************************************************************/

/********************************* DATA DOSEN *********************************/
$('.btn-submit-teacher').click(function(e){
    e.preventDefault();

    $('form[name=teacher_form]').submit();
    // alert('hehe');
})

/*****************************************************************************/
