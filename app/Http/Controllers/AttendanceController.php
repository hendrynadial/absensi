<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\PersonalCalender;
use App\Models\LogAttendance;
use App\Models\CurriculumYear;
use App\Models\Employee;
use App\helper\Helpers;
use \Carbon\Carbon;
use DataTables;
use Auth;

class AttendanceController extends Controller
{
    public function home(Request $request)
    {
        if ($request->ajax()) {
            $formId = request('form');
		    $nik = null;
		    $nama = null;
		    $bulan = null;
		    $tahun = null;

            foreach($formId as $value)
            {
                if($value['name'] == "nik"){
                    $nik = $value['value'];
                }

                if($value['name'] == "nama"){
                    $nama = $value['value'];
                }

                if($value['name'] == "bulan"){
                    $bulan = $value['value'];
                }

                if($value['name'] == "tahun"){
                    $tahun = $value['value'];
                }
            }

            $modul = PersonalCalender::select([
                'employee_id',
            ])
            ->with('RelasiPegawai')
            ->where(function($x)use($bulan){
                if($bulan != null){
                    $x->whereMonth('date',$bulan);
                }else{
                    $x->whereMonth('date',date('m'));
                }
            })
            ->where(function($x)use($tahun){
                if($tahun != null){
                    $x->whereYear('date',$tahun);
                }else{
                    $x->whereYear('date',date('Y'));
                }
            })
            ->whereHas('RelasiPegawai', function ($query) use ($nik) {
                if (!empty($nik)) {
                    $query->where('nik', 'like', '%'.$nik.'%');
                }
            })
            ->whereHas('RelasiPegawai', function ($query) use ($nama) {
                if (!empty($nama)) {
                    $query->where('nama', 'like', '%'.$nama.'%');
                }
            })
            ->groupBy('employee_id');

            return Datatables::of($modul)
            ->addColumn('nik', function ($modul) {
                return $modul->RelasiPegawai->nik;
            })

            ->editColumn('employee_id', function ($modul) {
                return $modul->RelasiPegawai->nama;
            })

            ->addColumn('jumlah_hadir', function ($modul)use($bulan,$tahun) {
                return $modul::active($modul->employee_id,'Hadir',$bulan,$tahun)->count();
            })

            ->addColumn('jumlah_izin', function ($modul)use($bulan,$tahun) {
               return $modul::active($modul->employee_id,'Izin',$bulan,$tahun)->count();
            })
            
            ->addColumn('jumlah_absen', function ($modul)use($bulan,$tahun) {
                $checkKarayawan = $this->checkEmployeeType($modul->employee_id);
                if($checkKarayawan[0] == "Pegawai") {
                    return PersonalCalender::CountAbsenPegawai($modul->employee_id,$bulan,$tahun,$modul->RelasiPegawai->tanggal_bergabung,Carbon::today())->count();
                }else{
                    return PersonalCalender::CountAbsenGuru($modul->employee_id,$bulan,$checkKarayawan[1],$modul->RelasiPegawai->tanggal_bergabung,Carbon::today())->count();
                }
            })

            ->addColumn('action', function ($modul) {
                return '<a href="/daftar-kehadiran/'.$modul->employee_id.'" 
                            id="btn-list-kehadiran" 
                            class="text-success">
                            <i class="mdi mdi-calendar font-size-20"></i>
                        </a>';
            })
            ->addIndexColumn()
            ->rawColumns(['nik','jumlah_hadir','jumlah_izin','jumlah_absen','action'])
            ->removeColumn('date')
            ->make(true);
        }
        return view('module/attendance/home');
    }

