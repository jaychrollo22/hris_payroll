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

        @foreach($date_range as $date_r)
        @php
            $employee_schedule = employeeSchedule($schedules,$date_r,$emp->schedule_id);
        @endphp
        <tr>
            <td>{{$emp->employee_number}}</td>
            {{-- <td>{{$emp->first_name . ' ' . $emp->last_name}}</td> --}}
            <td class="@if($employee_schedule) @else bg-danger text-white @endif">{{date('d/m/Y',strtotime($date_r))}}</td>

            @php
                $if_has_ob = employeeHasOBDetails($emp->approved_obs,date('Y-m-d',strtotime($date_r)));
                $if_has_wfh = employeeHasWFHDetails($emp->approved_wfhs,date('Y-m-d',strtotime($date_r)));
                $if_has_dtr = employeeHasDTRDetails($emp->approved_dtrs,date('Y-m-d',strtotime($date_r)));
            @endphp
            @if($if_has_ob)
                @php
                    $ob_start = new DateTime($if_has_ob->date_from); 
                    $ob_diff = $ob_start->diff(new DateTime($if_has_ob->date_to)); 
                @endphp
                <td>{{date('h:i A',strtotime($if_has_ob->date_from))}}</td>
                <td>{{date('h:i A',strtotime($if_has_ob->date_to)) }}</td>
                <td>{{ $ob_diff->h }} hrs. {{ $ob_diff->i }} mins. </td>
                <td>OB</td>
            @elseif($if_has_wfh)
                @php
                    $wfh_start = new DateTime($if_has_wfh->date_from); 
                    $wfh_diff = $wfh_start->diff(new DateTime($if_has_wfh->date_to)); 
                @endphp
                <td>{{date('h:i A',strtotime($if_has_wfh->date_from))}}</td>
                <td>{{date('h:i A',strtotime($if_has_wfh->date_to))}}</td>
                <td>{{ $wfh_diff->h }} hrs. {{ $wfh_diff->i }} mins. </td>
                <td>{{ $if_has_wfh->approve_percentage ? 'Work from Home ' . $if_has_wfh->approve_percentage .'%' : "WFH"}}</td>
            @else
                @php
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
                        $dtr_correction_both = $if_has_dtr->correction == 'Both'  ? $if_has_dtr : "";
                    }
                @endphp
                
                @if($dtr_correction_both)
                    @php
                        $dtr_start = new DateTime($if_has_dtr->time_in); 
                        $dtr_diff = $dtr_start->diff(new DateTime($if_has_dtr->time_out)); 
                    @endphp
                    <td>{{date('h:i A',strtotime($if_has_dtr->time_in))}}</td>
                    <td>{{date('h:i A',strtotime($if_has_dtr->time_out))}}</td>
                    <td>{{ $dtr_diff->h }} hrs. {{ $dtr_diff->i }} mins.</td>
                    <td>DTR Correction</td>
                @else
                
                    @if($dtr_correction_time_in)
                        <td>{{date('h:i A',strtotime($dtr_correction_time_in))}}</td>
                    @else
                        {{-- Time In --}}
                        @if($time_in != null)
                        <td>
                            {{date('h:i A',strtotime($time_in->time_in))}}
                        </td>
                        @if($time_in->time_out != null)
                            <td>
                                {{date('h:i A',strtotime($time_in->time_out))}}
                            </td>
                        @else
                            @if($dtr_correction_time_out)
                                <td>{{date('h:i A',strtotime($dtr_correction_time_out))}}</td>
                            @else
                                @php
                                    $time_in_out = 1;
                                @endphp
                                <td class='bg-warning'></td>
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
                                <td class='bg-warning'>

                                </td>
                                @if($time_out == null)
                                        @php
                                        $time_in_out = 1;
                                        @endphp
                                    <td class='bg-warning'>

                                    </td>
                                @else
                                    <td>
                                        {{date('h:i A',strtotime($time_out->time_out))}}
                                    </td>
                                @endif
                            @endif

                            @endif
                        @endif
                    @endif
                    
                    
                    {{-- Time Out --}}
                    @if($time_in_out == 1)
                    <td></td>
                    @else
                        <td>
                            @if($time_in != null)
                                @if($time_in->time_out != null)
                                    @php
                                        if(strtotime(date('H:i:00',strtotime($time_in->time_in))) >= strtotime("07:00:00"))
                                        {
                                        $time_in_data = $time_in->time_in;
                                        }
                                        else
                                        {
                                        $time_in_data = date('Y-m-d 07:00:00',strtotime($time_in->time_in));
                                        }
                                        $start_datetime = new DateTime($time_in_data); 
                                        $diff = $start_datetime->diff(new DateTime($time_in->time_out)); 
                                    @endphp
                                    
                                    {{-- {{round((((strtotime($time_in->time_out) - strtotime($time_in_data)))/3600),2)}} hrs <br> --}}
                                    {{ $diff->h }} hrs. {{ $diff->i }} mins. 
                                    @php
                                    
                                    // $work = $work + round((((strtotime($time_in->time_out) - strtotime($time_in_data)))/3600),2);
                                    $work =  round((((strtotime($time_in->time_out) - strtotime($time_in_data)))/3600),2);
                                    @endphp
                                @endif
                            @endif
                        </td>

                        @if(in_array(date('l',strtotime($date_r)),$schedules->pluck('name')->toArray()))
                            
                            @else
                            

                        @endif
                    @endif
                
                    {{-- Remarks --}}
                    <td>
                        @php
                            $check_if_holiday = checkIfHoliday(date('Y-m-d',strtotime($date_r)),$emp->location);
                        @endphp
                        @if($time_in == null)
                            @if($employee_schedule)
                                @php 
                                    $is_absent = '';
                                    $if_leave = '';
                                    $if_attendance_holiday = '';
                                    $check_if_holiday = checkIfHoliday(date('Y-m-d',strtotime($date_r)),$emp->location);
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
                                {{$if_attendance_holiday_status}}
                            @endif
                        @else
                            @php
                                //CWTOAM
                                $time_out_status = '';
                                if($time_in->time_out){
                                    $time_out_default = date('Y-m-d H:i:s',strtotime($time_in->time_out));
                                    $date_time_in = date('Y-m-d',strtotime($time_in->time_in));
                                    $date_time_in_validator = $date_time_in . ' 24:00:00';
                                    if($time_out_default > $date_time_in_validator){
                                        $time_out_status = 'CWTOAM';
                                    }
                                }elseif($dtr_correction_time_out){
                                    $time_out_default = date('Y-m-d H:i:s',strtotime($dtr_correction_time_out));
                                    $date_time_in = date('Y-m-d',strtotime($time_in->time_in));
                                    $date_time_in_validator = $date_time_in . ' 24:00:00';
                                    if($time_out_default > $date_time_in_validator){
                                        $time_out_status = 'CWTOAM';
                                    }
                                }

                                //HDAM
                                $compressed_work_with_saturday = '';
                                if((date('l',strtotime($date_r)) == "Saturday")){ // if Compressed Work (With Saturday)
                                    if($emp->schedule_id == '9'){
                                        $compressed_work_with_saturday = 'HDAM';
                                    }   
                                    
                                }
                            @endphp
                            {{$time_out_status}}
                            {{$compressed_work_with_saturday}}
                        @endif
                    </td>

                    {{-- Date Description --}}
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
            @endif
        </tr>
        @endforeach
    </tbody>
    @endforeach
</table>