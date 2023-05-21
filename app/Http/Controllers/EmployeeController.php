<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\TimeSettingsEmployee;
use App\Models\TimeSettingsTeacher;
use App\Models\CurriculumYear;
use App\Models\PersonalCalender;
use App\Models\Employee;
use App\Models\Permission;
use App\Models\User;
use App\helper\Helpers;
use DataTables;
use Artisan;
use File;
use DB;

class EmployeeController extends Controller
{
    public function home(Request $request)
    {
        if ($request->ajax()) {
            $formId = request('form');
		    $nik = null;
		    $nama = null;
		    $jp = null;
		    $unit = null;

            foreach($formId as $value)
            {
                if($value['name'] == "nik"){
                    $nik = $value['value'];
                }

                if($value['name'] == "nama"){
                    $nama = $value['value'];
                }

                if($value['name'] == "jenis_pegawai"){
                    $jp = $value['value'];
                }

                if($value['name'] == "unit"){
                    $unit = $value['value'];
                }
            }
            $modul =  Employee::select([
                'id',
                'jenis_pegawai',
                'nik',
                'nama',
                'jenis_kelamin',
                'jabatan',
            ])
            ->where(function($x)use($nama){
                if($nama != null)
                {
                    $x->where('nama', 'LIKE', '%'.$nama.'%');
                }
            })
            ->where(function($x)use($nik){
                if($nik != null)
                {
                    $x->where('nik',$nik);
                }
            })
            ->where(function($x)use($jp){
                if($jp != null)
                {
                    $x->where('jenis_pegawai',$jp);
                }
            })
            ->where(function($x)use($unit){
                if($unit != null)
                {
                    $x->where('unit',$unit);
                }
            })
            ->orderBy('id','desc');
            return Datatables::of($modul)
            
            ->addColumn('status', function ($modul) {
                $yes = '<i class="text-success mdi mdi-check-circle font-size-20"></i>';
                $no = '<i class="text-danger mdi mdi-close-circle font-size-20"></i>';

                if($modul->jenis_pegawai == "Guru"){
                    $curriculumYear = CurriculumYear::where('active',true)->first();
                    $calendar = PersonalCalender::where('employee_id',$modul->id)->where('curriculum_year_id',$curriculumYear->id)->first();
                }

                if($modul->jenis_pegawai == "Pegawai"){
                    $calendar = PersonalCalender::where('employee_id',$modul->id)->where('year',date('Y'))->first();
                }
                return $calendar != null ? $yes : $no;
            })

            ->addColumn('action', function ($modul) {
                $btn = '<a href="/pegawai/'.$modul->id.'/edit" 
                            id="edit-time-guru"
                            class="me-3 text-primary">
                            <i class="mdi mdi-pencil font-size-18"></i>
                        </a>';
                if($modul->jenis_pegawai == "Guru")
                {
                    $btn .= '<a href="/pegawai/'.$modul->id.'/generate-calender-guru" 
                        id="generate-calender-guru"
                        class="me-3 text-warning">
                        <i class="mdi mdi-calendar-outline font-size-18"></i>
                    </a>';
                }else{
                    $btn .= '<a href="/pegawai/'.$modul->id.'/generate-calender-pegawai" 
                        id="generate-calender-pegawai"
                        class="me-3 text-warning">
                        <i class="mdi mdi-calendar-outline font-size-18"></i>
                    </a>';
                }

                $btn .= '<a href="/pegawai/'.$modul->id.'/delete" 
                            id="delete-employee"
                            class="me-3 text-danger">
                            <i class="mdi mdi-trash-can font-size-18"></i>
                        </a>';
                return $btn;
            })
            ->addIndexColumn()
            ->rawColumns(['action','status'])
            ->make(true);
        }
        return view('module/employee/home');
    }

    public function add()
    {   
        $timeSettingEmployee = TimeSettingsEmployee::get();
        return view('module/employee/add',['timeSettingEmployee'=> $timeSettingEmployee]);
    }

