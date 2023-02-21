@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Overtime Reports</h4>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered tablewithSearch">
                                <thead>
                                    <tr>
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
