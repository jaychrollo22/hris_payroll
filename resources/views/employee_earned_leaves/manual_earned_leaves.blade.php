@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Manual Employee Earned Leaves</h4>
						@if ($errors->any())
							@foreach ($errors->all() as $error)
								<div class="alert alert-danger alert-dismissible fade show" role="alert">
									{{ $error }}
								</div>
							@endforeach
						@endif
                        <form method='POST' action='manual-employee-earned-leaves-store' onsubmit='show()'>
                            <div class="row">
                                    @csrf
                                    <div class="col-md-3 mt-1">
                                        Employee : 
                                        <select data-placeholder="Select Employee" class="form-control form-control-sm required js-example-basic-multiple "
                                            style='width:100%;' name='user_id' required>
                                            <option value="">--Select Employee--</option>
                                            @foreach ($employees_selection as $employee)
                                                <option value="{{ $employee->user_id }}" @if ($employee->user_id == $user_id) selected @endif>
                                                    {{ $employee->user_id . ' - ' . $employee->last_name . ', ' . $employee->first_name . ' (' . $employee->original_date_hired . ') ' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mt-1">
                                        From : 
                                        <input type="month" class="form-control" name="from" value="{{$date_from}}">
                                    </div>
                                    <div class="col-md-3 mt-1">
                                        To : 
                                        <input type="month" class="form-control" name="to" value="{{$date_to}}">
                                    </div>
                                    <div class="col-md-3 mt-1">
                                        <button class="btn btn-md btn-primary mt-1">Submit</button>
                                        <a href="/employee-earned-leaves" class="btn btn-info mt-1">Earned Leaves</a>
                                    </div>
                            </div>
                        </form>
                        
                        @if(count($earned_leaves) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered tablewithSearch">
                                    <thead>
                                        <tr>
                                            <th>Earned Date</th>
                                            <th>Employee</th>
                                            <th>Classification</th>
                                            <th>Company</th>
                                            <th>Type</th>
                                            <th>Month</th>
                                            <th>Year</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($earned_leaves as $item)
                                            <tr>
                                                <td>{{ date('F Y',strtotime($item->earned_date))}}</td>
                                                <td>{{ $item->employee->first_name . ' ' . $item->employee->last_name}}</td>
                                                <td>{{ $item->employee->classification_info->name}}</td>
                                                <td>{{ $item->employee->company->company_name}}</td>
                                                <td>{{ $item->leave_type_info->leave_type}}</td>
                                                <td>{{ date('F',strtotime($item->earned_date))}}</td>
											    <td>{{ date('Y',strtotime($item->earned_date))}}</td>
                                                <td align="center">
                                                    <button  id="{{ $item->id }}" class="btn btn-icon btn-warning" onclick="cancel('{{$item->id}}','{{$item->employee->user_id}}','{{$date_from}}','{{$date_to}}')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td> 
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('ForApprovalScript')
	<script>
		function cancel(id,user_id,from,to) {
			swal({
					title: "Are you sure?",
					text: "You want to delete this leave earned?",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willCancel) => {
					if (willCancel) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: '/manual-employee-earned-leaves-delete?id='+id+'&user_id='+user_id+'&from='+from+'&to='+to,
							method: "GET",
							data: {
								id: id
							},
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
                                location.reload();
                            }
						})

					} else {
            swal({text:"You stop the cancelation of leave.",icon:"success"});
					}
				});
		}

	</script>
@endsection