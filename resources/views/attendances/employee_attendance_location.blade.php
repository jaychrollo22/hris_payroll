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
                        <label class="col-sm-4 col-form-label text-right">Location</label>
                        <div class="col-sm-8">
                            <select data-placeholder="Select Location" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='location' required>
                                <option value="">-- Select Location --</option>
                                @foreach($locations as $location)
                                    <option value="{{$location->location}}" @if($loc == $location->location) selected @endif>{{$location->location}}</option>
                                @endforeach
                              </select>
                        </div>
                      </div>
                    </div>
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
                          <input type="date" value='{{$to_date}}'  class="form-control" name="to" id='to' max='{{date('Y-m-d')}}' required/>
                        </div>
                      </div>
                    </div>
                    <div class='col-md-3'>
                      <button type="submit" class="btn btn-primary mb-2">Submit</button>
                    </div>
                  </div>
                  </form>
                </p>
                @if($from_date)
                    <a class='btn btn-info mb-2' href="/bio-per-location-export?location={{$location}}&from={{$from_date}}&to={{$to_date}}">Export</a>
                @endif
                <div class="table-responsive">
                  <table border="1" class="table table-hover table-bordered tablewithSearch" id='employee_attendance'>
                    <thead>
                      <tr>
                        <th>Full Name</th>
                        <th>Emp Code</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Type</th>
                        <th>Location</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                          <tr>
                              <td>@if($attendance->employee){{$attendance->employee->first_name}} {{$attendance->employee->last_name}}@endif</td>
                              <td>{{$attendance->emp_code}}</td>
                              <td>{{date('Y-m-d',strtotime($attendance->datetime))}}</td>
                              <td>{{date('h:i A',strtotime($attendance->datetime))}}</td>
                              <td>{{($attendance->type == 0) ? "Time In" : "Time Out"}}</td>
                              <td>{{$attendance->ip_address}}</td>
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
</div>
<script>
    function get_min(value)
    {
      document.getElementById("to").min = value;
    }
</script>
@endsection
