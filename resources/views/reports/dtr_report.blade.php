@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">
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
										<input type="date" value='{{$from}}' class="form-control form-control-sm" name="from"
												max='{{ date('Y-m-d') }}' onchange='get_min(this.value);' required />
									</div>
								</div>
								<div class='col-md-2'>
									<div class="form-group">
										<label class="text-right">To</label>
										<input type="date" value='{{$to}}' class="form-control form-control-sm" id='to' name="to"
												max='{{ date('Y-m-d') }}' required />
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
						<div class='row'>
							<div class="col-lg-12 grid-margin stretch-card">
							  <div class="card">
								<div class="card-body">
								  <h4 class="card-title">DTR Report <a href="/dtr-report-export?company={{$company}}&from={{$from}}&to={{$to}}" title="Export" class="btn btn-outline-primary btn-icon-text btn-sm text-center"><i class="ti-arrow-down btn-icon-prepend"></i></a></h4>
								  <div class="table-responsive">
									<table class="table table-hover table-bordered tablewithSearch">
									  <thead>
										<tr>
                                            <th>User ID</th>
                                            <th>Employee Name</th>
                                            <th>Date Filed</th>
                                            <th>DTR Date </th>
                                            <th>Correction</th>
                                            <th>Time-in</th>
                                            <th>Time-Out</th>
                                            <th>Approved Date</th>
                                            <th>Remarks</th>
                                            <th>Status</th>
										</tr>
									  </thead>
									  <tbody> 
										@foreach ($employee_dtrs as $form_approval)
										<tr>
                                            <td>{{$form_approval->user->id}}</td>
                                            <td>{{$form_approval->user->name}}</td>
                                            <td>{{date('d/m/Y h:i A', strtotime($form_approval->created_at))}}</td>
                                            <td>{{date('d/m/Y', strtotime($form_approval->dtr_date))}}</td>
                                            <td>{{$form_approval->correction}}</td>
                                            <td> {{(isset($form_approval->time_in)) ? date('d/m/Y h:i A', strtotime($form_approval->time_in)) : '----'}}</td>
                                            <td> {{(isset($form_approval->time_out)) ? date('d/m/Y h:i A', strtotime($form_approval->time_out)) : '----'}}</td>
                                            <td>{{date('d/m/Y', strtotime($form_approval->approved_date))}}</td>
											<td>
												{{$form_approval->remarks}} <br>
												@if($form_approval->attachment)
													<a href="{{url($form_approval->attachment)}}" target='_blank' class="text-start"><button type="button" class="btn btn-outline-info btn-sm ">View Attachment</button></a>
												@endif
											</td>
                                            <td>{{$form_approval->status}}</td>
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
@endsection 
