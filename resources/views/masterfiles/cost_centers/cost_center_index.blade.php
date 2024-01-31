@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Cost Centers</h4>
						<p class="card-description">
							<button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal"
								data-target="#newCostCenter">
								<i class="ti-plus btn-icon-prepend"></i>
								New
							</button>
						</p>

						<div class="table-responsive">
							<table class="table table-hover table-bordered tablewithSearch">
								<thead>
									<tr>
										<th>Code</th>
										<th>Name</th>
										<th>Company</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($cost_centers as $cost_center)
										<tr>
											<td>{{ $cost_center->code }}</td>
											<td>{{ $cost_center->name }}</td>
											<td>{{ $cost_center->company_code }}</td>
											<td>{{ $cost_center->status }}</td>
											<td id="tdActionId{{ $cost_center->id }}" data-id="{{ $cost_center->id }}">
												<a href="/edit-cost-center/{{$cost_center->id}}" class="btn btn-outline-info btn-icon-text btn-sm">
													Edit
													<i class="ti-pencil btn-icon-append"></i>
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
	@include('masterfiles.cost_centers.new_cost_center')
@endsection