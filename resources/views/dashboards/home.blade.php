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
            <div class="col-md-10  transparent">
                <div class="row">
                    <div class="col-md-4 mb-4 transparent">
                        <div class="card">
                          <div class="card-body">
                            <h3 class="card-title">{{date('M d, Y')}}</h3>
                            <div class="media">
                                <i class="ti-time icon-md text-info d-flex align-self-center mr-3"></i>
                                <div class="media-body">
                                  <p class="card-text">Time In : 
                                    @if($attendance_now != null){{date('h:i A',strtotime($attendance_now->time_in))}} <br>
                                      @if($attendance_now->time_out == null )
                                          @php
                                            $employee_schedule = employeeSchedule($schedules,$attendance_now->time_in,$schedules[0]->schedule_id)
                                          @endphp
                                          Estimated Out : {{$employee_schedule ? date('h:i A',strtotime($employee_schedule['time_out_from'])) : ""}}
                                       @else
                                       Time Out : {{date('h:i A',strtotime($attendance_now->time_out))}}
                                       @endif
                                     @else NO TIME IN 
                                     @endif</p>
                                  <span id="span"></span>
                                   {{-- <button type="button" class="btn btn-outline-danger btn-fw btn-sm">Time Out</button> --}}
                                </div>
                              </div>
                          </div>
                        </div>
                        @if(count(auth()->user()->subbordinates) > 0)
                        <div class="card">
                          <div class="card-body">
                            <p class="card-title ">Subordinates <small>({{date('M d, Y')}})</small></p>
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
                                          $time_in = $attendance_employees->where('employee_code',$emp->employee_number)->first();
                                      @endphp
                                      <td>@if($time_in){{date('h:i a',strtotime($time_in->time_in))}}@endif</td>
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
                    <div class="col-md-8">
                      <div class="card">
                        <div class="card-body">
                          <h4 class="card-title">Calendar Events
                            <span class="badge text-white m-2" style='background-color:#257e4a;'><small>Regular Holidays</small></span> 
                            <span class="badge text-white m-2" style='background-color:#ff6600;'><small>Special Holidays</small></span> 
                            <span class="badge text-white m-2" style='background-color:#ff0000;'><small>Birthday Celebrants</small></span>
                          </h4>
                          <div id="calendar" class="full-calendar"></div>
                        </div>
                      </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4 ">
                    
                    
                  </div>
                </div>
            </div>
            <div class="col-md-2">
              <div class='row'>
                @if(count($announcements) > 0)
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Announcements</h4>
                      <ul class="list-star">
                        @foreach($announcements as $announcement)
                          <li><a href="{{url($announcement->attachment)}}" target='_blank' data-bs-toggle="tooltip" data-bs-placement="top" title="{{$announcement->user->name}}">{{$announcement->announcement_title}} </a> </li>
                        @endforeach
                      </ul>
                    </div>
                  </div>
                </div>
                @endif
                @if(auth()->user()->employee->company_id=='11')
                      <div class="col-md-12 mb-4  stretch-card transparent">
                          <div class="card">
                            <div class="card-body">
                              <h3 class="card-title">Employee Handbook</h3>
                              <div class="media">
                                  <i class="ti-book icon-md text-info d-flex align-self-center mr-3"></i>
                                  <div class="media-body">
                                    <p class="card-text">Handbook updated {{date('m.d.y',strtotime($handbook->created_at))}}</p>
                                    <a href="{{url($handbook->attachment)}}" target='_blank'><button type="button" class="btn btn-outline-primary btn-fw btn-sm">View Handbook</button></a>
                                  </div>
                                </div>
                            </div>
                          </div>
                      </div>
                    @endif
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
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         Description : <span id='modalBody'>
        </span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
  var span = document.getElementById('span');

  function time() {
  var d = new Date();
  var s = d.getSeconds();
  var m = d.getMinutes();
  var h = d.getHours();
  span.textContent = 
      ("0" + h).substr(-2) + ":" + ("0" + m).substr(-2) + ":" + ("0" + s).substr(-2);
  }

  setInterval(time, 1000);
</script>
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
        console.log(celebrants[ii]);
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
