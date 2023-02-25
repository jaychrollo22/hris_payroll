@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">
			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Leave Reports</h4>
						<p class="card-description">
						<form method='get' onsubmit='show();' enctype="multipart/form-data">
							<div class=row>
								<div class='col-md-4'>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label text-right">From</label>
										<div class="col-sm-8">
											<input type="date" value='{{$from}}' class="form-control form-control-sm" name="from"
												max='{{ date('Y-m-d') }}' onchange='get_min(this.value);' required />
										</div>
									</div>
								</div>
								<div class='col-md-4'>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label text-right">To</label>
										<div class="col-sm-8">
											<input type="date" value='{{$to}}' class="form-control form-control-sm" id='to' name="to"
												max='{{ date('Y-m-d') }}' required />
										</div>
									</div>
								</div>
								<div class='col-md-4'>
									<button type="submit" class="form-control form-control-sm btn btn-primary mb-2 btn-sm">Generate</button>
								</div>
							</div>
							
						</form>
						</p>
						<div class='row'>
							<div class="col-lg-12 grid-margin stretch-card">
							  <div class="card">
								<div class="card-body">
								  <h4 class="card-title">Leave Report <a href="/leave-report-export?from={{$from}}&to={{$to}}" title="Export" class="btn btn-outline-primary btn-icon-text btn-sm text-center"><i class="ti-arrow-down btn-icon-prepend"></i></a></h4>
								  <div class="table-responsive">
									<table class="table table-hover table-bordered tablewithSearch">
									  <thead>
										<tr>
										  <th>Employee Name</th>
										  <th>Form Type</th>
										  <th>From</th>
										  <th>To</th>
										  <th>Status</th> 
										  <th>Approved Date</th> 
										  <th>Reason/Remarks</th> 
										</tr>
									  </thead>
									  <tbody> 
										@foreach ($employee_leaves as $form_approval)
										<tr>
										  <td>{{$form_approval->user->name}}</td>
										  <td>{{$form_approval->leave->leave_type}}</td>
										  <td>{{date('d/m/Y', strtotime($form_approval->date_from))}}</td>
										  <td>{{date('d/m/Y', strtotime($form_approval->date_to))}}</td>
										  <td>
											{{$form_approval->status}}
										  </td>
										  <td>{{date('d/m/Y', strtotime($form_approval->approved_date))}}</td>
										  <td>{{$form_approval->reason}}</td>
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
