@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Employee Leave Balances</h4>

                        <h4 class="card-title">Filter</h4>
						<p class="card-description">
						<form method='get' onsubmit='show();' enctype="multipart/form-data">
							<div class=row>
								<div class='col-md-4'>
									<div class="form-group">
										<select data-placeholder="Select Company" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='company' required>
											<option value="">-- Select Company --</option>
											@foreach($companies as $comp)
											<option value="{{$comp->id}}" @if ($comp->id == $company) selected @endif>{{$comp->company_name}} - {{$comp->company_code}}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class='col-md-3'>
									<div class="form-group">
										
                                        <select data-placeholder="Select Department" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='department'>
                                            <option value="">-- Select Department --</option>
                                            @foreach($departments as $dep)
                                            <option value="{{$dep->id}}" @if ($dep->id == $department) selected @endif>{{$dep->name}} - {{$dep->code}}</option>
                                            @endforeach
                                        </select>
										
									</div>
								</div>
								<div class='col-md-2'>
									<button type="submit" class="form-control form-control-sm btn btn-primary mb-2 btn-sm">Filter</button>
								</div>
							</div>
							
						</form>
						</p>

                        <div class="table-responsive">
							<table class="table table-hover table-bordered tablewithSearch">
								<thead>
									<tr>
										<th>User ID</th>
										<th>Employee</th>
										<th>Company</th>
										<th>Department</th>
										<th>Leaves Balances</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($employees as $employee)
                                    <tr>
                                        <td>{{ $employee->user_id}}</td>
                                        <td>
                                            {{ $employee->first_name . ' ' . $employee->last_name}} <br>
                                            <small>Date Hired: {{ $employee->original_date_hired }}</small>
                                        
                                        </td>
                                        <td>{{ $employee->company->company_name}}</td>
                                        <td>{{ $employee->department->name}}</td>
                                        <td>
                                            @php
                                                $used_vl = checkUsedSLVLSILLeave($employee->user_id,1,$employee->original_date_hired);
                                                $used_sl = checkUsedSLVLSILLeave($employee->user_id,2,$employee->original_date_hired);
                                                $used_sil = checkUsedSLVLSILLeave($employee->user_id,10,$employee->original_date_hired);
                                                $used_ml = checkUsedLeave($employee->user_id,3);
                                                $used_pl = checkUsedLeave($employee->user_id,4);
                                                $used_spl = checkUsedLeave($employee->user_id,5);
                                                $used_splw = checkUsedLeave($employee->user_id,7);
                                                $used_splvv = checkUsedLeave($employee->user_id,9);
                                            
                                                $earned_vl = checkEarnedLeave($employee->user_id,1,$employee->original_date_hired);
                                                $earned_sl = checkEarnedLeave($employee->user_id,2,$employee->original_date_hired);
                                                $earned_sil = checkEarnedLeave($employee->user_id,10,$employee->original_date_hired);

                                              

                                              
                                                // if($total_months > 11){ //
                                                
                                                //     $original_date_hired_m_d = date('m-d',strtotime($employee->original_date_hired));
                                                //     $original_date_hired_m = date('m',strtotime($employee->original_date_hired));
                                                //     $today = date('Y-m-d');
                                                //     $last_year = date('Y', strtotime('-1 year', strtotime($today)) );
                                                //     $original_date_hired = $last_year . '-' . $original_date_hired_m_d;

                                                //     if($last_year == 2022){
                                                //         $vl_beginning_balance = checkEmployeeLeaveCredits($employee->user_id,1);
                                                //         $sl_beginning_balance = checkEmployeeLeaveCredits($employee->user_id,2);
                                                //     }
                                                // }
                                                // else{
                                                    $vl_beginning_balance = checkEmployeeLeaveCredits($employee->user_id,1);
                                                    $sl_beginning_balance = checkEmployeeLeaveCredits($employee->user_id,2);
                                                // }

                                                
                                                $total_vl = $vl_beginning_balance + $earned_vl;
                                                $total_sl = $sl_beginning_balance + $earned_sl;

                                            @endphp
                                            Total VL : {{ ($total_vl) }} Used : {{$used_vl}} Remaining Balance : {{ ($total_vl) - $used_vl }} <br>
                                            Total SL : {{ ($total_sl) }} Used : {{$used_sl}} Remaining Balance : {{ ($total_sl) - $used_sl }} <br> 


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
@endsection