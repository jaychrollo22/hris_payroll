@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Filter</h4>
                        <p class="card-description">
                            <form method='get' onsubmit='show();' enctype="multipart/form-data">
                                <div class=row>
                                    <div class='col-md-2'>
                                        <div class="form-group">
                                            <label class="text-right">Company</label>
                                            <select data-placeholder="Select Company" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='company' required>
                                                <option value="">-- Select Company --</option>
                                                @foreach($companies as $comp)
                                                <option value="{{$comp->id}}" @if ($comp->id == $company) selected @endif>{{$comp->company_name}} - {{$comp->company_code}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class='col-md-2'>
                                        <div class="form-group">
                                            <label class="text-right">From</label>
                                            <input type="date" value='{{$from}}' class="form-control" name="from" max='{{date('Y-m-d')}}' onchange='get_min(this.value);' required />
                                        </div>
                                    </div>
                                    <div class='col-md-2'>
                                        <div class="form-group">
                                            <label class="text-right">To</label>
                                            <input type="date" value='{{$to}}' class="form-control" name="to" id='to' max='{{date('Y-m-d')}}' required />
                                            
                                        </div>
                                    </div>
                                    <div class='col-md-2 mr-2'>
                                        <div class="form-group">
                                            <label class="text-right">Status</label>
                                            <select data-placeholder="Select Status" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='status' required>
                                                <option value="">-- Select Status --</option>
                                                <option value="Approved" @if ('Approved' == $status) selected @endif>Approved</option>
                                                <option value="Pending" @if ('Pending' == $status) selected @endif>Pending</option>
                                                <option value="Cancelled" @if ('Cancelled' == $status) selected @endif>Cancelled</option>
                                                <option value="Declined" @if ('Declined' == $status) selected @endif>Declined</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class='col-md-2'>
                                        <button type="submit" class="form-control form-control-sm btn btn-primary mb-2 btn-sm">Generate</button>
                                    </div>
                                </div>
                            </form>
                        </p>
                        <h4 class="card-title">OB Report <a href="/ob-report-export?company={{$company}}&from={{$from}}&to={{$to}}" title="Export" class="btn btn-outline-primary btn-icon-text btn-sm text-center"><i class="ti-arrow-down btn-icon-prepend"></i></a></h4>

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered tablewithSearch" id="ob_report">
                                <thead>
                                    <tr>
                                        <th>User ID</th>
                                        <th>Employee Name</th>
                                        <th>Date Filed</th>
                                        <th>Date</th>
                                        <th>Time In-Out</th>
                                        <th>OB Count</th> 
                                        <th>Approved Date </th>
                                        <th>Remarks </th>
                                        <th>Status </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employee_obs as $item)
                                    <tr>
                                        <td>{{$item->employee->user_id}}</td>
                                        <td>{{$item->user->name}}</td>
                                        <td>{{date('d/m/Y h:i A', strtotime($item->created_at))}}</td>
                                        <td>{{ date('d/m/Y ', strtotime($item->applied_date)) }}</td>
                                        <td>{{ date('d/m/Y h:i A', strtotime($item->date_from)) }} - {{ date('d/m/Y h:i A', strtotime($item->date_to)) }}  </td>
                                        <td>{{get_count_days($item->schedule,$item->date_from,$item->date_to)}}</td>
                                        <td>{{ $item->approved_date ? date('d/m/Y', strtotime($item->approved_date)) : ""}}</td>
                                        <td>{{$item->remarks}}
                                            <br>
                                            @if($item->attachment)
                                                <a href="{{url($item->attachment)}}" target='_blank' class="text-start"><button type="button" class="btn btn-outline-info btn-sm ">View Attachment</button></a>
                                            @endif
                                        </td>
                                        <td>{{$item->status}}</td>
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

@php
function get_count_days($data,$date_from,$date_to)
 {
    $data = ($data->pluck('name'))->toArray();
    $count = 0;
    $startTime = strtotime($date_from);
    $endTime = strtotime($date_to);

    for ( $i = $startTime; $i <= $endTime; $i = $i + 86400 ) {
      $thisDate = date( 'l', $i ); // 2010-05-01, 2010-05-02, etc
      if(in_array($thisDate,$data)){
          $count= $count+1;
      }
    }

    return($count);
 } 
@endphp  


@endsection
@section('footer')
<script src="{{ asset('body_css/vendors/inputmask/jquery.inputmask.bundle.js') }}"></script>
<script src="{{ asset('body_css/vendors/inputmask/jquery.inputmask.bundle.js') }}"></script>
<script src="{{ asset('body_css/js/inputmask.js') }}"></script>
@endsection
