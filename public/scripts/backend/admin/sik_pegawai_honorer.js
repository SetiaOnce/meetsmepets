"use strict";
//Class Definition
var table;
//Load Datatables Data Kantor
const _loadDataKantor = () => {
    table = $('#dt-pegawaiHonorer').DataTable({
        searchDelay: 300,
        processing: true,
        serverSide: false,
        ajax: {
            url: base_url+ 'app_admin/sik/ajax/load_pegawai_honorer',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            type: 'POST',
        },
        destroy: true,
        draw: true,
        deferRender: true,
        responsive: false,
        autoWidth: false,
        LengthChange: true,
        paginate: true,
        pageResize: true,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex'},
            { data: 'nama', name: 'nama'},
            { data: 'nik', name: 'nik'},
            { data: 'tanggal_lahir', name: 'tanggal_lahir'},
            { data: 'jenis_kelamin', name: 'jenis_kelamin'},
            { data: 'unit', name: 'unit'},
            { data: 'kantor', name: 'kantor'},
        ],
        //Set column definition initialisation properties.
        "columnDefs": [
            { "width": "5%", "targets": 0, "className": "align-top text-center" },
            { "width": "25%", "targets": 1, "className": "align-top" },
            { "width": "20%", "targets": 2, "className": "align-top" },
            { "width": "10%", "targets": 3, "className": "align-top text-center" },
            { "width": "10%", "targets": 4, "className": "align-top text-center", "orderable": false },
            { "width": "20%", "targets": 5, "className": "align-top", "orderable": false },
            { "width": "20%", "targets": 6, "className": "align-top", "orderable": false },
        ],
        oLanguage: {
            sEmptyTable: "Tidak ada Data yang dapat ditampilkan..",
            sInfo: "Menampilkan _START_ s/d _END_ dari _TOTAL_",
            sInfoEmpty: "Menampilkan 0 - 0 dari 0 entri.",
            sInfoFiltered: "",
            sProcessing: `<div class="d-flex justify-content-center align-items-center"><span class="spinner-border align-middle me-3"></span> Mohon Tunggu...</div>`,
            sZeroRecords: "Tidak ada Data yang dapat ditampilkan..",
            sLengthMenu: `<select class="mb-2 show-tick form-select-solid" data-width="fit" data-style="btn-sm btn-secondary" data-container="body">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="40">40</option>
                <option value="50">50</option>
                <option value="-1">Semua</option>
            </select>`,
            oPaginate: {
                sPrevious: "Sebelumnya",
                sNext: "Selanjutnya",
            },
        },
        "dom": "<'row'<'col-sm-6 d-flex align-items-center justify-conten-start'l><'col-sm-6 d-flex align-items-center justify-content-end'f>><'table-responsive'tr><'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>",
        fnDrawCallback: function (settings, display) {
            $('[data-bs-toggle="tooltip"]').tooltip("dispose"), $(".tooltip").hide();
            //Custom Table
            $("#dt-pegawaiHonorer_length select").selectpicker(),
            $('[data-bs-toggle="tooltip"]').tooltip({ 
                trigger: "hover"
            }).on("click", function () {
                $(this).tooltip("hide");
            });
            $('.image-popup').magnificPopup({
                type: 'image', closeOnContentClick: true, closeBtnInside: false, fixedContentPos: true,
                image: {
                    verticalFit: true
                }
            });
        },
    });
    $("#dt-pegawaiHonorer").css("width", "100%");
}
// handle sincronize data pegawai
const _sinkronisasi = () => {
    $('#btn-btnSincronice').attr('data-kt-indicator', 'on').attr('disabled', true);
    Swal.fire({
        title: "",
        html: 'Sinkronisasi data pegawai?',
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Ya",
        cancelButtonText: "Tidak, Batalkan!"
    }).then(result => {
        if (result.value) {
            let target = document.querySelector('#card-data'), blockUi = new KTBlockUI(target, {message: messageBlockUi, zIndex: 9 });
            blockUi.block(), blockUi.destroy();

            $.ajax({
                url: base_url+ 'app_admin/sik/ajax/sincronize_sik_pegawai_honorer',
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: 'POST',
                dataType: 'JSON',
                success: function (data) {
                    $('#btn-btnSincronice').removeAttr('data-kt-indicator').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    if (data.status == true) {
                        Swal.fire({title: "Success!",text: "Sinkronisasi data pegawai sukses...",icon: "success",allowOutsideClick: false,})
                    } else {
                        if(data.pesan_error) {
							Swal.fire({title: "Ooops!", text: data.pesan_error, icon: "warning", allowOutsideClick: false});  
						}else{
                            Swal.fire({title: "Ooops!", text: "Gagal memproses data, Periksa koneksi internet lalu coba kembali...", icon: "warning", allowOutsideClick: false});
                        }
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $('#btn-btnSincronice').removeAttr('data-kt-indicator').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    console.log("Proses data is error!");
                    Swal.fire({
                        title: "Ooops!",
                        text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.",
                        icon: "error",
                        allowOutsideClick: false,
                    });
                },
            });
        }else{
            $('#btn-btnSincronice').removeAttr('data-kt-indicator').attr('disabled', false);
        }
    });
}
// Class Initialization
jQuery(document).ready(function() {
    _loadDataKantor();
});