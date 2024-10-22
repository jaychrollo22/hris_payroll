@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Deductions</h4>
						<p class="card-description">
							<button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#newDeduction">
								<i class="ti-plus btn-icon-prepend"></i>
								New Deduction
							</button>
						</p>

						<h4 class="card-title">Filter</h4>
						<p class="card-description">
							<form method='get' onsubmit='show();' enctype="multipart/form-data">
								<div class=row>
									<div class='col-md-2 mr-2'>
										<div class="form-group">
											<label class="text-right">Status</label>
											<select data-placeholder="Select Status" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='status' required>
												<option value="">-- Select Status --</option>
												<option value="Active" @if ('Active' == $status) selected @endif>Active</option>
												<option value="Inactive" @if ('Inactive' == $status) selected @endif>Inactive</option>
											</select>
										</div>
									</div>
									<div class='col-md-2'>
										<button type="submit" class="form-control form-control-sm btn btn-primary mb-2 btn-sm">Generate</button>
									</div>
								</div>
								
							</form>
						</p>

						<div class="table-responsive">
							<table class="table table-hover table-bordered tablewithSearch">
								<thead>
									<tr>
										<th>Deduction Name</th>
										<th>Date Created</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($deductions as $deduction)
										<tr>
											<td>{{ $deduction->name }}</td>
											<td> {{ date('M d Y ', strtotime($deduction->created_at)) }}</td>
											<td id="tdId{{ $deduction->id }}">
												@if ($deduction->status == 'Active')
													<label id="status{{ $deduction->id }}" class="badge badge-success">{{ $deduction->status }}</label>
												@else
													<label id="status{{ $deduction->id }}" class="badge badge-danger">{{ $deduction->status }}</label>
												@endif
											</td>
											<td id="tdActionId{{ $deduction->id }}" data-id="{{ $deduction->id }}">
												@if ($deduction->status == 'Active')
													<button type="button" id="edit{{ $deduction->id }}" class="btn btn-info btn-rounded btn-icon"
														data-target="#edit_deduction{{ $deduction->id }}" data-toggle="modal" title='Edit'>
														<i class="ti-pencil-alt"></i>
													</button>
													<button title='Disable' id="{{ $deduction->id }}" onclick="disable(this.id)"
														class="btn btn-rounded btn-danger btn-icon">
														<i class="fa fa-ban"></i>
													</button>
												@endif
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
	@foreach ($deductions as $deduction)
		@include('deductions.edit_deduction')
	@endforeach
	@include('deductions.new_deduction')
@endsection
@section('deductionScript')
	<script>
		function disable(id) {
			var element = document.getElementById('tdActionId' + id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "Once disabled, you will not be able to recover this imaginary file!",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDisable) => {
					if (willDisable) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "disable-deduction/" + id,
							method: "GET",
							data: {
								id: id
							},
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("Deduction has been disable!", {
									icon: "success",
								}).then(function() {
									document.getElementById("tdId" + id).innerHTML =
										"<label class='badge badge-danger'>Inactive</label>";
									document.getElementById("tdActionId" + dataID).innerHTML =
										"<button title='Activate' id='action" + id +
										"' onclick='activate(" + id +
										")' class = 'btn btn-rounded btn-primary btn-icon' ><i class ='fa fa-check' > </i></button > "

								});
							}
						})

					} else {
						swal("Deduction is safe!");
					}
				});
		}
	</script>
@endsection
