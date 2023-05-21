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
                            <h4>Pengaturan Waktu</h4>
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Pengaturan</a></li>
                                <li class="breadcrumb-item active">Waktu Guru</li>
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
                                <form id="add-time-teacher" action="">
                                    <input type="hidden" name="teacher_id" id="teacher_id_data" value="{{$modul[0]->teacher_id}}">
                                    <input type="hidden" name="curriculum_year_id" id="curriculum_year_id" value="{{$modul[0]->curriculum_year_id}}">
                                    @foreach($daftarHari as $key => $value)
                                    <div class="formDaftarHari">
                                        <input class="form-check-input" key="{!! $key!!}" type="checkbox" value="{{ $modul[$key]->active }}" {{ $modul[$key]->active == 1 ? 'checked' : '' }} name="activeCheck" id="activeCheck">
                                        <label class="form-check-label header-title" for="activeCheck">{{ ucfirst($value) }}</label>

                                        <div class="row mb-3">
                                            <input type="hidden" name="hari" value="{{ ucfirst($value) }}">
                                            <input type="hidden" class="form-control" value="{{ $modul[$key]->id }}" name="id_data">
                                            
                                            <div class="col-md-3">
                                                <label for="" class="col-form-label">Jam Masuk Awal<span class="required-form-star">*</span></label>
                                                <div class="col-sm-10">
                                                    <input class="form-control check_in_start_{!!$key!!}" value="{{ $modul[$key]->check_in_start }}" type="time" 
                                                    name="check_in_start" {{ $modul[$key]->active == 1 ? 'required ' : 'readonly' }}>

                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="" class="col-form-label">Jam Masuk Akhir<span class="required-form-star">*</span></label>
                                                <div class="col-sm-10">
                                                    <input class="form-control check_in_end_{!!$key!!}" value="{{ $modul[$key]->check_in_end }}" type="time" 
                                                    name="check_in_end" {{ $modul[$key]->active == 1 ? 'required ' : 'readonly' }}>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="" class="col-form-label">Jam Keluar Awal<span class="required-form-star">*</span></label>
                                                <div class="col-sm-10">
                                                    <input class="form-control check_out_start_{!!$key!!}" value="{{ $modul[$key]->check_out_start }}" type="time" 
                                                    name="check_out_start" {{ $modul[$key]->active == 1 ? 'required ' : 'readonly' }}>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="" class="col-form-label">Jam Keluar Akhir<span class="required-form-star">*</span></label>
                                                <div class="col-sm-10">
                                                    <input class="form-control check_out_end_{!!$key!!}" value="{{ $modul[$key]->check_out_end }}" type="time" 
                                                    name="check_out_end" {{ $modul[$key]->active == 1 ? 'required ' : 'readonly' }}>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach

                                    <div class="col-md-3">
                                        <label for="" class="col-form-label">Keterangan</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" value="{{ $modul[0]->description }}" type="text" name="description" id="description">
                                        </div>
                                    </div>

                                    <div class="row mb-3 mt-5">
                                        <div class="col-md-6">
                                            <button type="submit" id="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                                        </div>
                                    </div>
                                </form>
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

$(document).on('click','#activeCheck',function(event){
    var key = $(this).attr('key')
    var check_in_start = `.check_in_start_${key}`
    var check_in_end = `.check_in_end_${key}`
    var check_out_start = `.check_out_start_${key}`
    var check_out_end = `.check_out_end_${key}`

    if($(this).is(":checked")){
        $(this).val('1')
        $(check_in_start).prop('readonly',false);
        $(check_in_start).prop('required',true);

        $(check_in_end).prop('readonly',false);
        $(check_in_end).prop('required',true);

        $(check_out_start).prop('readonly',false);
        $(check_out_start).prop('required',true);

        $(check_out_end).prop('readonly',false);
        $(check_out_end).prop('required',true);
    }

    if ($(this).is(':not(:checked)')){
        $(this).val('0')
        $(check_in_start).prop('readonly',true);
        $(check_in_start).prop('required',false);
        $(check_in_start).val('00:00');

        $(check_in_end).prop('readonly',true);
        $(check_in_end).prop('required',false);
        $(check_in_end).val('00:00');

        $(check_out_start).prop('readonly',true);
        $(check_out_start).prop('required',false);
        $(check_out_start).val('00:00');

        $(check_out_end).prop('readonly',true);
        $(check_out_end).prop('required',false);
        $(check_out_end).val('00:00');
    }
});


$("#add-time-teacher").submit(function(event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    const forms = document.querySelectorAll('.formDaftarHari');
    const reqBodyList = [];
    forms.forEach((form) => {
        const reqBody = {}
        const myNodeList = form.querySelectorAll("input")
        myNodeList.forEach(function(input,key) {
            var nameInput = input.getAttribute('name');
            reqBody[nameInput] = input.value;
        });
        reqBodyList.push(reqBody)
    });

    var description = $('#description').val();
    var teacherIdData = $('#teacher_id_data').val();
    var curriculumYearId = $('#curriculum_year_id').val();
    
    $.ajax({
        url: '{!! url("/pengaturan-waktu-guru/update/") !!}',
        type: "POST",
        data: { 
            data: reqBodyList,
            description : description,
            teacherIdData : teacherIdData,
            curriculumYearId : curriculumYearId,
        },
        success: function(e) {
            if (e.status == 1) {
                Swal.fire({
                    icon: 'success', 
                    title: 'Berhasil',
                    text: e.message, 
                    timer: 1000, 
                })
                location.href = "/pengaturan-waktu-guru";
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

</script>
@endpush