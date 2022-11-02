@extends('layouts.header')

@section('content')

<div class="main-panel">
    <div class="content-wrapper">
            @if (count($errors))
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissible fade show " role="alert">
                        <span class="alert-inner--icon"><i class="ni ni-fat-remove"></i></span>
                        <span class="alert-inner--text"><strong>Error!</strong> {{ $error }}</span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endforeach
            @endif
       @include('links')
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Timekeeping 
                    <button type="button" class="btn btn-outline-danger btn-icon-text btn-sm"  data-toggle="modal" data-target="#timekeepingID">
                        <i class="ti-upload btn-icon-prepend"></i>                                                    
                        Upload
                    </button>
                    <a href='{{url('att_summary.xlsx')}}' target='_blank'><button type="button" class="btn btn-primary btn-icon-text btn-sm">
                        <i class="ti-file btn-icon-prepend "></i>
                        Download Format
                    </button></a>
                </h4>
              
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                        <tr>
                            <th>Period Date</th>
                            <th>Employee Count</th>
                            <th>Total Days Work</th>
                            <th>Total Days Absent</th>
                            <th>Total Late</th>
                            <th>Total Adjustments Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances->unique('period_from') as $att)
                            <tr>
                                <td>{{date('M d, Y', strtotime($att->period_from))}} - {{date('M d, Y', strtotime($att->period_to))}}</td>
                                <td><a href='#' data-toggle="modal" data-target="#view_attendance{{$att->period_from}}">{{count($attendances->where('period_from',$att->period_from))}}</a></td>
                                <td>{{number_format($attendances->where('period_from',$att->period_from)->sum('tot_days_work'),2)}}</td>
                                <td>{{number_format($attendances->where('period_from',$att->period_from)->sum('tot_days_absent'),2)}}</td>
                                <td>{{number_format($attendances->where('period_from',$att->period_from)->sum('tot_lates'),2)}}</td>
                                <td>{{number_format($attendances->where('period_from',$att->period_from)->sum('total_adjstmenthrs'),2)}}</td>
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
    @foreach($attendances as $att)
        @include('payroll.view_attendances')   
    @endforeach
    @include('payroll.upload_attendance') 
@endsection

