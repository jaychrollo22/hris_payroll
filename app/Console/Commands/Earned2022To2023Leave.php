<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Employee;
use App\EmployeeEarnedLeave;

use DateTime;
class Earned2022To2023Leave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:earned_2022_to_2023_leave';

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
        $request = $this->getEarneds();

        return $this->info($request);
    }

    public function getEarneds()
    {
        $year = 2023;
        $classifications = [1,2,3];
        $companies = [14,11,7,2,1,4,16,5];

        $employees = Employee::whereYear('original_date_hired','<',$year)
                                ->where('status','Active')
                                ->whereIn('classification',$classifications)
                                ->whereIn('company_id',$companies)
                                ->get();

        $count_employeee = 0;
        foreach($employees as $employee){
            if($employee->original_date_hired){
                $date_hired_from_2022 = date('2022-m-d',strtotime($employee->original_date_hired));
                $date_hired_to_2023 = date('2023-m-d',strtotime($employee->original_date_hired));

                $fromMonth = new DateTime($date_hired_from_2022); // Replace with your desired starting month and year
                $toMonth = new DateTime($date_hired_to_2023); // Replace with your desired ending month and year

                // Generate the range of month and year combinations
                $count = 0;
                $month_years = [];
                $currentMonth = clone $fromMonth;
                
                $dates = [];
                while ($currentMonth <= $toMonth) {
                    $month = $currentMonth->format('m'); // Format the month and year as desired
                    $year = $currentMonth->format('Y'); // Format the month and year as desired
                    $day = date('d',strtotime($employee->original_date_hired)); // Format the month and year as desired
                    $leave_date=$year . '-' . $month . '-' . '01';
                    $dates[] = $leave_date;
                    // Move to the next month
                    $currentMonth->modify('+1 month');
                }
                
                if(count($dates) > 0){
                    foreach($dates as $leave_date){

                        if(date('2023-08-01') > date('Y-m-d',strtotime($leave_date)) ){
                            $year = date('Y',strtotime($leave_date));
                            $month = date('m',strtotime($leave_date));
                            $day = date('d',strtotime($leave_date));
                            $earned_date = date('Y-m-d',strtotime($leave_date));

                            $check_if_exist_vl = EmployeeEarnedLeave::where('user_id',$employee->user_id)
                                                                        ->where(function($q) use($month,$year){
                                                                            $q->whereMonth('earned_date',$month)
                                                                            ->whereYear('earned_date',$year);
                                                                        })
                                                                        ->where('leave_type',1)
                                                                        ->first();                

                            if(empty($check_if_exist_vl)){
                                    $earned_leave = new EmployeeEarnedLeave;
                                    $earned_leave->leave_type = 1; // Vacation Leave
                                    $earned_leave->user_id = $employee->user_id;
                                    $earned_leave->earned_day = $day;
                                    $earned_leave->earned_month = $month;
                                    $earned_leave->earned_year = $year;
                                    $earned_leave->earned_date = $earned_date;
                                    $earned_leave->earned_leave = 0.833;
                                    $earned_leave->save();
                                    $count++;
                            }
            
                            $check_if_exist_sl = EmployeeEarnedLeave::where('user_id',$employee->user_id)
                                                                        ->where(function($q) use($month,$year){
                                                                            $q->whereMonth('earned_date',$month)
                                                                            ->whereYear('earned_date',$year);
                                                                        })
                                                                        ->where('leave_type',2)
                                                                        ->first();
            
                            if(empty($check_if_exist_sl)){
                                    $earned_leave = new EmployeeEarnedLeave;
                                    $earned_leave->leave_type = 2; // Sick Leave
                                    $earned_leave->user_id = $employee->user_id;
                                    $earned_leave->earned_day = $day;
                                    $earned_leave->earned_month = $month;
                                    $earned_leave->earned_year = $year;
                                    $earned_leave->earned_date = $earned_date;
                                    $earned_leave->earned_leave = 0.833;
                                    $earned_leave->save();
                                    $count++;
                            }
                        }
                        
                    }
                }
                $count_employeee++;
            }
        }

        return $this->info($count_employeee);
    }
}
