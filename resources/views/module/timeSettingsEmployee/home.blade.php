@extends('layouts/app',['title'=>'Waktu Pegawai'])
@section('content')

<div class="main-content">
    <div class="page-content">
        <!-- start page title -->
        <div class="page-title-box bg-success">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="page-title">
                            <h4>Waktu Pegawai</h4>
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                <li class="breadcrumb-item active">Waktu Pegawai</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="container-fluid">
            <div class="page-content-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <a href="/pengaturan-waktu-pegawai/add" 
                                        id="add-time-employee"
                                        class="btn btn-info mb-2" 
                                        data-bs-toggle="modal" 
                                        data-bs-target=".staticBackdrop">
                                        <i class="mdi mdi-plus me-2"></i> Tambah Waktu
                                    </a>
                                </div>

                                <div class="mt-3">
                                    <table id="tblPengaturanWaktuPegawai" class="table table-centered dt-responsive nowrap">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th class="text-center">Jam Masuk<br>(Senin-jumat)</th>
                                                <th class="text-center">Jam Keluar<br>(Senin-jumat)</th>
                                                <th class="text-center">Jam Masuk<br>(Sabtu)</th>
                                                <th class="text-center">Jam Keluar<br>(Sabtu)</th>
                                                <th class="text-center">Keterangan</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(function(){
        $('#tblPengaturanWaktuPegawai').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!!url("/pengaturan-waktu-pegawai")!!}',
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className: "dt-center", orderable:false, searchable:false},
                {data: 'check_in_start', name: 'check_in_start',className:"text-center"}
                {data: 'check_out_start', name: 'check_out_start',className:"text-center"},
                {data: 'saturday_check_in_start', name: 'saturday_check_in_start',className:"text-center"},
                {data: 'saturday_check_out_start', name: 'saturday_check_out_start',className:"text-center"},
                {data: 'description', name: 'description',className:"text-center"},
                {data: 'action', name: 'action',className:"text-center",orderable: false, searchable: false},
            ]
        });
    });

    $('#add-time-employee').on('click', function(e){
        e.preventDefault();
		e.stopPropagation();
		e.stopImmediatePropagation();
        $('#modal-default').modal('show').find('.modal-dialog').load($(this).attr('href'));
    });

    var $modal = $('#modal-default');
	$(document).on('click','#edit-time-employee', function(e){
		e.preventDefault();
		e.stopPropagation();
		e.stopImmediatePropagation();
		var uri = $(this).attr('href');
		$.ajax({
			url: uri, 
			type: "GET",
			success: function (result) {
				$modal.find(".modal-dialog").html(result);
			}
		});
		$modal.modal('show');
	});

    $(document).on('click','#btn-delete', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
            buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
            title: 'Apakah kamu yakin?',
            text: "Data tidak akan bisa dikembalikan",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Tidak, Batalkan!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                let uri = $(this).attr('href');
                $.ajax({
                    url: uri,
                    type: "POST", 
                    data : {
                        '_token':"{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    success: function (e) {
                        if (e.status == 1) { 
                            swalWithBootstrapButtons.fire(
                                'Terhapus!',
                                e.message,
                                'success'
                            )
                            $('#tblPengaturanWaktuPegawai').DataTable().ajax.reload();
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

</script>
@endpush
