<?php
namespace App\Helper;
use \Carbon\Carbon;
use \Carbon\CarbonPeriod;
use App\Models\CurriculumYear;
use App\Models\CalenderHoliday;
use App\Models\TimeSettingsTeacher;
use DB;

class Helpers {
    function formatTimeCarbon($data)
    {
        return Carbon::createFromFormat('H:i:s',$data)->format('H:i');
    }

    function generatePersonalCalender($employeeID,$curriculumYearID=null,$year=null)
    {
        $checkHolidayExist = CalenderHoliday::where('year',intval(date('Y')))->first();
        if($checkHolidayExist == null)
        {
            $arr['status'] = 0;
            $arr['message'] = "Kalender libur tahun ".date('Y')." masih kosong";
            return $arr;
        }

        //Guru
        if($curriculumYearID != null)
        {
            $cy = CurriculumYear::find($curriculumYearID);
            $startDate = $cy->start_date->format('Y-m-d');
            $endDate = $cy->end_date->format('Y-m-d');
        }
        
        //Pegawai
        if($year != null)
        {
            $date = Carbon::createFromDate($year, 1, 1);
            $startDate = $date->copy()->startOfYear();
            $endDate = $date->copy()->endOfYear();
        }
        
        $data = [];
        $period = CarbonPeriod::create($startDate, $endDate);
        foreach ($period as $key => $date) 
        {
            $data[$key]['employee_id'] = $employeeID;
            $data[$key]['curriculum_year_id'] = $curriculumYearID;
            $data[$key]['year'] = $year;
            $data[$key]['date'] = $date->format('Y-m-d');
            $data[$key]['day'] = $date->isoFormat('dddd');
            $data[$key]['status_check_in'] = false;
            $data[$key]['status_check_out'] = false;
            

            $checkHoliday = CalenderHoliday::whereDate('date',$date->format('Y-m-d'))->first();
            if($checkHoliday != null)
            {
                $data[$key]['status'] = "Libur";
                $data[$key]['id_calender_holiday'] = $checkHoliday->id;
                $data[$key]['reason'] = $checkHoliday->reason;
            }else{
                $data[$key]['status'] = $date->isoFormat('dddd') == "Minggu" ? "Libur" : "Absen";
                $data[$key]['id_calender_holiday'] = null;
                $data[$key]['reason'] = $date->isoFormat('dddd') == "Minggu" ? "Minggu" : null;
            }
            $data[$key]['created_at'] = date('Y-m-d H:i:s');
        }
        
        $modul = DB::table('personal_calender')->insert($data);
        if($modul){
            $arr['status'] = 1;
            $arr['message'] = "Kalender berhasil digenerate";
            return $arr;
        }else{
            $arr['status'] = 0;
            $arr['message'] = "Kalender gagal digenerate";
            return $arr;
        }
    }

    public function ReturnResponseAPI($code,$message,$result)
    {
        return response()->json([
            'StatusCode' => $code,
            'Message' => $message, 
            'Result' => $result
        ],$code);
    }
}