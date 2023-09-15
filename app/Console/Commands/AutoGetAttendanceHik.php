<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\HikAttLog;
use App\Employee;
use App\Attendance;

class AutoGetAttendanceHik extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:auto_get_attendance_hik';

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
        
        $attendances = HikAttLog::whereBetween('authDate',[$from." 00:00:01", $to." 23:59:59"])
                                                    ->orderBy('authDate','asc')
                                                    ->orderBy('direction','asc')
                                                    ->get();
        
        $count = 0;
        if(count($attendances) > 0){
            foreach($attendances as $att)
            {
                $direction = str_replace(' ', '', $att->direction);
                
                if($direction == 'In' || $direction == 'IN')
                {
                    $attend = Attendance::where('employee_code',$att->emp_code)->whereDate('time_in',date('Y-m-d', strtotime($att->authDate)))->first();
                    if($attend == null)
                    {
                        $attendance = new Attendance;
                        $attendance->employee_code  = $att->employeeID;   
                        $attendance->time_in = date('Y-m-d H:i:s',strtotime($att->authDateTime));
                        $attendance->device_in = $att->deviceName;
                        $attendance->save();
                        $count++; 
                    }
                }
                else if($direction == 'Out' || $direction == 'OUT' )
                {
                    $time_in_after = date('Y-m-d H:i:s',strtotime($att->authDateTime));
                    $time_in_before = date('Y-m-d H:i:s', strtotime ( '-22 hour' , strtotime ( $time_in_after ) )) ;
                    $update = [
                        'time_out' =>  date('Y-m-d H:i:s', strtotime($att->authDateTime)),
                        'device_out' => $att->deviceName,
                        // 'last_id' =>$att->id,
                    ];
                    // return $time_in_after . ' - ' .$time_in_before;
                    $attendance_in = Attendance::where('employee_code',$att->employeeID)
                    ->whereBetween('time_in',[$time_in_before,$time_in_after])->first();

                    Attendance::where('employee_code',$att->employeeID)
                    ->whereBetween('time_in',[$time_in_before,$time_in_after])
                    ->update($update);

                    if($attendance_in ==  null)
                    {
                        $attendance = new Attendance;
                        $attendance->employee_code  = $att->employeeID;   
                        $attendance->time_out = date('Y-m-d H:i:s', strtotime($att->authDateTime));
                        $attendance->device_out = $att->deviceName;
                        $attendance->save(); 
                    }

                    $count++;
                }
            }
        }

        return $count;
    }
}
