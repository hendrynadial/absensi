@extends('layouts/app',['title'=>'Izin'])
@section('content')

<div class="main-content">
    <div class="page-content">
        <!-- start page title -->
        <div class="page-title-box bg-success">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="page-title">
                            <h4>Izin</h4>
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                <li class="breadcrumb-item active">Izin Karyawan</li>
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
                                <div class="mb-3">
                                    <form id="filterForm" action="">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label class="form-label">&nbsp;</label>
                                                <input type="text" name="nama" class="form-control" placeholder="Nama">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">&nbsp;</label>
                                                <input type="date" name="tanggal" class="form-control" >
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">&nbsp;</label>
                                                <select name="status" class="form-select" aria-label="Status">
                                                    <option value="">Pilih Status</option>
                                                    <option value="Menunggu">Menunggu</option>
                                                    <option value="Disetujui">Disetujui</option>
                                                    <option value="Ditolak">Ditolak</option>
                                                </select>
                                            </div>

                                            <div class="col-sm-2">
                                                <label class="form-label">&nbsp;</label>
                                                <br>
                                                <button type="submit" class="form-control btn btn-info waves-effect waves-light">
                                                    <i class="fas fa-filter"></i> Filter
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="mt-3">
                                    <table id="tblPermission" class="table table-centered dt-responsive nowrap">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th class="text-left">Nama</th>
                                                <th class="text-center">Tanggal Izin</th>
                                                <th class="text-left">Keterangan</th>
                                                <th class="text-left">File</th>
                                                <th class="text-center">Status</th>
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
        $('#tblPermission').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: false,
            pageLenght: 10,
            ajax: {
                url: '{!!url("/permission")!!}',
                data: function (d) {
                    d.form = $('#filterForm').serializeArray();
                },
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className: "dt-center", orderable:false, searchable:false},
                {data: 'employee_id', name: 'employee_id',className:"text-left"},
                {data: 'start_date', name: 'start_date',className:"text-center"},
                {data: 'remark', name: 'remark',className:"text-left"},
                {data: 'file', name: 'file',className:"text-left"},
                {data: 'status', name: 'status',className:"text-left"},
                {data: 'action', name: 'action',className:"text-center",orderable: false, searchable: false},
            ]
        });
    });

    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        $('#tblPermission').DataTable().ajax.reload();
    });
    
    var $modal = $('#modal-default');
	$(document).on('click','#reject-permission', function(e){
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


    $(document).on('click','#approve-permission', function(e){
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
            title: 'Setujui data permohonan izin ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Setuju!',
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
                                'Disetujui',
                                e.message,
                                'success'
                            )
                            $('#tblPermission').DataTable().ajax.reload();
                        }else{
                            swalWithBootstrapButtons.fire(
                                'Gagal Menyetujui',
                                e.message,
                                'error'
                            )
                        }	
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire(
                    'Dibatalkan',
                    'Data tidak disetujui',
                    'error'
                )
            }
        })
    });
</script>
@endpush
