@extends('layouts.header')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
         
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Attendances (HIK)</h4>
                <p class="card-description">
                  <form method='get' onsubmit='show();'  enctype="multipart/form-data">
                    <div class=row>                      
                      <div class='col-md-2'>
                        <label for="">From</label>
                        <div class="form-group">
                          <input type="date" value='{{$from_date}}' class="form-control" name="from" max='{{date('Y-m-d')}}' onchange='get_min(this.value);' required/>
                        </div>
                      </div>
                      <div class='col-md-2'>
                        <label for="">To</label>
                        <div class="form-group">
                          <input type="date" value='{{$to_date}}'  class="form-control" name="to" id='to' max='{{date('Y-m-d')}}' required/>
                        </div>
                      </div>
                      <div class='col-md-3'>
                          <button type="submit" class="btn btn-primary mb-2">Filter</button>
                          <button type="button" class="btn btn-success mb-2" data-toggle="modal" data-target="#uploadHikAttendance" title="Upload Attendance">Upload</button>
                      </div>
                    </div>
                  </form>
                </p>

                <div class="table-responsive">
                  <table border="1" class="table table-hover table-bordered employee_seabased_attendance" id='employee_seabased_attendance'>
                      <thead>
                          <tr>
                              <td>User ID</td>
                              <td>Name</td>
                              <td>Date</td>
                              <td>Direction</td>
                              <td>Device</td>
                              <td>Uploaded Date</td>
                          </tr>
                      </thead>
                      <tbody>
                        @foreach($attendances as $attendance)
                          <tr>
                              <td>{{$attendance->employee_code}}</td>
                              <td>@if($attendance->employee){{$attendance->employee->first_name}} {{$attendance->employee->last_name}}@endif</td>
                              <td>{{$attendance->attendance_date ? date('Y-m-d h:i A',strtotime($attendance->attendance_date)) : ""}}</td>
                              <td>{{$attendance->direction}}</td>
                              <td>{{$attendance->device}}</td>
                              <td>{{date('Y-m-d h:i A',strtotime($attendance->created_at))}}</td>
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
@include('attendances.upload_hik_attendance')
@endsection