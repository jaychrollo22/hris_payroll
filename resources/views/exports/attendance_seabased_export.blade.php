
<table border="1" class="table table-hover table-bordered employee_seabased_attendance" id='employee_seabased_attendance'>
    <thead>
        <tr>
            <td>Employee Code</td>
            <td>Name</td>
            <td>Attendance Date</td>
            <td>Time In</td>
            <td>Time Out</td>
            <td>Shift</td>
            <td>Working Hours</td>
            <td>Night Diff</td>
            <td>Uploaded Date</td>
        </tr>
    </thead>
    <tbody>
        @if(count($attendances) > 0)
            @foreach($attendances as $attendance)
                <tr>
                    <td>{{$attendance->employee_code}}</td>
                    <td>@if($attendance->employee){{$attendance->employee->first_name}} {{$attendance->employee->last_name}}@endif</td>
                    <td>{{date('Y-m-d',strtotime($attendance->attendance_date))}}</td>
                    <td>{{date('Y-m-d h:i A',strtotime($attendance->time_in))}}</td>
                    <td>{{date('Y-m-d h:i A',strtotime($attendance->time_out))}}</td>
                    <td>{{$attendance->shift}}</td>
                    <td>
                    @php
                        $working_hours_start = new DateTime($attendance->time_in); 
                        $working_hours_diff = $working_hours_start->diff(new DateTime($attendance->time_out)); 
                    @endphp
                    {{ $working_hours_diff->h }} hrs. {{ $working_hours_diff->i }} mins.
                    </td>
                    <td>
                    @php
                        echo round(night_difference(strtotime($attendance->time_in),strtotime($attendance->time_out)),2)." hrs";
                    @endphp
                    </td>
                    <td>{{date('Y-m-d h:i A',strtotime($attendance->created_at))}}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
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
