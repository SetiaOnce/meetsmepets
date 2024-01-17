"use strict";
//Class Definition
var table;
//Load Datatables Data Kantor
const _loadDataKantor = () => {
    table = $('#dt-kantor').DataTable({
        searchDelay: 300,
        processing: true,
        serverSide: false,
        ajax: {
            url: base_url+ 'app_admin/sik/ajax/load_data_kantor',
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
            { data: 'kode_kantor', name: 'kode_kantor'},
            { data: 'nama_kantor', name: 'nama_kantor'},
            { data: 'kode_struktur', name: 'kode_struktur'},
            { data: 'eselon', name: 'eselon'},
            { data: 'level_struktur', name: 'level_struktur'},
            { data: 'nama_struktur', name: 'nama_struktur'},
            { data: 'nama_pejabat', name: 'nama_pejabat'},
            { data: 'nip_pejabat', name: 'nip_pejabat'},
        ],
        //Set column definition initialisation properties.
        "columnDefs": [
            { "width": "5%", "targets": 0, "className": "align-top text-center" },
            { "width": "10%", "targets": 1, "className": "align-top" },
            { "width": "20%", "targets": 2, "className": "align-top" },
            { "width": "10%", "targets": 3, "className": "align-top" },
            { "width": "10%", "targets": 4, "className": "align-top text-center", "orderable": false },
            { "width": "10%", "targets": 5, "className": "align-top text-center", "orderable": false },
            { "width": "15%", "targets": 6, "className": "align-top", "orderable": false },
            { "width": "10%", "targets": 7, "className": "align-top", "orderable": false },
            { "width": "10%", "targets": 8, "className": "align-top", "orderable": false },
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
            $("#dt-kantor_length select").selectpicker(),
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
    $("#dt-kantor").css("width", "100%");
}
// Class Initialization
jQuery(document).ready(function() {
    _loadDataKantor();
});