<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Employee;
use App\EmployeeLeaveTypeBalance;

use Illuminate\Support\Carbon;

class ResetLeaveEveryJanuary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:reset_leave_january';

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
        return $this->reset_leave_january();
    }

    public function reset_leave_january(){

        // $classifications = [1,2,3];
        $year = date('Y');
        $classifications = [2];
        // $companies = [14,11,7,2,1,4,16,5];

        $oneYearAgo = now()->subYear()->toDateString();

        $employees = Employee::whereYear('original_date_hired','<=', $oneYearAgo)
                                ->where('status','Active')
                                ->whereIn('classification',$classifications)
                                // ->whereIn('company_id',$companies)
                                // ->where('user_id','1463')
                                ->get();

        $count_employee = 0;
        foreach($employees as $employee){

            if($employee->original_date_hired){                
                
                $vl_id = 1;
                $sl_id = 2;

                $additional_leave = $this->earnedAdditionalLeave($employee->original_date_hired);
        
                $leave_type_balance_vl = EmployeeLeaveTypeBalance::where('user_id',$employee->user_id)
                                                                        ->where('year',$year)
                                                                        ->where('leave_type','VL')
                                                                        ->first();
                if(empty($leave_type_balance_vl)){
                    $leave_type_balance_vl = new EmployeeLeaveTypeBalance;
                }

                $leave_type_balance_vl->user_id = $employee->user_id;
                $leave_type_balance_vl->year = $year;
                $leave_type_balance_vl->leave_type = 'VL';
                $leave_type_balance_vl->balance = 10 + $additional_leave;
                $leave_type_balance_vl->status = 'Active';
                $leave_type_balance_vl->save();
                

                $leave_type_balance_sl = EmployeeLeaveTypeBalance::where('user_id',$employee->user_id)
                                                                        ->where('year',$year)
                                                                        ->where('leave_type','SL')
                                                                        ->first();

                if(empty($leave_type_balance_sl)){
                    $leave_type_balance_sl = new EmployeeLeaveTypeBalance;
                }

                $leave_type_balance_sl->user_id = $employee->user_id;
                $leave_type_balance_sl->year = $year;
                $leave_type_balance_sl->leave_type = 'SL';
                $leave_type_balance_sl->balance = 10 + $additional_leave;
                $leave_type_balance_sl->status = 'Active';
                $leave_type_balance_sl->save();
                

                $count_employee++;
            }
        }

        return $this->info('New Year Leaves for Regular Employee has been credited ' . $count_employee);

    }


    public function earnedAdditionalLeave($date_hired)
    {
        if($date_hired){
            if(date('Y',strtotime($date_hired)) >= 1984){
                // Get the current date and the employee's date hired
                $yearsOfService = Carbon::parse($date_hired)->diffInYears(Carbon::now());

                // Check if the employee has completed 5 years of service
                if ($yearsOfService >= 1 && $yearsOfService < 4) { //5th and 9th year
                    return 0;
                }
                else if ($yearsOfService >= 5 && $yearsOfService < 10) { //5th and 9th year
                    return 2;
                }
                elseif ($yearsOfService >= 10 && $yearsOfService < 15) { //10th and 14th year
                    return 5;
                }
                elseif ($yearsOfService >= 15 && $yearsOfService < 20) { //10th and 14th year
                    return 7;
                }
                elseif ($yearsOfService >= 20) { //10th and 14th year
                    return 10;
                }
            } 
        } 
    }
}
