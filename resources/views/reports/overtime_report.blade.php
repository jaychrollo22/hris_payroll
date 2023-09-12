@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Filter</h4>
                        <p class="card-description">
                            <form method='get' onsubmit='show();' enctype="multipart/form-data">
                                <div class=row>
                                    <div class='col-md-2'>
                                        <div class="form-group">
                                            <label class="text-right">Company</label>
                                            <select data-placeholder="Select Company" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='company' required>
                                                <option value="">-- Select Company --</option>
                                                @foreach($companies as $comp)
                                                <option value="{{$comp->id}}" @if ($comp->id == $company) selected @endif>{{$comp->company_name}} - {{$comp->company_code}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class='col-md-2'>
                                        <div class="form-group">
                                            <label class="text-right">From</label>
                                            <input type="date" value='{{$from_date}}' class="form-control" name="from" max='{{date('Y-m-d')}}' onchange='get_min(this.value);' required />
                                        </div>
                                    </div>
                                    <div class='col-md-2'>
                                        <div class="form-group">
                                            <label class="text-right">To</label>
                                            <input type="date" value='{{$to_date}}' class="form-control" name="to" id='to' max='{{date('Y-m-d')}}' required />
                                        </div>
                                    </div>
                                    <div class='col-md-2 mr-2'>
                                        <div class="form-group">
                                            <label class="text-right">Status</label>
                                            <select data-placeholder="Select Status" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='status' required>
                                                <option value="">-- Select Status --</option>
                                                <option value="Approved" @if ('Approved' == $status) selected @endif>Approved</option>
                                                <option value="Pending" @if ('Pending' == $status) selected @endif>Pending</option>
                                                <option value="Cancelled" @if ('Cancelled' == $status) selected @endif>Cancelled</option>
                                                <option value="Declined" @if ('Declined' == $status) selected @endif>Declined</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class='col-md-2'>
                                        <button type="submit" class="form-control form-control-sm btn btn-primary mb-2 btn-sm">Generate</button>
                                    </div>
                                </div>
                            </form>
                        </p>
                        <h4 class="card-title">Overtime Report <a href="/overtime-report-export?company={{$company}}&from={{$from_date}}&to={{$to_date}}" title="Export" class="btn btn-outline-primary btn-icon-text btn-sm text-center"><i class="ti-arrow-down btn-icon-prepend"></i></a></h4>

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered tablewithSearch" id="overtime_report">
                                <thead>
                                    <tr>
                                        <th>User ID</th>
                                        <th>Employee Name</th>
                                        <th>Date Filed</th>
                                        <th>OT Date</th> 
                                        <th>OT Time</th> 
                                        <th>OT Requested (Hrs)</th>
                                        <th>Break (Hrs)</th>
                                        <th>OT Approved (Hrs)</th>
                                        <th>Total OT Approved (Hrs)</th>
                                        <th>Approve Date </th>
                                        <th>Remarks </th>
                                        <th>Status </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employee_overtimes as $item)
                                    <tr>
                                        <td>{{$item->employee->user_id}}</td>
                                        <td>{{$item->user->name}}</td>
                                        <td>{{date('d/m/Y h:i A', strtotime($item->created_at))}}</td>
                                        <td>{{date('d/m/Y', strtotime($item->ot_date))}}</td>
                                        <td>{{date('d/m/Y h:i A', strtotime($item->start_time))}} - {{date('d/m/Y h:i A', strtotime($item->end_time))}}</td>
                                        {{-- <td>{{intval((strtotime($item->end_time)-strtotime($item->start_time))/60/60)}}</td> --}}
                                        <td>
                                            @php
                                                $startTime = new DateTime($item->start_time);
                                                $endTime = new DateTime($item->end_time);

                                                // Calculate the time difference
                                                $timeDifference = $endTime->diff($startTime);
                                                // Convert the time difference to decimal hours
                                                $total = ($timeDifference->days * 24) + $timeDifference->h + ($timeDifference->i / 60);
                                            @endphp
                                            {{ number_format($total,2)}}
                                        </td>
                                        <td>{{$item->break_hrs}}</td>
                                        <td>{{$item->ot_approved_hrs}}</td>
                                        <td>{{$item->ot_approved_hrs - $item->break_hrs}}</td>
                                        <td>{{ $item->approved_date ? date('d/m/Y', strtotime($item->approved_date)) : ""}}</td>
                                        <td>
                                            {{$item->remarks}}
                                            <br>
                                            @if($item->attachment)
                                                <a href="{{url($item->attachment)}}" target='_blank' class="text-start"><button type="button" class="btn btn-outline-info btn-sm ">View Attachment</button></a>
                                            @endif
                                        </td>
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
