<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Employee;
use App\helper\Helpers;
use \Carbon\Carbon;
use \Carbon\CarbonPeriod;
use Validator;
use File;

class PermissionControllerAPI extends Controller
{
    public function __construct()
    {
        $this->date = date('d-m-Y-H-i-s');
    }

    public function getPermission($employee_id,Request $request)
    {
        $modul = Permission::where('employee_id',$employee_id)
        ->where(function($x)use($request){
            if($request->start_date != null && $request->end_date != null){
                $x->where('start_date','>=',$request->start_date)
                ->where('end_date','<=',$request->end_date);
            }
        })
        ->where(function($x)use($request){
            if($request->status != null){
                $x->where('status',$request->status);
            }
        })->get();
        return Helpers::ReturnResponseAPI(200,"Success",$modul);
    }

    public function getAllPermission($employee_id, Request $request) {
        $modul = Permission::where('employee_id', $employee_id)
        ->where(function($x)use($request){
            if($request->status != null){
                $x->where('status',$request->status);
            }
        })->get();
        return Helpers::ReturnResponseAPI(200, "Sucess", $modul);
    }

    public function getDetailPermission($id)
    {
        $modul = Permission::find($id);
        return Helpers::ReturnResponseAPI(200,"Success",$modul);
    }

    public function isOverlapping($a, $b, $c, $d) {
        return $a->lte($d) && $c->lte($b);
    }

    public function createPermission($employee_id, Request $request)
    {
        $employee = Employee::find($employee_id);
        if($employee == null){
            return Helpers::ReturnResponseAPI(404,"Data pegawai tidak ditemukan",null);
        }

        $validator = Validator::make($request->all(),[
            'start_date'  => 'required|date',
            'end_date'  => 'required|date',
            'remark'  => 'required|string',
            'file'  => 'mimes:png,jpg,jpeg,pdf,doc,docx|max:2048',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        /*
            jika ada pengajuan izin tanggal a sampai b dengan status 'Ditolak', maka boleh lagi ajukan tanggal di tanggal tersebut,
            maka kita exclude yang sudah ditolak
        */
        $getTanggal = Permission::select(['start_date','end_date'])->where('employee_id',$employee_id)->where('status','!=','Ditolak')->get();
        foreach($getTanggal as $key => $value) {
            $a = Carbon::createFromFormat('Y-m-d', $request->start_date);
            $b = Carbon::createFromFormat('Y-m-d', $request->end_date);
            $c = Carbon::createFromFormat('Y-m-d', $value->start_date);
            $d = Carbon::createFromFormat('Y-m-d', $value->end_date);

            if($this->isOverlapping($a, $b, $c, $d)) {
                return Helpers::ReturnResponseAPI(400, "Tidak bisa mengajukan izin pada tanggal tersebut", null);
            }
        }

        $file = null;
        $fileName = $request->employee_id."_".$this->date;
        if ($request->file('file')) {
            $filePath = $request->file('file');
            $ext = $filePath->getClientOriginalExtension();
            $file = "$fileName.$ext";
            $path = $request->file('file')->storeAs('permission', $file, 'public');
            $file = '/storage/'.$path;
        }

        $modul = new Permission;
        $modul->employee_id = $employee_id;
        $modul->start_date = $request->start_date;
        $modul->end_date = $request->end_date;
        $modul->file = $file;
        $modul->remark = $request->remark;
        $modul->status = "Menunggu";

        if($modul->save()){
            return Helpers::ReturnResponseAPI(200,"Success",$modul);
        }else{
            return Helpers::ReturnResponseAPI(400,"Failed",null);
        }
    }

    public function updatePermission($id, Request $request)
    {
        $validator = Validator::make($request->all(),[
            'start_date'  => 'required|date',
            'end_date'  => 'required|date',
            'remark'  => 'required|string',
            'file'  => 'mimes:png,jpg,jpeg,pdf,doc,docx|max:2048',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $modul = Permission::find($id);

        /*
            Tes fungsi isOverlapping

            Pada update, cek tanggal request ke semua row table izin kecuali $modul dan yang ditolak
            maka exclude 'Ditolak' dan $modul
        */

        $getTanggal = Permission::select(['start_date','end_date'])->where('employee_id', $modul->employee_id)->where('id','!=',$id)->where('status','!=','Ditolak')->get();
        foreach($getTanggal as $key => $value) {
            $a = Carbon::createFromFormat('Y-m-d', $request->start_date);
            $b = Carbon::createFromFormat('Y-m-d', $request->end_date);
            $c = Carbon::createFromFormat('Y-m-d', $value->start_date);
            $d = Carbon::createFromFormat('Y-m-d', $value->end_date);

            if($this->isOverlapping($a, $b, $c, $d)) {
                return Helpers::ReturnResponseAPI(400, "Tidak bisa mengajukan izin pada tanggal tersebut", null);
            }
        }

        if($modul->status != "Menunggu"){
            return Helpers::ReturnResponseAPI(400,"Izin tidak bisa di edit karena sudah di $modul->status",null);
        }
        if ($request->file('file') != null) {
            $filePath = $request->file('file');
            $ext = $filePath->getClientOriginalExtension();
            $file = public_path($modul->file);
            if (file_exists($file)) {
                File::delete($file);
            }
            $fileName = $modul->employee_id."_".$this->date.".".$ext;
            $path = $request->file('file')->storeAs('permission', $fileName, 'public');
            $file = '/storage/'.$path;
            $modul->file = $file;
        }
        $modul->start_date = $request->start_date;
        $modul->end_date = $request->end_date;
        $modul->remark = $request->remark;
        $modul->status = "Menunggu";

        if($modul->update()){
            return Helpers::ReturnResponseAPI(200,"Success",$modul);
        }else{
            return Helpers::ReturnResponseAPI(400,"Failed",null);
        }
    }

    public function deletePermission($id)
    {
        $modul = Permission::find($id);
        if($modul->status != "Menunggu"){
            return Helpers::ReturnResponseAPI(400,"Izin tidak bisa di hapus karena sudah di $modul->status",null);
        }
        if($modul->file != null){
            $file = public_path($modul->file);
            if (file_exists($file)) {
                File::delete($file);
            }
        }
        if($modul->delete()){
            return Helpers::ReturnResponseAPI(200,"Success",null);
        }else{
            return Helpers::ReturnResponseAPI(400,"Failed",null);
        }
    }
}
