<div class="modal fade" id="cancel-used-leave-{{$leave->id}}" tabindex="-1" role="dialog" aria-labelledby="cancelUsedLeave" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelUsedLeave">Are you sure you want to Cancel this leave?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method='POST' action='{{url('cancel-employee-used-leaves/' . $leave->id)}}' onsubmit="btnCancel.disabled = true; return true;" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="badge badge-danger mt-1">Cancelled</h4>
                        </div>
                        <input type="hidden" name="status" value="Cancelled">
                        <div class='col-md-12 form-group'>
                            Remarks:
                            <textarea class="form-control" name="hr_cancel_remarks" id="" cols="30" rows="5" placeholder="Input Cancel Remarks"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="btnCancel" class="btn btn-danger">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
