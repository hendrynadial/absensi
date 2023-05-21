<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Tambah Kalender Libur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form id="add-calender" action="">
        @csrf
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Tanggal<span class="required-form-star">*</span></label>
                        <input type="date" name="date" class="form-control" required>
                        <small>*<i>Pastikan kembali tanggal libur yang anda input sudah benar</i></small>
                        {{-- <input type="date" min='{{ Carbon\Carbon::now()->format('Y-m-d') }}' name="date" class="form-control" required> --}}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Keterangan<span class="required-form-star">*</span></label>
                        <textarea name="reason" placeholder="Keterangan" class=" form-control" required></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary waves-effect waves-light" id="btn-simpan">Simpan</button>
        </div>
    </form>
</div>

<script>
$(document).on('click','#btn-simpan', function(e){
    e.preventDefault();
    e.stopImmediatePropagation();
    const form = document.getElementById("add-calender");
    const submitter = document.getElementById('btn-simpan');
    const formData = new FormData(form, submitter);
    const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
    },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: 'Apakah Anda yakin ingin menyimpan data ini ?',
        text: "Data absensi tidak bisa dikembalikan lagi",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, simpan!',
        cancelButtonText: 'Tidak, Batalkan!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{!! url("/kalender-libur/store/") !!}',
                type: "POST", 
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: function (e) {
                    if (e.status == 1) { 
                        swalWithBootstrapButtons.fire(
                            'Tersimpan!',
                            e.message,
                            'success'
                        )
                        $('#modal-default').modal('hide');
                        $('#tblkalenderlibur').DataTable().ajax.reload();
                    }else{
                        swalWithBootstrapButtons.fire(
                            'Gagal',
                            e.message,
                            'error'
                        )
                    }	
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire(
                'Dibatalkan',
                'Data ini aman',
                'error'
            )
        }
    })
});
// $("#add-calender").submit(function(event) {
//     event.preventDefault();
//     event.stopImmediatePropagation();
//     var formData = new FormData($(this)[0]);
//     $.ajax({
//         url: '{!! url("/kalender-libur/store/") !!}',
//         type: "POST",
//         data: formData,
//         cache: false,
//         processData: false,
//         contentType: false,
//         success: function(e) {
//             if (e.status == 1) {
//                 Swal.fire({
//                     icon: 'success', 
//                     title: 'Berhasil', 
//                     text: e.message, 
//                     timer: 1000, 
//                 })
//                 $('#modal-default').modal('hide');
//                 $('#tblkalenderlibur').DataTable().ajax.reload();
//             } else {
//                 Swal.fire({
//                     icon: 'error',
//                     title: 'Opss...',
//                     text: e.message, 
//                     timer: 1000, 
//                 })
//             }
//         }
//     });
// });
</script>

