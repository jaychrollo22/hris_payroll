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
                            <form method='get' onsubmit='show();' enctype="multipart/form-data">
                                <div class=row>
                                    <div class='col-md-3'>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label text-right">Company</label>
                                            <div class="col-sm-8">
                                                <select data-placeholder="Select Company" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='company' required>
                                                    <option value="">-- Select Employee --</option>
                                                    @foreach($companies as $comp)
                                                    <option value="{{$comp->id}}" @if ($comp->id == $company) selected @endif>{{$comp->company_name}} - {{$comp->company_code}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col-md-3'>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label text-right">From</label>
                                            <div class="col-sm-8">
                                                <input type="date" value='{{$from_date}}' class="form-control" name="from" max='{{date('d/m/Y')}}' onchange='get_min(this.value);' required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col-md-3'>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label text-right">To</label>
                                            <div class="col-sm-8">
                                                <input type="date" value='{{$to_date}}' class="form-control" name="to" id='to' max='{{date('d/m/Y')}}' required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col-md-3'>
                                        <button type="submit" class="btn btn-primary mb-2">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </p>
                        @if($date_range)
                        {{-- <button class='btn btn-info mb-1' onclick="exportTableToExcel('employee_attendance','{{$from_date}} - {{$to_date}}')">Export</button> --}}
                        <a href="attendance-per-company-export?company={{$company}}&from={{$from_date}}&to={{$to_date}}" class='btn btn-info mb-1'>Export</a>
                        @endif


                        <div class="table-responsive">

                            <table border="1" class="table table-hover table-bordered employee_attendance" id='employee_attendance'>

                                <thead>
                                    {{-- <tr>
                                        <td colspan='5'>{{$emp->emp_code}} - {{$emp->first_name}} {{$emp->last_name}}</td>
                                    </tr> --}}
                                    <tr>
                                        <td>User ID</td>
                                        {{-- <td>Biometric ID</td> --}}
                                        <td>Name</td>
                                        <td>Date</td>
                                        <td>Time In</td>
                                        <td>Time Out</td>
                                        <td>Work </td>
                                        <td>Remarks </td>

                                        {{-- <td>Lates </td>
                                        <td>Undertime</td>
                                        <td>Overtime</td>
                                        <td>Approved Overtime</td>
                                        <td>Night Diff</td>
                                        <td>OT Night Diff</td>
                                        <td>Remarks</th> --}}

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
                                    <tr>
                                        <td>{{$emp->employee_number}}</td>
                                        {{-- <td>{{$emp->employee_number}}</td> --}}
                                        <td>{{$emp->first_name . ' ' . $emp->last_name}}</td>
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
                                                <td class='bg-warning'></td>
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
                                        {{-- <td></td> --}}
                                        {{-- <td></td> --}}
                                        {{-- <td></td> --}}
                                        {{-- <td></td>
                                        <td></td>
                                        <td>
                                        </td>
                                        <td>
                                        </td> --}}
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
                                                {{-- @php
                                                $id = array_search(date('l',strtotime($date_r)),$schedules->pluck('name')->toArray());
                                                $late = (strtotime(date("01-01-2022 h:i",strtotime($time_in_data))) - strtotime(date("01-01-2022 h:i",strtotime("01-01-2022 ".$schedules[$id]->time_in_to))))/60;
                                                $working_minutes = (((strtotime($time_in->time_out) - strtotime($time_in_data)))/3600);
                                                $overtime = number_format($working_minutes - $schedules[$id]->working_hours,2);
                                                if($late > 0)
                                                {
                                                $late_data = $late;
                                                }
                                                else {
                                                $late_data = 0;

                                                }
                                                $undertime = number_format($working_minutes - $schedules[$id]->working_hours + ($late_data/60),2);
                                                @endphp --}}

                                                {{-- <td>
                                                    {{number_format($late_data/60,2)}} hrs
                                                @php
                                                $lates = $lates+ round($late_data/60,2);
                                                @endphp
                                                </td>
                                                <td>
                                                    @if($undertime < 0) {{number_format(($undertime*60*-1)/60,2)}} hrs @php $undertimes=$undertimes + round(($undertime*60*-1)/60,2); @endphp @else 0 hrs @endif </td>
                                                <td>
                                                    @if($overtime > .5)
                                                    {{$overtime}} hrs
                                                    @php
                                                    $overtimes = $overtimes +round($overtime,2);
                                                    @endphp
                                                    @else
                                                    0 hrs
                                                    @endif
                                                </td>
                                                <td>0 hrs</td>
                                                <td>
                                                    0 hrs
                                                </td>
                                                <td>
                                                    @php
                                                    $night_diff_ot = $night_diff_ot + round(night_difference(strtotime($time_in_data),strtotime($time_in->time_out)),2);
                                                    echo round(night_difference(strtotime($time_in_data),strtotime($time_in->time_out)),2)." hrs";
                                                    @endphp

                                                </td>
                                                <td>
                                                    <small>Time In : {{$time_in->device_in}} <br>
                                                        Time Out : {{$time_in->device_out}}</small>
                                                </td> --}}
                                                @else
                                                {{-- <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td> --}}

                                            @endif
                                        @endif
                                        <td>
                                            @if($time_in == null)
                                                @if((date('l',strtotime($date_r)) == "Saturday") || (date('l',strtotime($date_r)) == "Sunday"))
                                                    
                                                @else
                                                    @php 
                                                        $if_leave = employeeHasLeave($emp->leaves,date('Y-m-d',strtotime($date_r)));
                                                        $if_ob = employeeHasOB($emp->obs,date('Y-m-d',strtotime($date_r)));
                                                        $if_wfh = employeeHasWFH($emp->wfhs,date('Y-m-d',strtotime($date_r)));
                                                    @endphp
                                                    {{$if_leave}}
                                                    {{$if_ob}}
                                                    {{$if_wfh}}
                                                @endif
                                            @endif
                                        </td>

                                    </tr>
                                    @endforeach
                                    {{-- <tr> --}}
                                        {{-- <td colspan='3'>Total</td>
                                        <td >{{$work}} hrs</td>
                                        <td>{{$lates}} hrs</td>
                                        <td>{{$undertimes}} hrs </td>
                                        <td>{{$overtimes}} hrs</td>
                                        <td>0 hrs</td>
                                        <td>0 hrs</td>
                                        <td>{{$night_diff_ot}} hrs</td>
                                        <td></td> --}}
                                    {{-- </tr> --}}
                                    {{-- <tr>
                                        <td colspan='11'>&nbsp;</td>
                                    </tr> --}}
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
$end_night = mktime('06','00','00',date('m',$start_work),date('d',$start_work) + 1,date('Y',$start_work));

if($start_work >= $start_night && $start_work <= $end_night) { if($end_work>= $end_night)
    {
    return ($end_night - $start_work) / 3600;
    }
    else
    {
    return ($end_work - $start_work) / 3600;
    }
    }
    elseif($end_work >= $start_night && $end_work <= $end_night) { if($start_work <=$start_night) { return ($end_work - $start_night) / 3600; } else { return ($end_work - $start_work) / 3600; } } else { if($start_work < $start_night && $end_work> $end_night)
        {
        return ($end_night - $start_night) / 3600;
        }
        return 0;
        }
        }

        @endphp
        <script>
            function get_min(value) {
                document.getElementById("to").min = value;
            }

        </script>
        @endsection
