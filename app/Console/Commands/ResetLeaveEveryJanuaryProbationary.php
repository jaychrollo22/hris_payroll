<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Employee;
use App\EmployeeLeaveTypeBalance;

use Illuminate\Support\Carbon;

class ResetLeaveEveryJanuaryProbationary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:reset_leave_january_probi';

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

        $year = date('Y');
        $previousYear = Carbon::now()->subYear()->year;

        $classifications = [2,3];
        $oneYearAgo = now()->subYear()->format('Y-m-d');

        $employees = Employee::whereYear('original_date_hired','>=', Carbon::now()->subYear())
                                ->where('status','Active')
                                ->whereIn('classification',$classifications)
                                // ->where('user_id','6157')
                                ->get();

        $count_employee = 0;
        foreach($employees as $employee){

            if(date('Y',strtotime($employee->original_date_hired)) == $previousYear){                
                
                $vl_id = 1;
                $sl_id = 2;

                $leave_type_balance_vl = EmployeeLeaveTypeBalance::where('user_id',$employee->user_id)
                                                                        ->where('year',$year)
                                                                        ->where('leave_type','VL')
                                                                        ->first();

                $prev_leave_type_balance_vl = EmployeeLeaveTypeBalance::where('user_id',$employee->user_id)
                                                                        ->where('year',$previousYear)
                                                                        ->where('leave_type','VL')
                                                                        ->first();

                if($prev_leave_type_balance_vl){

                    if(empty($leave_type_balance_vl)){
                        $leave_type_balance_vl = new EmployeeLeaveTypeBalance;
                    }

                    $vl_additional_leave = checkEmployeeEarnedLeaveAdditional($employee->user_id,$vl_id,$previousYear);

                    

                    $vl_used_leave = checkUsedLeave($employee->user_id,$vl_id,$previousYear);
                    $vl_accrual = (round($vl_additional_leave) + $prev_leave_type_balance_vl->balance) - $vl_used_leave;

                    if($vl_accrual > 0){
                        $leave_type_balance_vl->user_id = $employee->user_id;
                        $leave_type_balance_vl->year = $year;
                        $leave_type_balance_vl->leave_type = 'VL';
                        $leave_type_balance_vl->balance = $vl_accrual;
                        $leave_type_balance_vl->status = 'Active';
                        $leave_type_balance_vl->save();
                    }
                }
                
                

                $leave_type_balance_sl = EmployeeLeaveTypeBalance::where('user_id',$employee->user_id)
                                                                        ->where('year',$year)
                                                                        ->where('leave_type','SL')
                                                                        ->first();

                $prev_leave_type_balance_sl = EmployeeLeaveTypeBalance::where('user_id',$employee->user_id)
                                                                        ->where('year',$previousYear)
                                                                        ->where('leave_type','SL')
                                                                        ->first();
                
                if($prev_leave_type_balance_sl){
                    
                    
                    if(empty($leave_type_balance_sl)){
                        $leave_type_balance_sl = new EmployeeLeaveTypeBalance;
                    }

                    $sl_additional_leave = checkEmployeeEarnedLeaveAdditional($employee->user_id,$sl_id,$previousYear);
                    $sl_used_leave = checkUsedLeave($employee->user_id,$sl_id,$previousYear);
                    $sl_accrual = (round($sl_additional_leave) + $prev_leave_type_balance_sl->balance) - $sl_used_leave;

                    if($sl_accrual > 0){
                        $leave_type_balance_sl->user_id = $employee->user_id;
                        $leave_type_balance_sl->year = $year;
                        $leave_type_balance_sl->leave_type = 'SL';
                        $leave_type_balance_sl->balance = $sl_accrual;
                        $leave_type_balance_sl->status = 'Active';
                        $leave_type_balance_sl->save();
                    }
                }
                

                $count_employee++;
            }
        }

        return $this->info('New Year Leaves for Regular Employee has been credited ' . $count_employee);

    }
}
