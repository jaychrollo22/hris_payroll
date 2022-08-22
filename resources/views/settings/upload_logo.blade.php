<div class="modal fade" id="uploadLogo" tabindex="-1" role="dialog" aria-labelledby="uploadIconlabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="uploadIconlabel">Upload Logo</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form  method='POST' action='upload-logo' onsubmit='show()' enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class='col-md-12 text-center'>
                <img id='logo_change' src='@if($settings->first()){{URL::asset($settings->logo)}}@endif' onerror="this.src='{{URL::asset('/images/obanana_brand.png')}}';"   class="rounded-square circle-border m-b-md border" alt="profile" height='130px;' width='431px;'> <br>
                <i><small>SIZE : 130px x 130px</small></i>
              </div>
            </div>
            <div class="row mt-3">
              <div class='col-md-12 text-center'>
                <label title="Upload image file" for="asd" class="btn btn-primary btn-sm ">
                    <input type="file" accept="image/*" name="file" id="asd" style="display:none"  onchange='upload_logo(this)'>
                    Change Logo
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
      function upload_logo(input)
        {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    $('#logo_change').attr('src', e.target.result);
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
  </script>