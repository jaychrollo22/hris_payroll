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
											<input type="date" value='{{$from}}' class="form-control form-control-sm" name="from"
												max='{{ date('Y-m-d') }}' onchange='get_min(this.value);' required />
										</div>
									</div>
								</div>
								<div class='col-md-3'>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label text-right">To</label>
										<div class="col-sm-8">
											<input type="date" value='{{$to}}' class="form-control form-control-sm" id='to' name="to"
												max='{{ date('Y-m-d') }}' required />
										</div>
									</div>
								</div>
								<div class='col-md-3'>
									<button type="submit" class="form-control form-control-sm btn btn-primary mb-2 btn-sm">Generate</button>
								</div>
							</div>
							
						</form>
						</p>
						<div class='row'>
							<div class="col-lg-12 grid-margin stretch-card">
							  <div class="card">
								<div class="card-body">
								  <h4 class="card-title">WFH Report <a href="/wfh-report-export?company={{$company}}&from={{$from}}&to={{$to}}" title="Export" class="btn btn-outline-primary btn-icon-text btn-sm text-center"><i class="ti-arrow-down btn-icon-prepend"></i></a></h4>
								  <div class="table-responsive">
									<table class="table table-hover table-bordered tablewithSearch">
									  <thead>
										<tr>
										  <th>User ID</th>
										  <th>Employee Name</th>
										  <th>Date Filed</th>
										  <th>WFH Date</th>
										  <th>WFH Time In-Out</th>
                                          {{-- <th>WFH Count(Days)</th>  --}}
										  <th>Status</th> 
										  <th>Approved Date</th> 
										  <th>Reason/Remarks</th> 
										</tr>
									  </thead>
									  <tbody> 
										@foreach ($employee_wfhs as $form_approval)
										<tr>
										  <td>{{$form_approval->user->id}}</td>
										  <td>{{$form_approval->user->name}}</td>
										  <td>{{date('d/m/Y', strtotime($form_approval->created_at))}}</td>
										  <td>{{date('d/m/Y', strtotime($form_approval->applied_date))}}</td>
										  <td>{{date('H:i', strtotime($form_approval->date_from)) . '-' . date('H:i', strtotime($form_approval->date_to))}}</td>
										  <td>
											{{$form_approval->status}}
										  </td>
										  <td>{{date('d/m/Y', strtotime($form_approval->approved_date))}}</td>
										  <td>{{$form_approval->remarks}}</td>
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
