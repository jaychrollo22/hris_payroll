@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Employee Leave Type Balance : {{$employee_leave_type_balance->user->name}}</h4>
                        <div class="col-md-12">
                            <form method='POST' action='{{url('update-employee-leave-type-balance/'.$employee_leave_type_balance->id)}}' onsubmit='show()'>
                                @csrf
                                <input type="hidden" name="search" value="{{$search}}"/>
                                <input type="hidden" name="company" value="{{$company}}"/>
                                <input type="hidden" name="department" value="{{$department}}"/>
                                <input type="hidden" name="status" value="{{$status}}"/>
                                
                                <div class="modal-body">
                                    <div class="row">
                                        <div class='col-lg-12 form-group'>
                                            <label for="leaveType">Leave Type</label>
                                            <select data-placeholder="Select Leave Type"
                                                class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='leave_type'
                                                required>
                                                <option value="">--Select Leave Type--</option>
                                                @foreach ($leave_types as $leaveType)
                                                    <option value="{{ $leaveType->code }}" {{ $employee_leave_type_balance->leave_type == $leaveType->code ? 'selected' : "" }}>
                                                        {{ $leaveType->leave_type }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    
                                        <div class="col-lg-12 form-group">
                                            <label for="balance">Leave Balance</label>
                                            <input type="number" class="form-control form-control-sm" name="balance" id="balance" required min=".00" step='0.01'
                                                placeholder="0.00" value="{{$employee_leave_type_balance->balance}}">
                                        </div>
                            
                                        <div class="col-lg-12 form-group">
                                            <label for="year">Year</label>
                                            <input type="number" class="form-control form-control-sm" name="year" id="year" required
                                                placeholder="0000" value="{{$employee_leave_type_balance->year}}">
                                        </div>

                                        <div class="col-lg-12 form-group">
                                            <label for="status">Status</label>
                                            <select name="status" class="form-control">
                                                <option value="Active" {{ $employee_leave_type_balance->status == 'Active' ? 'selected' : "" }}>Active</option>
                                                <option value="Inactive" {{ $employee_leave_type_balance->status == 'Inactive' ? 'selected' : "" }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="{{url()->previous()}}" type="button" class="btn btn-secondary" data-dismiss="modal">Close</a>
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

