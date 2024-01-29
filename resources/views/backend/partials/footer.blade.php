<!--begin::Footer-->
<div id="kt_app_footer" class="app-footer">
    <div class="app-container container-fluid d-flex flex-column">
        <div class="text-dark order-2 order-md-1 text-center" id="footerCopyright">
            <h3 class="placeholder-glow">
                <span class="placeholder rounded col-4"></span>
            </h3>
        </div>
    </div>
</div>
<!--end::Footer-->
<!--begin::Modal Read Pendaftaran to Approve-->
<div class="modal fade" tabindex="-1" id="mdl-dtlRegisterToApprove" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-danger" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg fs-2x"></i>
                </div>
                <!--end::Close-->
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <div class="me-3">
                    <button type="button" class="btn btn-sm btn-danger validasi-register" id="btn-rejectRegister">
                        <span class="indicator-label d-flex align-items-center">
                            <i class="ki-duotone ki-cross fs-3 me-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>Tolak
                        </span>
                        <span class="indicator-progress">
                            Mohon tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-sm btn-primary validasi-register" id="btn-approveRegister">
                        <span class="indicator-label d-flex align-items-center">
                            <i class="ki-duotone ki-check fs-3 me-1"></i>Setujui
                        </span>
                        <span class="indicator-progress">
                            Mohon tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
                <button type="button" class="btn btn-sm btn-light btn-active-light-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg fs-3 me-2"></i>Tutup</button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal Read Pendaftaran to Approve-->