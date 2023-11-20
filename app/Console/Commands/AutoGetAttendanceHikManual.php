<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\HikVisionAttendance;
use App\Employee;
use App\Attendance;


class AutoGetAttendanceHikManual extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:auto_get_attendance_hik_manual';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $request = $this->getAttendances();

        return $this->info($request);
    }

    public function getAttendances(){
        
        $from = date('Y-m-d',strtotime('-1 day'));
        $to = date('Y-m-d');
        
        $attendances = HikVisionAttendance::whereBetween('created_at',[$from." 00:00:01", $to." 23:59:59"])
                                ->orderBy('created_at','asc')
                                ->orderBy('direction','asc')
                                ->get();
        
        $count = 0;
        if(count($attendances) > 0){
            foreach($attendances as $att)
            {
                $direction = str_replace(' ', '', $att->direction);

                if($direction == 'In' || $direction == 'IN')
                {
                    $attend = Attendance::where('employee_code',$att->employee_code)->whereDate('time_in',date('Y-m-d', strtotime($att->attendance_date)))->first();
                    if($attend == null)
                    {
                        $attendance = new Attendance;
                        $attendance->employee_code  = $att->employee_code;   
                        $attendance->time_in = date('Y-m-d H:i:s',strtotime($att->attendance_date));
                        $attendance->device_in = $att->device;
                        $attendance->save();
                        $count++; 
                    }
                }
                else if($direction == 'Out' || $direction == 'OUT' )
                {
                    $time_in_after = date('Y-m-d H:i:s',strtotime($att->attendance_date));
                    $time_in_before = date('Y-m-d H:i:s', strtotime ( '-22 hour' , strtotime ( $time_in_after ) )) ;
                    $update = [
                        'time_out' =>  date('Y-m-d H:i:s', strtotime($att->attendance_date)),
                        'device_out' => $att->device,
                        // 'last_id' =>$att->id,
                    ];
                    // return $time_in_after . ' - ' .$time_in_before;
                    $attendance_in = Attendance::where('employee_code',$att->employee_code)
                    ->whereBetween('time_in',[$time_in_before,$time_in_after])->first();

                    Attendance::where('employee_code',$att->employee_code)
                    ->whereBetween('time_in',[$time_in_before,$time_in_after])
                    ->update($update);

                    if($attendance_in ==  null)
                    {
                        $attendance = new Attendance;
                        $attendance->employee_code  = $att->employee_code;   
                        $attendance->time_out = date('Y-m-d H:i:s', strtotime($att->attendance_date));
                        $attendance->device_out = $att->device;
                        $attendance->save(); 
                    }

                    $count++;
                }
            }
        }

        return $count;
    }
}
