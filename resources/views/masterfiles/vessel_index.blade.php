@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Vessels</h4>
						<p class="card-description">
							<button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal"
								data-target="#newVessel">
								<i class="ti-plus btn-icon-prepend"></i>
								New Vessel
							</button>
						</p>

						<div class="table-responsive">
							<table class="table table-hover table-bordered tablewithSearch">
								<thead>
									<tr>
										<th>Code</th>
										<th>Name</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($vessels as $vessel)
										<tr>
											<td>{{ $vessel->code }}</td>
											<td>{{ $vessel->name }}</td>
											<td>{{ $vessel->status }}</td>
											<td id="tdActionId{{ $vessel->id }}" data-id="{{ $vessel->id }}">
												<a href="/edit-vessel/{{$vessel->id}}" class="btn btn-outline-info btn-icon-text btn-sm">
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
	@include('masterfiles.new_vessel')
@endsection