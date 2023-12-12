<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Employee;
use App\Attendance;
use App\iclockterminal_mysql;
use App\iclocktransactions_mysql;

class AutoGetAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:auto_get_attendance';

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
        
        $terminals = iclockterminal_mysql::orderBy('id','ASC')->pluck('id')->toArray();
        $employee_numbers = Employee::pluck('employee_number')->toArray();
        $attendances = iclocktransactions_mysql::whereIn('emp_code',$employee_numbers)
                                                    ->whereIn('terminal_id',$terminals)
                                                    ->whereBetween('punch_time',[$from." 00:00:01", $to." 23:59:59"])
                                                    ->orderBy('punch_time','asc')
                                                    ->get();
        
        $count = 0;
        if(count($attendances) > 0){
            foreach($attendances as $att)
            {
                if($att->punch_state == 0)
                {
                    $attend = Attendance::where('employee_code',$att->emp_code)->whereDate('time_in',date('Y-m-d', strtotime($att->punch_time)))->first();
                    if($attend == null)
                    {
                        $attendance = new Attendance;
                        $attendance->employee_code  = $att->emp_code;   
                        $attendance->time_in = date('Y-m-d H:i:s',strtotime($att->punch_time));
                        $attendance->device_in = $att->terminal_alias;
                        $attendance->save();
                        $count++; 
                    }
                }
                else if($att->punch_state == 1 || $att->punch_state == 5)
                {
                    $time_in_after = date('Y-m-d H:i:s',strtotime($att->punch_time));
                    $time_in_before = date('Y-m-d H:i:s', strtotime ( '-18 hour' , strtotime ( $time_in_after ) )) ;
                    $update = [
                        'time_out' =>  date('Y-m-d H:i:s', strtotime($att->punch_time)),
                        'device_out' => $att->terminal_alias,
                        'last_id' =>$att->id,
                    ];

                    $attendance_in = Attendance::where('employee_code',$att->emp_code)
                    ->whereBetween('time_in',[$time_in_before,$time_in_after])->first();

                    Attendance::where('employee_code',$att->emp_code)
                    ->whereBetween('time_in',[$time_in_before,$time_in_after])
                    ->update($update);

                    if($attendance_in ==  null)
                    {
                        $attendance = new Attendance;
                        $attendance->employee_code  = $att->emp_code;   
                        $attendance->time_out = date('Y-m-d H:i:s', strtotime($att->punch_time));
                        $attendance->device_out = $att->terminal_alias;
                        $attendance->save(); 
                    }

                    $count++;
                }
            }
        }

        return $count;
    }
}
