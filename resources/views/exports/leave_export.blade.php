<table>
    <thead>
    <tr>
        <th>USER ID</th>
        {{-- <th>NAME</th> --}}
        <th>DATE</th>
        <th>PARTICULAR</th>
        <th>DESCRIPTION</th>
    </tr>
    </thead>
       
        @foreach($leaves as $leave)
            @php
                $date_range = dateRangeHelper($leave->date_from,$leave->date_to);
            @endphp
            @if($date_range)
            <tbody>
                @foreach($date_range as $date_r)
                <tr>
                    <td>{{$leave->employee->employee_number}}</td>
                    {{-- <td>{{$leave->employee->first_name . ' ' . $leave->employee->last_name}}</td> --}}
                    <td>{{date('d/m/Y',strtotime($date_r))}}</td>
                    <td>{{$leave->leave->leave_type}}</td>
                    <td>
                        {{-- {{$leave->withpay == 1 ? 'With-Pay' : 'Without-Pay'}} --}}
                        @php
                            $remarks = '';   
                            if($leave->withpay == '1'){
                                $remarks = 'Full-Pay';
                                if($leave->halfday == '1'){
                                    if($leave->halfday_status == 'First Shift'){
                                        $remarks = 'Half-Pay (FS)';
                                    }
                                    else if($leave->halfday_status == 'Second Shift'){
                                        $remarks = 'Half-Pay (SS)';
                                    }
                                }
                            }else{
                                $remarks = 'Without-Pay';
                            }
                        @endphp
                        {{$remarks}}
                    </td>
                </tr>
                @endforeach
            </tbody>
            @endif
        @endforeach
</table>