@extends('layouts.header')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
         
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Attendances (Seabased)</h4>
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
                          <button type="submit" class="btn btn-primary mb-2">Submit</button>
                          <button type="button" class="btn btn-success mb-2" data-toggle="modal" data-target="#uploadSeabasedAttendance" title="Upload Attendance">Upload</button>
                          {{-- <button class='btn btn-info mb-2' onclick="exportTableToExcel('employee_seabased_attendance','{{$from_date}} - {{$to_date}}')">Export</button> --}}
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
                              <td>Time In</td>
                              <td>Time Out</td>
                              <td>Shift</td>
                              <td>Uploaded Date</td>
                              {{-- <td>Uploaded By</td> --}}
                          </tr>
                      </thead>
                      <tbody>
                        @foreach($attendances as $attendance)
                          <tr>
                              <td>{{$attendance->employee_code}}</td>
                              <td>@if($attendance->employee){{$attendance->employee->first_name}} {{$attendance->employee->last_name}}@endif</td>
                              <td>{{date('Y-m-d',strtotime($attendance->attendance_date))}}</td>
                              <td>{{date('Y-m-d h:i A',strtotime($attendance->time_in))}}</td>
                              <td>{{date('Y-m-d h:i A',strtotime($attendance->time_out))}}</td>
                              <td>{{$attendance->shift}}</td>
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

@include('attendances.upload_seabased_attendance')

@endsection