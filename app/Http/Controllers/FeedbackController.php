<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Feedback;
use DataTables;
use File;


class FeedbackController extends Controller
{
    public function home(Request $request)
    {
        if ($request->ajax()) {
            $modul = Feedback::select([
                'id',
                'user_id',
                'device_type',
                'photo',
                'problem_description',
                'problem_solving_description',
                'status',
            ]);
            return Datatables::of($modul)
            ->editColumn('photo', function ($modul) {
                return $modul->photo != null ? "<a href='$modul->photo' target='_blank'>Photo</a>" : "Tidak Ada Photo";
            })

            ->addColumn('action', function ($modul) {
                $btn = '<a href="/feedback/'.$modul->id.'/edit" 
                            id="edit-feedback"
                            class="me-3 text-primary">
                            <i class="mdi mdi-pencil font-size-18"></i>
                        </a>';
                $btn .= '<a href="/feedback/'.$modul->id.'/delete" 
                            id="btn-feedback-delete" 
                            class="text-danger">
                            <i class="mdi mdi-trash-can font-size-18"></i>
                        </a>';
                return $btn;
            })
            ->rawColumns(['action','photo'])
            ->make(true);
        }
        return view('module/feedback/home');
    }

    public function edit($id)
    {   
        $modul = Feedback::find($id);
        return view('module/feedback/edit',['modul'=> $modul]);
    }

    public function update(Request $request)
    {   
        $modul = Feedback::find($request->id);
        $modul->problem_solving_description = $request->problem_solving_description;
        $modul->status = $request->status;
        if($modul->update()){
            $arr['status'] = 1;
            $arr['message'] = "Berhasil";
            return $arr;
        }else{
            $arr['status'] = 0;
            $arr['message'] = "Gagal";
            return $arr;
        }
    }

    public function delete($id)
    {   
        $modul = Feedback::find($id);
        if($modul->photo != null){
            $file = public_path($modul->photo);
            if (file_exists($file)) {
                File::delete($file);
            }
        }
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
}
