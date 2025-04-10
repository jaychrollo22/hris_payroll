@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Non Taxable Consultants</h4>
						<p class="card-description">
							<button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#newConsultant">
								<i class="ti-plus btn-icon-prepend"></i>
								New Consultant
							</button>
						</p>

						<div class="table-responsive">
							<table class="table table-hover table-bordered tablewithSearch">
								<thead>
									<tr>
										<th>Name</th>
										<th>Date Created</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($nontaxable_consultants as $consultant)
										<tr>
											<td>{{ $consultant->first_name. ' '. $consultant->last_name }}</td>
											<td> {{ date('M d Y ', strtotime($consultant->created_at)) }}</td>
											<td>
												<a href="delete-consultant/{{$consultant->user_id}}">
													<button  title='DELETE' onclick="return confirm('Are you sure you want to delete this Consultant Setting?')" class="btn btn-rounded btn-danger btn-icon">
														<i class="ti-trash"></i>
													</button>
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
	{{-- @foreach ($deductions as $deduction)
		@include('deductions.edit_deduction')
	@endforeach --}}
	@include('consultant_settings.new_consultant')
@endsection
{{-- @section('empAllowScript')
	<script>
		function disable(id) {
			var element = document.getElementById('tdActionId' + id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "Once disabled, you will not be able to recover this.",
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
@endsection --}}
