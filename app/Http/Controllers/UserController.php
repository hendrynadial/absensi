<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\PersonalCalender;
use App\Models\TimeSettingsTeacher;
use App\Models\TimeSettingsEmployee;
use App\Models\CurriculumYear;
use DataTables;
use Auth;
use File;

class UserController extends Controller
{
    public function home(Request $request)
    {
        if ($request->ajax()) {

            $formId = request('form');
		    $name = null;
		    $username = null;
		    $profile = null;

            foreach($formId as $value)
            {
                if($value['name'] == "name"){
                    $name = $value['value'];
                }

                if($value['name'] == "username"){
                    $username = $value['value'];
                }

                if($value['name'] == "profile"){
                    $profile = $value['value'];
                }
            }

            $modul = User::select([
                'id',
                'name',
                'username',
                'email',
                'profile',
            ])
            ->where('status','Terverifikasi')
            ->where(function($x)use($name){
                if(!empty($name))
                {
                    $x->where('name', 'like', '%'.$name.'%');
                }
            })
            ->where(function($x)use($username){
                if(!empty($username))
                {
                    $x->where('username','like', '%'.$username.'%');
                }
            })
            ->where(function($x)use($profile){
                if($profile != "Pilih Profile")
                {
                    $x->where('profile',$profile);
                }
            });

            return Datatables::of($modul)
            ->addColumn('action', function ($modul) {
                $btn = '<a href="/user/'.$modul->id.'/edit" 
                            id="edit-user-modal"
                            class="me-3 text-primary">
                            <i class="mdi mdi-pencil font-size-18"></i>
                        </a>';
                if($modul->id != Auth::user()->id)
                {
                    $btn .= '<a href="/user/'.$modul->id.'/delete" 
                    id="btn-delete" 
                    class="text-danger">
                    <i class="mdi mdi-trash-can font-size-18"></i>
                    </a>';
                }
                return $btn;
            })
            ->addIndexColumn()
            ->make(true);
        }
        return view('module/user/home');
    }

    public function add()
    {   
        return view('module/user/add');
    }

    public function store(Request $request)
    {  
        // Validasi Username
        $username = strtolower($request->username);
        $CheckUsername = User::where('username',$username)->first();
        if($CheckUsername != null)
        {
            $arr['status'] = 0;
            $arr['message'] = "Username $request->username sudah digunakan";
            return $arr;
        }

        // Validasi Email
        $CheckEmail = User::where('email',$request->email)->first();
        if($CheckEmail != null)
        {
            $arr['status'] = 0;
            $arr['message'] = "Email $request->email sudah digunakan";
            return $arr;
        }

        $foto = null;
        if ($request->file('foto')) {
            $imagePath = $request->file('foto');
            $ext = $imagePath->guessClientExtension();
            $file = "$username.$ext";
            $path = $request->file('foto')->storeAs('admin', $file, 'public');
            $foto = '/storage/'.$path;
        }

        $modul = new User;
        $modul->name = $request->name;
        $modul->email = $request->email;
        $modul->username = $username;
        $modul->password = bcrypt($request->password);
        $modul->profile = 'Admin';
        $modul->status = 'Terverifikasi';
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
        $modul = User::find($id);
        return view('module/user/edit',['modul'=> $modul]);
    }

    public function update(Request $request)
    {   
        // Validasi Username
        $username = strtolower($request->username);
        $CheckUsername = User::where('username',$username)->where('id','!=',$request->id)->first();
        if($CheckUsername != null)
        {
            $arr['status'] = 0;
            $arr['message'] = "Username $request->username sudah digunakan";
            return $arr;
        }

        // Validasi Email
        $CheckEmail = User::where('email',$request->email)->where('id','!=',$request->id)->first();
        if($CheckEmail != null && $request->email != null)
        {
            $arr['status'] = 0;
            $arr['message'] = "Email $request->email sudah digunakan";
            return $arr;
        }

        $modul = User::find($request->id);
        $modul->name = $request->name;
        $modul->email = $request->email;
        $modul->username = $username;
        if($request->password != null){
            $modul->password = bcrypt($request->password);
        }


        if ($request->file('foto') != null) {

            $imagePath = $request->file('foto');
            $ext = $imagePath->guessClientExtension();
            $file = public_path('storage/admin/' . "$username.$ext");
            if (file_exists($file)) {
                File::delete($file);
            }
            $fileName = "$username.$ext";
            $path = $request->file('foto')->storeAs('admin', $fileName, 'public');
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
        $modul = User::find($id);
        if($modul->delete()){
            $arr['status'] = 1;
            $arr['message'] = "Data berhasil dihapus";
            return $arr;
        }else{
            $arr['status'] = 0;
            $arr['message'] = "Data gagal dihapus";
            return $arr;
        }
    }

    public function homeEmployeeVerification(Request $request)
    {
        if ($request->ajax()) {
            $modul = User::select([
                'id',
                'nik',
                'name',
                'username',
            ])
            ->where('status','Menunggu Verifikasi');

            return Datatables::of($modul)
            ->addColumn('action', function ($modul) {
                $btn = '<a href="/user/verifikasi-pegawai/'.$modul->id.'" 
                            id="verifikasi-user"
                            class="me-3 text-primary">
                            <i class="mdi mdi-account-check font-size-20"></i>
                        </a>';
                return $btn;
            })
            ->addIndexColumn()
            ->make(true);
        }
        return view('module/user/homeEmployeeVerification');
    }


    public function employeeVerification($id)
    {   
        $modul = User::find($id);
        $employee = Employee::where('nik',$modul->nik)->first();
        if(empty($employee))
        {
            $arr['status'] = 0;
            $arr['message'] = "NIK tidak terdaftar pada data pegawai";
            return $arr;
        }

        $checkCalender = PersonalCalender::where('employee_id',$employee->id)->whereYear('date',date('Y'))->first();
        if($checkCalender == null)
        {
            $arr['status'] = 0;
            $arr['message'] = "Kalender $employee->nama untuk tahun ini belum digenerate";
            return $arr;
        }

        if($employee->jenis_pegawai == "Guru"){
            //Check Kurikulum Aktif Saat ini
            $checkCuriculum = CurriculumYear::where('active',true)->first();
            if($checkCuriculum == null){
                $arr['status'] = 0;
                $arr['message'] = "Tahun ajaran belum ada yang aktif";
                return $arr;
            }
            // Check Waktu Guru berdasarkan tahun ajaran
            $checkWaktuGuru = TimeSettingsTeacher::where('teacher_id',$employee->id)->where('curriculum_year_id', $checkCuriculum->id)->first();
            if($checkWaktuGuru == null){
                $arr['status'] = 0;
                $arr['message'] = "Pengaturan waktu kehadiran guru belum diset";
                return $arr;
            }
        }else if ($employee->jenis_pegawai == "Pegawai"){
            $checkEmployee = TimeSettingsEmployee::first();
            if($checkEmployee == null){
                $arr['status'] = 0;
                $arr['message'] = "Pengaturan waktu kehadiran pegawai belum diset";
                return $arr;
            }
        }
        
        $modul->email = $employee->email;
        $modul->profile = $employee->jenis_pegawai;
        $modul->status = "Terverifikasi";
        if($modul->update()){
            $arr['status'] = 1;
            $arr['message'] = "Data berhasil diverifikasi";
            return $arr;
        }else{
            $arr['status'] = 0;
            $arr['message'] = "Data gagal diverifikasi";
            return $arr;
        }
    }
}
