<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Feedback</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form id="edit-feedback-form" action="">
        @csrf
        <input type="hidden" name="id" value="{{ $modul->id }}">
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Solusi<span class="required-form-star">*</span></label>
                        <textarea name="problem_solving_description" placeholder="Solusi" class=" form-control" required></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Status<span class="required-form-star">*</span></label>
                        <input type="text" name="status" placeholder="On Check / Solved" class="form-control" required>
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
$("#edit-feedback-form").submit(function(event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    var formData = new FormData($(this)[0]);
    $.ajax({
        url: '{!! url("/feedback/update/") !!}',
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
                    timer: 1000, 
                })
                $('#modal-default').modal('hide');
                $('#tblfeedback').DataTable().ajax.reload();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Opss...',
                    text: e.message, 
                    timer: 1000, 
                })
            }
        }
    });
});
</script>

