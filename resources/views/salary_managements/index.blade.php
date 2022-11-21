@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">
			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Salary Managements</h4>
						<p class="card-description">
							<button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal"
								data-target="#newSalaryAdjustment">
								<i class="ti-plus btn-icon-prepend"></i>
								New Salary Adjustment
							</button>
						</p>

						<div class="table-responsive">
							<table class="table table-hover table-bordered tablewithSearch">
								<thead>
									<tr>
										<th>Employee</th>
										<th>Category</th>
										<th>Amount</th>
										<th>Remarks</th>
										<th>Date Created</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($adjustments as $adjustment)
										<tr>
											<td>
												{{ $adjustment->employee->last_name . ', ' . $adjustment->employee->first_name . ' ' . $adjustment->employee->middle_name }}
											</td>
											<td>{{ $adjustment->category }}</td>
											<td>{{ $adjustment->type }}</td>
											<td>{{ number_format($adjustment->amount) }}</td>
											<td>{{ $adjustment->description }}</td>
											<td>{{ $adjustment->remarks }}</td>
											<td>{{ date('M d Y ', strtotime($adjustment->created_at)) }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			@include('salary_managements.new_salary_adjust')
		</div>
	@endsection
