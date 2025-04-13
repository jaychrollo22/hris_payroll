@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Pagibig Contribution Settings</h4>
						<p class="card-description">
							<button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#newPagibigContribution">
								<i class="ti-plus btn-icon-prepend"></i>
								New Pagibig Contribution 
							</button>
						</p>

						<div class="table-responsive">
							<table class="table table-hover table-bordered tablewithSearch">
								<thead>
									<tr>
										<th>Name</th>
										<th>Amount</th>
										<th>Payrill Cut-Off</th>
										<th>Date Created</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($pagibig_contributions as $pagibig_contribution)
										<tr>
											<td>{{ $pagibig_contribution->employee->first_name. ' '. $pagibig_contribution->employee->last_name }}</td>
											<td>{{ number_format($pagibig_contribution->amount,2) }}</td>
											<td>{{ $pagibig_contribution->payroll_cutoff }}</td>
											<td> {{ date('M d Y ', strtotime($pagibig_contribution->created_at)) }}</td>
											<td>
												<button type="button" id="edit{{ $pagibig_contribution->id }}" class="btn btn-info btn-rounded btn-icon"
													data-target="#edit_pagibig_contribution{{ $pagibig_contribution->id }}" data-toggle="modal" title='Edit'>
													<i class="ti-pencil-alt"></i>
												</button>
												<a href="delete-pagibig-contribution/{{$pagibig_contribution->id}}">
													<button  title='DELETE' onclick="return confirm('Are you sure you want to delete this Pagibig Contribution Setting?')" class="btn btn-rounded btn-danger btn-icon">
														<i class="ti-trash"></i>
													</button>
												</a>
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
	@foreach ($pagibig_contributions as $pagibig_contribution)
		@include('pagibig_contribution_settings.edit_pagibig_contribution')
	@endforeach
	@include('pagibig_contribution_settings.new_pagibig_contribution')
@endsection
