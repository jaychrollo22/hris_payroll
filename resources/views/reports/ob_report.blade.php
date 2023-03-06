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
                                    <div class='col-md-3'>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label text-right">Company</label>
                                            <div class="col-sm-8">
                                                <select data-placeholder="Select Company" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='company' required>
                                                    <option value="">-- Select Employee --</option>
                                                    @foreach($companies as $comp)
                                                    <option value="{{$comp->id}}" @if ($comp->id == $company) selected @endif>{{$comp->company_name}} - {{$comp->company_code}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col-md-3'>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label text-right">From</label>
                                            <div class="col-sm-8">
                                                <input type="date" value='{{$from}}' class="form-control" name="from" max='{{date('Y-m-d')}}' onchange='get_min(this.value);' required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col-md-3'>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label text-right">To</label>
                                            <div class="col-sm-8">
                                                <input type="date" value='{{$to}}' class="form-control" name="to" id='to' max='{{date('Y-m-d')}}' required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col-md-3'>
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
                                        <th>OB From</th> 
                                        <th>OB To</th> 
                                        <th>OB Count</th> 
                                        <th>Remarks </th>
                                        <th>Status </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employee_obs as $item)
                                    <tr>
                                        <td>{{$item->employee->user_id}}</td>
                                        <td>{{$item->user->name}}</td>
                                        <td>{{date('d/m/Y', strtotime($item->created_at))}}</td>
                                        <td>{{date('d/m/Y', strtotime($item->date_from))}}</td>
                                        <td>{{date('d/m/Y', strtotime($item->date_to))}}</td>
                                        <td>{{get_count_days($item->schedule,$item->date_from,$item->date_to)}}</td>
                                        <td>{{$item->remarks}}</td>
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
