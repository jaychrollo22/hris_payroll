<div class="modal fade" id="uploadAvatar" tabindex="-1" role="dialog" aria-labelledby="uploadAvatarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="uploadAvatarLabel">Upload Avatar</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form  method='POST' action='upload-avatar' onsubmit='show()' enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class='col-md-12 text-center'>
                <img class="rounded-circle" style='width:170px;height:170px;' id='avatar' src='{{URL::asset(auth()->user()->employee->avatar)}}' onerror="this.src='{{URL::asset('/images/no_image.png')}}';">
              </div>
            </div>
            <div class="row mt-3">
              <div class='col-md-12 text-center'>
                <label title="Upload image file" for="inputImage" class="btn btn-primary btn-sm ">
                    <input type="file" accept="image/*" name="file" id="inputImage" style="display:none"  onchange='uploadimage(this)'>
                    Change Avatar
                </label>
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
  <script>
      function uploadimage(input)
        {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    $('#avatar').attr('src', e.target.result);
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
  </script>