@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Employee Allowance for {{ $employee_allowance->employee->first_name . ' ' . $employee_allowance->employee->last_name }}</h4>
                        <div class="col-md-12">
							<form method='POST' action='{{url('update-employee-allowance/'.$employee_allowance->id)}}' onsubmit='show()'>
								@csrf
								<div class="modal-body">
									<div class="row">
										<div class='col-lg-6 form-group'>
											<label for="allowanceType">Particular</label>
											<select data-placeholder="Select Particular"
												class="form-control form-control-sm required js-example-basic-single " style='width:100%;' name='allowance_type'>
												<option value="">--Select Particular--</option>
												@foreach ($allowanceTypes as $allowanceType)
													<option value="{{ $allowanceType->id }}" {{ $employee_allowance->allowance_id == $allowanceType->id ? 'selected' : '' }}>
														{{ $allowanceType->name }}</option>
												@endforeach
											</select>
				
										</div>
										<div class="col-lg-6 form-group">
											<label for="employee">Employee</label>
											<select data-placeholder="Select Employee" class="form-control form-control-sm required js-example-basic-single "
												style='width:100%;' name='user_id' disabled>
												<option value="">--Select Employee--</option>
												@foreach ($employees as $employee)
													<option value="{{ $employee->user_id }}" {{ $employee_allowance->user_id == $employee->user_id ? 'selected' : '' }}>
														{{ $employee->last_name . ', ' . $employee->first_name . ' ' . $employee->middle_name }}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6 form-group">
											<label for="description">Description</label>
											<input type="text" class="form-control form-control-sm" name="description" id="description"
												value="{{ $employee_allowance->description }}" placeholder="Any Remarks">
										</div>

										<div class="col-lg-6 form-group">
											<label for="application">Application</label>
											<select id="application" class="form-control form-control-sm" name="application">
												<option value="">Choose Application</option>
												<option value="Daily" {{ $employee_allowance->application == 'Daily' ? 'selected' : '' }}>Daily</option>
												<option value="Fixed" {{ $employee_allowance->application == 'Fixed' ? 'selected' : '' }}>Fixed</option>
											</select>
										</div>

										<div class="col-lg-6 form-group">
											<label for="type">Type</label>
											<select name="type" id="type" class="form-control form-control-sm">
												<option value="">Choose Type</option>
												<option value="Basic Allowance" {{ $employee_allowance->type == 'Basic Allowance' ? 'selected' : '' }}>Basic Allowance</option>
												<option value="Grade Allowance" {{ $employee_allowance->type == 'Grade Allowance' ? 'selected' : '' }}>Grade Allowance</option>
											</select>
										</div>

										<div class="col-lg-6 form-group">
											<label for="schedule">Credit Schedule</label>
											<select name="schedule" id="schedule" class="form-control form-control-sm">
												<option value="">Choose Credit Schedule</option>
												<option value="First Cut-Off" {{ $employee_allowance->schedule == 'First Cut-Off' ? 'selected' : '' }}>First Cut-Off</option>
												<option value="Last Cut-Off" {{ $employee_allowance->schedule == 'Last Cut-Off' ? 'selected' : '' }}>Last Cut-Off</option>
												<option value="Every Cut-Off" {{ $employee_allowance->schedule == 'Every Cut-Off' ? 'selected' : '' }}>Every Cut-Off</option>
											</select>
										</div>

										<div class="col-lg-6 form-group">
											<label for="amount">Amount</label>
											<input type="number" class="form-control form-control-sm" name="amount" id="amount"  required min="1"
												value="{{ $employee_allowance->allowance_amount }}" placeholder="0.00">
										</div>

										<div class="col-lg-6 form-group">
											<label for="percentage">Percentage</label>
											<input type="number" class="form-control form-control-sm" name="percentage" id="percentage" step="0.01" placeholder="0.00"
												value="{{ $employee_allowance->percentage }}" placeholder="0">
										</div>

										<div class="col-lg-6 form-group">
											<label for="end_date">Effective Date</label>
											<input type="date" class="form-control form-control-sm" name="effective_date" id="effective_date"
												value="{{ $employee_allowance->effective_date }}">
										</div>

										<div class="col-lg-6 form-group">
											<label for="end_date">End Date</label>
											<input type="date" class="form-control form-control-sm" name="end_date" id="end_date"
												value="{{ $employee_allowance->end_date }}">
										</div>

										<div class="col-lg-6 form-group">
											<label for="frequency">Frequency</label>
											<select id="frequency" class="form-control form-control-sm" name="frequency">
												<option value="">Choose Frequency</option>
												<option value="monthly" {{ $employee_allowance->frequency == 'monthly' ? 'selected' : '' }}>Monthly</option>
												<option value="quarterly" {{ $employee_allowance->frequency == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
												<option value="annually" {{ $employee_allowance->frequency == 'annually' ? 'selected' : '' }}>Annually</option>
											</select>
										</div>
				
										<div class="col-lg-6 form-group">
											<label for="is_taxable">Taxable</label>
											<select id="is_taxable" class="form-control form-control-sm" name="is_taxable">
												<option value="" disabled>Choose Type</option>
												<option value="1" {{ $employee_allowance->is_taxable == '1' ? 'selected' : '' }}>Yes</option>
												<option value="0" {{ $employee_allowance->is_taxable == '0' ? 'selected' : '' }}>No</option>
											</select>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<a href="/employee-allowance" type="button" class="btn btn-secondary" data-dismiss="modal">Close</a>
									<button type="submit" class="btn btn-primary">Save</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
