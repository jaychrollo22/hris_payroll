@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Early Cutoffs</h4>
						<p class="card-description">
							<button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal"
								data-target="#newEarlyCutoff">
								<i class="ti-plus btn-icon-prepend"></i>
								New
							</button>
						</p>

						<div class="table-responsive">
							<table class="table table-hover table-bordered tablewithSearch">
								<thead>
									<tr>
										<th>From</th>
										<th>To</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($early_cutoffs as $early_cutoff)
										<tr>
											<td>{{ $early_cutoff->from }}</td>
											<td>{{ $early_cutoff->to }}</td>
											<td>{{ $early_cutoff->status }}</td>
											<td id="tdActionId{{ $early_cutoff->id }}" data-id="{{ $early_cutoff->id }}">
												<a href="/edit-early-cutoff/{{$early_cutoff->id}}" class="btn btn-outline-info btn-icon-text btn-sm">
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
	@include('masterfiles.early_cutoffs.new_early_cutoff')
@endsection