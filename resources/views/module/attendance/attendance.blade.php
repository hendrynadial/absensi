@extends('layouts/app',['title'=>'Daftar Kehadiran'])
@section('content')

<div class="main-content">
    <div class="page-content">
        <!-- start page title -->
        <div class="page-title-box bg-success">
            <div class="container-fluid">
                <div class="row align-items-left">
                    <div class="col-sm-6">
                        <div class="page-title">
                            <h4>Daftar Kehadiran</h4>
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                <li class="breadcrumb-item active">List Pegawai</li>
                                <li class="breadcrumb-item active">Daftar Absensi</li>
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
                    <div class="col-12">
                        <!-- Left sidebar -->
                        <div class="email-leftbar card">
                            <img src="{{ $photo }}" alt="" class="img-fluid mx-auto d-block tab-img rounded">
                            <table class="mt-3">
                                    <tr>
                                        <td>Nama</td>
                                        <td>:</td>
                                        <th><span class='badge badge-pill badge-soft-success font-size-14'>{{$dataEmployee->nama}}</span></th>
                                    </tr>
                                    <tr><td></td></tr>
                                    <tr>
                                        <td>NIK</td>
                                        <td>:</td>
                                        <th><span class='badge badge-pill badge-soft-success font-size-14'>{{$dataEmployee->nik}}</span></th>
                                    </tr>
                                    {{-- <tr>
                                        <td>Jabatan</td>
                                        <td>:</td>
                                        <th><span class='badge badge-pill badge-soft-success font-size-14'>{!! join("<br>",$jabatanData) !!}</span></th>
                                    </tr> --}}
                                </table>
                        </div>
                        <!-- End Left sidebar -->

                        <div class="email-rightbar mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <form action="" id="filterForm">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label class="form-label">&nbsp;</label>
                                                    <select name="bulan" class="form-select" aria-label="Profile">
                                                        <option value="01" {{ date('m') == "01" ? 'selected' : '' }}>Januari</option>
                                                        <option value="02" {{ date('m') == "02" ? 'selected' : '' }}>Februari</option>
                                                        <option value="03" {{ date('m') == "03" ? 'selected' : '' }}>Maret</option>
                                                        <option value="04" {{ date('m') == "04" ? 'selected' : '' }}>April</option>
                                                        <option value="05" {{ date('m') == "05" ? 'selected' : '' }}>Mei</option>
                                                        <option value="06" {{ date('m') == "06" ? 'selected' : '' }}>Juni</option>
                                                        <option value="07" {{ date('m') == "07" ? 'selected' : '' }}>Juli</option>
                                                        <option value="08" {{ date('m') == "08" ? 'selected' : '' }}>Agustus</option>
                                                        <option value="09" {{ date('m') == "09" ? 'selected' : '' }}>September</option>
                                                        <option value="10" {{ date('m') == "10" ? 'selected' : '' }}>Oktober</option>
                                                        <option value="11" {{ date('m') == "11" ? 'selected' : '' }}>November</option>
                                                        <option value="12" {{ date('m') == "12" ? 'selected' : '' }}>Desember</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">&nbsp;</label>
                                                    <select name="tahun" class="form-select" aria-label="Profile">
                                                        @php
                                                            $year = date('Y');
                                                        @endphp
                                                        @for($i = $year; $i <= $year+1; $i++)
                                                            <option value="{{$i}}">{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                
                                                <div class="col-md-3">
                                                    <label class="form-label">&nbsp;</label>
                                                    <select name="status" class="form-select" aria-label="Profile">
                                                        <option value ="">Pilih Status</option>
                                                        <option value="Hadir">Hadir</option>
                                                        <option value="Absen">Absen</option>
                                                        <option value="Izin">Izin</option>
                                                        <option value="Libur">Libur</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="col-md-3">
                                                    <label class="form-label">&nbsp;</label>
                                                    <br>
                                                    <button style="width: 100px;" type="button" id="filter" class="form-control btn btn-info waves-effect waves-light">
                                                        <i class="fas fa-filter"></i> Filter
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <table id="tblListKehadiran" class="table table-centered dt-responsive nowrap">
                                        <thead>
                                            <tr>
                                                <th>Hari</th>
                                                <th>Tanggal</th>
                                                <th class="text-center">Jam Masuk</th>
                                                <th class="text-center">Jam Keluar</th>
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
        var employeeID = '{!! $employee_id !!}';
        $('#tblListKehadiran').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: false,
            paging: false,
            pageLenght: 35,
            ajax: {
                url: '{!!url("/daftar-kehadiran/'+employeeID+'")!!}',
                data: function (d) {
                    d.form = $('#filterForm').serializeArray();
                },
            },
            columns: [
                {data: 'day', name: 'day',className:"text-left",orderable: false},
                {data: 'date', name: 'date',className:"text-center",orderable: false},
                {data: 'check_in', name: 'check_in',className:"text-center",orderable: false},
                {data: 'check_out', name: 'check_out',className:"text-center",orderable: false},
                {data: 'status', name: 'status',className:"text-center",orderable: false},
                {data: 'action', name: 'action',className:"text-center",orderable: false, searchable: false},
            ]
        });
    });

    $('#filter').on('click', function(e) {
        $('#tblListKehadiran').DataTable().ajax.reload();
    });

    var $modal = $('#modal-default');
	$(document).on('click','#edit-attandance', function(e){
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

	$(document).on('click','#detail-attandance', function(e){
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
</script>
@endpush