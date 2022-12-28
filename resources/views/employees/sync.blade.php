@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">
			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Sync Biometrics</h4>
						<p class="card-description">
						<form method='get' onsubmit='show();' enctype="multipart/form-data">
							<div class=row>
								<div class='col-md-3'>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label text-right">Terminals</label>
										<div class="col-sm-8">
                                            <select data-placeholder="Terminal" class="form-control form-control-sm required js-example-basic-single " style='width:100%;' name='terminal' required>
                                                <option value="">--Terminal--</option>
                                                  @foreach($terminals as $terminal)
                                                    <option value="{{$terminal->id}}">{{$terminal->alias}}</option>
                                                  @endforeach
                                              </select>
										</div>
									</div>
								</div>
								<div class='col-md-3'>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label text-right">From</label>
										<div class="col-sm-8">
											<input type="date" value='' class="form-control form-control-sm" name="from"
												 onchange='get_min(this.value);' required />
										</div>
									</div>
								</div>
								<div class='col-md-3'>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label text-right">To</label>
										<div class="col-sm-8">
											<input type="date" value='' class="form-control form-control-sm" id='to' name="to"
												required />
										</div>
									</div>
								</div>
								<div class='col-md-3'>
									<button type="submit" class="form-control form-control-sm btn btn-primary mb-2 btn-sm">Sync</button>
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
