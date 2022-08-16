<div class="modal fade" id="uploadSignature" tabindex="-1" role="dialog" aria-labelledby="uploadSignaturelabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="uploadSignaturelabel">Upload Signature</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form  method='POST' action='upload-signature' onsubmit='show()' enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class='col-md-12 text-center'>
                <img class='border' id='signature-data' src='{{URL::asset(auth()->user()->employee->signature)}}' onerror="this.src='{{URL::asset('/images/signature.png')}}';" height='100px;' width='225px;'> 
              </div>
            </div>
            <div class="row mt-3">
              <div class='col-md-12 text-center'>
                <label title="Upload image file" for="signature" class="btn btn-primary btn-sm ">
                    <input type="file" accept="image/*"  name="signature" id="signature" style="display:none"  onchange='upload_signature(this)'>
                    Change Signature
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
      function upload_signature(input)
    {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                $('#signature-data').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
  </script>