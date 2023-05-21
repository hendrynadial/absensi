@extends('layouts/app',['title'=>'Edit Karyawan'])
@section('content')
<div class="main-content">
    <div class="page-content">
        <!-- start page title -->
        <div class="page-title-box bg-success">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="page-title">
                            <h4>Edit Karyawan</h4>
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Karyawan</a></li>
                                <li class="breadcrumb-item active">Edit Karyawan</li>
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
                        <form id="form-edit-teacher" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="{{ $modul->id }}" name="id" value="{{$modul->id}}">
                            <div class="email-leftbar card">
                                <a class="nav-link" id="product-4-tab" data-bs-toggle="pill" href="#product-4" role="tab">
                                    <img src="{{ $modul->foto != null ? $modul->foto : asset('/assets/images/users/avatar-1.png') }}" alt="" class="img-fluid mx-auto d-block tab-img rounded">
                                </a>
                                <br>
                                <input type="file" name="foto" class="btn btn-info btn-block waves-effect waves-light" id="image-input">
                            </div>
                            <!-- End Left sidebar -->

                            <!-- Right Sidebar -->
                            <div class="email-rightbar mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <h4>Profile</h4>
                                            <hr>
                                            <div class="col-md-6">

                                                <label>Jenis Karyawan<span class="required-form-star">*</span></label>
                                                <div class="mb-3">
                                                    <select name="jenis_pegawai" id="jenis_pegawai" class="form-select" aria-label="Jenis Pegawai" required>
                                                        <option>Jenis Karyawan</option>
                                                        <option value="Guru" {{ $modul->jenis_pegawai == "Guru" ? "Selected" : "" }}>Guru</option>
                                                        <option value="Pegawai" {{ $modul->jenis_pegawai == "Pegawai" ? "Selected" : "" }}>Pegawai</option>
                                                    </select>
                                                </div>

                                                <label>NIK <span class="required-form-star">*</span></label>
                                                <div class="mb-3">
                                                    <input type="number" name="nik" value="{{$modul->nik}}" class="form-control" placeholder="NIK" required>
                                                </div>

                                                <label>Nama Lengkap <span class="required-form-star">*</span></label>
                                                <div class="mb-3">
                                                    <input type="text" name="nama" value="{{$modul->nama}}" class="form-control" placeholder="Nama Lengkap" required>
                                                </div>

                                                <label>Tempat Lahir <span class="required-form-star">*</span></label>
                                                <div class="mb-3">
                                                    <input type="text" name="tempat_lahir" value="{{$modul->tempat_lahir}}" class="form-control" placeholder="Tempat Lahir" required>
                                                </div>

                                                <label>Agama <span class="required-form-star">*</span></label>
                                                <div class="mb-3">
                                                    <select name="agama" class="form-select" aria-label="Agama" required>
                                                        <option>Agama</option>
                                                        <option value="Islam" {{ $modul->agama == "Islam" ? "Selected" : "" }}>Islam</option>
                                                        <option value="Kristen Protestan" {{ $modul->agama == "Kristen Protestan" ? "Selected" : "" }}>Kristen Protestan</option>
                                                        <option value="Kristen Katolik" {{ $modul->agama == "Kristen Katolik" ? "Selected" : "" }}>Kristen Katolik</option>
                                                        <option value="Hindu" {{ $modul->agama == "Hindu" ? "Selected" : "" }}>Hindu</option>
                                                        <option value="Buddha" {{ $modul->agama == "Buddha" ? "Selected" : "" }}>Buddha</option>
                                                    </select>
                                                </div>

                                                <label>Status Karyawan <span class="required-form-star">*</span></label>
                                                <div class="mb-3">
                                                    <input type="text" name="status_pegawai" value="{{$modul->status_pegawai}}" placeholder="Status Pegawai" class="form-control" required>
                                                </div>

                                                @php
                                                    $unitDataArr = json_decode($modul->unit,TRUE);
                                                @endphp
                                                <div class="mb-3 unitHide hide">
                                                    <label class="form-label">Unit <span class="required-form-star">*</span></label>
                                                    <select id="select2-unit" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Pilih Unit ...">
                                                        <optgroup label="Unit">
                                                            <option value="TK">TK</option>
                                                            <option value="SD">SD</option>
                                                            <option value="SMP">SMP</option>
                                                            <option value="SMA">SMA</option>
                                                            <option value="SMK">SMK</option>
                                                        </optgroup>
                                                    </select>
                                                </div>

                                                <div class="mb-3 jam-masuk hide">
                                                    <label>Jam Masuk <span class="required-form-star">*</span></label>
                                                    <select name="jam_masuk" class="form-select" aria-label="Jam Masuk">
                                                        <option value="">Pilih Jam Masuk</option>
                                                        @foreach ($timeSettingEmployee as $value)
                                                        <option value="{{ $value->id }}" {{ $modul->id_time_settings_employee == $value->id ? "Selected" : "" }}>
                                                            {{ App\helper\Helpers::formatTimeCarbon($value->check_in_start) }} - {{ App\helper\Helpers::formatTimeCarbon($value->check_out_end) }} ({{ $value->description }})
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">

                                                <label>NIP <span class="required-form-star">*</span></label>
                                                <div class="mb-3">
                                                    <input type="text" name="nip" value="{{$modul->nip}}" class="form-control" placeholder="Nomor Induk Pegawai" required>
                                                </div>

                                                <label>Jenis Kelamin <span class="required-form-star">*</span></label>
                                                <div class="mb-3">
                                                    <select name="jenis_kelamin" class="form-select" aria-label="Jenis Kelamin" required>
                                                        <option>Jenis Kelamin</option>
                                                        <option value="Laki-Laki" {{ $modul->jenis_kelamin == "Laki-Laki" ? "Selected" : "" }}>Laki-Laki</option>
                                                        <option value="Perempuan" {{ $modul->jenis_kelamin == "Perempuan" ? "Selected" : "" }}>Perempuan</option>
                                                    </select>
                                                </div>

                                                <label>Tanggal Lahir <span class="required-form-star">*</span></label>
                                                <div class="mb-3">
                                                    <input type="date" name="tanggal_lahir" value="{{$modul->tanggal_lahir->format('Y-m-d')}}" class="form-control" required>
                                                </div>

                                                <label>Jabatan <span class="required-form-star">*</span></label>
                                                <div class="mb-3">
                                                    <input type="text" name="jabatan" value="{{join("|",json_decode($modul->jabatan,TRUE))}}" placeholder="Jabatan" class="form-control" required>
                                                    <small><i>Jika jabatan > 1, gunakan "|" untuk memisahkan<i></small>
                                                </div>

                                                <label>Alamat <span class="required-form-star">*</span></label>
                                                <div class="mb-3">
                                                    <input type="text" name="alamat" value="{{$modul->alamat}}" class="form-control" Placeholder="Alamat" required>
                                                </div>

                                                <label>Tanggal Bergabung <span class="required-form-star">*</span></label>
                                                <div class="mb-3">
                                                    <input type="date" name="tanggal_bergabung" value="{{$modul->tanggal_bergabung->format('Y-m-d')}}" placeholder="Tanggal Bergabung" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <h4>Kontak</h4>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Email </label>
                                                <div class="mb-3">
                                                    <input type="email" name="email" value="{{$modul->email}}" placeholder="Email" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>No Hp <span class="required-form-star">*</span></label>
                                                <div class="mb-3">
                                                    <input type="text" name="no_hp" value="{{$modul->no_hp}}" placeholder="Telp" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-success">Simpan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end Col-9 -->
                        </form>
                    </div>
                </div><!-- End row -->
            </div>
        </div> <!-- container-fluid -->
    </div>
