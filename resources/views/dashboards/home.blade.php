@extends('layouts.header')
@section('css_header')
<link rel="stylesheet" href="{{asset('./body_css/vendors/fullcalendar/fullcalendar.min.css')}}">
@endsection
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                  <h3 class="font-weight-bold">Welcome {{auth()->user()->employee->first_name}},</h3>
                </div>
              </div>
            </div>
        </div>
          <div class="row">
            <div class="col-md-9  transparent">
                <div class="row">
                    <div class="col-md-4 mb-4 transparent">
                        <div class="card">
                          <div class="card-body">
                            <h3 class="card-title">{{date('M d, Y')}} </h3>
                            <div class="media">
                                <i class="ti-time icon-md text-info d-flex align-self-center mr-3"></i>
                                <div class="media-body">
                                  <p class="card-text">Time In : 
                                    @if($attendance_now != null){{date('h:i A',strtotime($attendance_now->time_in))}} <br>
                                    @php
                                          $employee_schedule = employeeSchedule($schedules,$attendance_now->time_in,$schedules[0]->schedule_id);
                                          $estimated_out = "";
                                          if($employee_schedule != null)
                                          {
                                            if(date('h:i A',strtotime($attendance_now->time_in)) < date('h:i A',strtotime($employee_schedule['time_in_from'])))
                                            {
                                                $estimated_out = date('h:i A',strtotime($employee_schedule['time_out_from']));
                                            }
                                            else
                                            {
                                                $hours = intval($employee_schedule['working_hours']);
                                                $minutes = ($employee_schedule['working_hours']-$hours)*60;
                                                $estimated_out = date('h:i A', strtotime("+".$hours." hours",strtotime($attendance_now->time_in)));
                                                $estimated_out = date('h:i A', strtotime("+".$minutes." minutes",strtotime($estimated_out)));
                                            }
                                            if(date('h:i A',strtotime($attendance_now->time_in)) > date('h:i A',strtotime($employee_schedule['time_in_to'])))
                                            {
                                                $estimated_out = date('h:i A',strtotime($employee_schedule['time_out_to']));
                                            }

                                          }
                                          else {
                                            $estimated_out = "No Schedule";
                                          }
                                          
                                        @endphp
                                    @if($attendance_now->time_out == null )
                                        
                                        Estimated Out : {{$estimated_out}} 
                                     @else
                                     Time Out : {{date('h:i A',strtotime($attendance_now->time_out))}} <br>
                                     Estimated Out : {{$estimated_out}} 
                                     @endif
                                   @else NO TIME IN 
                                   @endif</p>
                                   {{-- <button type="button" class="btn btn-outline-danger btn-fw btn-sm">Time Out</button> --}}
                                </div>
                              </div>
                          </div>
                        </div>
                        @if(count(auth()->user()->subbordinates) > 0)
                        <div class="card mt-2">
                          <div class="card-body">
                            <p class="card-title ">Subordinates </p>
                              <div class="table-responsive" >
                                <table class="table table-hover table-bordered tablewithSearchonly" >
                                  <thead>
                                    <tr>
                                      <th>Name</th>
                                      <th>In</th>
                                      <th>Out</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                      
                                    @foreach(auth()->user()->subbordinates as $emp)
                                    <tr>
                                      <td>{{$emp->first_name}} {{$emp->last_name}} </td>
                                      @php
                                          // dd($attendance_employees);
                                          $time_in = $attendance_employees->where('employee_code',$emp->employee_number)->where('time_in','!=',null)->first();
                                      @endphp
                                      <td>@if($time_in){{date('h:i A',strtotime($time_in->time_in))}}@endif</td>
                                      <td>@if($time_in) @if($time_in->time_out){{date('h:i a',strtotime($time_in->time_out))}} @endif @endif</td>
                                    </tr>
                                    @endforeach
                    
                                  </tbody>
                              </table>
                              </div>
                          </div>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-5 ">
                      <div class="card">
                        <div class="card-body">
                          <p class="card-title mb-0">(<small><i>{{date('M 01')}} - {{date('M t')}}</i></small>)</p>
                          <div class="table-responsive">
                            <table class="table table-striped table-borderless">
                              <thead>
                                <tr>
                                  <th>Holiday</th>
                                  <th>Date</th>
                                </tr>  
                              </thead>
                              <tbody>
                                @foreach($holidays as $holiday)
                                <tr>
                                  <td>{{$holiday->holiday_name}}</td>
                                  <td class="font-weight-medium"><div class="badge badge-success">{{date('M d',strtotime($holiday->holiday_date))}}</div></td>
                                </tr>
                                @endforeach
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      
                    </div>
                </div>
            </div>
            <div class="col-md-3 ">
              <div class='row'>
                <div class="col-md-12">
                  <div class="card mt-2">
                    <div class="card-body " style="overflow-y: scroll; height:400px;">
                      <p class="card-title">Welcome new Hires</p>
                      <ul class="icon-data-list" >
                        @foreach($employees_new_hire as $employee)
                        <li>
                          <div class="d-flex">
                            <img src="{{URL::asset($employee->avatar)}}"  onerror="this.src='{{URL::asset('/images/no_image.png')}}';" alt="user">
                            <div>
                              <p class="text-info mb-1"><small>{{$employee->first_name}} {{$employee->last_name}}</small></p>
                              <p class="mb-0"><small>{{$employee->company->company_name}}</small></p>
                              <p class="mb-0"><small>{{$employee->position}}</small></p>
                              <small>{{date('M. d',strtotime($employee->original_date_hired))}}</small>
                            </div>
                          </div>
                        </li>
                        @endforeach
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class='row'>
                <div class="col-md-12">
                  <div class="card mt-2">
                    <div class="card-body " style="overflow-y: scroll; height:400px;">
                      <p class="card-title">Birthday Celebrants</p>
                      <ul class="icon-data-list" >
                        @foreach($employee_birthday_celebrants as $celebrant)
                        <li>
                          <div class="d-flex">
                            <img src="{{URL::asset($celebrant->avatar)}}"  onerror="this.src='{{URL::asset('/images/no_image.png')}}';" alt="user">
                            <div>
                              <p class="text-info mb-1"><small>{{$celebrant->first_name}} {{$celebrant->last_name}}</small></p>
                              <small>{{date('M d',strtotime($celebrant->birth_date))}}</small>
                            </div>
                          </div>
                        </li>
                        @endforeach
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>    
          <div class='row'>
       
          </div>
    </div>
