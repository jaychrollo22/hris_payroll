<?php
use App\ApplicantSystemNotification;
use App\UserAllowedCompany;
use App\UserAllowedLocation;
use App\UserAllowedProject;
use App\UserPrivilege;
use App\Employee;
use App\EmployeeLeave;
use App\EmployeeEarnedLeave;
use App\EmployeeLeaveCredit;
use App\Holiday;
use App\Attendance;
use App\EmployeeOvertime;
use App\EmployeeWfh;
use App\EmployeeOb;
use App\EmployeeDtr;

function employee_name($employee_names,$employee_number){
    foreach($employee_names as $item){
        if($item['employee_number'] == $employee_number){
            return $item->last_name . ' ' . $item->first_name;
        }
    }
}
function getInitial($text) {
    preg_match_all('#([A-Z]+)#', $text, $capitals);
    if (count($capitals[1]) >= 2) {
        return substr(implode('', $capitals[1]), 0, 1);
    }
    return strtoupper(substr($text, 0, 1));
}

function appFormatDate($date) {
    return date("Y-m-d", strtotime($date));
}

function appFormatFullDate($date) {
    return date("F d, Y h:i A", strtotime($date));
}

function roleValidation(){
    if(session('role_ids'))
    {
        if(in_array(1,session('role_ids')) || in_array(3,session('role_ids')) || in_array(9,session('role_ids'))){ //Administrator,DCO and ADCO
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}
function roleValidationAsAdministrator(){
    if(session('role_ids'))
    {
        if(in_array(1,session('role_ids'))){ //Administrator,DCO and ADCO
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}


function get_count_days_leave($data,$date_from,$date_to)
 {
    $data = ($data->pluck('name'))->toArray();
    $count = 0;
    $startTime = strtotime($date_from);
    $endTime = strtotime($date_to);

    for ( $i = $startTime; $i <= $endTime; $i = $i + 86400 ) {
      $thisDate = date( 'l', $i ); // 2010-05-01, 2010-05-02, etc
      if(in_array($thisDate,$data)){
          $count= $count+1;
      }
    }
    return($count);
 } 
 
function dateRangeHelper( $first, $last, $step = '+1 day', $format = 'Y-m-d' ) {
    $dates = [];
    $current = strtotime( $first );
    $last = strtotime( $last );

    while( $current <= $last ) {
        $curr = date('D',$current);
        if ($curr == 'Sat' || $curr == 'Sun') {
            $current = strtotime( $step, $current);
        }else{
            $dates[] = date( $format, $current);
            $current = strtotime( $step, $current );
        }
    }

    return $dates;
}
function dateRangeHelperLeave( $first, $last, $step = '+1 day', $format = 'Y-m-d' ) {
    $dates = [];
    $current = strtotime( $first );
    $last = strtotime( $last );

    while( $current <= $last ) {
        $curr = date('D',$current);
        if ($curr == 'Sun') {
            $current = strtotime( $step, $current);
        }else{
            $dates[] = date( $format, $current);
            $current = strtotime( $step, $current );
        }
    }

    return $dates;
}

function dateRange( $first, $last, $step = '+1 day', $format = 'Y-m-d' ) {
    $dates = [];
    $current = strtotime( $first );
    $last = strtotime( $last );

    while( $current <= $last ) {

        $dates[] = date( $format, $current );
        $current = strtotime( $step, $current );
    }

    return $dates;
}


function employeeSchedule($schedules = array(), $check_date, $schedule_id){
    $schedule_name = date('l',strtotime($check_date));
    if(count($schedules) > 0){
        foreach($schedules as $item){
            if($item['schedule_id'] == $schedule_id && $item['name'] == $schedule_name){
                return $item;
            }
        }
    }
}

function isRestDay( $date ) {
    
    $check_day = date('D',strtotime($date));
    $check = 0;
    if ($check_day == 'Sat' || $check_day == 'Sun') {
        $check = 1;
    }else{
        $check = 0;
    }
    return $check;
}

function employeeHasLeave($employee_leaves = array(), $check_date,$schedule = array()){
    if(count($employee_leaves) > 0 && $schedule){
        foreach($employee_leaves as $item){
            if($item['date_from'] == $item['date_to']){
                if(date('Y-m-d',strtotime($check_date)) == date('Y-m-d',strtotime($item['date_from']))){
                    $status = 'Without-Pay';
                    if($item['withpay'] == 1){
                        $status = 'With-Pay';
                    }
                    if($item['halfday'] == '1'){
                        return $item['leave']['code'] . ' ' . $item['halfday_status'] . ' ' . $status;
                    }else{
                        return $item['leave']['code'] . ' ' . $status;
                    }
                }
            }else{
                $date_range = dateRangeHelperLeave($item['date_from'],$item['date_to']);
                if(count($date_range) > 0){
                    foreach($date_range as $date_r){
                        if(date('Y-m-d',strtotime($date_r)) == date('Y-m-d',strtotime($check_date))){
                            $status = 'Without-Pay';
                            if($item['withpay'] == 1){
                                $status = 'With-Pay';
                            }
                            if($item['halfday'] == '1'){
                                
                                return $item['leave']['code'] . ' ' . $item['halfday_status'] . ' ' . $status;
                            }else{
                                return $item['leave']['code'] . ' ' . $status;
                            }
                        }
                    }
                }
            }
        }
    }
}

function employeeHasOB($employee_obs = array(), $check_date){
    if(count($employee_obs) > 0){
        foreach($employee_obs as $item){
            if(date('Y-m-d',strtotime($item['applied_date'])) == date('Y-m-d',strtotime($check_date))){
                return 'OB';
            }
        }
    }
}

function employeeHasOBDetails($employee_obs = array(), $check_date){
    if(count($employee_obs) > 0){
        foreach($employee_obs as $item){
            if(date('Y-m-d',strtotime($item['applied_date'])) == date('Y-m-d',strtotime($check_date))){
                return $item;
            }
        }
    }
}

function employeeHasWFH($employee_wfhs = array(), $check_date){
    if(count($employee_wfhs) > 0){
        foreach($employee_wfhs as $item){
            if(date('Y-m-d',strtotime($item['applied_date'])) == date('Y-m-d',strtotime($check_date))){
                return 'WFH';
            }
        }
    }
}

function employeeHasWFHDetails($employee_wfhs = array(), $check_date){
    if(count($employee_wfhs) > 0){
        foreach($employee_wfhs as $item){
            if(date('Y-m-d',strtotime($item['applied_date'])) == date('Y-m-d',strtotime($check_date))){
                return $item;
            }
        }
    }
}

function employeeHasOTDetails($employee_ots = array(), $check_date){
    if(count($employee_ots) > 0){
        $total_approved_overtime = 0;
        foreach($employee_ots as $item){
            if(date('Y-m-d',strtotime($item['ot_date'])) == date('Y-m-d',strtotime($check_date))){

                $total = $item['ot_approved_hrs'] - $item['break_hrs'];

                $total_approved_overtime += $total;
            }
        }
        return $total_approved_overtime;
    }
}

function employeeHasDTRDetails($employee_dtrs = array(), $check_date){
    if($employee_dtrs){
        foreach($employee_dtrs as $item){
            if(date('Y-m-d',strtotime($item['dtr_date'])) == date('Y-m-d',strtotime($check_date))){
                return $item;
            }
        }
    }
}

function getUserAllowedCompanies($user_id){
    $user_allowed_companies = UserAllowedCompany::where('user_id',$user_id)->first();

    if($user_allowed_companies){
        return json_decode($user_allowed_companies->company_ids);
    }else{
        return [];
    }
}
function getUserAllowedLocations($user_id){
    $user_allowed_locations = UserAllowedLocation::where('user_id',$user_id)->first();

    if($user_allowed_locations){
        return json_decode($user_allowed_locations->location_ids);
    }else{
        return [];
    }
}
function getUserAllowedProjects($user_id){
    $user_allowed_projects = UserAllowedProject::where('user_id',$user_id)->first();

    if($user_allowed_projects){
        return json_decode($user_allowed_projects->project_ids);
    }else{
        return [];
    }
}

function checkUserPrivilege($field,$user_id){
    $user_privilege = UserPrivilege::select('id')->where($field,'on')->where('user_id',$user_id)->first();
    if($user_privilege){
        return 'yes';
    }else{
        return 'no';
    }
}

function checkUserAllowedOvertime($user_id){
    $employee = Employee::select('level')->where('user_id',$user_id)->first();
    if($employee->level == 'RANK&FILE' || $employee->level == '1'){
        return 'yes';
    }else{
        return 'no';
    }
}

function checkUsedSLVLSILLeave($user_id,$leave_type,$date_hired){

    $count = 0;
    if($date_hired){
        $today  = date('Y-m-d');
        $date_hired_md = date('m-d',strtotime($date_hired));
        $date_hired_m = date('m',strtotime($date_hired));
        $last_year = date('Y', strtotime('-1 year', strtotime($today)) );
        $this_year = date('Y');

        $date_hired_this_year = $this_year . '-'. $date_hired_md;

        if($today > $date_hired_this_year  ){
            $filter_date_leave = $this_year . '-'. $date_hired_md;
        }else{
            $filter_date_leave = $last_year . '-'. $date_hired_md;
        }
        $employee_vl = EmployeeLeave::where('user_id',$user_id)
                                        ->where('leave_type',$leave_type)
                                        ->where('status','Approved')
                                        // ->where('date_from','>',$filter_date_leave)
                                        ->get();
        
        $date_today = date('Y-m-d');
        if($employee_vl){
            foreach($employee_vl as $leave){
                if($leave->withpay == 1 && $leave->halfday == 1){
                    if(date('Y-m-d',strtotime($leave->date_from)) <= $date_today){
                        $count += 0.5;
                    }
                }else{
                    $date_range = dateRangeHelper($leave->date_from,$leave->date_to);
                    if($date_range){
                        foreach($date_range as $date_r){
                            $leave_Date = date('Y-m-d', strtotime($date_r));
                            if($leave->withpay == 1 && $leave_Date <= $date_today){
                                $count += 1;
                            }
                        }
                    }
                }
                
            }
        }
    }
    return $count;
}

function checkEarnedLeave($user_id,$leave_type,$date_hired){

    //Get From Last Year Earned
    // if($date_hired){
    //     $today  = date('Y-m-d');
    //     $date_hired_md = date('m-d',strtotime($date_hired));
    //     $date_hired_m = date('m',strtotime($date_hired));
    //     $last_year = date('Y', strtotime('-1 year', strtotime($today)) );
    //     $this_year = date('Y');

    //     $date_hired_this_year = $this_year . '-'. $date_hired_md;
    //     $date_hired_last_year = $last_year . '-'. $date_hired_md;

    //     if($today >= $date_hired_this_year){ //if Date hired meets todays date get earned leaves from last year to this year date_hired
    //         $date_hired_this_minus_1_month = date('Y-m-d', strtotime('-1 month', strtotime($date_hired_this_year)) );
            return $vl_earned = EmployeeEarnedLeave::where('user_id',$user_id)
                                                        ->where('leave_type',$leave_type)
                                                        ->whereNull('converted_to_cash')
                                                        // ->whereBetween('earned_date', [$date_hired_last_year, $date_hired_this_minus_1_month])
                                                        ->sum('earned_leave');
        // }else{
        //     return 0;
        // }
    // }

    
    
}

function checkUsedSickLeave($user_id){
    $employee_sl = EmployeeLeave::where('user_id',$user_id)
                                    ->where('leave_type','2')
                                    ->where('status','Approved')
                                    ->get();

    $count = 0;
    if($employee_sl){
        foreach($employee_sl as $leave){
            if($leave->withpay == 1 && $leave->halfday == 1){
                $count += 0.5;
            }else{
                $date_range = dateRangeHelper($leave->date_from,$leave->date_to);
                if($date_range){
                    foreach($date_range as $date_r){
                        if($leave->withpay == 1){
                            $count += 1;
                        }
                    }
                }
            }
        }
    }

    return $count;
}

function checkUsedServiceIncentiveLeave($user_id){
    $employee_sil = EmployeeLeave::where('user_id',$user_id)
                                    ->where('leave_type','10')
                                    ->where('status','Approved')
                                    ->get();

    $count = 0;
    if($employee_sil){
        foreach($employee_sil as $leave){
            if($leave->withpay == 1 && $leave->halfday == 1){
                $count += 0.5;
            }else{
                $date_range = dateRangeHelper($leave->date_from,$leave->date_to);
                if($date_range){
                    foreach($date_range as $date_r){
                        if($leave->withpay == 1){
                            $count += 1;
                        }
                    }
                }
            }
        }
    }

    return $count;
}

function checkUsedLeave($user_id,$leave_type){
    $employee_leave = EmployeeLeave::where('user_id',$user_id)
                                    ->where('leave_type',$leave_type)
                                    ->where('status','Approved')
                                    ->get();

    $count = 0;
    if($employee_leave){
        foreach($employee_leave as $leave){
            if($leave->withpay == 1 && $leave->halfday == 1){
                $count += 0.5;
            }else{
                $date_range = dateRangeHelper($leave->date_from,$leave->date_to);
                if($date_range){
                    foreach($date_range as $date_r){
                        if($leave->withpay == 1){
                            $count += 1;
                        }
                    }
                }
            }
        }
    }

    return $count;
}

function checkIfHoliday($date,$location){
    $check_holiday = Holiday::where('holiday_date',$date)->first();
    if($check_holiday){
        if($check_holiday->location){
            if($check_holiday->location == $location){
                return $check_holiday->holiday_type;
            }else{
                return "";
            }
        }else{
            return $check_holiday->holiday_type;
        }
    }else{
        return "";
    }
}

function checkHasAttendanceHoliday($date,$employee_code,$location){

    $date_attendance = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $date) ) ));
    $check_if_holiday = checkIfHoliday($date_attendance,$location);
    $check_if_restday = isRestDay($date_attendance);

    if($check_if_holiday){ //Holiday
        $date_attendance_1 = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $date_attendance) ) ));
        $check_if_holiday_1 = checkIfHoliday($date_attendance_1,$location);
        $check_if_restday_1 = isRestDay($date_attendance_1);

        if($check_if_holiday_1){ //Holiday
            
            $date_attendance_2 = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $date_attendance_1) ) ));
            $check_if_holiday_2 = checkIfHoliday($date_attendance_2,$location);
            $check_if_restday_2 = isRestDay($date_attendance_2);

            if($check_if_holiday_2){ //Holiday

                $date_attendance_3 = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $date_attendance_2) ) ));
                $check_if_holiday_3 = checkIfHoliday($date_attendance_3,$location);
                $check_if_restday_3 = isRestDay($date_attendance_3);

                if($check_if_holiday_3){ //Holiday

                }else{ //Regular Work
                    if($check_if_restday_3 == 0){ //Rest day no
                        return $date_attendance_3;
                    }
                }
            }else{ //Regular Work
                if($check_if_restday_2 == 0){ //Rest day no
                    return $date_attendance_2;
                }
            }

        }else{ //Regular Work
            if($check_if_restday_1 == 0){ //Rest day no
                return $date_attendance_1;
            }else{
                $date_attendance_2 = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $date_attendance_1) ) ));
                $check_if_holiday_2 = checkIfHoliday($date_attendance_2,$location);
                $check_if_restday_2 = isRestDay($date_attendance_2);

                if($check_if_holiday_2){ //Holiday
                    
                    $date_attendance_3 = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $date_attendance_2) ) ));
                    $check_if_holiday_3 = checkIfHoliday($date_attendance_3,$location);
                    $check_if_restday_3 = isRestDay($date_attendance_3);

                    if($check_if_holiday_3){ //Holiday

                        $date_attendance_4 = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $date_attendance_3) ) ));
                        $check_if_holiday_4 = checkIfHoliday($date_attendance_4,$location);
                        $check_if_restday_4 = isRestDay($date_attendance_4);
                        
                        if($check_if_holiday_4){ //Holiday

                        }else{ //Regular Work
                            if($check_if_restday_4 == 0){ //Rest day no
                                return $date_attendance_4;
                            }
                        }
                    }else{ //Regular Work
                        if($check_if_restday_3 == 0){ //Rest day no
                            return $date_attendance_3;
                        }
                    }
                }else{ //Regular Work
                    if($check_if_restday_2 == 0){ //Rest day no
                        return $date_attendance_2;
                    }
                }
            }
        }
    }else{ //Regular Work
        if($check_if_restday == 0){
            return $date_attendance;
        }else{ // Regular days
            $date_attendance_1 = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $date_attendance) ) ));
            $check_if_holiday_1 = checkIfHoliday($date_attendance_1,$location);
            $check_if_restday_1 = isRestDay($date_attendance_1);

            if($check_if_holiday_1){ //Holiday

                $date_attendance_2 = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $date_attendance_1) ) ));
                $check_if_holiday_2 = checkIfHoliday($date_attendance_2,$location);
                $check_if_restday_2 = isRestDay($date_attendance_2);

                if($check_if_holiday_2){ //Holiday
                    $date_attendance_3 = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $date_attendance_2) ) ));
                    $check_if_holiday_3 = checkIfHoliday($date_attendance_3,$location);
                    $check_if_restday_3 = isRestDay($date_attendance_3);

                    if($check_if_holiday_3){ //Holiday

                    }else{ //Regular Work
                        if($check_if_restday_3 == 0){ //Rest day no
                            return $date_attendance_3;
                        }
                    }
                }else{ //Regular Work
                    if($check_if_restday_2 == 0){ //Rest day no
                        return $date_attendance_2;
                    }
                }
            }else{ //Regular Work
                if($check_if_restday_1 == 0){ //Rest day no
                    return $date_attendance_1;
                }else{

                    $date_attendance_2 = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $date_attendance_1) ) ));
                    $check_if_holiday_2 = checkIfHoliday($date_attendance_2,$location);
                    $check_if_restday_2 = isRestDay($date_attendance_2);

                    if($check_if_holiday_2){ //Holiday

                        $date_attendance_3 = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $date_attendance_2) ) ));
                        $check_if_holiday_3 = checkIfHoliday($date_attendance_3,$location);
                        $check_if_restday_3 = isRestDay($date_attendance_3);

                        if($check_if_holiday_3){ //Holiday
                            $date_attendance_4 = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $date_attendance_3) ) ));
                            $check_if_holiday_4 = checkIfHoliday($date_attendance_4,$location);
                            $check_if_restday_4 = isRestDay($date_attendance_4);

                            if($check_if_holiday_4){ //Holiday

                            }else{ //Regular Work
                                if($check_if_restday_4 == 0){ //Rest day no
                                    return $date_attendance_4;
                                }
                            }
                        }else{ //Regular Work
                            if($check_if_restday_3 == 0){ //Rest day no
                                return $date_attendance_3;
                            }
                        }
                    }else{
                        if($check_if_restday_2 == 0){ //Rest day no
                            return $date_attendance_2;
                        }
                    }
                }
            }
        }
    }
}

function checkHasAttendanceHolidayStatus($attendances=array(),$check_date){
    $status =  '';
    if(count($attendances) > 0 && $check_date){
        foreach($attendances as $item){            
            if(date('Y-m-d',strtotime($item['time_in'])) == date('Y-m-d',strtotime($check_date))){
               return $item['time_in'];
            }
        }
    }
    return $status;
}

function checkEmployeeLeaveCredits($user_id, $leave_type){
    $employee_leave = EmployeeLeaveCredit::where('user_id',$user_id)
                                    ->where('leave_type',$leave_type)
                                    ->first();
    if($employee_leave){
        return $employee_leave->count;
    }else{
        return 0;
    }
}


