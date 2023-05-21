@extends('layouts/app',['title'=>'Tahun Ajaran'])
@section('content')

<div class="main-content">
    <div class="page-content">
        <!-- start page title -->
        <div class="page-title-box bg-success">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="page-title">
                            <h4>Tahun Ajaran</h4>
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                <li class="breadcrumb-item active">Tahun Ajaran</li>
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
                                    <a href="/tahun-ajaran/add" 
                                        id="add-time-employee"
                                        class="btn btn-info mb-2" 
                                        data-bs-toggle="modal" 
                                        data-bs-target=".staticBackdrop">
                                        <i class="mdi mdi-plus me-2"></i> Tambah Tahun Ajaran
                                    </a>
                                </div>

                                <div class="mt-3">
                                    <table id="tbltahunajaran" class="table table-centered dt-responsive nowrap">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Tahun Ajaran</th>
                                                <th class="text-center">Tanggal Awal</th>
                                                <th class="text-center">Tanggal Akhir</th>
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function(){
        $('#tbltahunajaran').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!!url("/tahun-ajaran")!!}',
            columns: [
                {width: "5%", data: 'active', name: 'active',className:"text-center"},
                {width: "*", data: 'curriculum_year', name: 'curriculum_year',className:"text-center"},
                {width: "*", data: 'start_date', name: 'start_date',className:"text-center"},
                {width: "*", data: 'end_date', name: 'end_date',className:"text-center"},
                {width: "*", data: 'description', name: 'description',className:"text-center"},
                {width: "20%", data: 'action', name: 'action',className:"text-center",orderable: false, searchable: false},
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
	$(document).on('click','#edit-curriculum-year', function(e){
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

    $(document).on('click','#switchActive',function(e){
        var value = $(this).val();
		$.ajax({
			url: '/tahun-ajaran/'+value+'/setActive',
			type: "POST",
			success: function (result) {
				if (result.status == 1) {    
                    $('#tbltahunajaran').DataTable().ajax.reload();
                }else{
                    alert("Data gagal diperbarui")
                }
			}
		});
    })


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
                            $('#tbltahunajaran').DataTable().ajax.reload();
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
