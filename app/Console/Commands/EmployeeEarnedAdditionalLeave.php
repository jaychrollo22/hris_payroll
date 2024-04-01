<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Employee;
use App\EmployeeLeaveTypeBalance;
use App\EmployeeLeaveAdditional;

class EmployeeEarnedAdditionalLeave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:employee_earned_leave_additional';

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
        $additional_leaves = $this->probationaryEarnedAdditionalLeaves();

        return $this->info($additional_leaves);
    }

    public function probationaryEarnedAdditionalLeaves(){

        $d = date('d');
        $m = date('m');
        $year = date('Y');
        $today = date('Y-m-d');

        $employees = Employee::select('id','user_id','classification','original_date_hired')
                                ->whereNotNull('original_date_hired')
                                ->whereRaw("DATE_FORMAT(original_date_hired, '%d') = ?", [$d])
                                ->where('classification','1')
                                ->where('status','Active')
                                ->get();

        $count = 0;
        if(count($employees) > 0){
            foreach($employees as $employee){

                $vl_id = 1;
                $sl_id = 2;

                $validate_leave_type_balance_vl = EmployeeLeaveTypeBalance::where('user_id',$employee->user_id)
                                                                        ->where('year',$year)
                                                                        ->where('leave_type','VL')
                                                                        ->first();
                if(empty($validate_leave_type_balance_vl)){
                    $new_leave_type_balance_vl = new EmployeeLeaveTypeBalance;
                    $new_leave_type_balance_vl->user_id = $employee->user_id;
                    $new_leave_type_balance_vl->year = $year;
                    $new_leave_type_balance_vl->leave_type = 'VL';
                    $new_leave_type_balance_vl->balance = 0;
                    $new_leave_type_balance_vl->status = 'Active';
                    $new_leave_type_balance_vl->save();
                }

                $validate_leave_type_balance_sl = EmployeeLeaveTypeBalance::where('user_id',$employee->user_id)
                                                                        ->where('year',$year)
                                                                        ->where('leave_type','SL')
                                                                        ->first();

                if(empty($validate_leave_type_balance_sl)){
                    $new_leave_type_balance_sl = new EmployeeLeaveTypeBalance;
                    $new_leave_type_balance_sl->user_id = $employee->user_id;
                    $new_leave_type_balance_sl->year = $year;
                    $new_leave_type_balance_sl->leave_type = 'SL';
                    $new_leave_type_balance_sl->balance = 0;
                    $new_leave_type_balance_sl->status = 'Active';
                    $new_leave_type_balance_sl->save();
                }

                $validate_vl = EmployeeLeaveAdditional::where('leave_type',$vl_id)
                                                        ->where('user_id',$employee->user_id)
                                                        ->where('earned_date',$today)
                                                        ->first();

                $validate_sl = EmployeeLeaveAdditional::where('leave_type',$sl_id)
                                                        ->where('user_id',$employee->user_id)
                                                        ->where('earned_date',$today)
                                                        ->first();
                if(empty($validate_vl)){
                    $earned_leave = new EmployeeLeaveAdditional;
                    $earned_leave->leave_type = $vl_id;
                    $earned_leave->user_id = $employee->user_id;
                    $earned_leave->earned_date = $today;
                    $earned_leave->earned_year = $year;
                    $earned_leave->earned_leave = 0.833;
                    $earned_leave->save();

                    $count++;
                }

                if(empty($validate_sl)){
                    $earned_leave = new  EmployeeLeaveAdditional;
                    $earned_leave->leave_type = $sl_id;
                    $earned_leave->user_id = $employee->user_id;
                    $earned_leave->earned_date = $today;
                    $earned_leave->earned_year = $year;
                    $earned_leave->earned_leave = 0.833;
                    $earned_leave->save();
                    $count++;
                }
            }
        }

        return $count;
        

    }
}
