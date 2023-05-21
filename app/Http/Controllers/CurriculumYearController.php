<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\CurriculumYear;
use App\Models\TimeSettingsTeacher;
use App\Models\PersonalCalender;
use DataTables;


class CurriculumYearController extends Controller
{
    public function home(Request $request)
    {
        if ($request->ajax()) {
            $modul = CurriculumYear::select([
                'id',
                'curriculum_year',
                'start_date',
                'end_date',
                'description',
                'active'
            ]);
            return Datatables::of($modul)
            ->addColumn('active',function($modul){
                $active = $modul->active == 1 ? "checked" : "";
                $btnCheck ='<div class="form-check form-switch mb-3" dir="ltr"><input type="checkbox" '.$active.' class="form-check-input" value="'.$modul->id.'" id="switchActive"></div>';
                return $btnCheck;
            })

            ->editColumn('start_date',function($modul){
                return $modul->start_date->format('d/m/Y');
            })

            ->editColumn('end_date',function($modul){
                return $modul->end_date->format('d/m/Y');
            })

            ->addColumn('action', function ($modul) {
                $checkCalendar = PersonalCalender::where('curriculum_year_id',$modul->id)->first();
                if($checkCalendar != null){
                     $btn = '<a href="/tahun-ajaran/'.$modul->id.'/delete" 
                            id="btn-delete" 
                            class="text-danger">
                            <i class="mdi mdi-trash-can font-size-18"></i>
                        </a>';
                }else{
                    $btn = '<a href="/tahun-ajaran/'.$modul->id.'/edit" 
                                id="edit-curriculum-year"
                                class="me-3 text-primary">
                                <i class="mdi mdi-pencil font-size-18"></i>
                            </a>';
                    $btn .= '<a href="/tahun-ajaran/'.$modul->id.'/delete" 
                                id="btn-delete" 
                                class="text-danger">
                                <i class="mdi mdi-trash-can font-size-18"></i>
                            </a>';
                }
                return $btn;
            })
            ->rawColumns(['action','active'])
            ->make(true);
        }
        return view('module/curriculumYear/home');
    }

    public function add()
    {   
        return view('module/curriculumYear/add');
    }

    public function store(Request $request)
    {  
        $lastData = CurriculumYear::latest('id')->first();
        if($lastData != null)
        {
            if($request->start_date <= $lastData->end_date){
                $arr['status'] = 0;
                $arr['message'] = "Tanggal sudah digunakan";
                return $arr;
            }
    
            if($request->end_date <= $request->start_date){
                $arr['status'] = 0;
                $arr['message'] = "Tanggal awal tidak bisa lebih besar dari tanggal akhir";
                return $arr;
            }
        }
        
        $modul = CurriculumYear::updateOrCreate(
        [
            'curriculum_year' => $request->curriculum_year,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ],
        [
            'description'=> $request->description
        ]);
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

    public function edit($id)
    {   
        $modul = CurriculumYear::find($id);
        return view('module/curriculumYear/edit',['modul'=> $modul]);
    }

    public function update(Request $request)
    {   
        $lastData = CurriculumYear::where('id','!=',$request->id)->latest('id')->first();
        if($lastData != null)
        {
            if($request->start_date <= $lastData->end_date){
                $arr['status'] = 0;
                $arr['message'] = "Tanggal sudah digunakan";
                return $arr;
            }

            if($request->end_date <= $request->start_date){
                $arr['status'] = 0;
                $arr['message'] = "Tanggal awal tidak bisa lebih besar dari tanggal akhir";
                return $arr;
            }
        }

        $modul = CurriculumYear::find($request->id);
        $modul->curriculum_year = $request->curriculum_year;
        $modul->start_date = $request->start_date;
        $modul->end_date = $request->end_date;
        $modul->description = $request->description;
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
        $checkDataTimeTeacher = TimeSettingsTeacher::where('curriculum_year_id',$id)->first();
        if(!empty($checkDataTimeTeacher)){
            $arr['status'] = 0;
            $arr['message'] = "Data masih digunakan pada Pengaturan Waktu Guru";
            return $arr;
        }

        $checkDataPersonalCalender = PersonalCalender::where('curriculum_year_id',$id)->first();
        if(!empty($checkDataPersonalCalender)){
            $arr['status'] = 0;
            $arr['message'] = "Data masih digunakan pada Calender";
            return $arr;
        }

        $modul = CurriculumYear::find($id);
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

    public function setActive($id)
    {   
        $modul = CurriculumYear::find($id);
        $modul->active = 1;
        if($modul->update()){
            $update = CurriculumYear::where('id','!=',$id)->update(['active'=>0]);
            $arr['status'] = 1;
            return $arr;
        }else{
            $arr['status'] = 0;
            return $arr;
        }
    }
}
