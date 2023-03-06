<table border="1" class="table table-hover table-bordered employee_attendance" id='employee_attendance'>
    <thead>
        <tr>
            <td>User ID</td>
            <td>Biometric ID</td>
            <td>Name</td>
            <td>Date</td>
            <td>Time In</td>
            <td>Time Out</td>
            <td>Work </td>
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
            <td>{{$emp->employee->user_id}}</td>
            <td>{{$emp->emp_code}}</td>
            <td>{{$emp->employee->first_name . ' ' . $emp->employee->last_name}}</td>
            <td class="@if(in_array(date('l',strtotime($date_r)),$schedules->pluck('name')->toArray())) @else bg-danger text-white @endif">{{date('d/m/Y',strtotime($date_r))}}</td>

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
        </tr>
        @endforeach
    </tbody>
    @endforeach
</table>