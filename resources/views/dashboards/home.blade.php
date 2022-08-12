@extends('layouts.header')

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
            <div class="col-md-12  transparent">
                <div class="row">
                    <div class="col-md-3 mb-4  stretch-card transparent">
                        <div class="card">
                          <div class="card-body">
                            <h3 class="card-title">{{date('M d, Y')}}</h3>
                            <div class="media">
                                <i class="ti-time icon-md text-info d-flex align-self-center mr-3"></i>
                                <div class="media-body">
                                  <p class="card-text">Time In : @if($attendance_now != null){{date('h:i A',strtotime($attendance_now->time_in))}} <br> Estimated Out : {{date('h:i A', strtotime($attendance_now->time_in . " +10 hours +30 minutes"))}} @else NO Time In @endif</p>
                                  <span id="span"></span> <button type="button" class="btn btn-outline-danger btn-fw btn-sm">Time Out</button>
                                </div>
                              </div>
                          </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4  stretch-card transparent">
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
                </div>
            </div>
          </div>     
          <div class="row">
            <div class="col-md-4 stretch-card ">
              <div class="card">
                <div class="card-body">
                  <p class="card-title mb-0">Attendances</p>
                  <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                      <thead>
                        <tr>
                          <th>Date</th>
                          <th>In</th>
                          <th>Out</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach(array_reverse($date_ranges) as $date_range)
                        <tr>
                          <td class=" @if((date('l',strtotime($date_range)) == "Saturday") || (date('l',strtotime($date_range)) == "Sunday")) bg-danger text-white @endif">{{date('M d - l',strtotime($date_range))}}</td>
                            @php
                              $time_in = $attendances->whereBetween('time_in',[$date_range." 00:00:00", $date_range." 23:59:59"])->first();
                            @endphp
                          <td>@if($time_in != null){{date('h:i A',strtotime($time_in->time_in))}}@endif</td>
                          <td>@if($time_in != null){{date('h:i A',strtotime($time_in->time_out))}}@endif</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4 ">
                    <div class="card">
                        <div class="card-body">
                        <p class="card-title">Available Leave Credits</p>
                            <div class="charts-data">
                                <div class="mt-3">
                                <p class="mb-0">Sick Leave</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="progress progress-md flex-grow-1 mr-4">
                                    <div class="progress-bar bg-inf0" role="progressbar" style="width:40%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <p class="mb-0">2</p>
                                </div>
                                </div>
                                <div class="mt-3">
                                <p class="mb-0">Vacation Leave</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="progress progress-md flex-grow-1 mr-4">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 40%" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <p class="mb-0">2</p>
                                </div>
                                </div>
                            </div>  
                        </div>
                    </div>
            </div>
            <div class="col-md-4  ">
              <div class="card">
                <div class="card-body">
                  <p class="card-title">Birthday Celebrants </p>
                  <ul class="icon-data-list">
                    <li>
                      <div class="d-flex">
                        <img src="body_css/images/faces/face1.jpg" alt="user">
                        <div>
                          <p class="text-info mb-1">Renz Christian Cabato</p>
                          <p class="mb-0">Department</p>
                          <small>{{date('F d')}}</small>
                        </div>
                      </div>
                    </li>
                   
                  </ul>
                </div>
              </div>
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
