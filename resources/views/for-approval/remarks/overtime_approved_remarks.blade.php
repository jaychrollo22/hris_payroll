<div class="modal fade" id="approve-ot-hrs-{{$overtime->id}}" tabindex="-1" role="dialog" aria-labelledby="approveOTHrs" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveOTHrs">Add Approve OT Hrs</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method='POST' action='approve-ot-hrs/{{$overtime->id}}' onsubmit='show()' enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="badge badge-success mt-1">Approved</h4>
                        </div>
                        <input type="hidden" name="status" value="Approved">

                        <div class="col-md-12 mb-2">
                            @php

                                $startTime = new DateTime($overtime->start_time);
                                $endTime = new DateTime($overtime->end_time);

                                // Calculate the time difference
                                $timeDifference = $endTime->diff($startTime);
                                // Convert the time difference to decimal hours
                                $total = ($timeDifference->days * 24) + $timeDifference->h + ($timeDifference->i / 60);
                            @endphp
                            @if($overtime->end_time && $overtime->start_time)
                                Requested Overtime (hrs): {{ number_format((float)$total, 2, '.', '') }}
                            @endif
                        </div>
                        <div class='col-md-12 form-group'>
                            Break (hrs):
                            @php
                                $break_hrs = $overtime->break_hrs ? $overtime->break_hrs : 0;
                            @endphp
                            @if($total > 3)
                                <input id="break_hrs" type="number" step="0.01" min="0" max="3" name='break_hrs' value='{{$overtime->break_hrs}}' class="form-control" required>
                            @else
                                <input id="break_hrs" type="number" step="0.01" min="0" max="3" name='break_hrs' value='{{$overtime->break_hrs}}' class="form-control">
                            @endif
                            
                        </div>
                        <div class='col-md-12 form-group'>
                            Approve Overtime (hrs):
                            @php
                                $approve_hrs = $overtime->ot_approved_hrs ? $overtime->ot_approved_hrs : number_format((float)$total, 2, '.', '');
                            @endphp
                            <input id="approve_hrs" type="number" name='ot_approved_hrs' value='{{ $approve_hrs }}' max="{{number_format($total,2)}}" step='0.01' class="form-control" required>
                        </div>
                        <div class='col-md-12 form-group'>
                            Total Approve Overtime (hrs):
                            <input id="total_approve_hours" type="number" class="form-control" disabled value="{{ $approve_hrs - $break_hrs}}">
                        </div>
                        
                        <div class='col-md-12 form-group'>
                            Remarks:
                            <textarea class="form-control" name="approval_remarks" id="" cols="30" rows="5" placeholder="Input Approval Remarks"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>
