<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Location;
use Auth;


class LocationController extends Controller
{
    public function home()
    {
        $modul = Location::find(1);
        return view('module/location/home',['modul' => $modul]);
    }

    public function store(Request $request)
    {
        $checkData = Location::count();
        $modul = $checkData == 0 ? new Location : Location::find(1);
        $modul->radius = $request->radius;
        $modul->latitude = $request->latitude;
        $modul->longitude = $request->longitude;
        $modul->modified_by = Auth::user()->id."|".Auth::user()->name;
        if($modul->save()){
            $arr['status'] = 1;
            $arr['message'] = "Konfigurasi berhasil di simpan";
            return $arr;
        }else{
            $arr['status'] = 0;
            $arr['message'] = "Konfigurasi gagal di simpan";
            return $arr;
        }
    }
}
