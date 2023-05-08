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
                        <div class="col-md-12 mb-2">
                            Requested Overtime (hrs): {{ number_format((strtotime($overtime->end_time)-strtotime($overtime->start_time))/3600,2)}}
                            @php
                                $total = number_format((strtotime($overtime->end_time)-strtotime($overtime->start_time))/3600,2);
                            @endphp
                        </div>
                        <div class='col-md-12 form-group'>
                            Break (hrs):
                            @php
                                $break_hrs = $overtime->break_hrs ? $overtime->break_hrs : 0;
                            @endphp
                            @if($total > 3)
                                <input id="break_hrs" type="number" name='break_hrs' value='{{$overtime->break_hrs}}' class="form-control" required>
                            @else
                                <input id="break_hrs" type="number" name='break_hrs' value='{{$overtime->break_hrs}}' class="form-control">
                            @endif
                            
                        </div>
                        <div class='col-md-12 form-group'>
                            Approve Overtime (hrs):
                            @php
                                $approve_hrs = $overtime->ot_approved_hrs ? $overtime->ot_approved_hrs : $total;
                            @endphp
                            <input id="approve_hrs" type="number" name='ot_approved_hrs' value='{{ $approve_hrs }}' max="{{$total}}" step='0.01' class="form-control" required>
                        </div>
                        <div class='col-md-12 form-group'>
                            Total Approve Overtime (hrs):
                            <input id="total_approve_hours" type="number" class="form-control" disabled value="{{ $approve_hrs - $break_hrs}}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
