<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Employee;
use App\EmployeeEarnedLeave;
use DateTime;
class AutoEarnedLeave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:auto_earned_leave';

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

        $vl_earned = $this->getEmployeeEarnedVacationLeaves();
        $sl_earned = $this->getEmployeeEarnedSickLeaves();

        return $this->info($vl_earned . ' VL ' . $sl_earned . ' SL');
    }

    //Earned Vacation Leave
    public function getEmployeeEarnedVacationLeaves(){

        $month = date('m');
        $day = date('01');
        $year = date('Y');
        $today = date('Y-m-d');
        $classifications = [1,2,3,5];

        $companies = [14,11,7,2,1,4,16]; //PLC, OBN , PIVI, MAC, PMI, MBI, PIMI

        $employees = Employee::select('id','user_id','classification','original_date_hired')
                                ->whereIn('classification',$classifications)
                                ->whereIn('company_id',$companies)
                                ->where('status','Active')
                                ->get();
        $count = 0;
        if(count($employees)){
            foreach($employees as $employee){
                $check_if_exist = EmployeeEarnedLeave::where('user_id',$employee->user_id)   
                                                        ->where(function($q) use($month,$year){
                                                            $q->whereMonth('earned_date',$month)
                                                            ->whereYear('earned_date',$year);
                                                        })
                                                        ->where('leave_type',1)
                                                        ->first();
                if(empty($check_if_exist)){
                    $earned_leave = new EmployeeEarnedLeave;
                    if($employee->classification = '3' || $employee->classification = '5'){ // Project Based and Fixed Rate after 1 YR anniversary
                        if($employee->original_date_hired >= '2023-04-01'){
                            if(date('m-d',strtotime($employee->original_date_hired) == date('m-d'))){
                                $date_from = new DateTime($employee->original_date_hired);
                                $date_diff = $date_from->diff(new DateTime(date('Y-m-d')));
                                if($date_diff->format('%y') >= 1){
                                    $earned_leave->leave_type = 10;
                                    $earned_leave->earned_leave = 5;
                                    $earned_leave->user_id = $employee->user_id;
                                    $earned_leave->earned_day = $day;
                                    $earned_leave->earned_month = $month;
                                    $earned_leave->earned_year = $year;
                                    $earned_leave->earned_date = $today;
                                    $earned_leave->save();
                                    $count++;
                                }   
                            }
                        }else{
                            $earned_leave->leave_type = 1;
                            $earned_leave->earned_leave = 0.833;
                            $earned_leave->user_id = $employee->user_id;
                            $earned_leave->earned_day = $day;
                            $earned_leave->earned_month = $month;
                            $earned_leave->earned_year = $year;
                            $earned_leave->earned_date = $today;
                            $earned_leave->save();
                            $count++;
                        }
                    }else if($employee->classification = '1' || $employee->classification = '2' || $employee->classification = '6'){ // Regular and Probitionary and Project Based Old
                        $earned_leave->leave_type = 1;
                        $earned_leave->user_id = $employee->user_id;
                        $earned_leave->earned_day = $day;
                        $earned_leave->earned_month = $month;
                        $earned_leave->earned_year = $year;
                        $earned_leave->earned_date = $today;
                        $earned_leave->earned_leave = 0.833;
                        $earned_leave->save();
                        $count++;
                    }
                   
                    
                }
            }
        }
        return $count;
    }

    //Earned Sick Leave
    public function getEmployeeEarnedSickLeaves(){

        $month = date('m');
        $day = date('01');
        $year = date('Y');
        $today = date('Y-m-d');
        
        $classifications = [1,2,3];
        $companies = [14,11,7,2,1,4,16]; //PLC, OBN , PIVI, MAC, PMI, MBI, PIMI

        $employees = Employee::select('id','user_id','classification','original_date_hired')
                                ->whereIn('classification',$classifications)
                                ->whereIn('company_id',$companies)
                                ->where('status','Active')
                                ->get();

        $count = 0;
        if(count($employees)){
            foreach($employees as $employee){

                $check_if_exist = EmployeeEarnedLeave::where('user_id',$employee->user_id)
                                                        ->where(function($q) use($month,$year){
                                                            $q->whereMonth('earned_date',$month)
                                                            ->whereYear('earned_date',$year);
                                                        })
                                                        ->where('leave_type',2)
                                                        ->first();
                if(empty($check_if_exist)){
                    $earned_leave = new EmployeeEarnedLeave;
                    if($employee->classification = '3' || $employee->classification = '5'){ // Project Based and Fixed Rate
                        if($employee->original_date_hired < '2023-04-01'){
                            $earned_leave->user_id = $employee->user_id;
                            $earned_leave->earned_day = $day;
                            $earned_leave->earned_month = $month;
                            $earned_leave->earned_year = $year;
                            $earned_leave->earned_date = $today;
                            $earned_leave->earned_leave = 0.833;
                            $earned_leave->leave_type = 2;
                            $earned_leave->save();
                            $count++;
                        }
                    }else if($employee->classification = '1' || $employee->classification = '2' || $employee->classification = '6'){ // Regular and Probitionary and Project Based Old
                        $earned_leave->user_id = $employee->user_id;
                        $earned_leave->earned_day = $day;
                        $earned_leave->earned_month = $month;
                        $earned_leave->earned_year = $year;
                        $earned_leave->earned_date = $today;
                        $earned_leave->earned_leave = 0.833;
                        $earned_leave->leave_type = 2;
                        $earned_leave->save();
                        $count++;
                    }
                }

            }
        }
        return $count;
    }
}
