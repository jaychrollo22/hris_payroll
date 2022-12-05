@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Employee Leave Credit</h4>
						<p class="card-description">
							<button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal"
								data-target="#newLeaveCredits">
								<i class="ti-plus btn-icon-prepend"></i>
								New Leave Credit
							</button>
						</p>
						@if ($errors->any())
							@foreach ($errors->all() as $error)
								<div class="alert alert-danger alert-dismissible fade show" role="alert">
									{{ $error }}
								</div>
							@endforeach
						@endif
						<div class="table-responsive">
							<table class="table table-hover table-bordered tablewithSearch">
								<thead>
									<tr>
										<th>Leave Credits</th>
										<th>Employee</th>
										<th>Date Created</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($leaveCredits as $leaveCredit)
										<tr>
											
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
	@include('employee_leave_credits.new_leave_credit')
@endsection
@section('leaveCreditScript')
	<script>
		
	</script>
@endsection
