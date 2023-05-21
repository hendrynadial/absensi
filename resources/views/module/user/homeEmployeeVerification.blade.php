@extends('layouts/app',['title'=>'Verifikasi Karyawan'])
@section('content')

<div class="main-content">
    <div class="page-content">
        <!-- start page title -->
        <div class="page-title-box bg-success">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="page-title">
                            <h4>Verifikasi Karyawan</h4>
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                <li class="breadcrumb-item active">Verifikas Karyawan</li>
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
                                    <table id="tblverifikasipegawai" class="table table-centered dt-responsive nowrap">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th class="text-left">NIK</th>
                                                <th class="text-left">Nama</th>
                                                <th class="text-left">Username</th>
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
        $('#tblverifikasipegawai').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!!url("/user/verifikasi-pegawai/")!!}',
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className: "dt-center", orderable:false, searchable:false},
                {data: 'nik', name: 'nik',className:"text-left"},
                {data: 'name', name: 'name',className:"text-left"},
                {data: 'username', name: 'username',className:"text-left"},
                {data: 'action', name: 'action',className:"text-center",orderable: false, searchable: false},
            ]
        });
    });

    $(document).on('click','#verifikasi-user', function(e){
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
            title: 'Verifikasi Akun Pegawai ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Verifikasi!',
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
                                'Terverifikasi',
                                e.message,
                                'success'
                            )
                            $('#tblverifikasipegawai').DataTable().ajax.reload();
                        }else{
                            swalWithBootstrapButtons.fire(
                                'Gagal Verifikasi',
                                e.message,
                                'error'
                            )
                        }	
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire(
                    'Dibatalkan',
                    'Data tidak diverifikasi',
                    'error'
                )
            }
        })
    });

</script>
@endpush
