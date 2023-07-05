@extends('layouts.header')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
         
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Attendances</h4>
                <p class="card-description">
                  <form method='get' onsubmit='show();'  enctype="multipart/form-data">
                  <div class=row>
                    <div class='col-md-3'>
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label text-right">Employee</label>
                        <div class="col-sm-8">
                            <select data-placeholder="Select Employee" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='employee[]' multiple required>
                                <option value="">-- Select Employee --</option>
                                 @foreach(auth()->user()->subbordinates as $emp)
                                    <option value="{{$emp->employee_number}}" @if ($emp->employee_number == $emp_code) selected @endif >{{$emp->employee_number}} - {{$emp->first_name}} {{$emp->last_name}}</option>
                                 @endforeach
                              </select>
                        </div>
                      </div>
                    </div>
                    <div class='col-md-3'>
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label text-right">From</label>
                        <div class="col-sm-8">
                          <input type="date" value='{{$from_date}}' class="form-control" name="from" max='{{date('Y-m-d')}}' onchange='get_min(this.value);' required/>
                        </div>
                      </div>
                    </div>
                    <div class='col-md-3'>
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label text-right">To</label>
                        <div class="col-sm-8">
                          <input type="date" value='{{$to_date}}'  class="form-control" name="to" id='to' max='{{date('Y-m-d')}}' required/>
                        </div>
                      </div>
                    </div>
                    <div class='col-md-3'>
                      <button type="submit" class="btn btn-primary mb-2">Submit</button>
                    </div>
                  </div>
                  </form>
                </p>
                
                <div class="table-responsive">

                    <table border="1" class="table table-hover table-bordered employee_attendance" id='employee_attendance'>
  
                        <thead>
                            <tr>
                                <td>User ID</td>
                                <td>Name</td>
                                <td>Date</td>
                                <td>Day</td>
                                <td>Time In</td>
                                <td>Time Out</td>
                                <td>Work </td>
                                <td>Lates </td>
                                <td>Undertime</td>
                                <td>Overtime</td>
                                <td>Approved Overtime</td>
                                <td>Night Diff</td>
                                <td>OT Night Diff</td>
                                <td>Device</th>
                                <td>Remarks</th>
  
                            </tr>
                        </thead>
  
                        @foreach($emp_data as $emp)
                        @php
                        $work =0;
                        $lates =0;
                        $undertimes =0;
                        $overtimes =0;
                        $approved_overtimes =0;
                        $night_diffs =0;
                        $night_diff_ot =0;
                        @endphp
                        
                        <tbody>
  
                            @foreach(array_reverse($date_range) as $date_r)
                            @php
                                $employee_schedule = employeeSchedule($schedules,$date_r,$emp->schedule_id);
                            @endphp
                            <tr>
                                <td>{{$emp->employee_number}}</td>
                                <td>{{$emp->first_name . ' ' . $emp->last_name}}</td>
                                <td class="@if($employee_schedule) @else bg-danger text-white @endif">{{date('d/m/Y',strtotime($date_r))}}</td>
                                <td>{{date('l',strtotime($date_r))}}</td>
                                
                                @php   
                                
                                    $time_in_data = '';
                                    $time_out_data = '';

                                    $if_has_ob = employeeHasOBDetails($emp->approved_obs,date('Y-m-d',strtotime($date_r)));
                                    $if_has_wfh = employeeHasWFHDetails($emp->approved_wfhs,date('Y-m-d',strtotime($date_r)));
                                    $if_has_dtr = employeeHasDTRDetails($emp->approved_dtrs,date('Y-m-d',strtotime($date_r)));
                                
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
                                        $dtr_correction_time_in = $if_has_dtr->correction == 'Time-in' ? $if_has_dtr->time_in : "";
                                        $dtr_correction_time_out = $if_has_dtr->correction == 'Time-out' ? $if_has_dtr->time_out : "";

                                        if($if_has_dtr->correction == 'Both'){
                                            $dtr_correction_time_in = $if_has_dtr->time_in;
                                            $dtr_correction_time_out = $if_has_dtr->time_out;
                                        }
                                        $dtr_correction_both = $if_has_dtr->correction == 'Both'  ? $if_has_dtr : "";
                                    }

                                    
                                @endphp

                                @if($if_has_ob)
                                    @php
                                        $ob_start = new DateTime($if_has_ob->date_from); 
                                        $ob_diff = $ob_start->diff(new DateTime($if_has_ob->date_to));
                                        $work_diff_hours = round($ob_diff->s / 3600 + $ob_diff->i / 60 + $ob_diff->h + $ob_diff->days * 24, 2);
                                        $work = (double) $work+$work_diff_hours;

                                        $overtime = (double) number_format($work_diff_hours - $employee_schedule['working_hours'],2);
                                    @endphp
                                    <td>{{$if_has_ob->date_from}}</td>
                                    <td>{{$if_has_ob->date_to}}</td>
                                    <td>{{ $ob_diff->h }} hrs. {{ $ob_diff->i }} mins. </td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        @if($overtime > .5)
                                            {{$overtime}} hrs
                                            @php
                                                $overtimes = (double) $overtimes +round($overtime,2);
                                            @endphp
                                        @else
                                            0 hrs
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                        $approved_overtime_hrs = $emp->approved_ots ? employeeHasOTDetails($emp->approved_ots,date('Y-m-d',strtotime($date_r))) : "";

                                        if($approved_overtime_hrs){
                                            $approved_overtimes = (double) $approved_overtimes + ($approved_overtime_hrs->ot_approved_hrs - $approved_overtime_hrs->break_hrs);
                                        }
                                        @endphp
                                        {{$approved_overtime_hrs ? (double) ($approved_overtime_hrs->ot_approved_hrs - $approved_overtime_hrs->break_hrs) : 0 }} hrs
                                    </td>
                                    <td>
                                        0 hrs
                                    </td>
                                    <td>
                                        @php
                                        $night_diff_ot = $night_diff_ot + round(night_difference(strtotime($if_has_ob->date_from),strtotime($if_has_ob->date_to)),2);
                                        echo round(night_difference(strtotime($if_has_ob->date_from),strtotime($if_has_ob->date_to)),2)." hrs";
                                        @endphp

                                    </td>
                                    <td></td>
                                    <td>OB</td>
                                @elseif($if_has_wfh)
                                    @php
                                        $wfh_start = new DateTime($if_has_wfh->date_from); 
                                        $wfh_diff = $wfh_start->diff(new DateTime($if_has_wfh->date_to)); 
                                        $work_diff_hours = round($wfh_diff->s / 3600 + $wfh_diff->i / 60 + $wfh_diff->h + $wfh_diff->days * 24, 2);
                                        $work = (double) $work+$work_diff_hours;

                                        $overtime = (double) number_format($work_diff_hours - $employee_schedule['working_hours'],2);
                                    @endphp
                                    <td>{{$if_has_wfh->date_from}}</td>
                                    <td>{{$if_has_wfh->date_to}}</td>
                                    <td>{{ $wfh_diff->h }} hrs. {{ $wfh_diff->i }} mins.</td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        @if($overtime > .5)
                                            {{$overtime}} hrs
                                            @php
                                                $overtimes = (double) $overtimes +round($overtime,2);
                                            @endphp
                                        @else
                                            0 hrs
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                        $approved_overtime_hrs = $emp->approved_ots ? employeeHasOTDetails($emp->approved_ots,date('Y-m-d',strtotime($date_r))) : "";

                                        if($approved_overtime_hrs){
                                            $approved_overtimes = (double) $approved_overtimes + ($approved_overtime_hrs->ot_approved_hrs - $approved_overtime_hrs->break_hrs);
                                        }
                                        @endphp
                                        {{$approved_overtime_hrs ? (double) ($approved_overtime_hrs->ot_approved_hrs - $approved_overtime_hrs->break_hrs) : 0 }} hrs
                                    </td>
                                    <td>
                                        0 hrs
                                    </td>
                                    <td>
                                        @php
                                        $night_diff_ot = $night_diff_ot + round(night_difference(strtotime($if_has_wfh->date_from),strtotime($if_has_wfh->date_to)),2);
                                        echo round(night_difference(strtotime($if_has_wfh->date_from),strtotime($if_has_wfh->date_to)),2)." hrs";
                                        @endphp

                                    </td>
                                    <td></td>
                                    <td>{{ $if_has_wfh->approve_percentage ? 'Work from Home ' . $if_has_wfh->approve_percentage .'%' : "WFH"}}</td>
                                @else

                                    {{-- Time In --}}
                                    @if($time_in || $if_has_dtr)
                                        <td>
                                            @if($dtr_correction_time_in)
                                                {{date('h:i A',strtotime($dtr_correction_time_in))}}
                                            @else
                                                {{date('h:i A',strtotime($time_in->time_in))}}
                                            @endif  
                                        </td>
                                        {{-- Time out --}}
                                        @if($dtr_correction_time_out)
                                            <td>{{date('h:i A',strtotime($dtr_correction_time_out))}}</td>
                                        @else
                                            @if($time_in)
                                                @if($time_in->time_out)
                                                    <td>{{date('h:i A',strtotime($time_in->time_out))}}</td>
                                                @else
                                                    @php
                                                        $time_in_out = 1;
                                                    @endphp
                                                    <td class='bg-warning'></td>
                                                @endif
                                            @else
                                                @if ($time_out)
                                                    @if ($time_out->time_out)
                                                        <td>
                                                            {{date('h:i A',strtotime($time_out->time_out))}}
                                                        </td>
                                                    @else
                                                        @php
                                                            $time_in_out = 1;
                                                        @endphp
                                                        <td class='bg-warning'></td>
                                                    @endif
                                                @else
                                                    @php
                                                        $time_in_out = 1;
                                                    @endphp
                                                    <td class='bg-warning'></td>
                                                @endif
                                            @endif
                                        @endif
                                    @else
                                        @if((date('l',strtotime($date_r)) == "Saturday") || (date('l',strtotime($date_r)) == "Sunday"))
                                            <td></td>
                                            <td></td>
                                        @else

                                            @if($dtr_correction_time_out)
                                                <td>{{date('h:i A',strtotime($dtr_correction_time_out))}}</td>
                                            @else
                                                @php
                                                $time_in_out = 1;
                                                @endphp
                                                <td class='bg-warning'></td>
                                                @if($time_in)
                                                    @if($time_in->time_out)
                                                    <td>
                                                        {{date('h:i A',strtotime($time_in->time_out))}}
                                                    </td>
                                                    @else
                                                        @php
                                                            $time_in_out = 1;
                                                        @endphp
                                                        <td class='bg-warning'></td>
                                                    @endif
                                                @else
                                                    @if ($time_out)
                                                        <td>
                                                            {{date('h:i A',strtotime($time_out->time_out))}}
                                                        </td>
                                                    @else
                                                        @php
                                                            $time_in_out = 1;
                                                            @endphp
                                                        <td class='bg-warning'></td>
                                                    @endif
                                                @endif
                                            @endif
                                        @endif
                                    @endif
                                    
        
                                    {{-- Working Hours / Lates / Undertimes --}}
                                    @if($time_in_out == 1)
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    @else
                                        {{-- Working Hours --}}
                                        <td>
                                            @if($time_in || $if_has_dtr)
                                                @php

                                                    $id = array_search(date('l',strtotime($date_r)),$schedules->pluck('name')->toArray());
                                                    $time_in_from = $employee_schedule ? $employee_schedule['time_in_from'] : "08:00";

                                                    $employee_time_in = '';
                                                    if($dtr_correction_time_in){
                                                        $employee_time_in = $dtr_correction_time_in;
                                                    }else{
                                                        $employee_time_in = $time_in->time_in;
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

                                                    if(strtotime(date('H:i:00',strtotime($employee_time_in))) >= strtotime($time_in_from))
                                                    {
                                                        $time_in_data = $employee_time_in;
                                                    }
                                                    else
                                                    {
                                                        $time_in_data = date('Y-m-d ' . $time_in_from,strtotime($employee_time_in));
                                                    }
                                                @endphp
                                                @php
                                                    $start_datetime = new DateTime($time_in_data); 
                                                    if($time_out_data){
                                                        $diff = $start_datetime->diff(new DateTime($time_out_data)); 
                                                    }
                                                @endphp
                                                @if($time_out_data)
                                                    {{ $diff->h }} hrs. {{ $diff->i }} mins. 
                                                    @php
                                                        $work_diff_hours = round($diff->s / 3600 + $diff->i / 60 + $diff->h + $diff->days * 24, 2);
                                                        $work = (double) $work+$work_diff_hours;
                                                        $overtime = (double) number_format($work_diff_hours,2);
                                                    @endphp
                                                @endif
                                            @endif
                                        </td>

                                        @if($employee_schedule && $time_in_data && $time_out_data)
                                            @php
                                                //Lates
                                                $time_in_data_full =  date('Y-m-d h:i:s',strtotime($time_in_data));
                                                $time_in_data_date =  date('Y-m-d',strtotime($time_in_data));
                                                $schedule_time_in =  $time_in_data_date . ' ' . $employee_schedule['time_in_to'];
                                                $schedule_time_in_with_grace =  date('Y-m-d h:15:s',strtotime($schedule_time_in));
                                                $schedule_time_in =  date('Y-m-d h:i:s',strtotime($schedule_time_in));
                                                $schedule_time_in_final =  new DateTime($schedule_time_in);
                                                $late_diff_hours = 0;

                                                if(date('Y-m-d h:i',strtotime($time_in_data)) > date('Y-m-d h:i',strtotime($schedule_time_in_with_grace))){

                                                    //IF Attendance Exceed in Grace Period
                                                    $new_schedule_time_in =  $time_in_data_date . ' ' . $employee_schedule['time_in_from'];
                                                    $new_time_in_within_grace = date('Y-m-d h:i:s',strtotime($new_schedule_time_in));
                                                    $new_time_in_within_grace = new DateTime($new_time_in_within_grace);
                                                    $late_diff = $new_time_in_within_grace->diff(new DateTime($time_in_data_full));
                                                    $late_diff_hours = round($late_diff->s / 3600 + $late_diff->i / 60 + $late_diff->h + $late_diff->days * 24, 2);    
                                                    
                                                    if($dtr_correction_time_out){
                                                        $working_minutes = (double) (((strtotime($dtr_correction_time_out) - (double) strtotime($time_in_data)))/3600);
                                                    }else{
                                                        $working_minutes = (double) (((strtotime($time_out_data) - (double) strtotime($time_in_data)))/3600);
                                                    }

                                                }else{
                                                    //IF Attendance is Within Grace Period
                                                    $new_schedule_time_in =  $time_in_data_date . ' ' . $employee_schedule['time_in_from'];
                                                    $new_time_in_within_grace = date('Y-m-d h:i:s',strtotime($new_schedule_time_in));
                                                    if($dtr_correction_time_out){
                                                        $working_minutes = (double) (((strtotime($dtr_correction_time_out) - (double) strtotime($new_time_in_within_grace)))/3600);
                                                    }else{
                                                        $working_minutes = (double) (((strtotime($time_out_data) - (double) strtotime($new_time_in_within_grace)))/3600);
                                                    }
                                                }

                                                $late =  (double) (strtotime(date("01-01-2022 h:i",strtotime($time_in_data))) - (double) strtotime(date("01-01-2022 h:i",strtotime("Y-m-d ".$employee_schedule['time_in_to']))))/60;
                                                
                                                
                                            
                                                $overtime = (double) number_format($working_minutes - $employee_schedule['working_hours'],2);
                                                if($late > 0)
                                                {
                                                    $late_data = $late;
                                                }
                                                else {
                                                    $late_data = 0;
                                                }

                                                //Undertime
                                                $undertime_hrs = 0;
                                                $undertime = (double) number_format($employee_schedule['working_hours'] - $working_minutes,2);
                                                if($undertime > 0){
                                                    if($late_diff_hours > 0){
                                                        $undertime_hrs = number_format(($undertime*60*-1)/60,2) - $late_diff_hours;
                                                    }else{
                                                        $undertime_hrs = $undertime;
                                                    }
                                                }   

                                            @endphp

                                            <td>
                                                {{  $late_diff_hours }} hrs
                                                @php
                                                    $lates = (double) $lates+$late_diff_hours;
                                                @endphp
                                            </td>
                                            <td>
                                                {{-- Undertime --}}
                                                @if($undertime_hrs > 0) 
                                                    {{$undertime_hrs}} hrs 
                                                    @php 
                                                        $undertimes=$undertimes + $undertime_hrs; 
                                                    @endphp 
                                                @else 
                                                    0 hrs 
                                                @endif 
                                            </td>
                                            <td>
                                                @if($overtime > .5)
                                                    {{$overtime}} hrs
                                                    @php
                                                        $overtimes = (double) $overtimes +round($overtime,2);
                                                    @endphp
                                                @else
                                                    0 hrs
                                                @endif
                                            </td>
                                            <td>
                                                    @php
                                                    $approved_overtime_hrs = $emp->approved_ots ? employeeHasOTDetails($emp->approved_ots,date('Y-m-d',strtotime($date_r))) : "";

                                                    if($approved_overtime_hrs){
                                                        $approved_overtimes = (double) $approved_overtimes + ($approved_overtime_hrs->ot_approved_hrs - $approved_overtime_hrs->break_hrs);
                                                    }
                                                    @endphp
                                                    {{$approved_overtime_hrs ? (double) ($approved_overtime_hrs->ot_approved_hrs - $approved_overtime_hrs->break_hrs) : 0 }} hrs
                                            </td>
                                            <td>
                                                0 hrs
                                            </td>
                                            <td>
                                                @php
                                                $night_diff_ot = $night_diff_ot + round(night_difference(strtotime($time_in_data),strtotime($time_out_data)),2);
                                                echo round(night_difference(strtotime($time_in_data),strtotime($time_out_data)),2)." hrs";
                                                @endphp

                                            </td>
                                            <td>
                                                @if($time_in)
                                                    <small>
                                                        Time In : {{$time_in->device_in}} <br>
                                                        Time Out : {{$time_in->device_out}}
                                                    </small>
                                                @endif
                                            </td>
                                        @else
                                            <td></td>
                                            <td></td>
                                            @if($time_in || $if_has_dtr)
                                                <td>
                                                    @if($overtime > .5)
                                                        {{$overtime}} hrs
                                                        @php
                                                            $overtimes = (double) $overtimes +round($overtime,2);
                                                        @endphp
                                                    @else
                                                        0 hrs
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                    $approved_overtime_hrs = $emp->approved_ots ? employeeHasOTDetails($emp->approved_ots,date('Y-m-d',strtotime($date_r))) : "";
            
                                                    if($approved_overtime_hrs){
                                                        $approved_overtimes = (double) $approved_overtimes + ($approved_overtime_hrs->ot_approved_hrs - $approved_overtime_hrs->break_hrs);
                                                    }
                                                    @endphp
                                                    {{$approved_overtime_hrs ? (double) ($approved_overtime_hrs->ot_approved_hrs - $approved_overtime_hrs->break_hrs) : 0 }} hrs
                                                </td>

                                                <td>
                                                    0 hrs
                                                </td>
                                                <td>
                                                    @php
                                                    $night_diff_ot = $night_diff_ot + round(night_difference(strtotime($time_in_data),strtotime($time_out_data)),2);
                                                    echo round(night_difference(strtotime($time_in_data),strtotime($time_out_data)),2)." hrs";
                                                    @endphp
    
                                                </td>

                                            @else
                                                <td></td>
                                                <td></td>
                                            
                                                <td></td>
                                                <td></td>
                                            @endif
                                            <td></td>
                                        @endif
                                    @endif
                                    
                                    {{-- Remarks --}}
                                    <td>
                                        @if($time_in == null)
                                            @if($employee_schedule)
                                                @php 
                                                    $is_absent = '';
                                                    $if_leave = '';
                                                    $if_dtr_correction = '';
                                                    $if_attendance_holiday = '';
                                                    $check_if_holiday = checkIfHoliday(date('Y-m-d',strtotime($date_r)),$emp->location);
                                                    $if_attendance_holiday_status = '';
                                                    if($check_if_holiday){
                                                        $if_attendance_holiday = checkHasAttendanceHoliday(date('Y-m-d',strtotime($date_r)), $emp->employee_number,$emp->location);
                                                        if($if_attendance_holiday){

                                                            $check_leave = employeeHasLeave($emp->approved_leaves,date('Y-m-d',strtotime($if_attendance_holiday)));
                                                            $check_wfh = employeeHasOBDetails($emp->approved_wfhs,date('Y-m-d',strtotime($if_attendance_holiday)));
                                                            $check_ob = employeeHasOBDetails($emp->approved_obs,date('Y-m-d',strtotime($if_attendance_holiday)));
                                                            $check_dtr = employeeHasDTRDetails($emp->approved_dtrs,date('Y-m-d',strtotime($if_attendance_holiday)));

                                                            if($check_leave || $check_wfh || $check_ob || $check_dtr){
                                                                $if_attendance_holiday_status = 'With-Pay';
                                                                if($check_leave){
                                                                    if($check_leave == 'SL Without-Pay' || $check_leave == 'VL Without-Pay'){
                                                                        $if_attendance_holiday_status = 'Without-Pay';
                                                                    }else{
                                                                        $if_attendance_holiday_status = 'With-Pay';
                                                                    }
                                                                }
                                                            }else{
                                                                $if_attendance_holiday_status = checkHasAttendanceHolidayStatus($if_attendance_holiday, $emp->employee_number);
                                                            }
                                                        }
                                                    }else{
                                                        $if_leave = employeeHasLeave($emp->approved_leaves,date('Y-m-d',strtotime($date_r)));
                                                        if(empty($if_leave)){
                                                            if(empty($if_has_dtr)){
                                                                if($dtr_correction_time_out == null){
                                                                    if($time_out == null){
                                                                        $is_absent = 'Absent';
                                                                    }
                                                                }
                                                            }else{
                                                                $if_dtr_correction = 'DTR Correction';
                                                            } 
                                                        } 
                                                    }
                                                        
                                                @endphp
                                                {{$if_leave}}
                                                {{$is_absent}}
                                                {{$if_dtr_correction}}
                                                {{$if_attendance_holiday_status}}
                                            @endif
                                        @else
                                            @php
                                                $is_absent = '';
                                                if($time_out_data == null){
                                                    $is_absent = 'Absent';
                                                }
                                                
                                                $if_leave = employeeHasLeave($emp->approved_leaves,date('Y-m-d',strtotime($date_r)));

                                            @endphp  
                                            {{$if_leave}}
                                            {{$is_absent}}
                                        @endif
                                    </td>
                                @endif
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan='6'>Total</td>
                                <td >{{$work}} hrs</td>
                                <td >{{$lates}} hrs</td>
                                <td >{{$undertimes}} hrs </td>
                                <td >{{$overtimes}} hrs</td>
                                <td >{{$approved_overtimes}} hrs</td>
                                <td >0 hrs</td>
                                <td >{{$night_diff_ot}} hrs</td>
                                <td ></td>
                                <td ></td>
                            </tr>
                        </tbody>
  
                        @endforeach
                    </table>
                  </div>
                </div>
            </div>
          </div>
        
        </div>
    </div>
</div>
@php
function night_difference($start_work,$end_work)
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

@endphp
<script>
    function get_min(value)
    {
      document.getElementById("to").min = value;
    }
  </script>
@endsection
