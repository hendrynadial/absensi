<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TimeSettingsTeacher;
use App\Models\TimeSettingsEmployee;
use App\Models\PersonalCalender;
use App\Models\Employee;
use App\helper\Helpers;
use \Carbon\Carbon;
use Validator;
use Auth;

class AttendanceControllerAPI extends Controller
{
    public function getAttendance($employee_id,Request $request)
    {
        $modul = PersonalCalender::where('employee_id',$employee_id)
        ->where(function($x)use($request){
            if($request->start_date != null && $request->end_date != null){
                $x->where('date','>=',$request->start_date)
                ->where('date','<=',$request->end_date);
            }
        })
        ->where(function($x)use($request){
            if($request->status != null){
                $x->where('status',$request->status);
            }
        })
        ->get();
        return Helpers::ReturnResponseAPI(200,"Success",$modul);
    }

    public function attendanceCheckIn(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'employee_id' => 'required|string',
            'date'  => 'required|date',
            'time'  => 'required|string',
            'latitude'  => 'required|string',
            'longitude'  => 'required|string',
            'photo'  => 'required|mimes:png,jpg,jpeg|max:2048',
        ]);

        if($validator->fails()){
            return Helpers::ReturnResponseAPI(400,$validator->errors(),null);
        }

        // Validasi jika tanggal yang dikirimkan bukan tanggal sekarang / tanggal server
        if(date('Y-m-d') != $request->date)
        {
            return Helpers::ReturnResponseAPI(400,"Tanggal yang dikirim, bukan tanggal hari ini",null);
        }

        // Validasi waktu kehadiran 
        $timeCheck = $this->timeCheck($request->employee_id,"Check-in",$request->time);
        if(!$timeCheck[0]){
            return Helpers::ReturnResponseAPI(404,$timeCheck[1],null);
        }

        // validasi personal calender apabila data tidak ada
        $personalCalender = PersonalCalender::where('employee_id',$request->employee_id)->whereDate('date',$request->date)->first();
        if($personalCalender == null)
        {
            return Helpers::ReturnResponseAPI(404,"Kalender tidak ditemukan!",null);
        }

        // validasi 2x melakukan check-in
        if($personalCalender->status_check_in)
        {
            return Helpers::ReturnResponseAPI(400,"Anda sudah melakukan Check-in",null);
        }

        $photoName = $request->employee_id."_".date('Ymd')."_in";
        $folderName = Auth::user()->username;
        $photo = null;
        if ($request->file('photo')) {
            $imagePath = $request->file('photo');
            $ext = $imagePath->getClientOriginalExtension();
            $file = "$photoName.$ext";
            $path = $request->file('photo')->storeAs('attendance/'.$folderName, $file, 'public');
            $photo = '/storage/'.$path;
        }
        $personalCalender->check_in = $request->time;
        $personalCalender->status_check_in = true;
        $personalCalender->photo_check_in = $photo;
        $personalCalender->latitude_check_in = $request->latitude;
        $personalCalender->longitude_check_in = $request->longitude;
        $personalCalender->status = "Absen";

        if($personalCalender->update()){
            return Helpers::ReturnResponseAPI(200,"Berhasil Check-in",null);
        }else{
            return Helpers::ReturnResponseAPI(400,"Gagal Check-in",null);
        }
    }

    public function attendanceCheckOut(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'employee_id' => 'required|string',
            'date'  => 'required|date',
            'time'  => 'required|string',
            'latitude'  => 'required|string',
            'longitude'  => 'required|string',
            'photo'  => 'required|mimes:png,jpg,jpeg|max:2048',
        ]);

        if($validator->fails()){
            return Helpers::ReturnResponseAPI(400,$validator->errors(),null);
        }

        // Validasi jika tanggal yang dikirimkan bukan tanggal sekarang / tanggal server
        if(date('Y-m-d') != $request->date)
        {
            return Helpers::ReturnResponseAPI(400,"Tanggal yang dikirim, bukan tanggal hari ini",null);
        }

        $timeCheck = $this->timeCheck($request->employee_id,"Check-out",$request->time);
        if(!$timeCheck[0]){
            return Helpers::ReturnResponseAPI(404,$timeCheck[1],null);
        }
        
        // validasi jika tidak melakukan check-in, maka tidak bisa melakukan check-out
        $personalCalender = PersonalCalender::where('employee_id',$request->employee_id)->whereDate('date',$request->date)->first();
        if(!$personalCalender->status_check_in)
        {
            return Helpers::ReturnResponseAPI(400,"Anda belum / tidak melakukan check-in",null);
        }

        // validasi jika 2x melakukan check-out
        if($personalCalender->status_check_out)
        {
            return Helpers::ReturnResponseAPI(400,"Anda sudah melakukan check-out",null);
        }

        $photoName = $request->employee_id."_".date('Ymd')."_out";
        $folderName = Auth::user()->username;
        $photo = null;
        if ($request->file('photo')) {
            $imagePath = $request->file('photo');
            $ext = $imagePath->getClientOriginalExtension();
            $file = "$photoName.$ext";
            $path = $request->file('photo')->storeAs('attendance/'.$folderName, $file, 'public');
            $photo = '/storage/'.$path;
        }
        $personalCalender->check_out = $request->time;
        $personalCalender->status_check_out = true;
        $personalCalender->photo_check_out = $photo;
        $personalCalender->latitude_check_out = $request->latitude;
        $personalCalender->longitude_check_out = $request->longitude;
        $personalCalender->status = "Hadir";

        if($personalCalender->update()){
            return Helpers::ReturnResponseAPI(200,"Berhasil Check-out",null);
        }else{
            return Helpers::ReturnResponseAPI(400,"Gagal Check-out",null);
        }
    }

    public function getCheckInStatus(Request $request)
    {
        $data = [];
        $employee = Employee::find($request->employee_id);
        $modul = PersonalCalender::select([
            'employee_id',
            'date',
            'check_in',
            'check_out',
            'status',
            'photo_check_in',
            'photo_check_out',
        ])
        ->where('employee_id',$request->employee_id)
        ->where('date',$request->date)
        ->first();

        if($modul == null){
            return Helpers::ReturnResponseAPI(404,"Data Absensi Tidak ditemukan",null);
        }

        if($modul->status == "Libur"){
            $data['is_holiday'] = true;
            $data['personal_calender'] = null;
            $data['time_settings'] = null;
        }else{
            if($employee->jenis_pegawai == "Guru"){
                $timeSettings = $this->getTimeSettingTeacher($request->employee_id,$request->date);
            }else{
                $timeSettings = TimeSettingsEmployee::select([
                    'check_in_start',
                    'check_in_end',
                    'check_out_start',
                    'check_out_end',
                    'description',
                    'saturday_check_in_start',
                    'saturday_check_in_end',
                    'saturday_check_out_start',
                    'saturday_check_out_end',
                ])->find($employee->id_time_settings_employee);

                if($timeSettings == null){
                    return Helpers::ReturnResponseAPI(404,"Jadwal Pegawai Tidak ditemukan",null);
                }
            }
            $data['personal_calender'] = $modul;
            $data['time_settings'] = $timeSettings ?? null;
        }
        return Helpers::ReturnResponseAPI(200,"Success",$data);
    }

    function getTimeSettingTeacher($employeeId,$date)
    {
        $day = Carbon::parse($date)->isoFormat('dddd');
        $modul = TimeSettingsTeacher::select([
            'check_in_start',
            'check_in_end',
            'check_out_start',
            'check_out_end',
        ])
        ->whereHas('RelasiTahunAjaran', function ($query) {
            $query->where('active',1);
        })
        ->where('teacher_id', $employeeId)
        ->where('day', $day)
        ->where('active', 1)
        ->first();

        return $modul;
    }

    function timeCheck($employeeId,$status,$time)
    {
        $day = date("l");
        $employee = Employee::find($employeeId);
        if($employee->jenis_pegawai == "Pegawai")
        {
            $modul = TimeSettingsEmployee::find($employee->id_time_settings_employee);
            $checkInStart = $day == "Saturday" ? $modul->saturday_check_in_start : $modul->check_in_start;
            $checkInEnd = $day == "Saturday" ? $modul->saturday_check_in_end : $modul->check_in_end;
            $checkOutStart = $day == "Saturday" ? $modul->saturday_check_out_start : $modul->check_out_start;
            $checkOutEnd = $day == "Saturday" ? $modul->saturday_check_out_end : $modul->check_out_end;
            
            if($status == "Check-in")
            { 
                //Check batas awal waktu bisa melakukan check-in
                if($time < $checkInStart){
                    return [false,"Anda belum bisa melakukan Check-in"];
                }

                //Check batas akhir waktu bisa melakukan check-in  
                if($time > $checkInEnd){
                    return [false, "Anda telah melewati batas waktu Check-in"];
                }
            }

            if($status == "Check-out")
            { 
                //Check batas awal waktu bisa melakukan check-in
                if($time < $checkOutStart){
                    return [false,"Anda belum bisa melakukan Check-out"];
                }

                //Check batas akhir waktu bisa melakukan check-in  
                if($time > $checkOutEnd){
                    return [false,"Anda telah melewati batas waktu Check-out"];
                }
            }
        }else{
            $date = date('Y-m-d');
            $modul = $this->getTimeSettingTeacher($employeeId,$date);
            if($modul == null){
                return [false, "Hari ini Anda tidak ada jadwal"];
            }

            if($status == "Check-in")
            { 
                //Check batas awal waktu bisa melakukan check-in
                if($time < $modul->check_in_start){
                    return [false,"Anda belum bisa melakukan Check-in"];
                }

                //Check batas akhir waktu bisa melakukan check-in  
                if($time > $modul->check_in_end){
                    return [false, "Anda telah melewati batas waktu Check-in"];
                }
            }

            if($status == "Check-out")
            { 
                //Check batas awal waktu bisa melakukan check-in
                if($time < $modul->check_out_start){
                    return [false,"Anda belum bisa melakukan Check-out"];
                }

                //Check batas akhir waktu bisa melakukan check-in  
                if($time > $modul->check_out_end){
                    return [false,"Anda telah melewati batas waktu Check-out"];
                }
            }
        }

        return [true,"Success"];
    }
}
