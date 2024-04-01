@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">MY PERFORMANCE PLAN</h4>
                <p class="card-description">
                  {{-- <a href="/create-performance-plan-review" type="button" class="btn btn-outline-success btn-icon-text">
                    <i class="ti-plus btn-icon-prepend"></i>                                                    
                    Create
                  </a> --}}
                </p>
                
                <form method='get' onsubmit='show();' enctype="multipart/form-data">
                  <div class=row>

                    <div class='col-md-2 mr-2'>
                      <div class="form-group">
                        <label class="text-right">Status</label>
                        <select data-placeholder="Select Status" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='status' required>
                          <option value="">-- Select Status --</option>
                          <option value="Draft" @if ('Draft' == $status) selected @endif>Draft</option>
                          <option value="For Review" @if ('For Review' == $status) selected @endif>For Review</option>
                          <option value="Approved" @if ('Approved' == $status) selected @endif>Approved</option>
                          <option value="Cancelled" @if ('Cancelled' == $status) selected @endif>Cancelled</option>
                        </select>
                      </div>
                    </div>
                    <div class='col-md-2'>
                      <button type="submit" class="form-control form-control-sm btn btn-primary mb-2 btn-sm">Filter</button>
                    </div>
                  </div>
                </form>

                <div class="table-responsive">
                  <table id="my-ppr" class="table table-hover table-bordered">
                    <thead>
                      <tr>
                        <th>Date Filed</th>
                        <th>Calendar Date</th>
                        <th>PPR Period</th>
                        <th>Review Date</th>
                        <th>Status</th>
                        <th>Remarks</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($employee_performance_evaluation as $eval)
                        <tr>
                          <td> 

                            {{-- @if($eval->status == 'Approved') --}}
                              <a href="/show-performance-plan-review/{{$eval->id}}" class="text-success btn-sm text-center" title="View PPR">
                                  <i class="ti-pencil btn-icon-prepend"></i>
                              </a>
                            {{-- @else
                              <a href="/edit-performance-plan-review/{{$eval->id}}" class="text-success btn-sm text-center" title="Edit PPR">
                                  <i class="ti-pencil btn-icon-prepend"></i>
                              </a>
                            @endif --}}
                            
                            {{ date('Y-m-d h:i A',strtotime($eval->created_at))}}</td>
                          <td>
                            {{ $eval->calendar_year}}</td>
                          <td>{{ $eval->period}}</td>
                          <td>{{ $eval->approved_by_date ? date('Y-m-d',strtotime($eval->approved_by_date)) : ""}}</td>
                          <td>
                            @if ($eval->status == 'Draft')
                              <label class="badge badge-default">{{ $eval->status }}</label>
                            @elseif ($eval->status == 'For Review')
                              <label class="badge badge-warning">{{ $eval->status }}</label>
                            @elseif($eval->status == 'Approved')
                              <label class="badge badge-success" title="{{$eval->approval_remarks}}">{{ $eval->status }}</label>
                            @elseif($eval->status == 'Declined' || $eval->status == 'Cancelled')
                              <label class="badge badge-danger" title="{{$eval->approval_remarks}}">{{ $eval->status }}</label>
                            @endif
                          </td>
                          <td>{{ $eval->approval_remarks}}</td>
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