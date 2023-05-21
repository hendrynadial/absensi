@extends('layouts/app',['title'=>'Dashboard'])
@section('content')

<div class="main-content">
    <div class="page-content">
        <!-- start page title -->
        <div class="page-title-box bg-success">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="page-title">
                            <h4>Dashboard</h4>
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="container-fluid">
            <div class="page-content-wrapper">
                <div class="row align-items-center">
                    <div class="col-xl-12">
                        <div class="row">
                            <div class="col-xl-4 col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <p class="font-size-16">Check In</p>
                                            <div class="mini-stat-icon mx-auto mb-4 mt-3">
                                                <span class="avatar-title rounded-circle bg-soft-success">
                                                    <i class="mdi mdi-account-outline text-success font-size-20"></i>
                                                </span>
                                            </div>
                                            <h5 class="font-size-22">
                                                <a href="/listCheckIn"
                                                    id="list-CheckIn"
                                                    data-bs-toggle="#myModal"
                                                    data-bs-target=".bs-example-modal-xl">
                                                    {{ $checkIn }}
                                                </a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <p class="font-size-16">Check Out</p>
                                            <div class="mini-stat-icon mx-auto mb-4 mt-3">
                                                <span class="avatar-title rounded-circle bg-soft-danger">
                                                    <i class="mdi mdi-timer text-danger font-size-20"></i>
                                                </span>
                                            </div>
                                            <h5 class="font-size-22">
                                                <a href="/listCheckOut"
                                                    id="list-CheckOut"
                                                    data-bs-toggle="#myModal"
                                                    data-bs-target=".bs-example-modal-xl">
                                                    {{ $checkOut }}
                                                </a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <p class="font-size-16">Izin</p>
                                            <div class="mini-stat-icon mx-auto mb-4 mt-3">
                                                <span class="avatar-title rounded-circle bg-soft-warning">
                                                    <i class="mdi mdi-account-arrow-left-outline text-warning font-size-20"></i>
                                                </span>
                                            </div>
                                            <h5 class="font-size-22">
                                                <a href="/listIzin"
                                                    id="list-izin"
                                                    data-bs-toggle="#myModal"
                                                    data-bs-target=".bs-example-modal-xl">
                                                    {{ $izin }}
                                                </a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-2 col-md-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center">
                                    <p class="font-size-16">Admin</p>
                                    <div class="mini-stat-icon mx-auto mb-4 mt-3">
                                        <span class="avatar-title rounded-circle bg-soft-primary">
                                            <i class="mdi mdi-account-circle text-primary font-size-20"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-22">{{ $user }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-2 col-md-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center">
                                    <p class="font-size-16">Karyawan</p>
                                    <div class="mini-stat-icon mx-auto mb-4 mt-3">
                                        <span class="avatar-title rounded-circle bg-soft-success">
                                            <i class="mdi mdi-human-female-female text-success font-size-20"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-22">{{ $employee::Employee()->count() }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-2 col-md-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center">
                                    <p class="font-size-16">Pegawai</p>
                                    <div class="mini-stat-icon mx-auto mb-4 mt-3">
                                        <span class="avatar-title rounded-circle bg-soft-warning">
                                            <i class="mdi mdi-human-male text-warning font-size-20"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-22">{{ $employee::Employee('Pegawai')->count() }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-2 col-md-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center">
                                    <p class="font-size-16">Guru</p>
                                    <div class="mini-stat-icon mx-auto mb-4 mt-3">
                                        <span class="avatar-title rounded-circle bg-soft-warning">
                                            <i class="mdi mdi-human-male text-warning font-size-20"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-22">{{ $employee::Employee('Guru')->count() }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-2 col-md-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center">
                                    <p class="font-size-16">Laki-laki</p>
                                    <div class="mini-stat-icon mx-auto mb-4 mt-3">
                                        <span class="avatar-title rounded-circle bg-soft-info">
                                            <i class="mdi mdi-gender-male text-info font-size-20"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-22">{{ $employee::Employee(null,'Laki-Laki')->count() }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-2 col-md-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center">
                                    <p class="font-size-16">Perempuan</p>
                                    <div class="mini-stat-icon mx-auto mb-4 mt-3">
                                        <span class="avatar-title rounded-circle bg-soft-info">
                                            <i class="mdi mdi-gender-female text-info font-size-20"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-22">{{ $employee::Employee(null,'Perempuan')->count() }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
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

    var $modal = $('#myModal');
	$(document).on('click','#list-CheckIn', function(e){
		e.preventDefault();
		e.stopPropagation();
		e.stopImmediatePropagation();
		var uri = $(this).attr('href');
		$.ajax({
			url: uri, 
			type: "GET",
			success: function (result) {
				$modal.find(".modal-content").html(result);
			}
		});
		$modal.modal('show');
	});

	$(document).on('click','#list-CheckOut', function(e){
		e.preventDefault();
		e.stopPropagation();
		e.stopImmediatePropagation();
		var uri = $(this).attr('href');
		$.ajax({
			url: uri, 
			type: "GET",
			success: function (result) {
				$modal.find(".modal-content").html(result);
			}
		});
		$modal.modal('show');
	});

	$(document).on('click','#list-izin', function(e){
		e.preventDefault();
		e.stopPropagation();
		e.stopImmediatePropagation();
		var uri = $(this).attr('href');
		$.ajax({
			url: uri, 
			type: "GET",
			success: function (result) {
				$modal.find(".modal-content").html(result);
			}
		});
		$modal.modal('show');
	});

</script>
@endpush