@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Schedules</h4>
                <p class="card-description">
                  <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#newSchedule">
                    <i class="ti-plus btn-icon-prepend"></i>                                                    
                    Add new schedule
                  </button>
                </p>
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                      <tr>
                        <th>Schedule Name</th>
                        <th>Sunday</th> 
                        <th>Monday</th> 
                        <th>Tuesday</th> 
                        <th>Wednesday</th> 
                        <th>Thursday</th> 
                        <th>Friday</th> 
                        <th>Saturday</th> 
                      </tr>
                    </thead>
                    <tbody>
                  
                        @foreach($schedules as $schedule)
                        <tr class='cursor-pointer' data-toggle="modal" data-target="#editSchedule{{$schedule->id}}">
                            <td>{{$schedule->schedule_name}}</td>
                            <td>
                               <small> {{set_data_final("Sunday",$schedule)}} </small>
                            </td> 
                            <td>
                                <small>  {{set_data_final("Monday",$schedule)}} </small>
                            </td> 
                            <td>
                                <small>  {{set_data_final("Tuesday",$schedule)}} </small>
                            </td> 
                            <td>
                                <small>  {{set_data_final("Wednesday",$schedule)}} </small>
                            </td>
                            <td>
                                <small>  {{set_data_final("Thursday",$schedule)}} </small>
                            </td>
                            <td>
                                <small>  {{set_data_final("Friday",$schedule)}} </small>
                            </td> 
                            <td>
                                <small>   {{set_data_final("Saturday",$schedule)}} </small>
                            </td>
                          </tr>
                          
                        @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          @foreach($schedules as $schedule)
            @include('schedules.edit_schedule')
          @endforeach
          @include('schedules.new_schedule')
          @php
            function set_data_final($data,$schedule)
                {
                    $dataperDay = $schedule->ScheduleData->where('name',$data)->first();
                    if($dataperDay == null)
                    {
                        echo "REST DAY";
                    }
                    else 
                    {
                        echo "Start Time : ".date('h:i a',strtotime($dataperDay->time_in_from))." - ".date('h:i a',strtotime($dataperDay->time_in_to))."<br>";
                        echo "End Time : ".date('h:i a',strtotime($dataperDay->time_out_from))." - ".date('h:i a',strtotime($dataperDay->time_out_to))."<br>";
                        echo "Working Hours : ".number_format($dataperDay->working_hours,1)." hrs<br>";
                    }
                }
            @endphp
        </div>
    </div>
</div>
@endsection
