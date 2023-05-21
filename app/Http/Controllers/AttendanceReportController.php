<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\AttendanceReport;
use App\Exports\AttendanceReportRekap;
use App\Models\PersonalCalender;
use \Carbon\Carbon;
use Excel;

class AttendanceReportController extends Controller
{
    public function home()
    {
        return view('module/attendanceReport/home');
    }
    
    public function exportAttendanceReport(Request $request)
    {
        $month = $request->bulan;
        $year = $request->tahun;
        $title = "LaporanAbsensi-$month#$year.xlsx";
        return Excel::download(new AttendanceReport((int)$month,(int)$year), $title);
    }

    public function exportAttendanceReportRekap(Request $request)
    {
        $month = $request->bulan;
        $year = $request->tahun;
        $title = "LaporanAbsensiRekap-$month#$year.xlsx";
        return Excel::download(new AttendanceReportRekap((int)$month,(int)$year), $title);
    }
}