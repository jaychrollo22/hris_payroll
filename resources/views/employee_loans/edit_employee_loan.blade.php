@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Employee Loan :{{$employee_loan->user->name}}</h4>
                        <form method='POST' action='{{url('update-employee-loan/'.$employee_loan->id)}}' onsubmit='show()'>
                            @csrf
                            <input type="hidden" name="search" value="{{$search}}"/>
                            <input type="hidden" name="company" value="{{$company}}"/>
                            <input type="hidden" name="department" value="{{$department}}"/>
                            <input type="hidden" name="status" value="{{$status}}"/>
                            
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-4 form-group">
                                        <label for="reference_id_number">Reference ID Number</label>
                                        <input type="text" class="form-control form-control-sm" name="reference_id_number" id="reference_id_number"
                                            value="{{ $employee_loan->reference_id_number }}" placeholder="Reference ID Number">
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label for="collection_date">Collection Date</label>
                                        <input type="date" class="form-control form-control-sm" name="collection_date" id="collection_date"
                                            value="{{ $employee_loan->collection_date }}" placeholder="">
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label for="due_date">Due Date</label>
                                        <input type="date" class="form-control form-control-sm" name="due_date" id="due_date"
                                            value="{{ $employee_loan->due_date }}" placeholder="">
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label for="particular">Particular</label>
                                        <select data-placeholder="Select Particular" class="form-control form-control-sm required js-example-basic-multiple "
                                        style='width:100%;' name='particular' required>
                                            <option value="">--Select Particular--</option>
                                            <option value="SSS" {{ $employee_loan->particular == 'SSS' ? 'selected' : "" }}>SSS</option>
                                            <option value="HDMF" {{ $employee_loan->particular == 'HDMF' ? 'selected' : "" }}>HDMF</option>
                                            <option value="Personal" {{ $employee_loan->particular == 'Personal' ? 'selected' : "" }}>Personal</option>
                                            <option value="Insurance" {{ $employee_loan->particular == 'Insurance' ? 'selected' : "" }}>Insurance</option>
                                            <option value="Miscellaneous" {{ $employee_loan->particular == 'Miscellaneous' ? 'selected' : "" }}>Miscellaneous</option>
                                            <option value="Others" {{ $employee_loan->particular == 'Others' ? 'selected' : "" }}>Others</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label for="description">Description</label>
                                        <input type="text" class="form-control form-control-sm" name="description" id="description"
                                            value="{{ $employee_loan->description }}" placeholder="">
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label for="particular">Credit Schedule</label>
                                        <select data-placeholder="Select Credit Schedule" class="form-control form-control-sm required js-example-basic-multiple "
                                        style='width:100%;' name='credit_schedule' required>
                                            <option value="">--Select Credit Schedule--</option>
                                            <option value="First Cut-Off" {{ $employee_loan->credit_schedule == 'First Cut-Off' ? 'selected' : "" }}>First Cut-Off</option>
                                            <option value="Last Cut-Off" {{ $employee_loan->credit_schedule == 'Last Cut-Off' ? 'sel ected' : "" }}>Last Cut-Off</option>
                                            <option value="Every Cut-Off" {{ $employee_loan->credit_schedule == 'Every Cut-Off' ? 'selected' : "" }}>Every Cut-Off</option>
                                        </select>
                                    </div>
            
                                    <div class='col-lg-4 form-group'>
                                        <label for="credit_company">Credit Company</label>
                                        <select data-placeholder="Select Credit Company"
                                            class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='credit_company'
                                            required>
                                            <option value="">--Select Credit Company--</option>
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->id }}" {{ $employee_loan->credit_company == $company->id ? 'selected' : "" }}>
                                                    {{ $company->company_name }} - {{ $company->company_code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class='col-lg-4 form-group'>
                                        <label for="credit_branch">Credit Branch</label>
                                        <select data-placeholder="Select Credit Branch"
                                            class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='credit_branch'
                                            required>
                                            <option value="">--Select Credit Branch--</option>
                                            <option value="MAIN BRANCH" {{ $employee_loan->credit_branch == 'MAIN BRANCH' ? 'selected' : "" }}>MAIN BRANCH</option>
                                        </select>
                                    </div>
                                    <div class='col-lg-4 form-group'>
                                        <label for="payable_amount">Payable Amount</label>
                                        <input type="number" class="form-control form-control-sm" name="payable_amount" id="description"
                                            value="{{ $employee_loan->payable_amount }}" min=".00" step='0.01' placeholder="0.00">
                                    </div>
                                    <div class='col-lg-4 form-group'>
                                        <label for="payable_adjustment">Payable Adjustment</label>
                                        <input type="number" class="form-control form-control-sm" name="payable_adjustment" id="payable_adjustment"
                                            value="{{ $employee_loan->payable_adjustment }}" min=".00" step='0.01' placeholder="0.00">
                                    </div>
                                    <div class='col-lg-4 form-group'>
                                        <label for="credit_branch">Outright Deduction Bolean</label>
                                        <select data-placeholder="Select Outright Deduction Bolean"
                                            class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='outright_deduction_bolean'
                                            required>
                                            <option value="">--Select Outright Deduction Bolean--</option>
                                            <option value="1" {{ $employee_loan->outright_deduction_bolean == '1' ? 'selected' : "" }}>True</option>
                                            <option value="0" {{ $employee_loan->outright_deduction_bolean == '0' ? 'selected' : "" }}>False</option>
                                        </select>
                                    </div>
                                    <div class='col-lg-4 form-group'>
                                        <label for="monthly_deduction">Monthly Deduction</label>
                                        <input type="number" class="form-control form-control-sm" name="monthly_deduction" id="monthly_deduction"
                                            value="{{ $employee_loan->monthly_deduction }}" min=".00" step='0.01' placeholder="0.00">
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <a href="{{url()->previous()}}" type="button" class="btn btn-secondary">Close</a>
                                <button id="btnNewLoan" name="btnNewLoan" type="submit" class="btn btn-primary">Save</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection