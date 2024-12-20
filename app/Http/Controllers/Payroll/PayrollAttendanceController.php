<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Company;
use App\Employee;
use App\PayrollPeriod;
use App\PayrollAttendance;
use App\ScheduleData;

use DateTime;

use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Excel;
use App\Exports\PayrollAttendanceExport;
use App\Imports\PayrollAttendanceImport;

class PayrollAttendanceController extends Controller
{
    public function index(Request $request){

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);

        $company = isset($request->company) ? $request->company : "";
        $payroll_period = isset($request->payroll_period) ? $request->payroll_period : "";

        $companies = Company::whereHas('employee_has_company')
                                ->whereIn('id',$allowed_companies)
                                ->get();

        $payroll_periods = PayrollPeriod::orderBy('created_at','DESC')->get();

        $payroll_attendances = PayrollAttendance::with('timeKeeper','overtimeApprover')
            ->whereHas('employee',function($q) use($allowed_companies){
                $q->whereIn('company_id',$allowed_companies);
            })
            ->with('employee.company');
        if($company){
            $payroll_attendances = $payroll_attendances->whereHas('employee',function($q) use($company){
                $q->where('company_id',$company);
            });
        }

        if($payroll_period){
            $payroll_attendances = $payroll_attendances->where('payroll_period_id',$payroll_period);
        }

        $payroll_attendances = $payroll_attendances->get();


