@extends('layouts/app',['title'=>'Feedback'])
@section('content')

<div class="main-content">
    <div class="page-content">
        <!-- start page title -->
        <div class="page-title-box bg-success">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="page-title">
                            <h4>Feedback</h4>
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                <li class="breadcrumb-item active">Feedback</li>
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
                                <div class="mt-3">
                                    <table id="tblfeedback" class="table table-centered dt-responsive nowrap">
                                        <thead>
                                            <tr>
                                                <th class="text-center">User</th>
                                                <th class="text-center">Tipe Device</th>
                                                <th class="text-center">File</th>
                                                <th class="text-center">Masalah</th>
                                                <th class="text-center">Solusi</th>
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function(){
        $('#tblfeedback').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!!url("/feedback")!!}',
            columns: [
                {width: "*", data: 'user_id', name: 'user_id',className:"text-center"},
                {width: "*", data: 'device_type', name: 'device_type',className:"text-center"},
                {width: "*", data: 'photo', name: 'photo',className:"text-center"},
                {width: "*", data: 'problem_description', name: 'problem_description',className:"text-center"},
                {width: "*", data: 'problem_solving_description', name: 'problem_solving_description',className:"text-center"},
                {width: "*", data: 'status', name: 'status',className:"text-center"},
                {width: "*", data: 'action', name: 'action',className:"text-center",orderable: false, searchable: false},
            ]
        });
    });

    var $modal = $('#modal-default');
	$(document).on('click','#edit-feedback', function(e){
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

    $(document).on('click','#btn-feedback-delete', function(e){
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
                            $('#tblfeedback').DataTable().ajax.reload();
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
