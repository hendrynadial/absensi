<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\PersonalCalender;
use Carbon\Carbon;

class UpdateDailyPersonalCalendar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:calendar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and Update Daily Personal Calender';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $employee = PersonalCalender::where('date',Carbon::today())
        ->where(function($x){
            $x->where('status_check_in',false)->orWhere('status_check_out',false);
        })
        ->where('status','!=','Libur')
        ->update([
            'status'=>"Absen"
        ]);

        info("Update Daily Personal Calender Finish ....");
    }
}
