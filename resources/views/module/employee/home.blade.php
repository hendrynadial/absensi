@extends('layouts/app',['title'=>'Karyawan'])
@section('content')
<div class="main-content">
    <div class="page-content">
        <!-- start page title -->
        <div class="page-title-box bg-success">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="page-title">
                            <h4>Data Karyawan</h4>
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                <li class="breadcrumb-item active">Karyawan</li>
                            </ol>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <a href="/pegawai/add" class="float-end d-none d-sm-block btn btn-info"><i class="mdi mdi-plus me-2"></i> Tambah Karyawan</a>
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
                                                <select name="jenis_pegawai" id="jenis_pegawai" class="form-select" aria-label="Jenis Pegawai">
                                                    <option value="">Karyawan</option>
                                                    <option value="Guru">Guru</option>
                                                    <option value="Pegawai">Pegawai</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 unit hide">
                                                <label class="form-label">&nbsp;</label>
                                                 <select name="unit" id="unit" class="form-select" aria-label="Unit">
                                                     <option value="" selected="">Pilih Unit</option>
                                                     <option value="TK">TK</option>
                                                     <option value="SD">SD</option>
                                                     <option value="SMP">SMP</option>
                                                     <option value="SMA">SMA</option>
                                                     <option value="SMK">SMK</option>
                                                 </select>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">&nbsp;</label>
                                                <input type="text" name="nik" class="form-control" placeholder="NIK">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">&nbsp;</label>
                                                <input type="text" name="nama" class="form-control" placeholder="Nama">
                                            </div>

                                            <div class="col-sm-2">
                                                <label class="form-label">&nbsp;</label>
                                                <br>
                                                <button type="submit" class="form-control btn btn-info waves-effect waves-light">
                                                    <i class="fas fa-filter"></i> Filter
                                                </button>
                                            </div>
                                            <div class="col-sm-2">
                                                <label class="form-label">&nbsp;</label>
                                                <br>
                                                <button type="button" id="generate-calendar-employee" class="form-control btn btn-info waves-effect waves-light">
                                                    Buat Kalender
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="mt-3">
                                    <table id="tblKaryawan" class="table table-centered dt-responsive nowrap">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Jenis Karyawan</th>
                                                <th>NIK</th>
                                                <th>Nama Lengkap</th>
                                                <th class="text-center">Status Kalender</th>
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

    $(document).on('change','#jenis_pegawai',function(e){
        event.preventDefault();
        var value = $(this).val();
        if(value == "Guru"){
            $('.unit').removeClass('hide')
            $('select[name="unit"]').removeAttr('disabled', 'disabled');
        }else{
            $('.unit').addClass('hide')
            $('select[name="unit"]').attr('disabled', 'disabled');
        }
    })

    $(function() {
        $('#tblKaryawan').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: false,
            pageLenght: 10,
            ajax: {
                url: '{!! url("/pegawai") !!}',
                data: function (d) {
                    d.form = $('#filterForm').serializeArray();
                },
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className: "dt-center", orderable:false, searchable:false},
                {data: 'jenis_pegawai', name: 'jenis_pegawai',className:"text-left"},
                {data: 'nik', name: 'nik',className:"text-left"},
                {data: 'nama', name: 'nama',className:"text-left"},
                {data: 'status', name: 'action',className:"text-center",orderable: false, searchable: false},
                {data: 'action', name: 'action',className:"text-center",orderable: false, searchable: false},
            ]
        });
    });

    $('#filterForm').on('submit', function(e) {
		e.preventDefault();
		$('#tblKaryawan').DataTable().ajax.reload();
	});

    var $modal = $('#modal-default');
	$(document).on('click','#generate-calender-guru', function(e){
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

    $(document).on('click','#generate-calender-pegawai', function(e){
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
            title: 'Generate Kalender untuk Tahun ini ?',
            text: "Kalender pegawai akan digenerate untuk tahun ini",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Ya, Generate!',
            cancelButtonText: 'Tidak, Batalkan!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                let uri = $(this).attr('href');
                $.ajax({
                    url: uri,
                    type: "POST", 
                    data : {
                        '_token':"{{ csrf_token() }}",
                        'jenisPegawai' : 'Pegawai'
                    },
                    dataType: 'json',
                    success: function (e) {
                        if (e.status == 1) { 
                            swalWithBootstrapButtons.fire(
                                'Berhasil Generate',
                                e.message,
                                'success'
                            )
                            $('#tblKaryawan').DataTable().ajax.reload();
                        }else{
                            swalWithBootstrapButtons.fire(
                                'Gagal Generate',
                                e.message,
                                'error'
                            )
                        }	
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire(
                    'Dibatalkan',
                    'Generate Kalender dibatalkan',
                    'error'
                )
            }
        })
    });


    $(document).on('click','#generate-calendar-employee', async (e) => {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        const fetchAllEmployee = async () => {
            try {
                const response = await fetch('/pegawai/get-all-employee');
                return response.json();
            } catch (e) {
                alert(e);
            }
        }

        
        const generateEmployeeCalendar = async ({jenis_pegawai, id}) => {
            const newPayload = {
                jenisPegawai: jenis_pegawai,
                employeeId: id
            }
            try {
                const response = await fetch('/pegawai/generate-all-calender', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json; charset=UTF-8',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify(newPayload)
                })
                if(response.ok) return await response.json();
                throw(await response.json())
            } catch (e) {
                return `Error: ${JSON.stringify(e)}`;
            }
        }

        const employees = await fetchAllEmployee();
        const errors = []
        let count = 0;
        Swal.fire({
            icon: 'info',
            title: 'Harap Tunggu ...',
            text : "Akan membutuhkan beberapa waktu",
            showConfirmButton: false,
            showCancelButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false
        })

        if(employees.length > 0) {
            const promises = []
            for(let i = 0; i < employees.length; i+=3) {
                for(let j = i; j < i+3; j++) {
                    if(j < employees.length) promises.push(generateEmployeeCalendar(employees[j]));
                }
                const results = await Promise.allSettled(promises);
                results.forEach((result, idx) => {
                    if(typeof(result.value) === 'string' && result.value.indexOf('Error') !== -1) {
                        errors.push(result.value);
                    }
                })
            }

            if(errors.length > 0){
                Swal.fire({
                    icon: 'warning',
                    title: 'Selesai',
                    text: 'Beberapa kalender karyawan gagal digenerate'
                })
            }else{
                Swal.fire({
                    icon: 'success',
                    title: 'Selesai',
                    text: 'Kalender karyawan selesai digenerate'
                })
            }
        }else{
            Swal.fire({
                icon: 'success',
                title: 'Selesai',
                text: 'Tidak ada kalender yang digenerate'
            })
        }

        
        $('#modal-default').modal('hide');
        $('#tblKaryawan').DataTable().ajax.reload();
    })

    $(document).on('click','#delete-employee', function(e){
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
            text: "Ini akan menghapus semua data yang terkait, seperti kalender dan pengaturan waktu",
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
                            $('#tblKaryawan').DataTable().ajax.reload();
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



