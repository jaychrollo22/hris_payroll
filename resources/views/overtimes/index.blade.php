@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Overtime Reports</h4>
                        <p class="card-description">
                            <form method='get' onsubmit='show();' enctype="multipart/form-data">
                                <div class=row>
                                    <div class='col-md-3'>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label text-right">Company</label>
                                            <div class="col-sm-8">
                                                <select data-placeholder="Select Company" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='company' required>
                                                    <option value="">-- Select Employee --</option>
                                                    @foreach($companies as $comp)
                                                    <option value="{{$comp->id}}">{{$comp->company_name}} - {{$comp->company_code}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col-md-3'>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label text-right">From</label>
                                            <div class="col-sm-8">
                                                <input type="date" value='{{$from_date}}' class="form-control" name="from" max='{{date('Y-m-d')}}' onchange='get_min(this.value);' required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col-md-3'>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label text-right">To</label>
                                            <div class="col-sm-8">
                                                <input type="date" value='{{$to_date}}' class="form-control" name="to" id='to' max='{{date('Y-m-d')}}' required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col-md-3'>
                                        <button type="submit" class="btn btn-primary mb-2">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </p>
                        @if(count($employee_overtimes) > 0)
                        <button class='btn btn-info mb-1' onclick="exportTableToExcel('overtime_report','Overtime Report {{$from_date}} - {{$to_date}}')">Export</button>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered tablewithSearch" id="overtime_report">
                                <thead>
                                    <tr>
                                        <th>User ID</th>
                                        <th>Biometric ID</th>
                                        <th>Name</th>
                                        <th>Date</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employee_overtimes as $item)
                                    <tr>
                                        <td>{{$item->employee->user_id}}</td>
                                        <td>{{$item->employee->employee_number}}</td>
                                        <td>{{$item->user->name}}</td>
                                        <td>{{$item->ot_date}}</td>
                                        <td>{{$item->start_time}}</td>
                                        <td>{{$item->end_time}}</td>
                                        <td>{{$item->status}}</td>
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


@endsection
@section('footer')
<script src="{{ asset('body_css/vendors/inputmask/jquery.inputmask.bundle.js') }}"></script>
<script src="{{ asset('body_css/vendors/inputmask/jquery.inputmask.bundle.js') }}"></script>
<script src="{{ asset('body_css/js/inputmask.js') }}"></script>
@endsection
