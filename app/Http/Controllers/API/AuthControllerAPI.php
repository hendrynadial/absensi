<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Employee;
use App\helper\Helpers;
use Auth;
use Validator;

class AuthControllerAPI extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nik'       => 'required|string|max:255|unique:users',
            'username'  => 'required|string|max:255|unique:users',
            'password'  => 'required|string|min:8'
        ]);

        if($validator->fails()){
            return Helpers::ReturnResponseAPI(404,$validator->errors(),null);      
        }

        $validateNIK = Employee::where('nik',$request->nik)->first();
        if(empty($validateNIK)){
            return Helpers::ReturnResponseAPI(403,"NIK tidak terdaftar pada data pegawai",null);
        }

        $user = new User;
        $user->name = $validateNIK->nama;
        $user->username = strtolower($request->username);
        $user->nik = $request->nik;
        $user->password = Hash::make($request->password);
        $user->status = "Menunggu Verifikasi";
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;
        $result = [
            'AccessToken' => $token, 
            'TokenType' => 'Bearer',
            'Data' => $user,
        ];
        return Helpers::ReturnResponseAPI(200,"Success",$result);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('username', 'password'))){
            return Helpers::ReturnResponseAPI(400,"Username / Password Salah",null);
        }
        
        $user = User::where('username', strtolower($request->username))->first();
        if($user->status == "Menunggu Verifikasi"){
            return Helpers::ReturnResponseAPI(403,"Akun Anda belum diverifikasi, silahkan hubungi Admin",null);
        }
            
        $token = $user->createToken('auth_token')->plainTextToken;
        $employee = Employee::where('nik',$user->nik)->first();
        $result = [
            'AccessToken' => $token, 
            'TokenType' => 'Bearer',
            'IdUser' => $user->id,
            'Data' => $employee,
        ];
        return Helpers::ReturnResponseAPI(200,"Success",$result);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return Helpers::ReturnResponseAPI(200,"Success",null);
    }

    public function changePassword($employeeId,Request $request)
    {
        $modul = User::find($employeeId);
        if($modul == null){
            return Helpers::ReturnResponseAPI(404,"User tidak ditemukan",null);
        }

        if(!Hash::check($request->oldPassword, $modul->password)){
            return Helpers::ReturnResponseAPI(404,"Password lama tidak cocok",null);
        }
        
        $modul->password = bcrypt($request->newPassword);
        if ($modul->update()) {
            return Helpers::ReturnResponseAPI(200,"Password berhasil diganti",null);
        } else {
            return Helpers::ReturnResponseAPI(404,"Password gagal diganti",null);
        }
    }
}