        return view(
            'payroll_attendances.index',
            array(
                'header' => 'payroll_attendances',
                'payroll_attendances' => $payroll_attendances,
                'company' => $company,
                'companies' => $companies,
                'payroll_period' => $payroll_period,
                'payroll_periods' => $payroll_periods,
            )
        );

    }

    public function generate(Request $request){
        $user_id = auth()->user()->id;
        $allowed_companies = getUserAllowedCompanies($user_id);

        $payroll_period = PayrollPeriod::where('id',$request->payroll_period)->first();

        $employees = Employee::with('company','department')
                                        ->whereIn('company_id',$allowed_companies)
                                        ->where('company_id',$request->company)
                                        ->where('status','Active')
                                        // ->where('id','1') // My Id
                                        ->get();

        $count = 0;
        if($employees && $payroll_period){ 
            if($employees){
                foreach($employees as $employee){

                    $employee_attendance = $this->getEmployeeAttendance($employee->user_id,$payroll_period->start_date,$payroll_period->end_date);

                    $payroll_attendance = PayrollAttendance::where('payroll_period_id',$payroll_period->id)
                                                            ->where('user_id',$employee->user_id)
                                                            ->first();

                    if(empty($payroll_attendance)){
                        $payroll_attendance = new PayrollAttendance;
                    }

                    $payroll_attendance->payroll_period_id = $payroll_period->id;
                    $payroll_attendance->user_id = $employee->user_id;
                    $payroll_attendance->full_name = $employee->first_name . ' ' . $employee->last_name;
                    $payroll_attendance->department = $employee->department ? $employee->department->name : null;
                    $payroll_attendance->company = $employee->company ? $employee->company->company_name : null;
                    $payroll_attendance->location = $employee->location;
                    $payroll_attendance->timekeeper = $user_id;
                    $payroll_attendance->overtime_approver = $employee->level2Approver->isNotEmpty() ? $employee->level2Approver[0]['approver_id'] : null;
                    
                    $rate = $employee->rate ? Crypt::decryptString($employee->rate) : "";

                    if($rate){
                        if($employee->work_description == 'Monthly'){

                            $daily_rate = $rate ? ((($rate*12)/313)/8)*9.5 : 0;
                            $hourly_rate = $rate ? (($rate*12)/313)/8 : 0; //Hourly Rate

                            $payroll_attendance->basic_pay =  $rate ? $rate / 2 : 0; //Basic Pay Computation
                            $payroll_attendance->daily_rate = $daily_rate; //Daily Rate Computation
                            $payroll_attendance->hourly_rate = $hourly_rate; //Hourly Rate Computation
                        }else{

                            $daily_rate = $rate;
                            $hourly_rate = $rate / 8; //Hourly Rate

                            $payroll_attendance->basic_pay =  ($rate * 313) / 12; //Basic Pay Computation
                            $payroll_attendance->daily_rate = $rate; //Daily Rate Computation
                            $payroll_attendance->hourly_rate = $rate / 8; //Hourly Rate Computation
                        }
                    }


                    $payroll_attendance->no_of_days_worked = $employee_attendance['total_work_day']; // Total Worked Days
                    $payroll_attendance->days_worked_amount = $daily_rate * $employee_attendance['total_work_day']; // Amount of Total Work Days

                    $payroll_attendance->absences_days = $employee_attendance['absent']; // Total Absent
                    $payroll_attendance->absences_amount = $daily_rate * $employee_attendance['absent']; // Amount of Total Absent

                    $payroll_attendance->lates_hours = $employee_attendance['late']; // Late Hours
                    $payroll_attendance->lates_amount = $hourly_rate * $employee_attendance['late']; // Amount of Late
                    
                    $payroll_attendance->undertime_hours = $employee_attendance['undertime']; // Undertime Hours
                    $payroll_attendance->undertime_amount = $hourly_rate * $employee_attendance['undertime']; // Amount of Undertime

                    $payroll_attendance->reg_ot_hours = $employee_attendance['reg_ot_hours']; // OT Hours
                    $payroll_attendance->reg_ot_amount = $hourly_rate * $employee_attendance['reg_ot_hours']; // Amount of Total Absent

                    $payroll_attendance->rest_day_hours = $employee_attendance['rest_day_hours']; // OT Hours
                    $payroll_attendance->rest_day_amount = $hourly_rate * $employee_attendance['rest_day_hours'] * 1.3; // Amount of Total Absent
                    
                    $payroll_attendance->rdot_shot_hours = $employee_attendance['rdot_shot_hours'];
                    $payroll_attendance->rdot_shot_amount = $hourly_rate * $employee_attendance['rdot_shot_hours'] * 1.69;
                    $payroll_attendance->overtime_adjustment = getUserOvertimeAdjustmentAmount($employee->user_id,$payroll_period->id); // Overtime adjustments
                    
                    $payroll_attendance->special_holiday_hours = $employee_attendance['special_holiday_hours'];
                    $payroll_attendance->special_holiday_amount = $hourly_rate * $employee_attendance['special_holiday_hours'] * 1.3;
                    
                    $payroll_attendance->shrd_hours = $employee_attendance['shrd_hours'];
                    $payroll_attendance->shrd_amount = $hourly_rate * $employee_attendance['shrd_hours'] * 1.5;
                    
                    $payroll_attendance->sh_rd_ot_hours = $employee_attendance['sh_rd_ot_hours'];
                    $payroll_attendance->sh_rd_ot_amount = $hourly_rate * $employee_attendance['sh_rd_ot_hours'] * 1.95;
                    
                    $payroll_attendance->regular_holiday_hours = $employee_attendance['regular_holiday_hours'];
                    $payroll_attendance->regular_holiday_amount = $hourly_rate * $employee_attendance['regular_holiday_hours'] * 1;
                    
                    $payroll_attendance->rh_rd_or_lh_ot_hours = $employee_attendance['rh_rd_or_lh_ot_hours'];
                    $payroll_attendance->rh_rd_or_lh_ot_amount = $hourly_rate * $employee_attendance['rh_rd_or_lh_ot_hours'] * 2.6;
                    
                    $payroll_attendance->lhrd_ot_hours = $employee_attendance['lhrd_ot_hours'];
                    $payroll_attendance->lhrd_ot_amount = $hourly_rate * $employee_attendance['lhrd_ot_hours'] * 3.38;
                    
                    $payroll_attendance->night_diff_hours = $employee_attendance['night_diff_hours'];
                    $payroll_attendance->night_diff_amount = $hourly_rate * $employee_attendance['night_diff_hours'] * .1;

                    $total_overtime_payroll = $payroll_attendance->reg_ot_amount + 
                            $payroll_attendance->rest_day_amount + 
                            $payroll_attendance->rdot_shot_amount + 
                            $payroll_attendance->special_holiday_amount +
                            $payroll_attendance->shrd_hours_amount +
                            $payroll_attendance->sh_rd_ot_amount +
                            $payroll_attendance->regular_holiday_amount +
                            $payroll_attendance->rh_rd_or_lh_ot_amount +
                            $payroll_attendance->lhrd_ot_amount +
                            $payroll_attendance->night_diff_amount + 
                            $payroll_attendance->overtime_adjustment;


                    $payroll_attendance->total_overtime_pay = $total_overtime_payroll;
                    $payroll_attendance->save();
                    $count++;
                    
                }
            }
        }

        Alert::success('Successfully Generated (' . $count. ')')->persistent('Dismiss');
        return redirect('/payroll-attendances?payroll_period=' . $request->payroll_period . '&company=' .$request->company );
    }


    public function getEmployeeAttendance($user_id,$from_date,$to_date){
        
        $schedules = ScheduleData::all();

        $emp = Employee::select('id','user_id','employee_number','first_name','last_name','schedule_id','work_description')
                                ->with(['schedule_info','attendances' => function ($query) use ($from_date, $to_date) {
                                        $query->whereBetween('time_in', [$from_date." 00:00:01", $to_date." 23:59:59"])
                                        ->orWhereBetween('time_out', [$from_date." 00:00:01", $to_date." 23:59:59"])
                                        ->orderBy('time_in','asc')
                                        ->orderby('time_out','desc')
                                        ->orderBy('id','asc');
                                }])
                                ->where('user_id', $user_id)
                                ->where('status','Active')
                                ->first();
        
        $work =0;
        $lates =0;
        $undertimes =0;
        $overtimes =0;
        
        $reg_ot_hours = 0; //Regular Holiday OT
        $rest_day_hours = 0;  //Rest Day OT
        $rdot_shot_hours = 0; //Rest Day OT Special Holiday OT
        $special_holiday_hours = 0; //Special Holiday OT
        $shrd_hours = 0; //Special Holiday Rest Day 1st 8 Hours
        $sh_rd_ot_hours = 0; // Special Holiday Rest Day OT after 8 Hours
        $regular_holiday_hours = 0; // Regular Holiday
        $rh_rd_or_lh_ot_hours = 0; // Regular Holiday Rest Day and Legal Holiday OT 1st 8 Hours
        $lhrd_ot_hours = 0;  // Regular Holiday Rest Day after 8 hours 
        
        $night_diff_hours = 0;

        $approved_overtimes =0;

        $date_range =  $this->dateRange( $from_date, $to_date);
        
        $total_work_day = 0;
        $total_absent = 0;
        $total_late = 0;
        $total_undertime = 0;

        $vl = 0;
        $sl = 0;
        $wfh = 0;
        

        foreach($date_range as $k => $date_r){

            $employee_schedule = employeeSchedule($schedules,$date_r,$emp->schedule_id);
            $check_if_holiday = checkIfHoliday(date('Y-m-d',strtotime($date_r)),$emp->location);
            $check_if_early_cutoff = checkIfEarlyCutoff(date('Y-m-d',strtotime($date_r)));

            $check_if_has_leave_shift = employeeHasLeaveShift($emp->approved_leaves,date('Y-m-d',strtotime($date_r)),$employee_schedule);

            $overtime = '';
            $time_in_data = '';
            $time_out_data = '';

            $if_has_ob = employeeHasOBDetails($emp->approved_obs,date('Y-m-d',strtotime($date_r)));
            $if_has_wfh = employeeHasWFHDetails($emp->approved_wfhs,date('Y-m-d',strtotime($date_r)));
            $if_has_dtr = employeeHasDTRDetails($emp->approved_dtrs,date('Y-m-d',strtotime($date_r)));
            $if_dtr_correction = '';
            $time_in_out = 0;
            $time_in = ($emp->attendances)->whereBetween('time_in',[$date_r." 00:00:00", $date_r." 23:59:59"])->first();
            $time_out = null;
            if($time_in == null)
            {
                $time_out = ($emp->attendances)->whereBetween('time_out',[$date_r." 00:00:00", $date_r." 23:59:59"])->where('time_in',null)->first();
            }
            
            $dtr_correction_time_in = "";
            $dtr_correction_time_out = "";
            $dtr_correction_both = "";

            
            if($if_has_dtr){
                if($if_has_dtr->time_in){
                    $dtr_correction_time_in = $if_has_dtr->correction == 'Time-in' ? $if_has_dtr->time_in : "";
                }
                if($if_has_dtr->time_out){
                    $dtr_correction_time_out = $if_has_dtr->correction == 'Time-out' ? $if_has_dtr->time_out : "";
                }
                

                if($if_has_dtr->correction == 'Both'){
                    $dtr_correction_time_in = $if_has_dtr->time_in ? $if_has_dtr->time_in : ""; 
                    $dtr_correction_time_out = $if_has_dtr->time_out ? $if_has_dtr->time_out : "";
                }
                $dtr_correction_both = $if_has_dtr->correction == 'Both'  ? $if_has_dtr : "";

                $if_dtr_correction = 'DTR Correction';
            }

            // If has OB ----------------------------------------------------------------------------------------------------------
            if($if_has_ob){            
                $late_diff_hours = 0;
                $overtime = 0;
                $undertime_hrs = 0;

                $ob_start = new DateTime($if_has_ob->date_from); 
                $ob_diff = $ob_start->diff(new DateTime($if_has_ob->date_to));
                $work_diff_hours = round($ob_diff->s / 3600 + $ob_diff->i / 60 + $ob_diff->h + $ob_diff->days * 24, 2);
                $work = (double) $work+$work_diff_hours;

                if($if_has_ob->date_from && $if_has_ob->date_to && $employee_schedule){
                    //Lates
                    $time_in_data_full =  date('Y-m-d H:i:s',strtotime($if_has_ob->date_from));
                    $time_in_data_date =  date('Y-m-d',strtotime($if_has_ob->date_from));
                    $schedule_time_in =  $time_in_data_date . ' ' . $employee_schedule['time_in_to'];
                    $schedule_time_out =  $time_in_data_date . ' ' . $employee_schedule['time_out_to'];
                    $schedule_time_in_with_grace =  date('Y-m-d H:15:s',strtotime($schedule_time_in));
                    $schedule_time_in =  date('Y-m-d H:i:s',strtotime($schedule_time_in));
                    $schedule_time_in_final =  new DateTime($schedule_time_in);
                    

                    if($emp->schedule_info->is_with_grace_period == 1){ //With Grace Period Schedule
                        if(date('Y-m-d H:i',strtotime($schedule_time_in_with_grace)) < date('Y-m-d H:i',strtotime($time_in_data_full))){
                            //IF Attendance Exceed in Grace Period
                            $new_schedule_time_in =  $time_in_data_date . ' ' . $employee_schedule['time_in_from'];
                            $new_time_in_within_grace = date('Y-m-d H:i:s',strtotime($new_schedule_time_in));
                            $new_time_in_within_grace = new DateTime($new_time_in_within_grace);
                            $late_diff = $new_time_in_within_grace->diff(new DateTime($time_in_data_full));
                            $late_diff_hours = round($late_diff->s / 3600 + $late_diff->i / 60 + $late_diff->h + $late_diff->days * 24, 2);
                        }
                    }else{ // Flexi Time Schedule
                        if($time_in_data && $schedule_time_in){
                            $time_in_data_full =  date('Y-m-d H:i:s',strtotime($time_in_data));
                            $schedule_time_in =  $time_in_data_date . ' ' . $employee_schedule['time_in_to'];
                            $schedule_time_in_final =  new DateTime($schedule_time_in);
                            if(date('Y-m-d H:i',strtotime($time_in_data_full)) > date('Y-m-d H:i',strtotime($schedule_time_in))){
                                $late_diff = $schedule_time_in_final->diff(new DateTime($time_in_data_full));
                                $late_diff_hours = round($late_diff->s / 3600 + $late_diff->i / 60 + $late_diff->h + $late_diff->days * 24, 2);
                            }
                        }
                    }
                    
                    
                    //Undertime and Overtime
                    if($emp->schedule_info->is_flexi == 1){ //Is Schedule is flexi time
                        //Overtime
                        if($work_diff_hours > $employee_schedule['working_hours']){
                            $overtime = (double) number_format($work_diff_hours - $employee_schedule['working_hours'],2);
                        }
                        //Undertime
                        if($employee_schedule['working_hours'] > $work_diff_hours){
                            $undertime = (double) number_format($employee_schedule['working_hours'] - $work_diff_hours,2);
                            if($undertime > 0){
                                if($late_diff_hours > 0){
                                    $undertime_hrs = $undertime - $late_diff_hours;
                                }else{
                                    $undertime_hrs = $undertime;
                                }
                            }  
                        }
                    }else{
                        if($if_has_ob->date_from && $if_has_ob->date_to){
                            $time_out_data = $if_has_ob->date_to;
                            $time_in_data_date =  date('Y-m-d',strtotime($if_has_ob->date_from));
                            $schedule_time_out =  $time_in_data_date . ' ' . $employee_schedule['time_out_to'];

                            $start_datetime = new DateTime($schedule_time_out);
                            
                            //Overtime 
                            if(date('Y-m-d H:i:s',strtotime($schedule_time_out)) < date('Y-m-d H:i:s',strtotime($time_out_data))){
                                $new_diff = $start_datetime->diff(new DateTime($time_out_data));
                                $work_ot_diff_hours = round($new_diff->s / 3600 + $new_diff->i / 60 + $new_diff->h + $new_diff->days * 24, 2);
                                $overtime = (double) number_format($work_ot_diff_hours,2); 
                            }

                            //Undertime
                            if($time_out_data && $schedule_time_out){
                                if(date('Y-m-d H:i:s',strtotime($schedule_time_out)) > date('Y-m-d H:i:s',strtotime($time_out_data))){
                                    $time_out_datetime = new DateTime($time_out_data);
                                    $new_diff = $time_out_datetime->diff(new DateTime($schedule_time_out));
                                    $work_ut_diff_hours = round($new_diff->s / 3600 + $new_diff->i / 60 + $new_diff->h + $new_diff->days * 24, 2);
                                    $undertime_hrs = (double) number_format($work_ut_diff_hours,2); 
                                }
                            }
                        }
                    }

                }

                if(empty($check_if_holiday)){
                    $lates = (double) $lates+$late_diff_hours;
                }
                if(empty($check_if_holiday)){
                    $undertimes = $undertimes + $undertime_hrs; 
                }
    
                $approved_overtime_hrs = $emp->approved_ots ? employeeHasOTDetails($emp->approved_ots,date('Y-m-d',strtotime($date_r))) : "";
                if($approved_overtime_hrs){
                    $approved_overtimes = (double) $approved_overtimes + $approved_overtime_hrs;
                }


                $night_diff_hours = $night_diff_hours + round($this->night_difference(strtotime($if_has_ob->date_from),strtotime($if_has_ob->date_to)),2);

            }
            // If has WFH----------------------------------------------------------------------------------------------------------
            if($if_has_wfh){
                $late_diff_hours = 0;
                $overtime = 0;
                $undertime_hrs = 0;

                $wfh_start = new DateTime($if_has_wfh->date_from); 
                $wfh_diff = $wfh_start->diff(new DateTime($if_has_wfh->date_to)); 
                $work_diff_hours = round($wfh_diff->s / 3600 + $wfh_diff->i / 60 + $wfh_diff->h + $wfh_diff->days * 24, 2);
                $work = (double) $work+$work_diff_hours;

                if($if_has_wfh->date_from && $if_has_wfh->date_to && $employee_schedule){

                    $wfh+=1;

                    //Lates
                    $time_in_data_full =  date('Y-m-d H:i:s',strtotime($if_has_wfh->date_from));
                    $time_in_data_date =  date('Y-m-d',strtotime($if_has_wfh->date_from));
                    $schedule_time_in =  $time_in_data_date . ' ' . $employee_schedule['time_in_to'];
                    $schedule_time_out =  $time_in_data_date . ' ' . $employee_schedule['time_out_to'];
                    $schedule_time_in_with_grace =  date('Y-m-d H:15:s',strtotime($schedule_time_in));
                    $schedule_time_in =  date('Y-m-d H:i:s',strtotime($schedule_time_in));
                    $schedule_time_in_final =  new DateTime($schedule_time_in);
                    

                    if($emp->schedule_info->is_with_grace_period == 1){ //With Grace Period Schedule
                        if(date('Y-m-d H:i',strtotime($schedule_time_in_with_grace)) < date('Y-m-d H:i',strtotime($time_in_data_full))){
                            //IF Attendance Exceed in Grace Period
                            $new_schedule_time_in =  $time_in_data_date . ' ' . $employee_schedule['time_in_from'];
                            $new_time_in_within_grace = date('Y-m-d H:i:s',strtotime($new_schedule_time_in));
                            $new_time_in_within_grace = new DateTime($new_time_in_within_grace);
                            $late_diff = $new_time_in_within_grace->diff(new DateTime($time_in_data_full));
                            $late_diff_hours = round($late_diff->s / 3600 + $late_diff->i / 60 + $late_diff->h + $late_diff->days * 24, 2);
                        }
                    }else{ // Flexi Time Schedule
                        if($time_in_data && $schedule_time_in){
                            $time_in_data_full =  date('Y-m-d H:i:s',strtotime($time_in_data));
                            $schedule_time_in =  $time_in_data_date . ' ' . $employee_schedule['time_in_to'];
                            $schedule_time_in_final =  new DateTime($schedule_time_in);
                            if(date('Y-m-d H:i',strtotime($time_in_data_full)) > date('Y-m-d H:i',strtotime($schedule_time_in))){
                                $late_diff = $schedule_time_in_final->diff(new DateTime($time_in_data_full));
                                $late_diff_hours = round($late_diff->s / 3600 + $late_diff->i / 60 + $late_diff->h + $late_diff->days * 24, 2);
                            }
                        }
                    }
                

                    //Undertime and Overtime
                    if($emp->schedule_info->is_flexi == 1){ //Is Schedule is flexi time
                        //Overtime
                        if($work_diff_hours > $employee_schedule['working_hours']){
                            $overtime = (double) number_format($work_diff_hours - $employee_schedule['working_hours'],2);
                        }
                        //Undertime
                        if($employee_schedule['working_hours'] > $work_diff_hours){
                            $undertime = (double) number_format($employee_schedule['working_hours'] - $work_diff_hours,2);
                            if($undertime > 0){
                                if($late_diff_hours > 0){
                                    $undertime_hrs = $undertime - $late_diff_hours;
                                }else{
                                    $undertime_hrs = $undertime;
                                }
                            }  
                        }
                    }else{
                        if($if_has_wfh->date_from && $if_has_wfh->date_to){
                            $time_out_data = $if_has_wfh->date_to;
                            $time_in_data_date =  date('Y-m-d',strtotime($if_has_wfh->date_from));
                            $schedule_time_out =  $time_in_data_date . ' ' . $employee_schedule['time_out_to'];

                            $start_datetime = new DateTime($schedule_time_out);
                            
                            //Overtime 
                            if(date('Y-m-d H:i:s',strtotime($schedule_time_out)) < date('Y-m-d H:i:s',strtotime($time_out_data))){
                                $new_diff = $start_datetime->diff(new DateTime($time_out_data));
                                $work_ot_diff_hours = round($new_diff->s / 3600 + $new_diff->i / 60 + $new_diff->h + $new_diff->days * 24, 2);
                                $overtime = (double) number_format($work_ot_diff_hours,2); 
                            }

                            //Undertime
                            if($time_out_data && $schedule_time_out){
                                if(date('Y-m-d H:i:s',strtotime($schedule_time_out)) > date('Y-m-d H:i:s',strtotime($time_out_data))){
                                    $time_out_datetime = new DateTime($time_out_data);
                                    $new_diff = $time_out_datetime->diff(new DateTime($schedule_time_out));
                                    $work_ut_diff_hours = round($new_diff->s / 3600 + $new_diff->i / 60 + $new_diff->h + $new_diff->days * 24, 2);
                                    $undertime_hrs = (double) number_format($work_ut_diff_hours,2); 
                                }
                            }
                        }
                    }

                }

                if(empty($check_if_holiday)){
                    $lates = (double) $lates+$late_diff_hours;
                }
                if(empty($check_if_holiday)){
                    $undertimes = $undertimes + $undertime_hrs; 
                }
    
                $approved_overtime_hrs = $emp->approved_ots ? employeeHasOTDetails($emp->approved_ots,date('Y-m-d',strtotime($date_r))) : "";
                if($approved_overtime_hrs){
                    $approved_overtimes = (double) $approved_overtimes + $approved_overtime_hrs;
                }

                $night_diff_hours = $night_diff_hours + round($this->night_difference(strtotime($if_has_wfh->date_from),strtotime($if_has_wfh->date_to)),2);
            }
           
            //Time In
            if ($time_in || $if_has_dtr) {
                // Time out
                if ($dtr_correction_time_out) {
                    
                } else {
                    if ($time_in) {
                        if ($time_in->time_out) {
                            
                        } else {
                            $time_in_out = 1;
                            
                        }
                    } else {
                        if ($time_out) {
                            if ($time_out->time_out) {
                                
                            } else {
                                $time_in_out = 1;
                            
                            }
                        } else {
                            $time_in_out = 1;
                            
                        }
                    }
                }
            } else {
                if ((date('l', strtotime($date_r)) == "Saturday") || (date('l', strtotime($date_r)) == "Sunday")) {
                
                } else {
                    if ($dtr_correction_time_out) {
                        
                    } else {
                        $time_in_out = 1;
                
            
                        if ($time_in) {
                            if ($time_in->time_out) {
                                
                            } else {
                                $time_in_out = 1;

                            }
                        } else {
                            if ($time_out) {
                        
                            } else {
                                $time_in_out = 1;
                            }
                        }
                    }
                }
            }

            if($time_in || $if_has_dtr){

                $id = array_search(date('l',strtotime($date_r)),$schedules->pluck('name')->toArray());
                $time_in_from = $employee_schedule ? $employee_schedule['time_in_from'] : "08:00";

                $employee_time_in = '';
                if($dtr_correction_time_in){
                    $employee_time_in = $dtr_correction_time_in;
                }else{
                    $employee_time_in = $time_in ? $time_in->time_in : "";
                }

                if($dtr_correction_time_out){
                    $time_out_data = $dtr_correction_time_out;
                }else{
                    if($time_in == null)
                    {
                        if($time_out){
                            $time_out_data = $time_out->time_out ? $time_out->time_out : "";
                        }
                    }else{
                        $time_out_data = $time_in->time_out ? $time_in->time_out : "";
                    }
                }
                if($employee_time_in){
                    if(strtotime(date('H:i:00',strtotime($employee_time_in))) >= strtotime($time_in_from))
                    {
                        $time_in_data = $employee_time_in;
                    }
                    else
                    {
                        $time_in_data = date('Y-m-d ' . $time_in_from,strtotime($employee_time_in));
                    }
                }

                if($time_in_data){
                    $start_datetime = new DateTime($time_in_data); 
                    if($time_out_data){
                        $diff = $start_datetime->diff(new DateTime($time_out_data)); 
                    }
                }

                if($time_in_data && $time_out_data)
                {
                    $work_diff_hours = round($diff->s / 3600 + $diff->i / 60 + $diff->h + $diff->days * 24, 2);
                    $work = (double) $work+$work_diff_hours;
                    $overtime = (double) number_format($work_diff_hours,2);
                }                       
            }

            if($employee_schedule && $time_in_data && $time_out_data){
                //Lates
                $time_in_data_full =  date('Y-m-d H:i:s',strtotime($time_in_data));
                $time_in_data_date =  date('Y-m-d',strtotime($time_in_data));
                $schedule_time_in =  $time_in_data_date . ' ' . $employee_schedule['time_in_to'];
                $schedule_time_out =  $time_in_data_date . ' ' . $employee_schedule['time_out_to'];
                $schedule_time_in_with_grace =  date('Y-m-d H:15:s',strtotime($schedule_time_in));
                $schedule_time_in =  date('Y-m-d H:i:s',strtotime($schedule_time_in));
                $schedule_time_in_final =  new DateTime($schedule_time_in);
                $late_diff_hours = 0;

                if($emp->schedule_info->is_with_grace_period == 1){ //With Grace Period Schedule
                    if(date('Y-m-d H:i',strtotime($schedule_time_in_with_grace)) < date('Y-m-d H:i',strtotime($time_in_data_full))){
                        //IF Attendance Exceed in Grace Period
                        $new_schedule_time_in =  $time_in_data_date . ' ' . $employee_schedule['time_in_from'];
                        $new_time_in_within_grace = date('Y-m-d H:i:s',strtotime($new_schedule_time_in));
                        $new_time_in_within_grace = new DateTime($new_time_in_within_grace);
                        $late_diff = $new_time_in_within_grace->diff(new DateTime($time_in_data_full));
                        $late_diff_hours = round($late_diff->s / 3600 + $late_diff->i / 60 + $late_diff->h + $late_diff->days * 24, 2);
                    }
                }else{ // Flexi Time Schedule
                    if($time_in_data && $schedule_time_in){
                        $time_in_data_full =  date('Y-m-d H:i:s',strtotime($time_in_data));
                        $schedule_time_in =  $time_in_data_date . ' ' . $employee_schedule['time_in_to'];
                        $schedule_time_in_final =  new DateTime($schedule_time_in);
                        if(date('Y-m-d H:i',strtotime($time_in_data_full)) > date('Y-m-d H:i',strtotime($schedule_time_in))){
                            $late_diff = $schedule_time_in_final->diff(new DateTime($time_in_data_full));
                            $late_diff_hours = round($late_diff->s / 3600 + $late_diff->i / 60 + $late_diff->h + $late_diff->days * 24, 2);
                        }
                    }
                }
                
                $overtime = 0;
                $undertime_hrs = 0;

                if($emp->schedule_info->is_flexi == 1){ //Is Schedule is flexi time
                    
                    $has_leave_shift_hrs = 0;
                    if($check_if_has_leave_shift){
                        if($check_if_has_leave_shift == 'First Shift' || $check_if_has_leave_shift == 'Second Shift'){
                            $compressed_work_weeks = [3,4,5,6,10,17]; //Compressed Schedules
                            // if(str_contains($emp->schedule_info->schedule_name, "Compressed") && !str_contains($emp->schedule_info->schedule_name, "Saturday")){
                            if(in_array($emp->schedule_info->id,$compressed_work_weeks)){
                                $has_leave_shift_hrs = 4.75;//Leave Shift Hrs for Compressed 5 days
                            }else{
                                $has_leave_shift_hrs = 4;//Leave Shift Hrs
                            }   
                        }
                    }
                    
                    //Overtime
                    if($work_diff_hours > $employee_schedule['working_hours']){
                        $overtime = (double) number_format($work_diff_hours - $employee_schedule['working_hours'],2);
                    }

                    //Undertime
                    if($employee_schedule['working_hours'] > $work_diff_hours){

                        if($has_leave_shift_hrs > 0){
                            $total_with_has_leave_shift_hrs = $work_diff_hours + $has_leave_shift_hrs;
                            $undertime = $employee_schedule['working_hours'] - $total_with_has_leave_shift_hrs;
                            if($undertime > 0){
                                $undertime_hrs = $undertime;
                            }  
                        }else{
                            $undertime = (double) number_format($employee_schedule['working_hours'] - $work_diff_hours,2);
                            if($undertime > 0){
                                if($late_diff_hours > 0){
                                    $undertime_hrs = $undertime - $late_diff_hours;
                                }else{
                                    $undertime_hrs = $undertime;
                                }
                            }  
                        }
                    }
                
                }else{
                    //Not Flexi
                    if($time_in_data){
                        $start_datetime = new DateTime($schedule_time_out);
                        
                        //Overtime 
                        if(date('Y-m-d H:i:s',strtotime($schedule_time_out)) < date('Y-m-d H:i:s',strtotime($time_out_data))){
                            $new_diff = $start_datetime->diff(new DateTime($time_out_data));
                            $work_ot_diff_hours = round($new_diff->s / 3600 + $new_diff->i / 60 + $new_diff->h + $new_diff->days * 24, 2);
                            $overtime = (double) number_format($work_ot_diff_hours,2); 
                        }

                        //Undertime
                        if($time_out_data && $schedule_time_out){
                            if(date('Y-m-d H:i:s',strtotime($schedule_time_out)) > date('Y-m-d H:i:s',strtotime($time_out_data))){
                                $time_out_datetime = new DateTime($time_out_data);
                                $new_diff = $time_out_datetime->diff(new DateTime($schedule_time_out));
                                $work_ut_diff_hours = round($new_diff->s / 3600 + $new_diff->i / 60 + $new_diff->h + $new_diff->days * 24, 2);
                                $undertime_hrs = (double) number_format($work_ut_diff_hours,2); 
                            }
                        }
                    }
                }

                //Late
                if ($check_if_has_leave_shift) {
                    if ($check_if_has_leave_shift == 'Second Shift') {
                        if (empty($check_if_holiday)) {
                            $lates = (double)$lates + $late_diff_hours;
                        }
                    } 
                } else {
                    if (empty($check_if_holiday)) {
                        $lates = (double)$lates + $late_diff_hours;
                    }
                }

                //Undertime
                if ($check_if_has_leave_shift) {
                    if ($check_if_has_leave_shift == 'First Shift') {
                        if (empty($check_if_holiday)) {
                            if ($undertime_hrs > 0) {
                                $undertimes = $undertimes + $undertime_hrs;
                            }
                        }
                    }
                } else {
                    if (empty($check_if_holiday)) {
                        if ($undertime_hrs > 0) {
                            $undertimes = $undertimes + $undertime_hrs;
                        }
                    }

                    if(empty($check_if_holiday)){
                        $night_diff_hours = $night_diff_hours + round($this->night_difference(strtotime($time_in_data),strtotime($time_out_data)),2);
                    }
                }

                //IF Has Schedule OT
                //Approved OT
                $approved_overtime_hrs = $emp->approved_ots ? employeeHasOTDetails($emp->approved_ots, date('Y-m-d', strtotime($date_r))) : "";
                if ($approved_overtime_hrs) {
                    $approved_overtimes = (double)$approved_overtimes + $approved_overtime_hrs;

                    if ($check_if_holiday) {
                        if ($check_if_holiday == 'Special Holiday') { //Special Holiday
                            if($approved_overtime_hrs > 8){
                                $special_holiday_hours = 8;
                                $rdot_shot_hours = $approved_overtime_hrs - 8; 
                            }else{
                                $special_holiday_hours += $approved_overtime_hrs;
                            }
                        }else{ //Regular Holiday
                            if($approved_overtime_hrs > 8){
                                $regular_holiday_hours = 8;
                                $rhrd_ot = $approved_overtime_hrs - 8; 
                            }else{
                                $regular_holiday_hours += $approved_overtime_hrs;
                            }
                        }
                    }else{
                        $reg_ot_hours += $approved_overtime_hrs;
                    }
                }
            }
            else{

                //IF No Schedule OT
                if ($time_in || $if_has_dtr) {
                    
                    if (empty($check_if_holiday)) {
                        if ($overtime > 0.5) {
                            
                            $overtimes = (double)$overtimes + round($overtime, 2);
                        }
                    }
                    $approved_overtime_hrs = $emp->approved_ots ? employeeHasOTDetails($emp->approved_ots, date('Y-m-d', strtotime($date_r))) : "";

                    if ($approved_overtime_hrs) {
                        $approved_overtimes = (double)$approved_overtimes + $approved_overtime_hrs;
                    }

                    if ($check_if_holiday) { //If Holiday OT
                        
                        if ($check_if_holiday == 'Special Holiday') { 
                            //Special Holiday and Rest Day
                            if($approved_overtime_hrs > 8){
                                $shrd_hours = 8; // Special Holiday Rest Day 1st 8 hours 
                                $sh_rd_ot_hours = $approved_overtime_hrs - 8;  // Special Holiday Rest Day after 8 hours 
                            }else{
                                $shrd_hours += $approved_overtime_hrs; // Special Holiday Rest Day Within 8 hours 
                            }
                        }else{ 
                            //Regular Holiday and Rest Day
                            if($approved_overtime_hrs > 8){
                                $rh_rd_or_lh_ot_hours = 8; // Regular Holiday Rest Day 1st 8 hours 
                                $lhrd_ot_hours = $approved_overtime_hrs - 8; // Regular Holiday Rest Day after 8 hours 
                            }else{
                                $rh_rd_or_lh_ot_hours += $approved_overtime_hrs; // Regular Holiday Rest Day Within 8 hours 
                            }
                        }

                        if(empty($check_if_holiday)){
                            $night_diff_hours = $night_diff_hours + round(night_difference(strtotime($time_in_data),strtotime($time_out_data)),2);
                        }

                    }else{ 
                        //Rest Day
                        if($approved_overtime_hrs > 8){
                            $rest_day_hours = 8; // Rest Day 1st 8 hours 
                            $rdot_shot_hours = $approved_overtime_hrs - 8; //Rest Day after 8 hours 
                        }else{
                            $rest_day_hours += $approved_overtime_hrs; //Rest Day Within 8 hours
                        }
                    }
                } 
            }


            if ($time_in == null) {
                if ($employee_schedule) {
                    $is_absent = '';
                    $if_leave = '';
                    $if_attendance_holiday = '';
                    $if_attendance_holiday_status = '';
            
                    if ($check_if_holiday) {
                        if ($check_if_holiday == 'Special Holiday' && $emp->work_description == 'Non-Monthly') {
                            // Condition for Daily Rate / Non Monthly
                            $if_attendance_holiday_status = 'Without-Pay';
                        } else {
                            $if_attendance_holiday = checkHasAttendanceHoliday(date('Y-m-d', strtotime($date_r)), $emp->employee_number, $emp->location);
            
                            if ($if_attendance_holiday) {
                                $check_leave = employeeHasLeave($emp->approved_leaves, date('Y-m-d', strtotime($if_attendance_holiday)), $employee_schedule);
                                $check_leave_count_vl = employeeHasLeaveCount($emp->approved_leaves, date('Y-m-d', strtotime($if_attendance_holiday)), $employee_schedule, 'VL');
                                $check_leave_count_sl = employeeHasLeaveCount($emp->approved_leaves, date('Y-m-d', strtotime($if_attendance_holiday)), $employee_schedule, 'SL');
                                $check_wfh = employeeHasOBDetails($emp->approved_wfhs, date('Y-m-d', strtotime($if_attendance_holiday)));
                                $check_ob = employeeHasOBDetails($emp->approved_obs, date('Y-m-d', strtotime($if_attendance_holiday)));
                                $check_dtr = employeeHasDTRDetails($emp->approved_dtrs, date('Y-m-d', strtotime($if_attendance_holiday)));
            
                                if ($check_leave || $check_wfh || $check_ob || $check_dtr) {
                                    $if_attendance_holiday_status = 'With-Pay';
                                    if ($check_leave) {
                                        if ($check_leave == 'SL Without-Pay' || $check_leave == 'VL Without-Pay') {
                                            $if_attendance_holiday_status = 'Without-Pay';
                                        } else {
                                            $if_attendance_holiday_status = 'With-Pay';

                                            
                                            $vl += $check_leave_count_vl;
                                            $sl += $check_leave_count_sl;
                                            
                                        }
                                    }
                                } else {
                                    $check_attendance = checkHasAttendanceHolidayStatus($emp->attendances, $if_attendance_holiday);
                                    if (empty($check_attendance)) {
                                        $is_absent = 'Absent';
                                    } else {
                                        $if_attendance_holiday_status = 'With-Pay';
                                    }
                                }
                            } else {
                                $if_attendance_holiday = checkHasAttendanceHoliday(date('Y-m-d', strtotime($date_r . '-1 day')), $emp->employee_number, $emp->location);
            
                                if ($if_attendance_holiday) {
                                    $check_leave = employeeHasLeave($emp->approved_leaves, date('Y-m-d', strtotime($if_attendance_holiday)), $employee_schedule);
                                    $check_leave_count_vl = employeeHasLeaveCount($emp->approved_leaves, date('Y-m-d', strtotime($if_attendance_holiday)), $employee_schedule, 'VL');
                                    $check_leave_count_sl = employeeHasLeaveCount($emp->approved_leaves, date('Y-m-d', strtotime($if_attendance_holiday)), $employee_schedule, 'SL');
                                    $check_wfh = employeeHasOBDetails($emp->approved_wfhs, date('Y-m-d', strtotime($if_attendance_holiday)));
                                    $check_ob = employeeHasOBDetails($emp->approved_obs, date('Y-m-d', strtotime($if_attendance_holiday)));
                                    $check_dtr = employeeHasDTRDetails($emp->approved_dtrs, date('Y-m-d', strtotime($if_attendance_holiday)));
            
                                    if ($check_leave || $check_wfh || $check_ob || $check_dtr) {
                                        $if_attendance_holiday_status = 'With-Pay';
                                        if ($check_leave) {
                                            if ($check_leave == 'SL Without-Pay' || $check_leave == 'VL Without-Pay') {
                                                $if_attendance_holiday_status = 'Without-Pay';
                                            } else {
                                                $if_attendance_holiday_status = 'With-Pay';

                                                $vl += $check_leave_count_vl;
                                            $sl += $check_leave_count_sl;
                                            }
                                        }
                                    } else {
                                        $check_attendance = checkHasAttendanceHolidayStatus($emp->attendances, $if_attendance_holiday);
                                        if (empty($check_attendance)) {
                                            $is_absent = 'Absent';
                                        } else {
                                            $if_attendance_holiday_status = 'With-Pay';
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $if_leave = employeeHasLeave($emp->approved_leaves, date('Y-m-d', strtotime($date_r)), $employee_schedule);
                        if (empty($if_leave)) {
                            if (empty($if_has_dtr)) {
                                if ($dtr_correction_time_out == null) {
                                    if ($time_out == null) {
                                        $is_absent = 'Absent';
                                    }
                                }
                            }
                        }
                        if ($time_out_data == null && empty($if_leave)) {
                            $is_absent = 'Absent';
                        }
                    }
            
                    if ($check_if_early_cutoff) {

                        if($emp->work_description == 'Monthly'){ // If Monthly Employee Only
                            $total_work_day++;
                        }else{
                            if($is_absent == 'Absent'){
                                $total_absent++;
                            }else{
                                $total_work_day++;
                            }
                        }
                        
                    }else{
                        if($is_absent == 'Absent'){
                            $total_absent++;
                        }else{
                            $total_work_day++;
                        }
                    }

                    
                }
            } else {
                $is_absent = '';
                $if_leave = '';
            
                if ($employee_schedule) {
                    if ($time_out_data == null) {
                        $is_absent = 'Absent';
                    }
                    $if_leave = employeeHasLeave($emp->approved_leaves, date('Y-m-d', strtotime($date_r)), $employee_schedule);

                    if($if_leave == 'VL With-Pay'){
                        $vl += 1;
                    }
                    if($if_leave == 'SL With-Pay'){
                        $sl += 1;
                    }

                    if ($check_if_early_cutoff) {
                        if($emp->work_description == 'Monthly'){ // If Monthly Employee Only
                            $total_work_day++;
                        }else{
                            if($is_absent == 'Absent'){
                                $total_absent++;
                            }else{
                                $total_work_day++;
                            }
                        }
                    }else{
                        if($is_absent == 'Absent' && empty($if_leave)){
                            $total_absent++;
                        }else{
                            $total_work_day++;
                        }
                    }   
                }
            } 
              
        }  


        return $response = [
            'work'=>$work,
            'late'=>$lates,
            'undertime'=>$undertimes,
            'overtime'=>$approved_overtimes,
            'reg_ot_hours' => $reg_ot_hours,
            'rest_day_hours' => $rest_day_hours,
            'rdot_shot_hours' => $rdot_shot_hours,
            'special_holiday_hours' => $special_holiday_hours,
            'shrd_hours' => $shrd_hours,
            'sh_rd_ot_hours' => $sh_rd_ot_hours,
            'regular_holiday_hours' => $regular_holiday_hours,
            'rh_rd_or_lh_ot_hours' => $rh_rd_or_lh_ot_hours,
            'lhrd_ot_hours' => $lhrd_ot_hours,
            'night_diff_hours' => $night_diff_hours,
            'absent'=>$total_absent,
            'total_work_day'=>$total_work_day,
            'vl' => $vl,
            'sl' => $sl,
            'wfh' => $wfh
        ];
            

    }

    public function dateRange( $first, $last, $step = '+1 day', $format = 'Y-m-d' ) {
        $dates = [];
        $current = strtotime( $first );
        $last = strtotime( $last );
    
        while( $current <= $last ) {
    
            $dates[] = date( $format, $current );
            $current = strtotime( $step, $current );
        }
    
        return $dates;
    }

    public function night_difference($start_work,$end_work)
    {
        $start_night = mktime('22','00','00',date('m',$start_work),date('d',$start_work),date('Y',$start_work));
        $end_night   = mktime('06','00','00',date('m',$start_work),date('d',$start_work) + 1,date('Y',$start_work));
    
        if($start_work >= $start_night && $start_work <= $end_night)
        {
            if($end_work >= $end_night)
            {
                return ($end_night - $start_work) / 3600;
            }
            else
            {
                return ($end_work - $start_work) / 3600;
            }
        }
        elseif($end_work >= $start_night && $end_work <= $end_night)
        {
            if($start_work <= $start_night)
            {
                return ($end_work - $start_night) / 3600;
            }
            else
            {
                return ($end_work - $start_work) / 3600;
            }
        }
        else
        {
            if($start_work < $start_night && $end_work > $end_night)
            {
                return ($end_night - $start_night) / 3600;
            }
            return 0;
        }
    }


    /**
     * Export to excel
     *
     */
    public function export(Request $request){
        $company = isset($request->company) ? $request->company : "";
        $payroll_period = isset($request->payroll_period) ? $request->payroll_period : "";
        $company_detail = Company::where('id',$company)->first();

        $company_code = $company_detail ? $company_detail->company_code : "";

        return Excel::download(new PayrollAttendanceExport($company,$payroll_period), $company_code. ' Payroll Attendance Export.xlsx');
    }


    public function import(Request $request){
        $user_id = auth()->user()->id;

        ini_set('memory_limit', '-1');
        $path = $request->file('file')->getRealPath();
        $data = Excel::toArray(new PayrollAttendanceImport, $request->file('file'));

        if(count($data[0]) > 0)
        {
            $save_count = 0;
            $not_save = [];
            foreach($data[0] as $key => $value)
            {
                $payroll_attendance = PayrollAttendance::where('payroll_period_id',$value['payroll_period_id'])
                                                        ->where('user_id',$value['user_id'])
                                                        ->first();
                
                if(!$payroll_attendance) $payroll_attendance = new PayrollAttendance();

                if (isset($value['payroll_period_id'])) $payroll_attendance->payroll_period_id = $value['payroll_period_id'];
                if (isset($value['user_id'])) $payroll_attendance->user_id = $value['user_id'];
                if (isset($value['full_name'])) $payroll_attendance->full_name = $value['full_name'];
                if (isset($value['department'])) $payroll_attendance->department = $value['department'];
                if (isset($value['company'])) $payroll_attendance->company = $value['company'];
                if (isset($value['location'])) $payroll_attendance->location = $value['location'];
                if (isset($value['overtime_approver_id'])) $payroll_attendance->overtime_approver = $value['overtime_approver_id'];
                if (isset($value['basic_pay'])) $payroll_attendance->basic_pay = $value['basic_pay'];
                if (isset($value['daily_rate'])) $payroll_attendance->daily_rate = $value['daily_rate'];
                if (isset($value['hourly_rate'])) $payroll_attendance->hourly_rate = $value['hourly_rate'];
                if (isset($value['no_of_days_worked'])) $payroll_attendance->no_of_days_worked = $value['no_of_days_worked'];
                if (isset($value['days_worked_amount'])) $payroll_attendance->days_worked_amount = $value['days_worked_amount'];
                if (isset($value['absences_days'])) $payroll_attendance->absences_days = $value['absences_days'];
                if (isset($value['absences_amount'])) $payroll_attendance->absences_amount = $value['absences_amount'];
                if (isset($value['lates_hours'])) $payroll_attendance->lates_hours = $value['lates_hours'];
                if (isset($value['lates_amount'])) $payroll_attendance->lates_amount = $value['lates_amount'];
                if (isset($value['undertime_hours'])) $payroll_attendance->undertime_hours = $value['undertime_hours'];
                if (isset($value['undertime_amount'])) $payroll_attendance->undertime_amount = $value['undertime_amount'];
                if (isset($value['regular_ot_hours'])) $payroll_attendance->reg_ot_hours = $value['regular_ot_hours'];
                if (isset($value['regular_ot_amount'])) $payroll_attendance->reg_ot_amount = $value['regular_ot_amount'];
                if (isset($value['rest_day_hours'])) $payroll_attendance->rest_day_hours = $value['rest_day_hours'];
                if (isset($value['rest_day_hours_amount'])) $payroll_attendance->rest_day_amount = $value['rest_day_hours_amount'];
                if (isset($value['rdod_shot_hours'])) $payroll_attendance->rdot_shot_hours = $value['rdod_shot_hours'];
                if (isset($value['rdot_shot_amount'])) $payroll_attendance->rdot_shot_amount = $value['rdot_shot_amount'];
                if (isset($value['special_holiday_hours'])) $payroll_attendance->special_holiday_hours = $value['special_holiday_hours'];
                if (isset($value['special_holiday_amount'])) $payroll_attendance->special_holiday_amount = $value['special_holiday_amount'];
                if (isset($value['shrd_hours'])) $payroll_attendance->shrd_hours = $value['shrd_hours'];
                if (isset($value['shrd_amount'])) $payroll_attendance->shrd_amount = $value['shrd_amount'];
                if (isset($value['sh_and_rd_ot_hours'])) $payroll_attendance->sh_rd_ot_hours = $value['sh_and_rd_ot_hours'];
                if (isset($value['sh_and_rd_ot_amount'])) $payroll_attendance->sh_rd_ot_amount = $value['sh_and_rd_ot_amount'];
                if (isset($value['regular_holiday_hours'])) $payroll_attendance->regular_holiday_hours = $value['regular_holiday_hours'];
                if (isset($value['regular_holiday_amount'])) $payroll_attendance->regular_holiday_amount = $value['regular_holiday_amount'];
                if (isset($value['sh_and_rd_or_rh_ot_hours'])) $payroll_attendance->rh_rd_or_lh_ot_hours = $value['sh_and_rd_or_rh_ot_hours'];
                if (isset($value['sh_and_rd_or_rh_ot_amount'])) $payroll_attendance->rh_rd_or_lh_ot_amount = $value['sh_and_rd_or_rh_ot_amount'];
                if (isset($value['lhrd_ot_hours'])) $payroll_attendance->lhrd_ot_hours = $value['lhrd_ot_hours'];
                if (isset($value['lhrd_ot_amount'])) $payroll_attendance->lhrd_ot_amount = $value['lhrd_ot_amount'];
                if (isset($value['night_diff_hours'])) $payroll_attendance->night_diff_hours = $value['night_diff_hours'];
                if (isset($value['night_diff_amount'])) $payroll_attendance->night_diff_amount = $value['night_diff_amount'];
                if (isset($value['overtime_adjustment'])) $payroll_attendance->overtime_adjustment = $value['overtime_adjustment'];
                if (isset($value['total_overtime_pay'])) $payroll_attendance->total_overtime_pay = $value['total_overtime_pay'];
                if (isset($value['time_keeper_id'])) $payroll_attendance->timekeeper = $value['time_keeper_id'];
                $payroll_attendance->save();
                $save_count+=1;                                 
            }

            Alert::success('Successfully Import Payroll Attendance (' . $save_count. ')')->persistent('Dismiss');

            return redirect('payroll-attendances');
        }
    }
}
