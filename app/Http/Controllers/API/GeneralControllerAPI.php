<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\helper\Helpers;
use App\Models\Location;
use App\Models\Feedback;
use \Carbon\Carbon;
use Validator;
use File;
use Auth;

class GeneralControllerAPI extends Controller
{
    public function getTimeServer()
    {
        $date = Carbon::now();
        return Helpers::ReturnResponseAPI(200,"Success",$date);
    }

    public function getValidationLocation()
    {
        $modul = Location::find(1);
        return Helpers::ReturnResponseAPI(200,"Success",$modul);
    }

    //Feedback
    public function saveFeedback(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'device_type'  => 'required|string',
            'file'          => 'required|mimes:png,jpg,jpeg,pdf,doc,docx|max:2048',
            'problem_description' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $file = null;
        $fileName = Auth::user()->id."_".date('d-m-Y-H-i-s');
        if ($request->file('file')) {
            $filePath = $request->file('file');
            $ext = $filePath->getClientOriginalExtension();
            $file = "$fileName.$ext";
            $path = $request->file('file')->storeAs('feedback', $file, 'public');
            $file = '/storage/'.$path;
        }

        $modul = new Feedback;
        $modul->user_id = Auth::user()->id;
        $modul->device_type = $request->device_type;
        $modul->photo = $file;
        $modul->problem_description = $request->problem_description;
        $modul->status = "On Check";

        if($modul->save()){
            return Helpers::ReturnResponseAPI(200,"Success",$modul);
        }else{
            return Helpers::ReturnResponseAPI(400,"Failed",null);
        }
    }

    public function deleteFeedback($id)
    {
        $modul = Feedback::find($id);
        if($modul->photo != null){
            $file = public_path($modul->photo);
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
