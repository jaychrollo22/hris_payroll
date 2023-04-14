<table border="1" class="table table-hover table-bordered employee_attendance" id='employee_attendance'>
    <thead>
        <tr>
            <td>USER ID</td>
            {{-- <td>Biometric ID</td> --}}
            <td>NAME</td>
            <td>DATE</td>
            <td>FIRST ACTUAL TIME IN</td>
            <td>SECOND ACTUAL TIME OUT</td>
            <td>WORK</td>
            <td>REMARKS</td>
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
        <tr>
            <td>{{$emp->employee_number}}</td>
            {{-- <td>{{$emp->employee_number}}</td> --}}
            <td>{{$emp->first_name . ' ' . $emp->last_name}}</td>
            <td class="@if(in_array(date('l',strtotime($date_r)),$schedules->pluck('name')->toArray())) @else bg-danger text-white @endif">{{date('d/m/Y',strtotime($date_r))}}</td>

            @php
                $if_has_ob = employeeHasOBDetails($emp->approved_obs,date('Y-m-d',strtotime($date_r)));
                $if_has_wfh = employeeHasWFHDetails($emp->approved_wfhs,date('Y-m-d',strtotime($date_r)));
            @endphp
            @if($if_has_ob)
                @php
                    $ob_start = new DateTime($if_has_ob->date_from); 
                    $ob_diff = $ob_start->diff(new DateTime($if_has_ob->date_to)); 
                @endphp
                <td>{{$if_has_ob->date_from}}</td>
                <td>{{$if_has_ob->date_to}}</td>
                <td>{{ $ob_diff->h }} hrs. {{ $ob_diff->i }} mins. </td>
                <td></td>
            @elseif($if_has_wfh)
                @php
                    $wfh_start = new DateTime($if_has_wfh->date_from); 
                    $wfh_diff = $wfh_start->diff(new DateTime($if_has_wfh->date_to)); 
                @endphp
                <td>{{$if_has_wfh->date_from}}</td>
                <td>{{$if_has_wfh->date_to}}</td>
                <td>{{ $wfh_diff->h }} hrs. {{ $wfh_diff->i }} mins. </td>
                <td></td>
            @else
                {{-- Time In --}}
                @php
                    $time_in_out = 0;
                    $time_in = ($emp->attendances)->whereBetween('time_in',[$date_r." 00:00:00", $date_r." 23:59:59"])->first();
                    $time_out = null;
                    if($time_in == null)
                    {
                        $time_out = ($emp->attendances)->whereBetween('time_out',[$date_r." 00:00:00", $date_r." 23:59:59"])->where('time_in',null)->first();
                    }
                @endphp
                @if($time_in != null)
                        <td>
                            {{date('h:i A',strtotime($time_in->time_in))}}
                        </td>
                        @if($time_in->time_out != null)
                            <td>
                                {{date('h:i A',strtotime($time_in->time_out))}}
                            </td>
                        @else
                            @php
                            $time_in_out = 1;
                            @endphp
                            <td class='bg-warning'>
                            </td>
                            @endif
                        @else
                        @if((date('l',strtotime($date_r)) == "Saturday") || (date('l',strtotime($date_r)) == "Sunday"))
                            <td></td>
                            <td></td>
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
                                {{ $diff->h }} hrs. {{ $diff->i }} mins. 
                                @php
                                $work =  round((((strtotime($time_in->time_out) - strtotime($time_in_data)))/3600),2);
                                @endphp
                            @endif
                        @endif
                    </td>
                @endif
                {{-- Remarks --}}
                <td>
                    @if($time_in == null)
                        @if((date('l',strtotime($date_r)) == "Saturday") || (date('l',strtotime($date_r)) == "Sunday"))
                            
                        @else
                            @php 
                                $if_leave = employeeHasLeave($emp->leaves,date('Y-m-d',strtotime($date_r)));
                                $is_absent = '';
                                if(empty($if_leave)){
                                    $is_absent = 'Absent';
                                }        
                            @endphp
                            {{$is_absent}}
                        @endif
                    @endif
                </td>
            @endif
        </tr>
        @endforeach
    </tbody>
    @endforeach
</table>