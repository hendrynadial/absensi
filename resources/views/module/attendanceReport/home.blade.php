@extends('layouts/app',['title'=>'Laporan'])
@section('content')

<div class="main-content">
    <div class="page-content">
        <!-- start page title -->
        <div class="page-title-box bg-success">
            <div class="container-fluid">
                <div class="row align-items-left">
                    <div class="col-sm-6">
                        <div class="page-title">
                            <h4>Laporan</h4>
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                <li class="breadcrumb-item active">Laporan Presensi</li>
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
                                                <select name="bulan" id="bulan" class="form-select" aria-label="Profile">
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
                                                <select name="tahun" id="tahun" class="form-select" aria-label="Profile">
                                                    @php
                                                        $year = date('Y');
                                                    @endphp
                                                    @for($i = $year; $i <= $year+1; $i++)
                                                        <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <table id="tblDaftarKehadiran" class="table table-centered dt-responsive nowrap">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-left">Nama Laporan</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td class="text-left">Laporan Presensi Bulanan</td>
                                            <td class="text-center">
                                                <a href='/laporan-absensi/report'
                                                    id="report"
                                                    class="me-3 text-info">
                                                    <i class="mdi mdi-printer font-size-18"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">2</td>
                                            <td class="text-left">Laporan Rekap Presensi Bulanan </td>
                                            <td class="text-center">
                                                <a href='/laporan-absensi/report-rekap'
                                                    id="report-rekap"
                                                    class="me-3 text-info">
                                                    <i class="mdi mdi-printer font-size-18"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
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
   $('#report').on('click',function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        var bulan = $('#bulan').val();
        var tahun = $('#tahun').val();
        var uri = `{{url('/laporan-absensi/report')}}?bulan=${bulan}&tahun=${tahun}`;
        document.location.href = uri
        return
   })
   $('#report-rekap').on('click',function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        var bulan = $('#bulan').val();
        var tahun = $('#tahun').val();
        var uri = `{{url('/laporan-absensi/report-rekap')}}?bulan=${bulan}&tahun=${tahun}`;
        document.location.href = uri
        return
   })
</script>
@endpush

