<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Tambah User Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form id="add-user-form" action="">
        @csrf
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Nama<span class="required-form-star">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Username<span class="required-form-star">*</span></label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Email<span class="required-form-star">*</span></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Password<span class="required-form-star">*</span></label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <input type="file" name="foto" class="form-control" id="image-input">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
        </div>
    </form>
</div>

<script>
$("#add-user-form").submit(function(event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    var formData = new FormData($(this)[0]);
    $.ajax({
        url: '{!! url("/user/store/") !!}',
        type: "POST",
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(e) {
            if (e.status == 1) {
                Swal.fire({
                    icon: 'success', 
                    title: 'Berhasil', 
                    text: e.message, 
                    timer: 1500, 
                })
                $('#modal-default').modal('hide');
                $('#tbluser').DataTable().ajax.reload();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Opss...',
                    text: e.message, 
                    timer: 1500, 
                })
            }
        }
    });
});
</script>

