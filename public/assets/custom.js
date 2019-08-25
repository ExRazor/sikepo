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

// Toggles
$('.br-toggle').on('click', function(e){
    // e.preventDefault();
    // $(this).toggleClass('on');
})

//Modal
$('.modal').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
})

// Tahun Akademik
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

                alertify.success(state.success);
            }           
            
        }
    });
})

$('#btn-add-ay').click(function(e) {
    e.preventDefault();

    var data = $('#form-academicYear').serialize();

    $.ajax({
        url: base_url+'/master/academic-year',
        data: data,
        type: 'POST',
        dataType: 'json',
        beforeSend: function() {
            $('#btn-add-ay').addClass('disabled');
            $('.btn-cancel').addClass('disabled');
            $('#btn-add-ay').html('<i class="fa fa-spinner fa-spin"></i>');
        },
        success: function (state) {

            $('#academicYear-add').modal('toggle');

            Swal.fire({
                title: "Berhasil!",
                text: "Data anda telah ditambahkan!",
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

            $('#btn-add-ay').removeClass('disabled');
            $('.btn-cancel').removeClass('disabled');
            $('#btn-add-ay').html('Simpan');
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
                        title: "Berhasil!",
                        text: state.success,
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

$('.btn-edit-ays').click(function(e){
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
                        title: "Berhasil!",
                        text: state.success,
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
