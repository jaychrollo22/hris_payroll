@extends('layouts.header')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
          <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Total Days Work (Days)</h4>
                <div class="media">
                  <i class="ti-layout-media-overlay icon-md text-info d-flex align-self-center mr-3"></i>
                  <div class="media-body">
                    <h2 class="card-text">0</h2>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">No In/Out (Days)</h4>
                <div class="media">
                  <i class="ti-pencil icon-md text-info d-flex align-self-center mr-3"></i>
                  <div class="media-body">
                    <h2 class="card-text">0</h2>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Total Absent (Days)</h4>
                <div class="media">
                  <i class="ti-close icon-md text-info d-flex align-self-center mr-3"></i>
                  <div class="media-body">
                    <h2 class="card-text">0</h2>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Approved Overtime (Hrs)</h4>
                <div class="media">
                  <i class="ti-check-box icon-md text-info d-flex align-self-center mr-3"></i>
                  <div class="media-body">
                    <h2 class="card-text">0</h2>
                  </div>
                </div>
              </div>
            </div>
          </div>
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
                          <input type="date" value='{{$from_date}}' class="form-control" name="from" required/>
                        </div>
                      </div>
                    </div>
                    <div class='col-md-3'>
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label text-right">To</label>
                        <div class="col-sm-8">
                          <input type="date" value='{{$to_date}}'  class="form-control" name="to" required/>
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
                        <th>Remarks</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($date_range as $date_r)
                        <tr>
                          <td  @if((date('l',strtotime($date_r)) == "Saturday") || (date('l',strtotime($date_r)) == "Sunday")) class="bg-danger text-white" @endif>{{date('M d, Y - l',strtotime($date_r))}}</td>
                          @php
                              $time_in = $attendances->whereBetween('time_in',[$date_r." 00:00:00", $date_r." 23:59:59"])->first();
                          @endphp
                          <td>@if($time_in != null){{$time_in->time_in}}@endif</td>
                          <td>@if($time_in != null){{$time_in->time_out}}@endif</td>
                          <td>@if($time_in != null){{round((((strtotime($time_in->time_out) - strtotime($time_in->time_in)))/3600),2)}}  hrs @endif </td>
                          <td> </td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
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
@endsection
