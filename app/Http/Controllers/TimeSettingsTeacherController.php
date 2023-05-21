<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\TimeSettingsTeacher;
use App\Models\Employee;
use App\Models\CurriculumYear;
use App\Models\PersonalCalender;
use \Carbon\Carbon;
use DataTables;
use DB;

class TimeSettingsTeacherController extends Controller
{
    public function home(Request $request)
    {
        if ($request->ajax()) {
            $formId = request('form');
		    $nip = null;
		    $nama = null;
		    $tahun_ajaran = null;

            foreach($formId as $value)
            {
                if($value['name'] == "nip"){
                    $nip = $value['value'];
                }

                if($value['name'] == "nama"){
                    $nama = $value['value'];
                }

                if($value['name'] == "tahun_ajaran"){
                    $tahun_ajaran = $value['value'];
                }
            }
            $modul = TimeSettingsTeacher::select([
                'teacher_id',
                'curriculum_year_id'
            ])
            ->with('RelasiGuru')
            ->with('RelasiTahunAjaran')
            ->whereHas('RelasiGuru', function ($query) use ($nama) {
                if (!empty($nama)) {
                    $query->where('nama', 'like', '%'.$nama.'%');
                }
            })
            ->whereHas('RelasiGuru', function ($query) use ($nip) {
                if (!empty($nip)) {
                    $query->where('nip',$nip);
                }
            })
            ->whereHas('RelasiTahunAjaran', function ($query) use ($tahun_ajaran) {
                if (!empty($tahun_ajaran)) {
                    $query->where('curriculum_year',$tahun_ajaran);
                }
            })
            ->groupBy('teacher_id')
            ->groupBy('curriculum_year_id');
                        
            return Datatables::of($modul)
            ->addColumn('nip',function($modul){
                return $modul->RelasiGuru->nip;
            })

            ->editColumn('teacher_id',function($modul){
                return $modul->RelasiGuru->nama;
            })

            ->editColumn('curriculum_year_id',function($modul){
                return $modul->RelasiTahunAjaran->curriculum_year;
            })

            ->addColumn('action', function ($modul) {
                $btn = '<a href="/pengaturan-waktu-guru/'.$modul->teacher_id.'/'.$modul->curriculum_year_id.'/edit" 
                            id="edit-time-teacher"
                            class="me-3 text-primary">
                            <i class="mdi mdi-pencil font-size-18"></i>
                        </a>';
                return $btn;
            })
            ->addIndexColumn()
            ->make(true);
        }
        $curriculumYear = CurriculumYear::get();
        return view('module/timeSettingsTeacher/home',['curriculumYear'=> $curriculumYear]);
    }

    public function add()
    {   
        $daftarHari = ['senin','selasa','rabu','kamis','jumat','sabtu'];
        $kurikulum = CurriculumYear::where('active',1)->get();
        $employee = TimeSettingsTeacher::select('teacher_id')->where('curriculum_year_id',$kurikulum->first()->id)->groupBy('teacher_id')->pluck('teacher_id')->toArray();
        $teacher = Employee::where('jenis_pegawai','Guru')->whereNotIn('id',$employee)->get();

        return view('module/timeSettingsTeacher/add',[
            'daftarHari' => $daftarHari,
            'teacher' => $teacher,
            'kurikulum' => $kurikulum
        ]);
    }

    public function store(Request $request)
    {  
        $data=[];
        foreach($request->data as $key => $value)
        {
            $data[$key]['teacher_id'] = $request->teacherId;
            $data[$key]['curriculum_year_id'] = $request->curriculumYearId;
            $data[$key]['day'] = $value['hari'];
            $data[$key]['check_in_start'] = $value['check_in_start'];
            $data[$key]['check_in_end'] = $value['check_in_end'];
            $data[$key]['check_out_start'] = $value['check_out_start'];
            $data[$key]['check_out_end'] = $value['check_out_end'];
            $data[$key]['description'] = $request->description ?? "-";
            $data[$key]['active'] = $value['activeCheck'];
            if($value['activeCheck'] == 0)
            {
                $calenderPersonal = PersonalCalender::where('employee_id',$request->teacherId)
                ->where('curriculum_year_id',$request->curriculumYearId)
                ->where('id_calender_holiday',null)
                ->where('day',$value['hari'])
                ->update([
                    'status'=> 'Libur',
                    'reason'=> 'Jam Kosong'
                ]);
            }
        }
        $modul = DB::table('time_settings_teacher')->insert($data);
        if($modul){
            $arr['status'] = 1;
            $arr['message'] = "Data berhasil disimpan";
            return $arr;
        }else{
            $arr['status'] = 0;
            $arr['message'] = "Data gagal disimpan";
            return $arr;
        }
    }

    public function edit($teacher_id,$curriculum_year_id)
    {   
        $daftarHari = ['senin','selasa','rabu','kamis','jumat','sabtu'];
        $teacher = Employee::where('jenis_pegawai','Guru')->get();
        $modul = TimeSettingsTeacher::where('teacher_id',$teacher_id)->where('curriculum_year_id',$curriculum_year_id)->get();
        return view('module/timeSettingsTeacher/edit',[
            'modul'=> $modul,
            'teacher' => $teacher,
            'daftarHari' => $daftarHari,
        ]);
    }

    public function update(Request $request)
    {   
        $calender = PersonalCalender::where('employee_id',$request->teacherIdData)
        ->where('curriculum_year_id',$request->curriculumYearId)
        ->where('date','>=',Carbon::now())
        ->where('reason','Jam Kosong')
        ->update([
            'status'=> null,
            'reason'=> null
        ]);

        foreach($request->data as $key => $value)
        {
            $modul = TimeSettingsTeacher::find($value['id_data']);
            $modul->teacher_id = $request->teacherIdData;
            $modul->curriculum_year_id = $request->curriculumYearId;
            $modul->day = $value['hari'];
            $modul->check_in_start = $value['check_in_start'];
            $modul->check_in_end = $value['check_in_end'];
            $modul->check_out_start = $value['check_out_start'];
            $modul->check_out_end = $value['check_out_end'];
            $modul->description = $request->description ?? "-";
            $modul->active = $value['activeCheck'];
            if($value['activeCheck'] == 0)
            {
                $calenderPersonal = PersonalCalender::where('employee_id',$request->teacherIdData)
                ->where('curriculum_year_id',$request->curriculumYearId)
                ->where('id_calender_holiday',null)
                ->where('day',$value['hari'])
                ->where('date','>=',Carbon::now())
                ->update([
                    'status'=> 'Libur',
                    'reason'=> 'Jam Kosong'
                ]);
            }
            $modul->update();
        }
        $arr['status'] = 1;
        $arr['message'] = "Data berhasil diperbarui";
        return $arr;
    }

    public function checkPersonalCalender(Request $request)
    {
        if($request->employeeID != null)
        {
            $modul = PersonalCalender::where('employee_id',$request->employeeID)->where('curriculum_year_id',$request->curriculumYear)->first();
            if($modul == null)
            {
                $arr['status'] = 0;
                $arr['message'] = "Kalender belum digenerate, silahkan generate";
                return $arr;
            }else{
                $arr['status'] = 1;
                return $arr;
            }
        }
    }
}
