<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Kalender Guru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form>
        <div class="modal-body">
            <div class="row">
                <input type="hidden" id="employeeID" value="{{ $id }}">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Tahun Ajaran<span class="required-form-star">*</span></label>
                        <select name="tahun_ajaran" id="tahun_ajaran" class="form-select" aria-label="Tahun Ajaran">
                            @foreach ($curriculumYear as $value)
                                <option value="{{ $value->id }}" selected>{{ $value->curriculum_year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">&nbsp;</label>
                        <button class="form-control btn btn-info" id="gen-calender"><i class="mdi mdi-cog"></i> Generate</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Tutup</button>
        </div>
    </form>
</div>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click','#gen-calender',function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        const tahun_ajaran = $('#tahun_ajaran').find(":selected").val();
        const employee_id = $('#employeeID').val();

        if(tahun_ajaran == null || tahun_ajaran == "")
        {
            alert("Pilih Tahun Ajaran")
            return;
        }
		$.ajax({
            url: '{!! url("/pegawai/generate-personal-calender/") !!}',
            type: "POST",
            data: {
                tahun_ajaran:tahun_ajaran,
                employee_id:employee_id,
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
                    $('#tblKaryawan').DataTable().ajax.reload();
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
