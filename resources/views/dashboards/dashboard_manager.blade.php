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
                            <h3 class="card-title">Work from home ({{date('F d, Y')}})</h3>
                            <div class="media">
                                <i class="ti-time icon-md text-info d-flex align-self-center mr-3"></i>
                                <div class="media-body">
                                  <p class="card-text">Time In : 08:03 AM <br> Estimated Out : 08:03 PM</p>
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
                    <div class="col-md-3 mb-4  stretch-card transparent">
                        <div class="card">
                          <div class="card-body">
                            <h3 class="card-title">NO IN / OUT</h3>
                            <div class="media">
                                <i class="ti-user icon-md text-info d-flex align-self-center mr-3"></i>
                                <div class="media-body">
                                  <p class="card-text"><span class='badge badge-warning'>2</span> as {{date('F d,Y')}}</p>
                                  <button type="button" class="btn btn-outline-primary btn-fw btn-sm">View </button>
                                </div>
                              </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12  transparent">
              <div class="row">
                <div class="col-md-3 mb-4 stretch-card transparent">
                  <div class="card card-tale">
                    <div class="card-body">
                      <p class="mb-4">Late this month</p>
                      <p class="fs-30 mb-2">30 min/s</p>
                      <p>as of {{date('F Y')}}</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 mb-4 stretch-card transparent">
                  <div class="card card-dark-blue">
                    <div class="card-body">
                      <p class="mb-4">Absent this Month</p>
                      <p class="fs-30 mb-2">2</p>
                      <p>as of {{date('F Y')}}</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 mb-4 stretch-card transparent">
                  <div class="card card-light-danger">
                    <div class="card-body">
                      <p class="mb-4">Approved Overtime this month</p>
                      <p class="fs-30 mb-2">10 hrs</p>
                      <p>as of {{date('F Y')}}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>  
          <div class="row">
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12 stretch-card grid-margin">
                        <div class="card">
                          <div class="card-body">
                            <p class="card-title mb-0">Previous Attendance ( 1 Week )</p>
                            <div class="table-responsive">
                              <table class="table table-borderless">
                                <thead>
                                  <tr>
                                    <th class="pl-0  pb-2 border-bottom">Date</th>
                                    <th class="border-bottom pb-2">In</th>
                                    <th class="border-bottom pb-2">Out</th>
                                    <th class="border-bottom pb-2">Type</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td class="pl-0">{{date('F d, Y')}}</td>
                                    <td>08:00 AM </td>
                                    <td >05:00 PM</td>
                                    <td >Office</td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12 stretch-card grid-margin">
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
                      <div class="col-md-12 stretch-card ">
                        <div class="card ">
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
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12 stretch-card ">
                        <div class="card">
                          <div class="card-body">
                            <p class="card-title mb-0">For Approval <button type="button" class="btn btn-inverse-danger btn-icon">
                                2
                              </button></p>
                            <div class="table-responsive">
                              <table class="table table-borderless table-hover ">
                                <thead>
                                  <tr>
                                    <th class="pl-0  pb-2 border-bottom">Name</th>
                                    <th class="border-bottom pb-2">Type</th>
                                    <th class="border-bottom pb-2">Date Filed</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td class="pl-0">Renz Christian Cabato</td>
                                    <td>Work From Home</td>
                                    <td>{{date('F d, Y')}}</td>
                                  </tr>
                                  <tr>
                                    <td class="pl-0">Renz Christian Cabato</td>
                                    <td>Leave</td>
                                    <td>{{date('F d, Y')}}</td>
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
