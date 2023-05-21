<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeSettingsEmployee;
use App\Models\Employee;
use App\Models\CurriculumYear;
use App\helper\Helpers;
use DataTables;

class TimeSettingsEmployeeController extends Controller
{
    public function home(Request $request)
    {
        if ($request->ajax()) {
            $modul = TimeSettingsEmployee::select([
                'id',
                'check_in_start',
                'check_in_end',
                'check_out_start',
                'check_out_end',
                'saturday_check_in_start',
                'saturday_check_in_end',
                'saturday_check_out_start',
                'saturday_check_out_end',
                'description',
            ]);
            return Datatables::of($modul)

                ->editColumn('check_in_start', function ($modul) {
                    return Helpers::formatTimeCarbon($modul->check_in_start) . " s/d " . Helpers::formatTimeCarbon($modul->check_in_end);
                })

                ->editColumn('check_out_start', function ($modul) {
                    return Helpers::formatTimeCarbon($modul->check_out_start) . " s/d " . Helpers::formatTimeCarbon($modul->check_out_end);
                })

                ->editColumn('saturday_check_in_start', function ($modul) {
                    return Helpers::formatTimeCarbon($modul->saturday_check_in_start) . " s/d " . Helpers::formatTimeCarbon($modul->saturday_check_in_end);
                })

                ->editColumn('saturday_check_out_start', function ($modul) {
                    return Helpers::formatTimeCarbon($modul->saturday_check_out_start) . " s/d " . Helpers::formatTimeCarbon($modul->saturday_check_out_end);
                })

                ->addColumn('action', function ($modul) {
                    $btn = '<a href="/pengaturan-waktu-pegawai/' . $modul->id . '/edit" 
                            id="edit-time-employee"
                            class="me-3 text-primary">
                            <i class="mdi mdi-pencil font-size-18"></i>
                        </a>';
                    $btn .= '<a href="/pengaturan-waktu-pegawai/' . $modul->id . '/delete" 
                            id="btn-delete" 
                            class="text-danger">
                            <i class="mdi mdi-trash-can font-size-18"></i>
                        </a>';
                    return $btn;
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('module/timeSettingsEmployee/home');
    }

    public function add()
    {
        return view('module/timeSettingsEmployee/add');
    }

    public function store(Request $request)
    {
        $modul = TimeSettingsEmployee::updateOrCreate(
            [
                'check_in_start' => $request->check_in_start,
                'check_in_end' => $request->check_in_end,
                'check_out_start' => $request->check_out_start,
                'check_out_end' => $request->check_out_end,
                'saturday_check_in_start' => $request->saturday_check_in_start,
                'saturday_check_in_end' => $request->saturday_check_in_end,
                'saturday_check_out_start' => $request->saturday_check_out_start,
                'saturday_check_out_end' => $request->saturday_check_out_end,
            ],
            [
                'description' => $request->description
            ]
        );
        if ($modul) {
            $arr['status'] = 1;
            $arr['message'] = "Data berhasil disimpan";
            return $arr;
        } else {
            $arr['status'] = 0;
            $arr['message'] = "Data gagal disimpan";
            return $arr;
        }
    }

    public function edit($id)
    {
        $modul = TimeSettingsEmployee::find($id);
        return view('module/timeSettingsEmployee/edit', ['modul' => $modul]);
    }

    public function update(Request $request)
    {
        $modul = TimeSettingsEmployee::find($request->id);
        $modul->check_in_start = $request->check_in_start;
        $modul->check_in_end = $request->check_in_end;
        $modul->check_out_start = $request->check_out_start;
        $modul->check_out_end = $request->check_out_end;
        $modul->description = $request->description;
        $modul->saturday_check_in_end = $request->saturday_check_in_end;
        $modul->saturday_check_in_start = $request->saturday_check_in_start;
        $modul->saturday_check_out_start = $request->saturday_check_out_start;
        $modul->saturday_check_out_end = $request->saturday_check_out_end;

        if ($modul->update()) {
            $arr['status'] = 1;
            $arr['message'] = "Data berhasil diperbarui";
            return $arr;
        } else {
            $arr['status'] = 0;
            $arr['message'] = "Data gagal diperbarui";
            return $arr;
        }
    }

    public function delete($id)
    {
        $checkExistData = Employee::where('id_time_settings_employee', $id)->first();
        if (!empty($checkExistData)) {
            $arr['status'] = 0;
            $arr['message'] = "Data masih digunakan pada Pegawai";
            return $arr;
        }

        $modul = TimeSettingsEmployee::find($id);
        if ($modul->delete()) {
            $arr['status'] = 1;
            $arr['message'] = "Data berhasil dihapus";
            return $arr;
        } else {
            $arr['status'] = 0;
            $arr['message'] = "Data gagal dihapus";
            return $arr;
        }
    }
}
