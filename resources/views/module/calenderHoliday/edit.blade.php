<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Edit Kalender</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form id="edit-kalender" action="">
        @csrf
        <input type="hidden" name="id" value="{{ $modul->id }}">
        <div class="modal-body">
            @if($modul->date >= \Carbon\Carbon::now())
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Tanggal<span class="required-form-star">*</span></label>
                            <input type="date" min='{{ Carbon\Carbon::now()->format('Y-m-d') }}' value="{{ $modul->date->format('Y-m-d') }}" name="date" class="form-control" required>
                        </div>
                    </div>
                </div>
            @else
                <input type="hidden" value="{{ $modul->date->format('Y-m-d') }}" name="date">
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Keterangan<span class="required-form-star">*</span></label>
                        <textarea name="reason" placeholder="Keterangan" class=" form-control" required>{{ $modul->reason }}</textarea>
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
$("#edit-kalender").submit(function(event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    var formData = new FormData($(this)[0]);
    $.ajax({
        url: '{!! url("/kalender-libur/update/") !!}',
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
                $('#tblkalenderlibur').DataTable().ajax.reload();
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

