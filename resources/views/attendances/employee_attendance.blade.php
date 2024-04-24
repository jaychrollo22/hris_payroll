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
                    <div class='col-md-4'>
                        <div class="form-group">
                          <select data-placeholder="Filter By Employee" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='employee[]' multiple>
                              <option value="">-- Filter By Employee --</option>
                              @foreach($employees as $emp)
                                  <option value="{{$emp->employee_number}}" @if($emp_code) @if (in_array($emp->employee_number,$emp_code)) selected @endif @endif>{{$emp->employee_number}} - {{$emp->first_name}} {{$emp->last_name}}</option>
                              @endforeach
                          </select>
                        </div> 
                    </div>
                    <div class='col-md-3'>
                          <div class="form-group">
                              <select data-placeholder="Filter By Company" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='company'>
                                  <option value="">-- Filter By Company --</option>
                                  @foreach($companies as $comp)
                                  <option value="{{$comp->id}}" @if ($comp->id == $company) selected @endif>{{$comp->company_name}} - {{$comp->company_code}}</option>
                                  @endforeach
                              </select>
                          </div>
                    </div>
                    
                    <div class='col-md-2'>
                      <div class="form-group">
                        <input type="date" value='{{$from_date}}' class="form-control" name="from" required/>
                      </div>
                    </div>
                    <div class='col-md-2'>
                      <div class="form-group">
                        <input type="date" value='{{$to_date}}'  class="form-control" name="to" id='to' required/>
                      </div>
                    </div>
                    <div class='col-md-3'>
                        <button type="submit" class="btn btn-primary mb-2">Submit</button>
                        @if($date_range)
                            <button class='btn btn-info mb-2' onclick="exportTableToExcel('employee_attendance','{{$from_date}} - {{$to_date}}')">Export</button>
                        @endif
                    
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
                                <td>Schedule</td>
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
  
                            @foreach($date_range as $date_r)
                            @php
                                $employee_schedule = employeeSchedule($schedules,$date_r,$emp->schedule_id);
                                $check_if_holiday = checkIfHoliday(date('Y-m-d',strtotime($date_r)),$emp->location);
                                $check_if_early_cutoff = checkIfEarlyCutoff(date('Y-m-d',strtotime($date_r)));

                                $check_if_has_leave_shift = employeeHasLeaveShift($emp->approved_leaves,date('Y-m-d',strtotime($date_r)),$employee_schedule);

                            @endphp
                            <tr>
                                <td>{{$emp->employee_number}}</td>
                                <td>{{$emp->first_name . ' ' . $emp->last_name}}</td>
                                <td>
                                    @if($employee_schedule)
                                        <small>{{$emp->schedule_info->schedule_name}}</small>
                                    @endif
                                </td>
                                <td class="@if($employee_schedule) @else bg-danger text-white @endif">{{date('m/d/Y',strtotime($date_r))}}</td>
                                <td>{{date('l',strtotime($date_r))}}</td>
                                
                                @php   
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

                                    
                                @endphp

                                @if($if_has_ob)
                                    @php

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

                                    @endphp
                                    <td>{{date('h:i A',strtotime($if_has_ob->date_from))}}</td>
                                    <td>{{date('h:i A',strtotime($if_has_ob->date_to))}}</td>
                                    <td>{{ $ob_diff->h }} hrs. {{ $ob_diff->i }} mins. </td>
                                    <td>
                                        {{-- Lates --}}
                                        @if(empty($check_if_holiday))
                                            {{  $late_diff_hours }} hrs
                                            @php
                                                $lates = (double) $lates+$late_diff_hours;
                                            @endphp
                                        @endif
                                    </td>
                                    
                                    <td>
                                        {{-- Undertime --}}
                                        @if(empty($check_if_holiday))
                                            @if($undertime_hrs > 0) 
                                                {{$undertime_hrs}} hrs 
                                                @php 
                                                    $undertimes=$undertimes + $undertime_hrs; 
                                                @endphp 
                                            @else 
                                                0 hrs 
                                            @endif 
                                        @endif 
                                    </td>
                                   
                                    <td>
                                        {{-- Overtime --}} 
                                        @if(empty($check_if_holiday))
                                            @if($overtime > .5)
                                                {{$overtime}} hrs
                                                @php
                                                    $overtimes = (double) $overtimes +round($overtime,2);
                                                @endphp
                                            @else
                                                0 hrs
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                        $approved_overtime_hrs = $emp->approved_ots ? employeeHasOTDetails($emp->approved_ots,date('Y-m-d',strtotime($date_r))) : "";

                                        if($approved_overtime_hrs){
                                            $approved_overtimes = (double) $approved_overtimes + $approved_overtime_hrs;
                                        }
                                        @endphp
                                        {{$approved_overtime_hrs ? (double) $approved_overtime_hrs : 0 }} hrs
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
                                    <td>OB
                                        @if($check_if_early_cutoff)
                                            {{$check_if_early_cutoff}}
                                        @endif
                                    </td>
                                @elseif($if_has_wfh)
                                    @php

                                        $late_diff_hours = 0;
                                        $overtime = 0;
                                        $undertime_hrs = 0;

                                        $wfh_start = new DateTime($if_has_wfh->date_from); 
                                        $wfh_diff = $wfh_start->diff(new DateTime($if_has_wfh->date_to)); 
                                        $work_diff_hours = round($wfh_diff->s / 3600 + $wfh_diff->i / 60 + $wfh_diff->h + $wfh_diff->days * 24, 2);
                                        $work = (double) $work+$work_diff_hours;

                                        if($if_has_wfh->date_from && $if_has_wfh->date_to && $employee_schedule){
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
                                        
                                    @endphp
                                    <td>{{date('h:i A',strtotime($if_has_wfh->date_from))}}</td>
                                    <td>{{date('h:i A',strtotime($if_has_wfh->date_to))}}</td>
                                    <td>{{ $wfh_diff->h }} hrs. {{ $wfh_diff->i }} mins.</td>
                                    
                                    <td>
                                        {{-- Lates --}}
                                        @if(empty($check_if_holiday))
                                            {{  $late_diff_hours }} hrs
                                            @php
                                                $lates = (double) $lates+$late_diff_hours;
                                            @endphp
                                        @endif
                                    </td>
                                    
                                    <td>
                                        {{-- Undertime --}}
                                        @if(empty($check_if_holiday))
                                            @if($undertime_hrs > 0) 
                                                {{$undertime_hrs}} hrs 
                                                @php 
                                                    $undertimes=$undertimes + $undertime_hrs; 
                                                @endphp 
                                            @else 
                                                0 hrs 
                                            @endif 
                                        @endif 
                                    </td>
                                   
                                    <td>
                                        {{-- Overtime --}} 
                                        @if(empty($check_if_holiday))
                                            @if($overtime > .5)
                                                {{$overtime}} hrs
                                                @php
                                                    $overtimes = (double) $overtimes +round($overtime,2);
                                                @endphp
                                            @else
                                                0 hrs
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                        $approved_overtime_hrs = $emp->approved_ots ? employeeHasOTDetails($emp->approved_ots,date('Y-m-d',strtotime($date_r))) : "";

                                        if($approved_overtime_hrs){
                                            $approved_overtimes = (double) $approved_overtimes + $approved_overtime_hrs;
                                        }
                                        @endphp
                                        {{$approved_overtime_hrs ? (double) $approved_overtime_hrs : 0 }} hrs
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
                                    <td>
                                        {{ $if_has_wfh->approve_percentage ? 'Work from Home ' . $if_has_wfh->approve_percentage .'%' : "WFH"}}
                                        @if($check_if_early_cutoff)
                                            {{$check_if_early_cutoff}}
                                        @endif
                                    </td>
                                @else

                                    {{-- Time In --}}
                                    @if($time_in || $if_has_dtr)
                                        <td>
                                            @if($dtr_correction_time_in)
                                                {{date('h:i A',strtotime($dtr_correction_time_in))}}
                                            @else
                                                @if($time_in)
                                                    {{date('h:i A',strtotime($time_in->time_in))}}
                                                @endif  
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
                                                    
                                                @endphp
                                                @php
                                                    if($time_in_data){
                                                        $start_datetime = new DateTime($time_in_data); 
                                                        if($time_out_data){
                                                            $diff = $start_datetime->diff(new DateTime($time_out_data)); 
                                                        }
                                                    }
                                                @endphp
                                                @if($time_in_data && $time_out_data)
                                                    
                                                    @php
                                                        $work_diff_hours = round($diff->s / 3600 + $diff->i / 60 + $diff->h + $diff->days * 24, 2);
                                                        $work = (double) $work+$work_diff_hours;
                                                        $overtime = (double) number_format($work_diff_hours,2);
                                                    @endphp
                                                    {{ $diff->h }} hrs. {{ $diff->i }} mins. ({{$work_diff_hours}})
                                                @endif
                                            @endif
                                        </td>

                                        @if($employee_schedule && $time_in_data && $time_out_data)
                                            @php
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
                                                            if(str_contains($emp->schedule_info->schedule_name, "Compressed") && !str_contains($emp->schedule_info->schedule_name, "Saturday")){
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

                                            @endphp

                                            <td>
                                                @if($check_if_has_leave_shift)
                                                    @if($check_if_has_leave_shift == 'Second Shift')
                                                        @if(empty($check_if_holiday))
                                                            {{  $late_diff_hours }} hrs
                                                            @php
                                                                $lates = (double) $lates+$late_diff_hours;
                                                            @endphp
                                                        @endif
                                                    @else 
                                                        0 hrs 
                                                    @endif
                                                @else
                                                    @if(empty($check_if_holiday))
                                                        {{  $late_diff_hours }} hrs
                                                        @php
                                                            $lates = (double) $lates+$late_diff_hours;
                                                        @endphp
                                                    @endif
                                                @endif


                                            
                                            </td>
                                            <td>
                                                {{-- Undertime --}}
                                                @if($check_if_has_leave_shift)
                                                    @if($check_if_has_leave_shift == 'First Shift')
                                                        @if(empty($check_if_holiday))
                                                            @if($undertime_hrs > 0) 
                                                                {{$undertime_hrs}} hrs 
                                                                @php 
                                                                    $undertimes=$undertimes + $undertime_hrs; 
                                                                @endphp 
                                                            @else 
                                                                0 hrs 
                                                            @endif 
                                                        @endif 
                                                    @else 
                                                        0 hrs 
                                                    @endif
                                                @else
                                                    @if(empty($check_if_holiday))
                                                        @if($undertime_hrs > 0) 
                                                            {{$undertime_hrs}} hrs 
                                                            @php 
                                                                $undertimes=$undertimes + $undertime_hrs; 
                                                            @endphp 
                                                        @else 
                                                            0 hrs 
                                                        @endif 
                                                    @endif 
                                                @endif 
                                            </td>
                                            <td>
                                                @if(empty($check_if_holiday))
                                                    @if($overtime > .5)
                                                        {{$overtime}} hrs
                                                        @php
                                                            $overtimes = (double) $overtimes +round($overtime,2);
                                                        @endphp
                                                    @else
                                                        0 hrs
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                $approved_overtime_hrs = $emp->approved_ots ? employeeHasOTDetails($emp->approved_ots,date('Y-m-d',strtotime($date_r))) : "";
        
                                                if($approved_overtime_hrs){
                                                    $approved_overtimes = (double) $approved_overtimes + $approved_overtime_hrs;
                                                }
                                                @endphp
                                                {{$approved_overtime_hrs ? (double) $approved_overtime_hrs : 0 }} hrs
                                            </td>
                                            <td>
                                                0 hrs
                                            </td>
                                            <td>
                                                @if(empty($check_if_holiday))
                                                    @php
                                                    $night_diff_ot = $night_diff_ot + round(night_difference(strtotime($time_in_data),strtotime($time_out_data)),2);
                                                    echo round(night_difference(strtotime($time_in_data),strtotime($time_out_data)),2)." hrs";
                                                    @endphp
                                                @endif
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
                                                    @if(empty($check_if_holiday))
                                                        @if($overtime > .5)
                                                            {{$overtime}} hrs
                                                            @php
                                                                $overtimes = (double) $overtimes +round($overtime,2);
                                                            @endphp
                                                        @else
                                                            0 hrs
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                    $approved_overtime_hrs = $emp->approved_ots ? employeeHasOTDetails($emp->approved_ots,date('Y-m-d',strtotime($date_r))) : "";

                                                    if($approved_overtime_hrs){
                                                        $approved_overtimes = (double) $approved_overtimes + $approved_overtime_hrs;
                                                    }
                                                    @endphp
                                                    {{$approved_overtime_hrs ? (double) $approved_overtime_hrs : 0 }} hrs
                                                </td>

                                                <td>
                                                    0 hrs
                                                </td>
                                                <td>
                                                    @if(empty($check_if_holiday))
                                                        @php
                                                        $night_diff_ot = $night_diff_ot + round(night_difference(strtotime($time_in_data),strtotime($time_out_data)),2);
                                                        echo round(night_difference(strtotime($time_in_data),strtotime($time_out_data)),2)." hrs";
                                                        @endphp
                                                    @endif
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
                                                        $if_attendance_holiday = '';
                                                        $if_attendance_holiday_status = '';
                                                        if($check_if_holiday){

                                                            if($check_if_holiday == 'Special Holiday' && $emp->work_description == 'Non-Monthly'){ //Condition if Daily Rate / Non Monthly
                                                                $if_attendance_holiday_status = 'Without-Pay';
                                                            }else{

                                                                $if_attendance_holiday = checkHasAttendanceHoliday(date('Y-m-d',strtotime($date_r)), $emp->employee_number,$emp->location);
                                                                if($if_attendance_holiday){

                                                                    $check_leave = employeeHasLeave($emp->approved_leaves,date('Y-m-d',strtotime($if_attendance_holiday)),$employee_schedule);
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
                                                                        
                                                                        $check_attendance = checkHasAttendanceHolidayStatus($emp->attendances,$if_attendance_holiday);

                                                                        if(empty($check_attendance)){
                                                                            $is_absent = 'Absent';
                                                                        }else{
                                                                            $if_attendance_holiday_status = 'With-Pay';
                                                                        }
                                                                    }
                                                                }else{

                                                                    $if_attendance_holiday = checkHasAttendanceHoliday(date('Y-m-d',strtotime($date_r . '-1 day')), $emp->employee_number,$emp->location);
                                                                
                                                                    if($if_attendance_holiday){
                                                                        $check_leave = employeeHasLeave($emp->approved_leaves,date('Y-m-d',strtotime($if_attendance_holiday)),$employee_schedule);
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
                                                                            
                                                                            $check_attendance = checkHasAttendanceHolidayStatus($emp->attendances,$if_attendance_holiday);

                                                                            if(empty($check_attendance)){
                                                                                $is_absent = 'Absent';
                                                                            }else{
                                                                                $if_attendance_holiday_status = 'With-Pay';
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }else{

                                                            $if_leave = employeeHasLeave($emp->approved_leaves,date('Y-m-d',strtotime($date_r)),$employee_schedule);
                                                            if(empty($if_leave)){
                                                                if(empty($if_has_dtr)){
                                                                    if($dtr_correction_time_out == null){
                                                                        if($time_out == null){
                                                                            $is_absent = 'Absent';
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            
                                                            if($time_out_data == null && empty($if_leave)){
                                                                $is_absent = 'Absent';
                                                            }
                                                        }

                                                        
                                                            
                                                    @endphp
                                                    {{$if_leave}}
                                                    {{$is_absent}}
                                                    {{$if_dtr_correction}}
                                                    {{$if_attendance_holiday_status}}


                                                    @if($check_if_early_cutoff)
                                                        {{$check_if_early_cutoff}}
                                                    @endif
                                                @endif
                                            @else
                                                @php
                                                    $is_absent = '';
                                                    $if_leave = '';
                                                    if($employee_schedule){
                                                        if($time_out_data == null){
                                                            $is_absent = 'Absent';
                                                        }
                                                        $if_leave = employeeHasLeave($emp->approved_leaves,date('Y-m-d',strtotime($date_r)),$employee_schedule);
                                                    }
                                                @endphp  
                                                {{$if_leave}}
                                                {{$if_dtr_correction}}
                                                {{$is_absent}}
                                                
                                                @if($check_if_early_cutoff)
                                                    @if($employee_schedule)
                                                        {{$check_if_early_cutoff}}
                                                    @endif
                                                @endif
                                                
                                            @endif
                                       

                                    </td>
                                @endif
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan='7'>Total</td>
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
