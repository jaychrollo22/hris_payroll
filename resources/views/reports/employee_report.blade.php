@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">
			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Employee Reports</h4>
						<p class="card-description">
						<form method='get' onsubmit='show();' enctype="multipart/form-data">
							<div class=row>
								<div class='col-md-4'>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label text-right">From</label>
										<div class="col-sm-8">
											<input type="date" value='' class="form-control form-control-sm" name="from"
												max='{{ date('Y-m-d') }}' onchange='get_min(this.value);' required />
										</div>
									</div>
								</div>
								<div class='col-md-4'>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label text-right">To</label>
										<div class="col-sm-8">
											<input type="date" value='' class="form-control form-control-sm" id='to' name="to"
												max='{{ date('Y-m-d') }}' required />
										</div>
									</div>
								</div>
								<div class='col-md-4'>
									<button type="submit" class="form-control form-control-sm btn btn-primary mb-2 btn-sm">Generate</button>
								</div>
							</div>
						</form>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
