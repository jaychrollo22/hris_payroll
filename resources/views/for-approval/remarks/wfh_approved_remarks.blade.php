<div class="modal fade" id="approve-wfh-percentage-{{$wfh->id}}" tabindex="-1" role="dialog" aria-labelledby="approveWfhPercentage" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveWfhPercentage">Add Approve </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method='POST' action='approve-wfh-percentage/{{$wfh->id}}' onsubmit='show()' enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="badge badge-success mt-1">Approved</h4>
                        </div>
                        <input type="hidden" name="status" value="Approved">
                        <div class='col-md-12 form-group'>
                            Approve WFH Percentage:
                            <select name="approve_percentage" id="" class="form-control" required>
                                <option value="60" {{$wfh->approve_percentage == '60' ? 'selected' : "" }}>WFH 60%</option>
                                <option value="100" {{$wfh->approve_percentage == '100' ? 'selected' : "" }}>WFH 100%</option>
                            </select>
                        </div>
                        <div class='col-md-12 form-group'>
                            Remarks:
                            <textarea class="form-control" name="approval_remarks" id="" cols="30" rows="5" placeholder="Input Approval Remarks"></textarea>
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
