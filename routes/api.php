<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthControllerAPI;
use App\Http\Controllers\API\AttendanceControllerAPI;
use App\Http\Controllers\API\PermissionControllerAPI;
use App\Http\Controllers\API\GeneralControllerAPI;


Route::post('/register', [AuthControllerAPI::class, 'register']);
Route::post('/login', [AuthControllerAPI::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    //Attendance
    Route::group(['prefix' => 'attendance'], function () {
        Route::get('/{employee_id}', [AttendanceControllerAPI::class, 'getAttendance']);
        Route::post('/status', [AttendanceControllerAPI::class, 'getCheckInStatus']);
        Route::post('/checkIn', [AttendanceControllerAPI::class, 'attendanceCheckIn']);
        Route::post('/checkOut', [AttendanceControllerAPI::class, 'attendanceCheckOut']);
    });

    //Permission
    Route::group(['prefix' => 'permission'], function () {
        Route::get('/{employeeId}', [PermissionControllerAPI::class, 'getPermission']);
        Route::get('/all/{employeeId}', [PermissionControllerAPI::class, 'getAllPermission']);
        Route::get('/detail/{id}', [PermissionControllerAPI::class, 'getDetailPermission']);
        Route::post('/{employeeId}', [PermissionControllerAPI::class, 'createPermission']);
        Route::post('/update/{id}', [PermissionControllerAPI::class, 'updatePermission']);
        Route::delete('/{id}', [PermissionControllerAPI::class, 'deletePermission']);
    });

    //General Controller
    Route::get('/get-time', [GeneralControllerAPI::class, 'getTimeServer']);
    Route::get('/get-validation-location', [GeneralControllerAPI::class, 'getValidationLocation']);
    Route::post('/feedback', [GeneralControllerAPI::class, 'saveFeedback']);
    Route::post('/feedback/{id}', [GeneralControllerAPI::class, 'deleteFeedback']);

    //Auth
    Route::post('/changePassword/{employeeId}', [AuthControllerAPI::class, 'changePassword']);
    Route::post('/logout', [AuthControllerAPI::class, 'logout']);
});
