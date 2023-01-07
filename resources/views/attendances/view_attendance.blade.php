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
                          <input type="date" value='{{$to_date}}'  class="form-control"  id='to' name="to" max='{{date('Y-m-d')}}' required/>
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
                  <table class="table table-hover table-bordered">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Work </th>
                        <th>Lates </th>
                        <th>Undertime</th>
                        <th>Overtime</th>
                        <th>Approved Overtime</th>
                        <th>Night Diff</th>
                        <th>OT Night Diff</th>
                        <th>Remarks</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php
                          $work =0;
                          $lates =0;
                          $undertimes =0;
                          $overtimes =0;
                          $approved_overtimes =0;
                          $night_diffs =0;
                          $ot_night_diffs =0;
                          $night_diff_ot =0;
                      @endphp
                        @foreach(array_reverse($date_range) as $date_r)
                        <tr>
                          {{-- {{dd($schedules->pluck('name'))}} --}}
                          <td class="@if(in_array(date('l',strtotime($date_r)),$schedules->pluck('name')->toArray())) @else bg-danger text-white @endif">{{date('M d - l',strtotime($date_r))}}</td>
                          @php

                            $time_in_out = 0;
                            $time_in = $attendances->whereBetween('time_in',[$date_r." 00:00:00", $date_r." 23:59:59"])->first();
                            $time_out = null;
                            if($time_in == null)
                            {
                              $time_out = $attendances->whereBetween('time_out',[$date_r." 00:00:00", $date_r." 23:59:59"])->where('time_in',null)->first();
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
                            <td >
                              {{date('h:i A',strtotime($time_out->time_out))}} 
                            </td>
                          @endif
                         
                          @endif
                        @endif
                            @if($time_in_out == 1)
                              <td></td>
                              <td> </td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td>
                              </td>
                              <td>
                              </td>
                            @else
                              <td>@if($time_in != null)
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
                                      @endphp
                                      {{round((((strtotime($time_in->time_out) - strtotime($time_in_data)))/3600),2)}} hrs 
                                      @php
                                           $work = $work + round((((strtotime($time_in->time_out) - strtotime($time_in_data)))/3600),2);
                                      @endphp
                                    @endif
                                  @endif
                              </td>
                         
                                  @if(in_array(date('l',strtotime($date_r)),$schedules->pluck('name')->toArray()))
                                      @php
                                          $id = array_search(date('l',strtotime($date_r)),$schedules->pluck('name')->toArray());
                                          $late = (strtotime(date("2022-01-01 h:i",strtotime($time_in_data)))  - strtotime(date("2022-01-01 h:i",strtotime("2022-01-01 ".$schedules[$id]->time_in_to))))/60;
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
                                      @endphp
                                      {{-- {{strtotime("2022-01-01 ".$schedules[$id]->time_in_to)}} <br>
                                      {{strtotime(date("2022-01-01 h:i".$time_in->time_in))}} <br> --}}
                                      <td>
                                        {{number_format($late_data/60,2)}} hrs
                                        @php
                                            $lates = $lates+ round($late_data/60,2);
                                        @endphp
                                    </td>
                                    <td>
                                      @if($undertime < 0)
                                       {{number_format(($undertime*60*-1)/60,2)}} hrs
                                       @php
                                           $undertimes = $undertimes + round(($undertime*60*-1)/60,2);
                                       @endphp
                                      @else
                                       0 hrs
                                      @endif
                                    </td>
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
                                         $night_diff_ot =  $night_diff_ot +  round(night_difference(strtotime($time_in_data),strtotime($time_in->time_out)),2);
                                        echo  round(night_difference(strtotime($time_in_data),strtotime($time_in->time_out)),2)." hrs";
                                      @endphp

                                      </td>
                                      <td>
                                        <small>Time In : {{$time_in->device_in}} <br>
                                        Time Out : {{$time_in->device_out}}</small>
                                      </td>
                                  @else
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  
                                  @endif
                            @endif
                        </tr>
                        @endforeach
                        <tr>
                          <td colspan='3'>Total</td>
                          <td >{{$work}} hrs</td>
                          <td >{{$lates}} hrs</td>
                          <td >{{$undertimes}} hrs </td>
                          <td >{{$overtimes}} hrs</td>
                          <td >0 hrs</td>
                          <td >0 hrs</td>
                          <td >0 hrs</td>
                          <td ></td>
                        </tr>
                    </tbody>
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
