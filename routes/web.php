<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CurriculumYearController;
use App\Http\Controllers\TimeSettingsTeacherController;
use App\Http\Controllers\TimeSettingsEmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CalenderHolidayController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AttendanceReportController;
use App\Http\Controllers\FeedbackController;

Auth::routes();
Route::get('privacy-policy', function(){
    return view('privacy-policy');
});

Route::post('login', [LoginController::class,'login']);
Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/listCheckIn', [HomeController::class, 'listCheckIn']);
    Route::get('/listCheckOut', [HomeController::class, 'listCheckOut']);
    Route::get('/listIzin', [HomeController::class, 'listIzin']);

    Route::group(['prefix' => 'daftar-kehadiran'], function () {
        Route::get('/', [AttendanceController::class, 'home']);
        Route::get('/{employee_id}', [AttendanceController::class, 'attendanceList']);
        Route::get('/detail/status/{id}', [AttendanceController::class, 'detailStatusAttendance']);
        Route::get('/change/status/{id}', [AttendanceController::class, 'viewChangeStatusAttendance']);
        Route::post('/change/status/', [AttendanceController::class, 'ChangeStatusAttendance']);
    });
    
    Route::group(['prefix' => 'pegawai'], function () {
        Route::get('/', [EmployeeController::class, 'home']);
        Route::get('/add', [EmployeeController::class, 'add']);
        Route::get('/get-all-employee', [EmployeeController::class, 'getAllEmployee']);
        Route::post('/store', [EmployeeController::class, 'store']);
        Route::get('/{id}/edit', [EmployeeController::class, 'edit']);
        Route::post('/update', [EmployeeController::class, 'update']);
        Route::post('/{id}/delete', [EmployeeController::class, 'delete']);
        Route::get('/{id}/generate-calender-guru', [EmployeeController::class, 'viewGeneratePersonalCalenderTeacher']);
        Route::post('/{id}/generate-calender-pegawai', [EmployeeController::class, 'generatePersonalCalender']);
        Route::post('/generate-personal-calender', [EmployeeController::class, 'generatePersonalCalender']);
        Route::post('/generate-all-calender', [EmployeeController::class, 'generateAllCalender']);
    });

    Route::group(['prefix' => 'pengaturan-waktu-guru'], function () {
        Route::get('/', [TimeSettingsTeacherController::class, 'home']);
        Route::get('/add', [TimeSettingsTeacherController::class, 'add']);
        Route::post('/store', [TimeSettingsTeacherController::class, 'store']);
        Route::get('/{teacher_id}/{curriculum_year_id}/edit', [TimeSettingsTeacherController::class, 'edit']);
        Route::post('/update', [TimeSettingsTeacherController::class, 'update']);
        Route::get('/checkPersonalCalender', [TimeSettingsTeacherController::class, 'checkPersonalCalender']);
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [UserController::class, 'home']);
        Route::get('/verifikasi-pegawai', [UserController::class, 'homeEmployeeVerification']);
        Route::post('/verifikasi-pegawai/{id}', [UserController::class, 'employeeVerification']);
        Route::get('/add', [UserController::class, 'add']);
        Route::post('/store', [UserController::class, 'store']);
        Route::get('/{id}/edit', [UserController::class, 'edit']);
        Route::post('/update', [UserController::class, 'update']);
        Route::post('/{id}/delete', [UserController::class, 'delete']);
    });

    Route::group(['prefix' => 'permission'], function () {
        Route::get('/', [PermissionController::class, 'home']);
        Route::post('/{id}/approve', [PermissionController::class, 'approvePermission']);
        Route::get('/{id}/reject', [PermissionController::class, 'reject']);
        Route::post('/{id}/reject', [PermissionController::class, 'rejectPermission']);
    });
    
    // Master Data
    Route::group(['prefix' => 'pengaturan-waktu-pegawai'], function () {
        Route::get('/', [TimeSettingsEmployeeController::class, 'home']);
        Route::get('/add', [TimeSettingsEmployeeController::class, 'add']);
        Route::post('/store', [TimeSettingsEmployeeController::class, 'store']);
        Route::get('/{id}/edit', [TimeSettingsEmployeeController::class, 'edit']);
        Route::post('/update', [TimeSettingsEmployeeController::class, 'update']);
        Route::post('/{id}/delete', [TimeSettingsEmployeeController::class, 'delete']);
    });

    Route::group(['prefix' => 'tahun-ajaran'], function () {
        Route::get('/', [CurriculumYearController::class, 'home']);
        Route::get('/add', [CurriculumYearController::class, 'add']);
        Route::post('/store', [CurriculumYearController::class, 'store']);
        Route::get('/{id}/edit', [CurriculumYearController::class, 'edit']);
        Route::post('/update', [CurriculumYearController::class, 'update']);
        Route::post('/{id}/delete', [CurriculumYearController::class, 'delete']);
        Route::post('/{id}/setActive', [CurriculumYearController::class, 'setActive']);
    });

    Route::group(['prefix' => 'kalender-libur'], function () {
        Route::get('/', [CalenderHolidayController::class, 'home']);
        Route::get('/add', [CalenderHolidayController::class, 'add']);
        Route::post('/store', [CalenderHolidayController::class, 'store']);
        Route::get('/{id}/edit', [CalenderHolidayController::class, 'edit']);
        Route::post('/update', [CalenderHolidayController::class, 'update']);
        Route::post('/{id}/delete', [CalenderHolidayController::class, 'delete']);
        Route::post('/generate-calender-holiday', [CalenderHolidayController::class, 'generateCalenderHoliday']);
    });

    Route::group(['prefix' => 'lokasi'], function () {
        Route::get('/', [LocationController::class, 'home']);
        Route::post('/store', [LocationController::class, 'store']);
    });

    //Report
    Route::group(['prefix' => 'laporan-absensi'], function () {
        Route::get('/', [AttendanceReportController::class, 'home']);
        Route::get('/report', [AttendanceReportController::class, 'exportAttendanceReport']);
        Route::get('/report-rekap', [AttendanceReportController::class, 'exportAttendanceReportRekap']);
    });


    Route::group(['prefix' => 'feedback'], function () {
        Route::get('/', [FeedbackController::class, 'home']);
        Route::get('/{id}/edit', [FeedbackController::class, 'edit']);
        Route::post('/update', [FeedbackController::class, 'update']);
        Route::post('/{id}/delete', [FeedbackController::class, 'delete']);
    });
    
});