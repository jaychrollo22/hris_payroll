<table border="1" class="table table-hover table-bordered employee_attendance" id='employee_attendance'>
    <thead>
        <tr>
            <td>USER ID</td>
            {{-- <td>Biometric ID</td> --}}
            {{-- <td>NAME</td> --}}
            <td>DATE</td>
            <td>FIRST ACTUAL TIME IN</td>
            <td>SECOND ACTUAL TIME OUT</td>
            <td>WORK</td>
            <td>REMARKS</td>
            <td>DATE DESCRIPTION</td>
        </tr>
    </thead>
    @foreach($attendances as $emp)
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
                $check_if_early_cutoff = checkIfEarlyCutoff(date('Y-m-d',strtotime($date_r)));
            @endphp
            <tr>
                <td>{{$emp->employee_number}}</td>
                {{-- <td>{{$emp->first_name . ' ' . $emp->last_name}}</td> --}}
                <td class="@if($employee_schedule) @else bg-danger text-white @endif">{{date('d/m/Y',strtotime($date_r))}}</td>

                @php

                    $check_if_holiday = checkIfHoliday(date('Y-m-d',strtotime($date_r)),$emp->location);

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
                        $ob_start = new DateTime($if_has_ob->date_from); 
                        $ob_diff = $ob_start->diff(new DateTime($if_has_ob->date_to)); 
                    @endphp
                    <td>{{date('H:i',strtotime($if_has_ob->date_from))}}</td>
                    <td>{{date('H:i',strtotime($if_has_ob->date_to))}}</td>
                    <td>{{ $ob_diff->h }} hrs. {{ $ob_diff->i }} mins. </td>
                    <td>
                        Business (WD)
                    </td>
                @elseif($if_has_wfh)
                    @php
                        $wfh_start = new DateTime($if_has_wfh->date_from); 
                        $wfh_diff = $wfh_start->diff(new DateTime($if_has_wfh->date_to)); 
                    @endphp
                    <td>{{date('H:i',strtotime($if_has_wfh->date_from))}}</td>
                    <td>{{date('H:i',strtotime($if_has_wfh->date_to))}}</td>
                    <td>{{ $wfh_diff->h }} hrs. {{ $wfh_diff->i }} mins. </td>
                    <td>{{ $if_has_wfh->approve_percentage ? 'WFH-' . $if_has_wfh->approve_percentage .'%' : "Work From Home"}}</td>
                @else
                    {{-- Time In --}}
                    @if($time_in || $if_has_dtr)
                        <td>
                            @if($dtr_correction_time_in)
                                {{date('H:i',strtotime($dtr_correction_time_in))}}
                            @else
                                @if($time_in)
                                    {{date('H:i',strtotime($time_in->time_in))}}
                                @endif  
                            @endif  
                        </td>
                        {{-- Time out --}}
                        @if($dtr_correction_time_out)
                            <td>{{date('H:i',strtotime($dtr_correction_time_out))}}</td>
                        @else
                            @if($time_in)
                                @if($time_in->time_out)
                                    <td>{{date('H:i',strtotime($time_in->time_out))}}</td>
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
                                            {{date('H:i',strtotime($time_out->time_out))}}
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
                                <td>{{date('H:i',strtotime($dtr_correction_time_out))}}</td>
                            @else
                                @php
                                $time_in_out = 1;
                                @endphp
                                <td class='bg-warning'></td>
                                @if($time_in)
                                    @if($time_in->time_out)
                                    <td>
                                        {{date('H:i',strtotime($time_in->time_out))}}
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
                                            {{date('H:i',strtotime($time_out->time_out))}}
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
                    @endif

                    {{-- Remarks --}}
                    <td>
                        @if($check_if_early_cutoff)
                            @if($employee_schedule)
                                {{$check_if_early_cutoff}}
                            @endif
                        @else
                            @if($time_in == null)
                                @if($employee_schedule)
                                    @php 
                                        $is_absent = '';
                                        $if_leave = '';
                                        $if_attendance_holiday = '';
                                        $if_attendance_holiday_status = '';
                                        if($check_if_holiday){
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
                                                }
                                                else{
                                                    $check_attendance = checkHasAttendanceHolidayStatus($emp->attendances,$if_attendance_holiday);

                                                    if(empty($check_attendance)){
                                                        $is_absent = 'Absent';
                                                    }else{
                                                        $if_attendance_holiday_status = 'With-Pay';
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
                                    // if($time_out_data == null){
                                    //     $is_absent = 'Absent';
                                    // }
                                    
                                    $if_leave = employeeHasLeave($emp->approved_leaves,date('Y-m-d',strtotime($date_r)),$employee_schedule);

                                @endphp  
                                {{$if_leave}}
                                {{$if_dtr_correction}}
                                {{$is_absent}}
                                
                            @endif
                        @endif
                    </td>

                    <td>
                        @php
                            $date_description = '';
                            if($check_if_holiday){
                                if($check_if_holiday == 'Legal Holiday'){
                                    $date_description = 'Regular Holiday';
                                }
                                else {
                                    $date_description = $check_if_holiday;
                                }
                            }
                        @endphp
                        {{$date_description}}
                    </td>
                    
                @endif
            </tr>
            @endforeach
    </tbody>
    @endforeach
</table>