@extends('layouts/app',['title'=>'User'])
@section('content')

<div class="main-content">
    <div class="page-content">
        <!-- start page title -->
        <div class="page-title-box bg-success">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="page-title">
                            <h4>User</h4>
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                <li class="breadcrumb-item active">User</li>
                            </ol>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <a href="/user/add" id="add-user" class="float-end d-none d-sm-block btn btn-info mb-2" data-bs-toggle="modal" data-bs-target=".staticBackdrop">
                            <i class="mdi mdi-plus me-2"></i> Tambah User
                        </a>
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
                                                <input type="text" name="name" class="form-control" placeholder="Nama">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">&nbsp;</label>
                                                <input type="text" name="username" class="form-control" placeholder="Username">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">&nbsp;</label>
                                                <select name="profile" class="form-select" aria-label="Profile">
                                                    <option>Pilih Profile</option>
                                                    <option value="Admin">Admin</option>
                                                    <option value="Guru">Guru</option>
                                                    <option value="Pegawai">Pegawai</option>
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
                                    <table id="tbluser" class="table table-centered dt-responsive nowrap">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th class="text-left">Nama</th>
                                                <th class="text-left">Username</th>
                                                <th class="text-left">Email</th>
                                                <th class="text-left">Profile</th>
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
        $('#tbluser').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: false,
            pageLenght: 10,
            ajax: {
                url: '{!!url("/user")!!}',
                data: function (d) {
                    d.form = $('#filterForm').serializeArray();
                },
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className: "dt-center", orderable:false, searchable:false},
                {data: 'name', name: 'name',className:"text-left"},
                {data: 'username', name: 'username',className:"text-left"},
                {data: 'email', name: 'email',className:"text-left"},
                {data: 'profile', name: 'profile',className:"text-left"},
                {data: 'action', name: 'action',className:"text-center",orderable: false, searchable: false},
            ]
        });
    });

    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        $('#tbluser').DataTable().ajax.reload();
    });


    $('#add-user').on('click', function(e){
        e.preventDefault();
		e.stopPropagation();
		e.stopImmediatePropagation();
        $('#modal-default').modal('show').find('.modal-dialog').load($(this).attr('href'));
    });

    var $modal = $('#modal-default');
	$(document).on('click','#edit-user-modal', function(e){
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
                            $('#tbluser').DataTable().ajax.reload();
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
