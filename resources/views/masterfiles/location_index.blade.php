@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Locations</h4>
						<p class="card-description">
							<button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal"
								data-target="#newLocation">
								<i class="ti-plus btn-icon-prepend"></i>
								New Location
							</button>
						</p>

						<div class="table-responsive">
							<table class="table table-hover table-bordered tablewithSearch">
								<thead>
									<tr>
										<th>Location</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($locations as $location)
										<tr>
											<td>{{ $location->location }}</td>
											<td id="tdActionId{{ $location->id }}" data-id="{{ $location->id }}">
												<a href="/edit-location/{{$location->id}}" class="btn btn-outline-info btn-icon-text btn-sm">
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
	@include('masterfiles.new_location')
@endsection