</div>
<div class="modal fade" id="event_data" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         Description : <span id='modalBody'>
        </span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection
@section('footer')
<script>
      var holidays = {!! json_encode($holidays->toArray()) !!};
      var celebrants = {!! json_encode($birth_date_celebrants->toArray()) !!};
      const d = new Date();
      let year = d.getFullYear();
      var data_holidays = [];
      for(i=0;i<holidays.length;i++)
      {

        var hol_date = new Date(holidays[i].holiday_date);
        var month = hol_date.getUTCMonth() + 1; //months from 1-12
        if(month < 10)
        {
          month = "0"+month;
        }
       
        var day = hol_date.getUTCDate();
        if(day < 10)
        {
          day = "0"+day;
        }
        var data = {};
            data.title = holidays[i].holiday_name;
            data.start = year + "-"+month+"-"+day;
            data.type = holidays[i].holiday_type;
            data.color = '#257e4a';
            if(holidays[i].holiday_type == "Special Holiday")
            {
              data.color = '#ff6600';
            }
            data.imageurl = 'images/1666674015_1661130183_icon.png';
            data_holidays.push(data);
      }
      for(ii=0;ii<celebrants.length;ii++)
      {
        var birth_date = new Date(celebrants[ii].birth_date);
        var month = birth_date.getUTCMonth() + 1; //months from 1-12
        if(month < 10)
        {
          month = "0"+month;
        }
       
        var day = birth_date.getUTCDate();
        if(day < 10)
        {
          day = "0"+day;
        }
        // console.log(celebrants[ii]);
        var data = {};
        data.title = celebrants[ii].first_name+" "+celebrants[ii].last_name;
        data.start = year + "-"+month+"-"+day;
        data.type = "Birthday Celebrant";
        data.color = '#ff0000';
        data_holidays.push(data);
      }
      
</script>
<script src="{{asset('./body_css/js/tooltips.js')}}"></script>
<script src="{{asset('./body_css/js/popover.js')}}"></script>
<script src="{{asset('./body_css/vendors/moment/moment.min.js')}}"></script>
<script src="{{asset('./body_css/vendors/fullcalendar/fullcalendar.min.js')}}"></script>
<script src="{{asset('./body_css/js/calendar.js')}}"></script>

@endsection
