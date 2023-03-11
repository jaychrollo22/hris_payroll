<?php
use App\ApplicantSystemNotification;

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

function employeeHasLeave($employee_leaves = array(), $check_date){
    if(count($employee_leaves) > 0){
        foreach($employee_leaves as $item){
            $date_range = dateRangeHelper($item['date_from'],$item['date_to']);
            if(count($date_range) > 0){
                foreach($date_range as $date_r){
                    if(date('Y-m-d',strtotime($date_r)) == date('Y-m-d',strtotime($check_date))){
                        return $item['leave']['code'];
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

function employeeHasWFH($employee_wfhs = array(), $check_date){
    if(count($employee_wfhs) > 0){
        foreach($employee_wfhs as $item){
            if(date('Y-m-d',strtotime($item['applied_date'])) == date('Y-m-d',strtotime($check_date))){
                return 'WFH';
            }
        }
    }
}