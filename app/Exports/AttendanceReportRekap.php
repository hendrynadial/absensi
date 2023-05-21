<?php

namespace App\Exports;
use App\Models\PersonalCalender;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use \Carbon\Carbon;


class AttendanceReportRekap implements FromView
{
    public function __construct(int $month,int $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function view(): View
    {
        $dataKehadiran = [];
        $dataPegawai = PersonalCalender::select('employee_id')->with('RelasiPegawai')->whereMonth('date',$this->month)->whereYear('date',$this->year)->groupBy('employee_id')->get();

        foreach($dataPegawai as $key => $value){
            $dataKehadiran[$key]['nama'] = $value['RelasiPegawai']['nama'];
            $dataKehadiran[$key]['nik'] = $value['RelasiPegawai']['nik'];
            $dataKehadiran[$key]['jenis_pegawai'] = $value['RelasiPegawai']['jenis_pegawai'];
            $dataKehadiran[$key]['absen'] = PersonalCalender::CountAbsen($value['RelasiPegawai']['id'],$this->month,$this->year,$value['RelasiPegawai']['tanggal_bergabung'],Carbon::today())->count();
            $dataKehadiran[$key]['izin'] = PersonalCalender::AmountStatusAttendance($value['RelasiPegawai']['id'],"Izin",$this->month,$this->year)->count();
            $dataKehadiran[$key]['hadir'] = PersonalCalender::AmountStatusAttendance($value['RelasiPegawai']['id'],"Hadir",$this->month,$this->year)->count();
        }

        return view('module/attendanceReport/attendanceReportRekap', [
            'month' => date('F', mktime(0, 0, 0, $this->month, 10)),
            'year' => $this->year,
            'module' => $dataKehadiran,
        ]);
    }
}
