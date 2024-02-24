@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">
			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Employee Used Leaves : {{$employee->first_name . ' ' . $employee->last_name}}</h4>
						<p class="card-description">
						<form method='get' onsubmit='show();' enctype="multipart/form-data">
							<div class=row>
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
						<div class='row'>
							<div class="col-lg-12 grid-margin stretch-card">
							  <div class="card">
								<div class="card-body">
								  
								  <div class="table-responsive">
									<table class="table table-hover table-bordered tablewithSearch">
									  <thead>
										<tr>
										  
										  <th>User ID</th>
										  <th>Employee Name</th>
										  <th>Date Filed</th>
										  <th>Form Type</th>
										  <th>From</th>
										  <th>To</th>
										  <th>With Pay </th>
										  <th>Half Day </th>
										  <th>Leave Count</th>
										  <th>Status</th> 
										  <th>Approved Date</th> 
										  <th>Reason/Remarks</th> 
										  <th>Action</th> 
										</tr>
									  </thead>
									  <tbody> 
										@foreach ($employee_leaves as $form_approval)
										<tr>
										  <td>{{$form_approval->user->id}}</td>
										  <td>{{$form_approval->user->name}}</td>
										  <td>{{date('d/m/Y h:i A', strtotime($form_approval->created_at))}}</td>
										  <td>{{$form_approval->leave->leave_type}}</td>
										  <td>{{date('d/m/Y', strtotime($form_approval->date_from))}}</td>
										  <td>{{date('d/m/Y', strtotime($form_approval->date_to))}}</td>
										  @if($form_approval->withpay == 1)   
											<td>Yes</td>
										  @else
											<td>No</td>
										  @endif  
										  @if($form_approval->halfday == 1)   
										 	<td>Yes</td>
										  @else
											<td></td>
										  @endif 
										  <td>{{get_count_days($form_approval->schedule,$form_approval->date_from,$form_approval->date_to,$form_approval->halfday)}}</td>
										  <td>
											{{$form_approval->status}}
										  </td>
										  <td>{{ $form_approval->approved_date ? date('d/m/Y', strtotime($form_approval->approved_date)) : ""}}</td>
										  <td>
												{{$form_approval->reason}} <br>
												@if($form_approval->attachment)
													<a href="{{url($form_approval->attachment)}}" target='_blank' class="text-start"><button type="button" class="btn btn-outline-info btn-sm ">View Attachment</button></a>
												@endif
										  </td>
                                          <td>
                                            <button class="btn btn-md btn-icon btn-info" title="Edit Leave"><i class="ti-pencil btn-icon-prepend"></i></button>
                                            <button class="btn btn-md btn-icon btn-danger" title="Cancel Leave"><i class="ti-close btn-icon-prepend"></i></button>
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
			</div>
		</div>
	</div>
@php
function get_count_days($data,$date_from,$date_to,$halfday)
 {

    if($date_from == $date_to){
        $count = 1;
    }else{
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
    }

    if($count == 1 && $halfday == 1){
      return '0.5';
    }else{
      return($count);
    }
    
 } 
@endphp 
@endsection