    public function store(Request $request)
    {  
        // Check Duplikat NIP
        $CheckNIK = Employee::where('nik',$request->nik)->first();
        if($CheckNIK != null)
        {
            $arr['status'] = 0;
            $arr['message'] = "NIK Sudah Terdaftar dengan nama $CheckNIK->nama";
            return $arr;
        }

        //Jika Jenis Karyawan adalah pegawai wajib isi jam masuk
        if($request->jenis_pegawai == "Pegawai" && $request->jam_masuk == null)
        {
            $arr['status'] = 0;
            $arr['message'] = "Pegawai Wajib Input Jam Masuk";
            return $arr;
        }

        //Jika Jenis Karyawan adalah Guru wajib isi Unit
        if($request->jenis_pegawai == "Guru" && $request->unit == null)
        {
            $arr['status'] = 0;
            $arr['message'] = "Guru Wajib Input Unit";
            return $arr;
        }

        //Check Validasi No HP
        $CheckNoHp = Employee::where('no_hp',$request->no_hp)->first();
        if($CheckNoHp != null)
        {
            $arr['status'] = 0;
            $arr['message'] = "Nomor Hp sudah terdaftar";
            return $arr;
        }

        $foto = null;
        if ($request->file('foto')) {
            $imagePath = $request->file('foto');
            $ext = $imagePath->guessClientExtension();
            $file = "$request->nik.$ext";
            $path = $request->file('foto')->storeAs('employee', $file, 'public');
            $foto = '/storage/'.$path;
        }

        $modul = new Employee;
        $modul->jenis_pegawai = $request->jenis_pegawai;
        $modul->nip = $request->nip;
        $modul->nama = $request->nama;
        $modul->tempat_lahir = $request->tempat_lahir;
        $modul->agama = $request->agama;
        $modul->status_pegawai = $request->status_pegawai;
        $modul->tanggal_bergabung = $request->tanggal_bergabung;
        $modul->nik = $request->nik;
        $modul->jenis_kelamin = $request->jenis_kelamin;
        $modul->tanggal_lahir = $request->tanggal_lahir;
        $modul->jabatan = json_encode([$request->jabatan]);
        $modul->alamat = $request->alamat;
        $modul->email = $request->email;
        $modul->no_hp = $request->no_hp;
        $modul->id_time_settings_employee = $request->jam_masuk ?? null;
        $dataUnit = explode(",",$request->unit);
        $modul->unit = json_encode($dataUnit) ?? null;
        $modul->foto = $foto;

        if($modul->save()){
            $arr['status'] = 1;
            $arr['message'] = "Data berhasil disimpan";
            return $arr;
        }else{
            $arr['status'] = 0;
            $arr['message'] = "Data gagal disimpan";
            return $arr;
        }
    }

    public function edit($id)
    {   
        $modul = Employee::find($id);
        $timeSettingEmployee = TimeSettingsEmployee::get();
        return view('module/employee/edit',['modul'=> $modul,'timeSettingEmployee'=>$timeSettingEmployee]);
    }

    public function update(Request $request)
    {   
        // Check Duplikat NIP
        $CheckNIK = Employee::where('nik',$request->nik)->where('id','!=',$request->id)->first();
        if($CheckNIK != null)
        {
            $arr['status'] = 0;
            $arr['message'] = "NIP Sudah Terdaftar dengan nama $CheckNIK->nama";
            return $arr;
        }

        //Jika Jenis Karyawan adalah Guru wajib isi Unit
        if($request->jenis_pegawai == "Guru" && $request->unit == null)
        {
            $arr['status'] = 0;
            $arr['message'] = "Guru Wajib Input Unit";
            return $arr;
        }

        //Jika Jenis Pegawai adalah pegawai wajib isi jam masuk
        if($request->jenis_pegawai == "Pegawai" && $request->jam_masuk == null)
        {
            $arr['status'] = 0;
            $arr['message'] = "Pegawai Wajib Input Jam Masuk";
            return $arr;
        }

        $modul = Employee::find($request->id);
        $nikBeforeUpdate = $modul->nik;

        $modul->jenis_pegawai = $request->jenis_pegawai;
        $modul->nip = $request->nip;
        $modul->nama = $request->nama;
        $modul->tempat_lahir = $request->tempat_lahir;
        $modul->agama = $request->agama;
        $modul->status_pegawai = $request->status_pegawai;
        $modul->tanggal_bergabung = $request->tanggal_bergabung;
        $modul->nik = $request->nik;
        $modul->jenis_kelamin = $request->jenis_kelamin;
        $modul->tanggal_lahir = $request->tanggal_lahir;
        $modul->jabatan = json_encode([$request->jabatan]);
        $modul->alamat = $request->alamat;
        $modul->email = $request->email;
        $modul->no_hp = $request->no_hp;
        $dataUnit = explode(",",$request->unit);
        $modul->unit = json_encode($dataUnit) ?? null;
        $modul->id_time_settings_employee = $request->jam_masuk ?? null;

        // Update NIK Table User
        $user = User::where('nik',$nikBeforeUpdate)->first();
        if ($user != null){
            $user->name = $modul->nama;
            $user->nik = $modul->nik;
            $user->update();
        }

        if ($request->file('foto') != null) {
            $imagePath = $request->file('foto');
            $ext = $imagePath->guessClientExtension();
            $file = public_path('storage/employee/' . "$request->nip.$ext");
            if (file_exists($file)) {
                File::delete($file);
            }
            $fileName = "$request->nip.$ext";
            $path = $request->file('foto')->storeAs('employee', $fileName, 'public');
            $foto = '/storage/'.$path;
            $modul->foto = $foto;
        }

        if($modul->update()){
            $arr['status'] = 1;
            $arr['message'] = "Data berhasil diperbarui";
            return $arr;
        }else{
            $arr['status'] = 0;
            $arr['message'] = "Data gagal diperbarui";
            return $arr;
        }
    }

