<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\PersonalCalender;
use App\Models\user;
use Carbon\Carbon;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $hadir = 0;
        $absen = 0;
        $izin = 0;
        $calendar = PersonalCalender::where('date',Carbon::today());
        $checkIn = $calendar->where('status_check_in',true)->count();
        $checkOut = $calendar->where('status_check_out',true)->count();
        $izin = $calendar->where('status','Izin')->count();
        
        $employee = new Employee;
        $user = User::where('profile','Admin')->count();
        return view('home',[
            'employee'=>$employee,
            'checkIn'=>$checkIn,
            'checkOut'=>$checkOut,
            'izin'=>$izin,
            'user'=>$user
        ]);
    }
    

    public function listCheckIn()
    {
        $calendar = PersonalCalender::select([
            'employee_id',
            'check_in',
        ])
        ->with('RelasiPegawai')
        ->where('date',Carbon::today())
        ->where('status_check_in',true)
        ->get();

        return view('listCheckIn',[
            'modul' => $calendar
        ]);
    }

    public function listCheckOut()
    {
        $calendar = PersonalCalender::select([
            'employee_id',
            'check_out',
        ])
        ->with('RelasiPegawai')
        ->where('date',Carbon::today())
        ->where('status_check_out',true)
        ->get();

        return view('listCheckOut',[
            'modul' => $calendar
        ]);
    }

    public function listIzin()
    {
        $calendar = PersonalCalender::select([
            'employee_id',
            'check_in',
        ])
        ->with('RelasiPegawai')
        ->where('date',Carbon::today())
        ->where('status','Izin')
        ->get();

        return view('listIzin',[
            'modul' => $calendar
        ]);
    }
}
