<?php

namespace App\Exports;
use App\Models\PersonalCalender;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use \Carbon\Carbon;


class AttendanceReport implements FromView
{
    public function __construct(int $month,int $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function view(): View
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1);
        
        $kehadiran = [];
        for($i=1; $i <= $date->daysInMonth; $i++){
            $kehadiran[$i] = ['status'=> "Kosong"]; 
        }
        
        $dataKehadiran = [];
        $dataPegawai = PersonalCalender::select('employee_id')->with('RelasiPegawai')->whereMonth('date',$this->month)->whereYear('date',$this->year)->groupBy('employee_id')->get();
        foreach($dataPegawai as $key => $value){
            $dataKehadiran[$key]['nama'] = $value['RelasiPegawai']['nama'];
            $dataKehadiran[$key]['nik'] = $value['RelasiPegawai']['nik'];
            $dataKehadiran[$key]['jenis_pegawai'] = $value['RelasiPegawai']['jenis_pegawai'];
            
            $attendance = PersonalCalender::select('date','status')->where('employee_id',$value['employee_id'])->whereMonth('date',$this->month)->whereYear('date',$this->year)->get()->toArray();
            foreach ($attendance as $keyAtt => $valueAtt) {
                $fixAtt[$keyAtt+1]['status'] = $valueAtt['status'];
                $fixAtt[$keyAtt+1]['date'] = $valueAtt['date'];
                $fixAtt[$keyAtt+1]['number'] = Carbon::parse($valueAtt['date'])->format('d') * 1;
            }
            foreach ($kehadiran as $keyKn => $valueKn) {
                if(array_key_exists($keyKn,$fixAtt)){
                    if($fixAtt[$keyKn]['date'] < Carbon::today()){
                        $kehadiran[$fixAtt[$keyKn]['number']]['status'] = $fixAtt[$keyKn]['status'];
                    }
                }else{
                    continue;
                }
            }
            $dataKehadiran[$key]['kehadiran'] = $kehadiran;
        }

        return view('module/attendanceReport/attendanceReport', [
            'month' => date('F', mktime(0, 0, 0, $this->month, 10)),
            'year' => $this->year,
            'date' => $date->daysInMonth,
            'module' => $dataKehadiran,
        ]);
    }
}
