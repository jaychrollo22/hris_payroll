@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Projects</h4>
						<p class="card-description">
							<button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal"
								data-target="#newProject">
								<i class="ti-plus btn-icon-prepend"></i>
								New Project
							</button>
						</p>

						<div class="table-responsive">
							<table class="table table-hover table-bordered tablewithSearch">
								<thead>
									<tr>
										<th>Project ID</th>
										<th>Project Title</th>
										<th>Company</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($projects as $project)
										<tr>
											<td>{{ $project->project_id }}</td>
											<td>{{ $project->project_title }}</td>
											<td>{{ $project->company->company_name }}</td>
											<td id="tdActionId{{ $project->id }}" data-id="{{ $project->id }}">
												<a href="/edit-project/{{$project->id}}" class="btn btn-outline-info btn-icon-text btn-sm">
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
	@include('masterfiles.new_project')
@endsection