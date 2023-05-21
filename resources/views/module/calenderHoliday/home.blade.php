@extends('layouts/app',['title'=>'Kalender Libur'])
@section('content')

<div class="main-content">
    <div class="page-content">
        <!-- start page title -->
        <div class="page-title-box bg-success">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="page-title">
                            <h4>Kalender Libur</h4>
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                <li class="breadcrumb-item active">Kalender Libur</li>
                            </ol>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <a href="/kalender-libur/generate-calender-holiday" 
                            id="generate-calender-holiday" 
                            class="float-end d-none d-sm-block btn btn-warning mb-2"><i class="mdi mdi-plus me-2"></i> Generate Kalender Libur
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
                                <div>
                                    <a href="/kalender-libur/add"
                                        id="add-calender-holiday"
                                        class="btn btn-info mb-2" 
                                        data-bs-toggle="modal" 
                                        data-bs-target=".staticBackdrop">
                                        <i class="mdi mdi-plus me-2"></i> Tambah Kalender
                                    </a>
                                </div>
                                 <form id="filterForm" action="">
                                     <div class="row">
                                        <div class="col-md-2">
                                            <label class="form-label">&nbsp;</label>
                                            <select name="bulan" class="form-select" aria-label="Profile">
                                                <option value="">Bulan</option>
                                                <option value="01">Januari</option>
                                                <option value="02">Februari</option>
                                                <option value="03">Maret</option>
                                                <option value="04">April</option>
                                                <option value="05">Mei</option>
                                                <option value="06">Juni</option>
                                                <option value="07">Juli</option>
                                                <option value="08">Agustus</option>
                                                <option value="09">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label">&nbsp;</label>
                                            <select name="tahun" class="form-select" aria-label="Profile">
                                                @php
                                                $year = date('Y');
                                                @endphp
                                                @for($i = $year; $i <= $year+1; $i++) <option value="{{$i}}">{{$i}}</option>@endfor
                                            </select>
                                        </div>

                                        <div class="col-sm-2">
                                            <label class="form-label">&nbsp;</label>
                                            <br>
                                            <button type="submit" id="filter-holiday" class="form-control btn btn-success waves-effect waves-light">
                                                <i class="fas fa-filter"></i> Filter
                                            </button>
                                        </div>
                                     </div>
                                 </form>


                                <div class="mt-3">
                                    <table id="tblkalenderlibur" class="table table-centered dt-responsive nowrap">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th class="text-left">Hari</th>
                                                <th class="text-center">Tanggal</th>
                                                <th class="text-left">Keterangan</th>
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
        $('#tblkalenderlibur').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: false,
            paging: false,
            pageLenght: 35,
            ajax: {
                url: '{!!url("/kalender-libur")!!}',
                data: function (d) {
                    d.form = $('#filterForm').serializeArray();
                },
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className: "dt-center", orderable:false, searchable:false},
                {data: 'day', name: 'day',className:"text-left",orderable: false, searchable: false},
                {data: 'date', name: 'date',className:"text-center",orderable: false, searchable: false},
                {data: 'reason', name: 'reason',className:"text-left",orderable: false, searchable: false},
                {data: 'action', name: 'action',className:"text-center",orderable: false, searchable: false},
            ]
        });
    });
    $('#filter-holiday').on('click', function(e) {
        e.preventDefault()
        $('#tblkalenderlibur').DataTable().ajax.reload();
    });

    $('#add-calender-holiday').on('click', function(e){
        e.preventDefault();
		e.stopPropagation();
		e.stopImmediatePropagation();
        $('#modal-default').modal('show').find('.modal-dialog').load($(this).attr('href'));
    });

    var $modal = $('#modal-default');
	$(document).on('click','#edit-calender-holiday', function(e){
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

	$(document).on('click','#generate-calender-holiday', function(e){
		e.preventDefault();
		e.stopPropagation();
		e.stopImmediatePropagation();
		var uri = $(this).attr('href');
		$.ajax({
			url: uri, 
			type: "POST",
			success: function (e) {
				if (e.status == 1) {
                    Swal.fire({
                        icon: 'success', 
                        title: 'Berhasil', 
                        text: e.message, 
                        timer: 1000, 
                    })
                    $('#modal-default').modal('hide');
                    $('#tblkalenderlibur').DataTable().ajax.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Opss...',
                        text: e.message, 
                        timer: 1000, 
                    })
            }
			}
		});
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

</script>
@endpush
