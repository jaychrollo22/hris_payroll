@extends('layouts.header')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Performance Plan Periods</h4>
                <p class="card-description">
                    @if (checkUserPrivilege('settings_add',auth()->user()->id) == 'yes')
                    <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#newPerformancePlanPeriod">
                      <i class="ti-plus btn-icon-prepend"></i>                                                    
                      New Performance Plan Period
                    </button>
                    @endif
                  </p>
             
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Period</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($performance_plan_periods as $period)
                            <tr >
                                <td>{{$period->id}}</td>
                                <td>{{$period->period}}</td>
                                <td id="tdActionId{{ $period->id }}" data-id="{{ $period->id }}">
                                  @if (checkUserPrivilege('settings_edit',auth()->user()->id) == 'yes')
                                    <button type="button" class="btn btn-info btn-rounded btn-icon" href="#edit_performance_plan_period{{$period->id}}" data-toggle="modal" title='EDIT'>
                                        <i class="ti-pencil-alt"></i>
                                    </button>
                                  @endif
                                </td>
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

@include('performance_plan_periods.new')

@foreach($performance_plan_periods as $performance_plan_period)
@include('performance_plan_periods.edit')
@endforeach

@endsection
