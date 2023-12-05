@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">PERFORMANCE PLAN REVIEW (PPR)</h4>
                <p class="card-description">
                  <a href="/create-performance-plan-review" type="button" class="btn btn-outline-success btn-icon-text">
                    <i class="ti-plus btn-icon-prepend"></i>                                                    
                    Create
                  </a>
                </p>
                
                <form method='get' onsubmit='show();' enctype="multipart/form-data">
                  <div class=row>
                    {{-- <div class='col-md-2'>
                      <div class="form-group">
                        <label class="text-right">From</label>
                        <input type="date" value='{{$from}}' class="form-control form-control-sm" name="from"
                            max='{{ date('Y-m-d') }}' onchange='get_min(this.value);' required />
                      </div>
                    </div>
                    <div class='col-md-2'>
                      <div class="form-group">
                        <label class="text-right">To</label>
                        <input type="date" value='{{$to}}' class="form-control form-control-sm" id='to' name="to" required />
                      </div>
                    </div> --}}
                    <div class='col-md-2 mr-2'>
                      <div class="form-group">
                        <label class="text-right">Status</label>
                        <select data-placeholder="Select Status" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='status' required>
                          <option value="">-- Select Status --</option>
                          {{-- <option value="Approved" @if ('Approved' == $status) selected @endif>Approved</option>
                          <option value="Pending" @if ('Pending' == $status) selected @endif>Pending</option>
                          <option value="Cancelled" @if ('Cancelled' == $status) selected @endif>Cancelled</option>
                          <option value="Declined" @if ('Declined' == $status) selected @endif>Declined</option> --}}
                        </select>
                      </div>
                    </div>
                    <div class='col-md-2'>
                      <button type="submit" class="form-control form-control-sm btn btn-primary mb-2 btn-sm">Filter</button>
                    </div>
                  </div>
                </form>

                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                      <tr>
                        <th>Employee Number</th>
                        <th>Employee</th>
                        <th>Calendar Date</th>
                        <th>Review Date</th>
                        <th>PPR Period</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    {{-- <tbody>
                      @foreach ($dtrs as $dtr)
                      <tr>
                        <td> {{ date('M. d, Y h:i A', strtotime($dtr->created_at)) }}</td>
                        <td> {{ date('M. d, Y ', strtotime($dtr->dtr_date)) }}</td>
                        <td>{{ $dtr->correction }}</td>
                        <td> {{(isset($dtr->time_in)) ? date('M. d, Y h:i A', strtotime($dtr->time_in)) : '----'}}</td>
                        <td> {{(isset($dtr->time_out)) ? date('M. d, Y h:i A', strtotime($dtr->time_out)) : '----'}}</td>
                        <td>
                          <p title="{{ $dtr->remarks }}" style="width: 250px;white-space: nowrap; overflow: hidden;text-overflow: ellipsis;">
                            {{ $dtr->remarks }}
                          </p>
                        </td>
                        <td id="tdStatus{{ $dtr->id }}">
                          @if ($dtr->status == 'Pending')
                            <label class="badge badge-warning">{{ $dtr->status }}</label>
                          @elseif($dtr->status == 'Approved')
                            <label class="badge badge-success">{{ $dtr->status }}</label>
                          @elseif($dtr->status == 'Declined' or $dtr->status == 'Cancelled')
                            <label class="badge badge-danger">{{ $dtr->status }}</label>
                          @endif                        
                        </td>
                        <td id="tdStatus{{ $dtr->id }}">
                          @if(count($dtr->approver) > 0)
                            @foreach($dtr->approver as $approver)
                              @if($dtr->level >= $approver->level)
                                @if ($dtr->level == 0 && $dtr->status == 'Declined')
                                {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                @else
                                  {{$approver->approver_info->name}} -  <label class="badge badge-success mt-1">Approved</label>
                                @endif
                              @else
                                @if ($dtr->status == 'Declined')
                                  {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                @else
                                  {{$approver->approver_info->name}} -  <label class="badge badge-warning mt-1">Pending</label>
                                @endif
                              @endif<br>
                            @endforeach
                          @else
                            <label class="badge badge-danger mt-1">No Approver</label>
                          @endif
                        </td>
                        <td id="tdActionId{{ $dtr->id }}" data-id="{{ $dtr->id }}">
                          @if ($dtr->status == 'Pending' and $dtr->level == 0)
                          <button type="button" id="view{{ $dtr->id }}" class="btn btn-primary btn-rounded btn-icon"
                            data-target="#view_dtr{{ $dtr->id }}" data-toggle="modal" title='View'>
                            <i class="ti-eye"></i>
                          </button>            
                            <button type="button" id="edit{{ $dtr->id }}" class="btn btn-info btn-rounded btn-icon"
                              data-target="#edit_dtr{{ $dtr->id }}" data-toggle="modal" title='Edit'>
                              <i class="ti-pencil-alt"></i>
                            </button>
                            <button title='Cancel' id="{{ $dtr->id }}" onclick="cancel(this.id)"
                              class="btn btn-rounded btn-danger btn-icon">
                              <i class="fa fa-ban"></i>
                            </button>
                          @elseif ($dtr->status == 'Pending' and $dtr->level > 0)
                            <button type="button" id="view{{ $dtr->id }}" class="btn btn-primary btn-rounded btn-icon"
                              data-target="#view_dtr{{ $dtr->id }}" data-toggle="modal" title='View'>
                              <i class="ti-eye"></i>
                            </button>            
                              <button title='Cancel' id="{{ $dtr->id }}" onclick="cancel(this.id)"
                                class="btn btn-rounded btn-danger btn-icon">
                                <i class="fa fa-ban"></i>
                              </button>
                          @elseif ($dtr->status == 'Approved')   
                          <button type="button" id="view{{ $dtr->id }}" class="btn btn-primary btn-rounded btn-icon"
                            data-target="#view_dtr{{ $dtr->id }}" data-toggle="modal" title='View'>
                            <i class="ti-eye"></i>
                          </button>                            
                            <button title='Cancel' id="{{ $dtr->id }}" onclick="cancel(this.id)"
                              class="btn btn-rounded btn-danger btn-icon">
                              <i class="fa fa-ban"></i>
                            </button>  
                          @else
                            <button type="button" id="view{{ $dtr->id }}" class="btn btn-primary btn-rounded btn-icon"
                              data-target="#view_dtr{{ $dtr->id }}" data-toggle="modal" title='View'>
                              <i class="ti-eye"></i>
                            </button>                                                                               
                          @endif
                        </td> 
                      </tr>
                    @endforeach  
                    </tbody> --}}
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div> 
@endsection