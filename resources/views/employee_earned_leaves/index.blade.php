@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Employee Earned Leaves</h4>
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
										<th>Date</th>
										<th>Employee</th>
										<th>Company</th>
										{{-- <th>Day</th>
										<th>Month</th> --}}
										<th>Type</th>
										<th>Earned</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($earned_leaves as $item)
									
                                        <tr>
                                            <td>{{ date('Y-m-d',strtotime($item->created_at))}}</td>
                                            <td>{{ $item->employee->first_name . ' ' . $item->employee->last_name}}</td>
                                            <td>{{ $item->employee->company->company_name}}</td>
                                            {{-- <td>{{ $item->earned_day}}</td>
                                            <td>{{ $item->earned_month}}</td> --}}
                                            <td>{{ $item->leave_type_info->leave_type}}</td>
                                            <td>{{ $item->earned_leave}}</td>
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
@endsection
