@extends('layouts/app',['title'=>'Pengaturan Waktu Guru'])
@section('content')

<div class="main-content">
    <div class="page-content">
        <!-- start page title -->
        <div class="page-title-box bg-success">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="page-title">
                            <h4>Waktu Guru</h4>
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                <li class="breadcrumb-item active">Waktu Guru</li>
                            </ol>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <a href="pengaturan-waktu-guru/add" class="float-end d-none d-sm-block btn btn-info mb-2"><i class="mdi mdi-plus me-2"></i> Tambah Waktu</a>
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
                                                <input type="text" name="nip" class="form-control" placeholder="NIP">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">&nbsp;</label>
                                                <input type="text" name="nama" class="form-control" placeholder="Nama">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">&nbsp;</label>
                                                <select name="tahun_ajaran" class="form-select" aria-label="Tahun Ajaran">
                                                    <option value="">Tahun Ajaran</option>
                                                    @foreach ($curriculumYear as $value)
                                                    <option value="{{ $value->curriculum_year }}">{{ $value->curriculum_year }}</option>
                                                    @endforeach
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

                                <div class="table-responsive mt-3">
                                    <table id="tblPengaturanWaktu" class="table table-centered dt-responsive nowrap">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIP</th>
                                                <th>Nama Guru</th>
                                                <th>Tahun Ajaran</th>
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
    
    $(function() {
        $('#tblPengaturanWaktu').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: false,
            pageLenght: 10,
            ajax: {
                url: '{!! url("/pengaturan-waktu-guru") !!}',
                data: function (d) {
                    d.form = $('#filterForm').serializeArray();
                },
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className: "dt-center", orderable:false, searchable:false},
                {data: 'nip', name: 'nip',className:"text-left"},
                {data: 'teacher_id', name: 'teacher_id',className:"text-left"},
                {data: 'curriculum_year_id', name: 'curriculum_year_id',className:"text-left"},
                {data: 'action', name: 'action',className:"text-center",orderable: false, searchable: false},
            ]
        });
    });

    $('#filterForm').on('submit', function(e) {
		e.preventDefault();
		$('#tblPengaturanWaktu').DataTable().ajax.reload();
	});
</script>
@endpush
