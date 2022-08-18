<!-- Modal -->
<div class="modal fade" id="newAnnouncement" tabindex="-1" role="dialog" aria-labelledby="newAnnouncementData" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newAnnouncementData">New Announcement</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method='post' onsubmit='show()' action='{{url('/new-announcement')}}' enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="modal-body">
            <div class="form-group row">
                <div class='col-md-2'>
                Title
                </div>
                <div class='col-md-10'>
                <input type="text" class="form-control" name="title"  placeholder="Title" required>
                </div>
            </div>
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
                    Expiration(optional)
                </div>
                <div class='col-md-10'>
                    <input type="date" class="form-control" name="expiration"  min ='{{date('Y-m-d')}}' placeholder="Expiration">
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