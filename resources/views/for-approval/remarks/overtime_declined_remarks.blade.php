<div class="modal fade" id="overtime-declined-remarks-{{$overtime->id}}" tabindex="-1" role="dialog" aria-labelledby="declinedOTremarks" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="declinedOTremarks">Are you sure you want to Decline this OB?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method='POST' action='decline-overtime/{{$overtime->id}}' onsubmit="btnApprove.disabled = true; return true;" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="badge badge-danger mt-1">Declined</h4>
                        </div>
                        <input type="hidden" name="status" value="Declined">
                        <div class='col-md-12 form-group'>
                            Remarks:
                            <textarea class="form-control" name="approval_remarks" id="" cols="30" rows="5" placeholder="Input Approval Remarks"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="btnApprove" class="btn btn-danger">Decline</button>
                </div>
            </form>
        </div>
    </div>
</div>