    public function delete($id)
    {

        try {
            DB::beginTransaction();
            $employee = Employee::find($id);
            $employee->delete();

            // Personal Calendar
            $personalCalender = PersonalCalender::where('employee_id',$id);
            if($personalCalender->exists()){
                $personalCalender->delete();
            }

            // Permission
            $permission = Permission::where('employee_id',$id);
            if($permission->exists()){
                $permission->delete();
            }

            // Time Setting teacher
            $timeSettingTeacher = TimeSettingsTeacher::where('teacher_id',$id);
            if($timeSettingTeacher->exists()){
                $timeSettingTeacher->delete();
            }

            // User
            $user = User::where('nik',$employee->nik);
            if($user->exists()){
                $user->delete();
            }

            DB::commit();
            $arr['status'] = 1;
            $arr['message'] = "Data berhasil di hapus";
            return $arr;
        } catch (Exception $e) {
            DB::rollBack();
            $arr['status'] = 0;
            $arr['message'] = "Data gagal di hapus";
            return $arr;
        }
    }

    public function viewGeneratePersonalCalenderTeacher($id)
    {   
        $curriculumYear = CurriculumYear::where('active',1)->get();
        return view('module/employee/generateCalender',['curriculumYear' => $curriculumYear,'id' => $id]);
    }

    public function generatePersonalCalender(Request $request)
    {   
        //Pegawai
        if($request->jenisPegawai == "Pegawai")
        {
            $checkEmployee = PersonalCalender::where('employee_id',$request->id)->where('year',date('Y'))->first();
            if(!empty($checkEmployee))
            {
                $arr['status'] = 0;
                $arr['message'] = "Kalender tahun ".date('Y')." sudah digenerate";
                return $arr;
            }
            $modul = Helpers::generatePersonalCalender($request->id,null,date('Y'));
            return $modul;
        }

        //Validasi Kalender Jika Jenis Pegawai Guru
        $checkPersonalCalender = PersonalCalender::where('employee_id',$request->employee_id)->where('curriculum_year_id',$request->tahun_ajaran)->first();
        if(!empty($checkPersonalCalender))
        {
            $arr['status'] = 0;
            $arr['message'] = "Kalender dengan tahun ajaran ini sudah ada";
            return $arr;
        }

        $modul = Helpers::generatePersonalCalender($request->employee_id,$request->tahun_ajaran,null);
        return $modul;
    }

    public function getAllEmployee()
    {
        $modul = Employee::select(['id','jenis_pegawai'])->Get();
        return $modul;
    }

    public function generateAllCalender(Request $request)
    {
        $curriculumYear = CurriculumYear::where('active',true)->first();
        $checkEmployee = PersonalCalender::where('employee_id',$request->employeeId)
        ->where(function($x)use($request,$curriculumYear){
            if($request->jenisPegawai == "Pegawai"){
                $x->where('year',date('Y'));
            }else{
                $x->where('curriculum_year_id',$curriculumYear->id);
            }
        })
        ->first();
        if(empty($checkEmployee)){
            $modul = $request->jenisPegawai == "Pegawai" ? Helpers::generatePersonalCalender($request->employeeId,null,date('Y')) : Helpers::generatePersonalCalender($request->employeeId,$curriculumYear->id,null);
            return $modul;
        }else{
            return response()->json([
                'StatusCode' => 400,
                'Message' => "Kalender gagal digenerate", 
                'Result' => null
            ],400);
        }
    }
}
