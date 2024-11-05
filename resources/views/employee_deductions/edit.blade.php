@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Employee Deduction for {{ $employee_deduction->employee->first_name . ' ' . $employee_deduction->employee->last_name }}</h4>
                        <div class="col-md-12">
							<form method='POST' action='{{url('update-employee-deduction/'.$employee_deduction->id)}}' onsubmit='show()'>
								@csrf
								<div class="modal-body">
									<div class="row">
                                        <div class='col-lg-6 form-group'>
                                            <label for="deductionType">Deduction</label>
                                            <select data-placeholder="Select Deduction"
                                                class="form-control form-control-sm required js-example-basic-single " style='width:100%;' name='deduction_id'
                                                required>
                                                <option value="">--Select Deduction --</option>
                                                @foreach ($deductionTypes as $deductionType)
                                                    <option value="{{ $deductionType->id }}" {{ $employee_deduction->deduction_id == $deductionType->id ? 'selected' : '' }}>
                                                        {{ $deductionType->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <label for="amount">Amount</label>
                                            <input type="number" class="form-control form-control-sm" name="amount" id="amount" required min="1"
                                                value="{{ $employee_deduction->amount }}" placeholder="0.00">
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label for="amount">No. of years Deduction</label>
                                            <input type="number" class="form-control form-control-sm" name="no_of_years_deduction" id="no_of_years_deduction" required min="1"
                                                value="{{ $employee_deduction->no_of_years_deduction }}" placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <label for="amortization">Amortization</label>
                                            <input type="number" class="form-control form-control-sm" name="amortization" id="amortization" required min="1"
                                                value="{{ $employee_deduction->amortization }}" placeholder="0.00">
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label for="type_of_deduction">Type of Deduction</label>
                                            <select name="type_of_deduction" id="type_of_deduction" class="form-control form-control-sm">
                                                <option value="">Choose Type of Deduction</option>
                                                <option value="First Cut-Off" {{ $employee_deduction->type_of_deduction == "First Cut-Off" ? 'selected' : '' }}>First Cut-Off</option>
                                                <option value="Second Cut-Off" {{ $employee_deduction->type_of_deduction == "Second Cut-Off" ? 'selected' : '' }}>Second Cut-Off</option>
                                                <option value="Every Cut-Off" {{ $employee_deduction->type_of_deduction == "Every Cut-Off" ? 'selected' : '' }}>Every Cut-Off</option>
                                            </select>
                                        </div>
                                    </div>
								</div>
								<div class="modal-footer">
									<a href="/employee-deduction" type="button" class="btn btn-secondary" data-dismiss="modal">Close</a>
									<button type="submit" class="btn btn-primary">Save</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
