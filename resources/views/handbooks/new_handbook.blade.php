<!-- Modal -->
<div class="modal fade" id="newHandbook" tabindex="-1" role="dialog" aria-labelledby="newHandbookData" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newHandbookData">New Handbook</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method='post' onsubmit='show()' action='{{url('/new-handbook')}}' enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="modal-body">
            <div class="form-group row">
                <div class='col-md-2'>
                Attachment
                </div>
                <div class='col-md-10'>
                <input type="file" class="form-control" name="attachment"  placeholder="Upload Supporting Documents" required>
                </div>
            </div>
            <div class="form-group row">
                <div class='col-md-2'>
                    Remarks
                </div>
                <div class='col-md-10'>
                    <textarea  name='reason' class="form-control" rows='4' requried></textarea>
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