</div>

@endsection
@push('scripts')
<script>

$(document).ready(function(){
    var value = $('#jenis_pegawai').find(":selected").val();
    if(value == "Pegawai")
    {
        $('.jam-masuk').removeClass('hide')
        $('select[name="jam_masuk"]').removeAttr('disabled', 'disabled');
        
        $('.unitHide').addClass('hide')
        $('select[name="unit"]').attr('disabled', 'disabled');
    }else if(value == "Guru"){
        $('.unitHide').removeClass('hide')
        $('select[name="unit"]').removeAttr('disabled', 'disabled');
        var dataUnit = '{!! $modul->unit !!}';
        $('#select2-unit').select2();
        $('#select2-unit').val(JSON.parse(dataUnit));
        $('#select2-unit').trigger('change');

        $('.jam-masuk').addClass('hide')
        $('select[name="jam_masuk"]').attr('disabled', 'disabled');
    }else{
        $('.unitHide').addClass('hide')
        $('select[name="unit"]').attr('disabled', 'disabled');

        $('.jam-masuk').addClass('hide')
        $('select[name="jam_masuk"]').attr('disabled', 'disabled');
    }
})

$(document).on('change','#jenis_pegawai',function(e){
    event.preventDefault();
    var value = $(this).val();
    if(value == "Pegawai")
    {
        $('.jam-masuk').removeClass('hide')
        $('select[name="jam_masuk"]').removeAttr('disabled', 'disabled');
        
        $('.unitHide').addClass('hide')
        $('select[name="unit"]').attr('disabled', 'disabled');
    }else if(value == "Guru"){
        $('.unitHide').removeClass('hide')
        $('select[name="unit"]').removeAttr('disabled', 'disabled');
        var dataUnit = '{!! $modul->unit !!}';
        $('#select2-unit').select2();
        $('#select2-unit').val(JSON.parse(dataUnit));
        $('#select2-unit').trigger('change');


        $('.jam-masuk').addClass('hide')
        $('select[name="jam_masuk"]').attr('disabled', 'disabled');
    }else{
        $('.unitHide').addClass('hide')
        $('select[name="unit"]').attr('disabled', 'disabled');

        $('.jam-masuk').addClass('hide')
        $('select[name="jam_masuk"]').attr('disabled', 'disabled');
    }
})


$("#form-edit-teacher").submit(function(event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    var formData = new FormData($(this)[0]);
    var unitData = $("#select2-unit").val();
    formData.append('unit', unitData);

    $.ajax({
        url: '{!! url("/pegawai/update/") !!}',
        type: "POST",
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(e) {
            if (e.status == 1) {
                Swal.fire({
                    icon: 'success', 
                    title: 'Berhasil', 
                    text: e.message, 
                    timer: 1000, 
                })
                location.href = "/pegawai";
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Opss...',
                    text: e.message, 
                    timer: 2000, 
                })
            }
        }
    });
});
</script>
@endpush
