<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Edit Waktu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form id="edit-time" action="">
        @csrf
        <input type="hidden" name="id" value="{{ $modul->id }}">
        <div class="modal-body">

            <h4>Senin - Jumat</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Jam Masuk Awal<span class="required-form-star">*</span></label>
                        <input type="time" name="check_in_start" value="{{ $modul->check_in_start }}" class="form-control" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Jam Masuk Akhir<span class="required-form-star">*</span></label>
                        <input type="time" name="check_in_end" value="{{ $modul->check_in_end }}" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Jam Keluar Awal<span class="required-form-star">*</span></label>
                        <input type="time" name="check_out_start" value="{{ $modul->check_out_start }}" class="form-control" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Jam Keluar Akhir<span class="required-form-star">*</span></label>
                        <input type="time" name="check_out_end" value="{{ $modul->check_out_end }}" class="form-control" required>
                    </div>
                </div>
            </div>

            <h4>Sabtu</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Jam Masuk Awal<span class="required-form-star">*</span></label>
                        <input type="time" name="saturday_check_in_start" value="{{ $modul->saturday_check_in_start }}" class="form-control" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Jam Masuk Akhir<span class="required-form-star">*</span></label>
                        <input type="time" name="saturday_check_in_end" value="{{ $modul->saturday_check_in_end }}" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Jam Keluar Awal<span class="required-form-star">*</span></label>
                        <input type="time" name="saturday_check_out_start" value="{{ $modul->saturday_check_out_start }}" class="form-control" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Jam Keluar Akhir<span class="required-form-star">*</span></label>
                        <input type="time" name="saturday_check_out_end" value="{{ $modul->saturday_check_out_end }}" class="form-control" required>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Keterangan<span class="required-form-star">*</span></label>
                        <textarea name="description" placeholder="Keterangan" class=" form-control" required>{{ $modul->description }}</textarea>
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
$("#edit-time").submit(function(event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    var formData = new FormData($(this)[0]);
    $.ajax({
        url: '{!! url("/pengaturan-waktu-pegawai/update/") !!}',
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
                $('#tblPengaturanWaktuPegawai').DataTable().ajax.reload();
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

