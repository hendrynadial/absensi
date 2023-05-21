<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Edit Waktu Kehadiran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form validate>
        <div class="modal-body">         
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-12">
                            <select required class="form-select" id="statusKehadiran" name="status" aria-label="Default select example">
                                <option>Pilih Status</option>
                                <option value="Hadir">Hadir</option>
                                <option value="Absen">Absen</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="col-sm-2 col-form-label">Alasan</label>
                        <div class="col-sm-12">
                            <textarea required class="form-control" name="reason" id="reason"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary waves-effect waves-light" id="saveData">Simpan</button>
        </div>
    </form>
</div>

<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on('click','#saveData',function(e){
    e.preventDefault();
    e.stopImmediatePropagation();
    const iddata = '{!! $id !!}';
    const reason = $('#reason').val();
    const status = $('#statusKehadiran').find(":selected").val();
    console.log(status)

    if(status == null || status == "")
    {
        alert("Pilih Status")
        return;
    }
    $.ajax({
        url: '{!! url("/daftar-kehadiran/change/status/") !!}',
        type: "POST",
        data: {
            iddata:iddata,
            status:status,
            reason:reason,
        },
        beforeSend: function(){
            Swal.fire({
                icon: 'info',
                title: 'Harap Tunggu ...',
                showConfirmButton: false,
                timer: 2000
            })
        },
        success: function(e) {
            if (e.status == 1) {
                Swal.fire({
                    icon: 'success', 
                    title: 'Berhasil', 
                    text: e.message, 
                    timer: 1500, 
                });
                $('#modal-default').modal('hide');
                $('#tblListKehadiran').DataTable().ajax.reload();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Opss...',
                    text: e.message, 
                    timer: 2000, 
                })
            }
        }
    });
})
</script>
