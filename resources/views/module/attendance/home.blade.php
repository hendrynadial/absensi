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
                        <div class="card">
                            <div class="card-body">

                                <div class="mb-3">
                                    <form action="" id="filterForm">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label class="form-label">&nbsp;</label>
                                                <input type="text" name="nik" class="form-control" placeholder="NIK">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">&nbsp;</label>
                                                <input type="text" name="nama" class="form-control" placeholder="Nama Pegawai">
                                            </div>

                                            <div class="col-md-2">
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

                                            <div class="col-md-2">
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

                                            <div class="col-md-2">
                                                <label class="form-label">&nbsp;</label>
                                                <br>
                                                <button style="width: 100px;" type="button" id="filtertable" class="form-control btn btn-info waves-effect waves-light">
                                                    <i class="fas fa-filter"></i> Filter
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <table id="tblDaftarKehadiran" class="table table-centered dt-responsive nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NIK</th>
                                            <th>Nama Karyawan</th>
                                            <th class="text-center">Jumlah Hadir</th>
                                            <th class="text-center">Jumlah Izin</th>
                                            <th class="text-center">Jumlah Absen</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(function(){
        $('#tblDaftarKehadiran').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: false,
            ajax: {
                url: '{!!url("/daftar-kehadiran")!!}',
                data: function (d) {
                    d.form = $('#filterForm').serializeArray();
                },
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className: "dt-center", orderable:false, searchable:false},
                {data: 'nik', name: 'nik',className:"text-left"},
                {data: 'employee_id', name: 'employee_id',className:"text-left"},
                {data: 'jumlah_hadir', name: 'jumlah_hadir',className:"text-center"},
                {data: 'jumlah_izin', name: 'jumlah_izin',className:"text-center"},
                {data: 'jumlah_absen', name: 'jumlah_absen',className:"text-center"},
                {data: 'action', name: 'action',className:"text-center",orderable: false, searchable: false},
            ]
        });
    });
    $('#filtertable').on('click', function(e) {
        $('#tblDaftarKehadiran').DataTable().ajax.reload();
    });
</script>
@endpush

