<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Detail Kehadiran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form validate>
        <div class="modal-body">         
            <div class="row">
                <div class="email-leftbar card" style="margin-right:10px;">
                    <span class='badge badge-pill badge-soft-success font-size-14'>Check In</span>
                    <br>
                    <img src="{{ $modul->photo_check_in ?? '/assets/images/users/avatar-1.png' }}" alt="" class="img-fluid mx-auto d-block tab-img rounded">
                </div>
                
                <div class="email-leftbar card">
                    <span class='badge badge-pill badge-soft-success font-size-14'>Check Out</span>
                    <br>
                    <img src="{{ $modul->photo_check_out ?? '/assets/images/users/avatar-1.png' }}" alt="" class="img-fluid mx-auto d-block tab-img rounded">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Tutup</button>
        </div>
    </form>
</div>
