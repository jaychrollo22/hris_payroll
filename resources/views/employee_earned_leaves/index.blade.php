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

						<h4 class="card-title">Filter</h4>
						<form method='get' onsubmit='show();' enctype="multipart/form-data">
							<div class=row>
                                <div class='col-md-2'>
									<div class="form-group">
                                        <input type="text" class="form-control" name="search" placeholder="Search Name" value="{{$search}}">
                                    </div>
                                </div>
                                <div class='col-md-2'>
									<div class="form-group">
                                        <select data-placeholder="Select Leave" class="form-control" style='width:100%;' name='leave_type'>
                                            <option value="">-- Select Leave --</option>
                                            @foreach($leave_types as $leave)
                                            <option value="{{$leave->id}}" @if ($leave->id == $leave_type) selected @endif>{{$leave->leave_type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
								<div class='col-md-2'>
									<div class="form-group">
										<input type="month" class="form-control" name="month_year" value="{{$month_year}}">
									</div>
								</div>
                                <div class='col-md-2'>
									<div class="form-group">
                                        <select data-placeholder="Filter Earned Leave" class="form-control" style='width:100%;' name='order_by'>
                                            <option value="">-- Filter Earned Leave --</option>
                                            <option value="ASC" @if ('ASC' == $order_by) selected @endif>Ascending</option>
                                            <option value="DESC" @if ('DESC' == $order_by) selected @endif>Descending</option>
                                        </select>
                                    </div>
                                </div>
                                <div class='col-md-3'>
									<button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="/employee-earned-leaves" class="btn btn-warning">Reset Filter</a>
                                    <a href="/manual-employee-earned-leaves" class="btn btn-info">Manual</a>
								</div>
                            </div>
                        </form>

						<div class="table-responsive">
							<table class="table table-hover table-bordered tablewithSearch">
								<thead>
									<tr>
										
										<th>User ID</th>
										<th>Employee</th>
										<th>Classification</th>
										<th>Company</th>
										<th>Month</th>
										<th>Year</th>
										<th>Type</th>
										<th>Earned</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($earned_leaves as $item)
									
                                        <tr>
                                            
                                            <td>{{ $item->user_id }}</td>
                                            <td>{{ $item->employee  ? $item->employee->first_name . ' ' . $item->employee->last_name . ' ('.$item->employee->original_date_hired.')' : ""}}</td>
                                            <td>{{ $item->employee  ? $item->employee->classification_info->name : ""}}</td>
                                            <td>{{ $item->employee  ? $item->employee->company->company_name : ""}}</td>
											<td>{{ date('F',strtotime($item->earned_date))}}</td>
											<td>{{ date('Y',strtotime($item->earned_date))}}</td>
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