    public function attendanceList($employee_id,Request $request)
    {
        if ($request->ajax()) {
            $formId = request('form');
		    $bulan = null;
		    $tahun = null;
		    $status = null;

            foreach($formId as $value)
            {
                if($value['name'] == "bulan"){
                    $bulan = $value['value'];
                }

                if($value['name'] == "tahun"){
                    $tahun = $value['value'];
                }

                if($value['name'] == "status"){
                    $status = $value['value'];
                }
            }

            $modul = PersonalCalender::select([
                'id',
                'employee_id',
                'day',
                'date',
                'check_in',
                'check_out',
                'status',
            ])
            ->with('RelasiPegawai')
            ->where('employee_id',$employee_id)
            ->whereMonth('date',$bulan)
            ->whereYear('date',$tahun)
            ->where(function($x)use($status){
                if(!empty($status))
                {
                    $x->where('status',$status);
                }
            })
            ->orderBy('date');
            
            return Datatables::of($modul)
            ->editColumn('day', function ($modul) {
                if($modul->day == "Sabtu"){
                    $class="info";
                }else if($modul->day == "Minggu"){
                    $class="danger";
                }else{
                    $class = "success";
                }
                return "<span class='badge badge-pill badge-soft-$class font-size-13'>$modul->day</span>";
            })

            ->editColumn('check_in', function ($modul) {
                return $modul->check_in == null ? "00:00" : Helpers::formatTimeCarbon($modul->check_in);
            })

            ->editColumn('check_out', function ($modul) {
                return $modul->check_out == null ? "00:00" : Helpers::formatTimeCarbon($modul->check_out);
            })
            
            ->editColumn('date', function ($modul) {
                return Carbon::parse($modul->date)->format('d / m / Y');
            })

            ->editColumn('status', function ($modul) {

                $status = $modul->status ?? "Kosong";
                if($status == "Absen"){
                    if($modul->date < $modul->RelasiPegawai->tanggal_bergabung->subdays(1) || $modul->date > Carbon::today()){
                        $status = "Kosong";
                        $class = 'dark';
                    }else{
                        $class = "danger";
                    }
                }else{
                    if($status == "Hadir"){
                        $class = "success"; 
                    }else if($status == "Libur"){
                        $class = "secondary";
                    }else if($status == "Izin"){
                        $class = "warning";
                    }else{
                        $class='dark'; // Kosong
                    }
                }
                
                return "<span class='badge badge-pill badge-soft-$class font-size-14'>$status</span>";
            })

            ->addColumn('action', function ($modul) {
                $btn = '<a href="/daftar-kehadiran/detail/status/'.$modul->id.'" id="detail-attandance" class="text-success">
                        <i class="mdi mdi-folder-open font-size-18"></i>
                        </a>';
                $btn .= '<a href="/daftar-kehadiran/change/status/'.$modul->id.'" id="edit-attandance" class="text-info">
                        <i class="mdi mdi-pencil font-size-18"></i>
                        </a>';
                return $btn;
            })
            ->rawColumns(['status','day','action'])
            ->removeColumn('tanggal_bergabung')
            ->make(true);
        }
        $dataEmployee = Employee::find($employee_id);
        return view('module/attendance/attendance',[
            'employee_id' => $employee_id, 
            'dataEmployee' => $dataEmployee, 
            'photo'=> $dataEmployee->foto == null ? '/assets/images/users/avatar-1.png' : $dataEmployee->foto 
        ]);
    }

    public function detailStatusAttendance($id)
    {
        $modul = PersonalCalender::find($id);
        return view('module/attendance/detail',['modul'=> $modul]);
    }

    public function viewChangeStatusAttendance($id)
    {
        return view('module/attendance/edit',['id'=> $id]);
    }

    public function ChangeStatusAttendance(Request $request)
    {
        $modul = PersonalCalender::find($request->iddata);
        $logBefore = $modul->toJson();
        $modul->status = $request->status;
        if($modul->update()){
            $modulLog = new LogAttendance;
            $modulLog->id_user = Auth::user()->id;
            $modulLog->username = Auth::user()->username;
            $modulLog->date = date('Y-m-d');
            $modulLog->before = $logBefore;
            $modulLog->After = $modul->toJson();
            $modulLog->reason = $request->reason;
            if($modulLog->save()){
                $arr['status'] = 1;
                $arr['message'] = "Data berhasil diubah";
                return $arr;
            }
        }
    }

    function checkEmployeeType($id)
    {
        $modul = Employee::find($id);
        $thnAjaran = CurriculumYear::where('active',1)->first();
        return [$modul->jenis_pegawai,$thnAjaran->id];
    }
}
