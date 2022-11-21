@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Loan Register</h4>
						<p class="card-description">
							<button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#newLoan">
								<i class="ti-plus btn-icon-prepend"></i>
								New loan
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
							<table class="table table-hover table-bordered" id="loanTbl">
								<thead>
									<tr>
										<th>Loan Type</th>
										<th>Employee</th>
										<th>Amount</th>
										<th>Start Date</th>
										<th>End Date</th>
										<th hidden>Initial Amount</th>
										<th>Status</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($loans as $loan)
										<tr>
											<td data-title="Loan Type">{{ $loan->loan_type->loan_name }}</td>
											<td data-title="Full Name">
												{{ $loan->employee->last_name . ', ' . $loan->employee->first_name . ' ' . $loan->employee->middle_name }}
											</td>
											<td data-title="Amount">{{ number_format($loan->amount) }}</td>
											<td data-title="Start Date">{{ date('M d, Y', strtotime($loan->start_date)) }}</td>
											<td data-title="End Date">{{ date('M d, Y', strtotime($loan->expiry_date)) }}</td>
											<td data-title="Initial Amount" hidden>{{ number_format($loan->initial_amount) }}</td>
											<td></td>
											<td>
												<button title='View loan details' id="" data-toggle="modal" data-target="#loanDetails"
													data-id="{{ $loan->id }}" class="btn  btn-rounded btn-primary btn-icon">
													<i class="fa fa-info"></i>
												</button>
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
	@include('loans.new_loan')
	@include('loans.loan_details')

@endsection
@section('loanRegScripts')
	<script>
		$(document).ready(function() {
			$('#loanDetails').on('show.bs.modal', function(e) {
				var _button = $(e.relatedTarget);
				var result = "";
				var $row = $(_button).closest("tr"); // Find the row
				var $tds = $row.find("td");
				$.each($tds, function() {
					var t = $(this).attr('data-title');
					var v = $(this).text();
					if (t != undefined) {
						result += '<div>' + t + ' : ' + v + '</div>';
					}

				});
				$(this).find("#container").html(result);
			});
		});

		$(document).ready(function() {
			$('#loanTbl').DataTable();
		});
	</script>
@endsection
