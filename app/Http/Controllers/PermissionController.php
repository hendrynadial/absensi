<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\PersonalCalender;
use \Carbon\Carbon;
use DataTables;


class PermissionController extends Controller
{
    public function home(Request $request)
    {
        if ($request->ajax()) {
            $formId = request('form');
		    $nama = null;
		    $tanggal = null;
		    $status = null;

            foreach($formId as $value)
            {
                if($value['name'] == "nama"){
                    $nama = $value['value'];
                }

                if($value['name'] == "tanggal"){
                    $tanggal = $value['value'];
                }

                if($value['name'] == "status"){
                    $status = $value['value'];
                }
            }

            $modul = Permission::select([
                'id',
                'employee_id',
                'start_date',
                'end_date',
                'file',
                'remark',
                'status',
                'reason',
            ])
            ->with('RelasiPegawai')
            ->whereHas('RelasiPegawai', function ($query) use ($nama) {
                if (!empty($nama)) {
                    $query->where('nama', 'like', '%'.$nama.'%');
                }
            })
            ->where(function($x)use($tanggal){
                if($tanggal != null){
                    $x->where('start_date',$tanggal)->orWhere('end_date',$tanggal);
                }
            })
            ->where(function($x)use($status){
                if($status != null){
                    $x->where('status',$status);
                }
            });

            return Datatables::of($modul)
            
            ->editColumn('employee_id', function ($modul) {
                return $modul->RelasiPegawai->nama;
            })

            ->editColumn('start_date', function ($modul) {
                return Carbon::parse($modul->start_date)->format('d/m/Y')." - ".Carbon::parse($modul->end_date)->format('d/m/Y');
            })

            ->editColumn('file', function ($modul) {
                return $modul->file != null ? "<a href='$modul->file' target='_blank'>File</a>" : "Tidak Ada File";
            })

            ->editColumn('status', function ($modul) {
                if($modul->status == "Ditolak"){
                    $badge = "<span class='badge badge-pill badge-soft-danger font-size-14'>$modul->status</span>";
                    $badge .= "<br><span class='badge badge-pill badge-soft-warning font-size-12'>$modul->reason</span>";
                }else if($modul->status == "Menunggu"){
                    $badge = "<span class='badge badge-pill badge-soft-warning font-size-14'>$modul->status</span>";
                }else{
                    $badge = "<span class='badge badge-pill badge-soft-success font-size-14'>$modul->status</span>";
                }
                return $badge;
            })

            ->addColumn('action', function ($modul) {
                if($modul->status == "Menunggu")
                {
                    $btn = '<a href="/permission/'.$modul->id.'/approve"
                                id="approve-permission"
                                class="me-3 text-primary">
                                <i class="mdi mdi-check font-size-18"></i>
                            </a>';
                    $btn .= '<a href="/permission/'.$modul->id.'/reject" 
                                id="reject-permission"
                                class="text-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target=".staticBackdrop">
                                <i class="mdi mdi-close-thick font-size-18"></i>
                            </a>';
                    return $btn;
                }else{
                    return "<b>No Action</b>";
                }
            })
            ->addIndexColumn()
            ->rawColumns(['action','file','status'])
            ->make(true);
        }
        return view('module/permission/home');
    }
    
    public function approvePermission($id)
    {
        $modul = Permission::find($id);
        $modul->status = "Disetujui";

        $calender = PersonalCalender::where('employee_id',$modul->employee_id)
        ->where('date','>=',$modul->start_date)
        ->where('date','<=',$modul->end_date)
        ->update([
            'status' => "Izin",
            'reason' => $modul->remark
        ]);

        if($modul->update()){
            $arr['status'] = 1;
            $arr['message'] = "Data berhasil disetujui";
            return $arr;
        }else{
            $arr['status'] = 0;
            $arr['message'] = "Data gagal disetujui";
            return $arr;
        }
    }

    public function reject($id)
    {
        return view('module/permission/reject',['id' => $id]);
    }

    public function rejectPermission($id, Request $request)
    {
        $modul = Permission::find($id);
        $modul->status = "Ditolak";
        $modul->reason = $request->reason;

        if($modul->update()){
            $arr['status'] = 1;
            $arr['message'] = "Data berhasil ditolak";
            return $arr;
        }else{
            $arr['status'] = 0;
            $arr['message'] = "Data gagal ditolak";
            return $arr;
        }
    }
}
