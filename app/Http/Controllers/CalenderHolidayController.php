<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\CalenderHoliday;
use App\Models\PersonalCalender;
use \Carbon\Carbon;
use DataTables;
use DB;


class CalenderHolidayController extends Controller
{
    public function home(Request $request)
    {
        if ($request->ajax()) {
            $formId = request('form');
		    $bulan = null;
		    $tahun = null;

            foreach($formId as $value)
            {
                if($value['name'] == "bulan"){
                    $bulan = $value['value'];
                }

                if($value['name'] == "tahun"){
                    $tahun = $value['value'];
                }
            }

            $modul = CalenderHoliday::select([
                'id',
                'day',
                'date',
                'reason',
            ])
            ->where(function($x)use($bulan){
                if($bulan != null)
                {
                    $x->whereMonth('date',$bulan);
                }
            })
            ->whereYear('date',$tahun)
            ->orderBy('date','ASC');

            return Datatables::of($modul)
            ->editColumn('date',function($modul){
                return $modul->date->format('d/m/Y');
            })
            
            ->addColumn('action', function ($modul) {
                if($modul->date >= Carbon::now())
                {
                    $btn = '<a href="/kalender-libur/'.$modul->id.'/edit" 
                                id="edit-calender-holiday"
                                class="me-3 text-primary">
                                <i class="mdi mdi-pencil font-size-18"></i>
                            </a>';
                    $btn .= '<a href="/kalender-libur/'.$modul->id.'/delete" 
                                id="btn-delete" 
                                class="text-danger">
                                <i class="mdi mdi-trash-can font-size-18"></i>
                            </a>';
                    return $btn;
                }else{
                    $btn = '<a href="/kalender-libur/'.$modul->id.'/edit" 
                                id="edit-calender-holiday"
                                class="me-3 text-primary">
                                <i class="mdi mdi-pencil font-size-18"></i>
                            </a>';
                    return $btn;
                    // return "No Action";
                }

            })
            ->addIndexColumn()
            ->make(true);
        }
        return view('module/calenderHoliday/home');
    }

    public function add()
    {   
        return view('module/calenderHoliday/add');
    }

    public function store(Request $request)
    {  

        $date = Carbon::parse($request->date);
        $modul = CalenderHoliday::updateOrCreate(
        [
            'day' => $date->isoFormat('dddd'),
            'date' => $date->format('Y-m-d'),
            'month' => $date->format('m'),
            'year' => $date->format('Y'),
        ],
        [
            'reason' => $request->reason
        ]);

        $update= PersonalCalender::where('date',$date->format('Y-m-d'))
        ->update([
            'status'=> 'Libur',
            'id_calender_holiday'=> $modul->id,
            'reason'=> $request->reason
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
        $modul = CalenderHoliday::find($id);
        return view('module/calenderHoliday/edit',['modul'=> $modul]);
    }

    public function update(Request $request)
    {   
        $date = Carbon::parse($request->date);
        $modul = CalenderHoliday::find($request->id);
        
        $checkDataExist = CalenderHoliday::where('id','!=',$request->id)->where('date',$request->date)->first();
        if($checkDataExist != null)
        {
            $arr['status'] = 0;
            $arr['message'] = "Tanggal sudah terdaftar";
            return $arr;
        }

        $updateCalenderToNull = PersonalCalender::where('date',$modul->date->format('Y-m-d'))
        ->update([
            'status'=> null,
            'id_calender_holiday'=> null,
            'reason'=> null
        ]);

        $modul->day = $date->isoFormat('dddd');
        $modul->date = $date->format('Y-m-d');
        $modul->month = $date->format('m');
        $modul->year = $date->format('Y');
        $modul->reason = $request->reason;

        $updateCalender = PersonalCalender::where('date',$date->format('Y-m-d'))
        ->update([
            'status'=> 'Libur',
            'id_calender_holiday'=> $request->id,
            'reason'=> $request->reason
        ]);

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
        $modul = CalenderHoliday::find($id);
        if($modul->delete()){
            $updateCalenderToNull = PersonalCalender::where('date',$modul->date->format('Y-m-d'))
            ->update([
                'status'=> null,
                'id_calender_holiday'=> null,
                'reason'=> null
            ]);
            $arr['status'] = 1;
            $arr['message'] = "Data berhasil dihapus";
            return $arr;
        }else{
            $arr['status'] = 0;
            $arr['message'] = "Data gagal dihapus";
            return $arr;
        }
    }

    public function generateCalenderHoliday()
    {
        // https://github.com/kresnasatya/api-harilibur
        $url = 'https://api-harilibur.vercel.app/api';
        $response = Http::get($url);
        $apiResponse = $response->body();
        $responseDecode = json_decode($apiResponse);
        $collectData = collect($responseDecode);
        $dataCollect = $collectData->where('is_national_holiday',true);
        
        $checkYearDB = CalenderHoliday::where('year', date('Y'))->first();
        if(!empty($checkYearDB))
        {
            $arr['status'] = 0;
            $arr['message'] = "Tahun ".date('Y')." sudah digenerate";
            return $arr;
        }

        $data =[];
        foreach($dataCollect as $key => $value)
        {
            $date = Carbon::parse($value->holiday_date);
            $data[$key]['day'] = $date->isoFormat('dddd');
            $data[$key]['date'] = $date;
            $data[$key]['month'] = $date->format('m');
            $data[$key]['year'] = $date->format('Y');
            $data[$key]['reason'] = $value->holiday_name;
            $data[$key]['created_at'] = date('Y-m-d H:i:s');
        }
        $modul = DB::table('calender_holiday')->insert($data);
        if($modul){
            $arr['status'] = 1;
            $arr['message'] = "Calender berhasil digenerate";
            return $arr;
        }else{
            $arr['status'] = 0;
            $arr['message'] = "Calender gagal digenerate";
            return $arr;
        }
    }
}
