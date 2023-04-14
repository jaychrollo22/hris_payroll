<!-- Modal -->
<div class="modal fade" id="new_hr_approver" tabindex="-1" role="dialog" aria-labelledby="dtrdata" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="dtrdata">New HR Approver</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method='POST' action='save-hr-approver-setting' onsubmit='show()'  enctype="multipart/form-data">
            @csrf      
            <div class="modal-body">
                <div class="row">
                    <div class='col-md-12 form-group'>
                        Select User
                        <select data-placeholder="User" class="form-control form-control-sm required js-example-basic-single " style='width:100%;' name='user_id' required>
                          <option value="">--User--</option>
                            @foreach($users as $item)
                              <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                      </div>
                    <div class="col-md-12 form-group">
                        Company
                        <select data-placeholder="Select Employee" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='companies[]' multiple>
                            <option value="">-- Select Company --</option>
                                @foreach($companies as $company)
                                <option value="{{$company->id}}">{{$company->company_name}}</option>
                                @endforeach
                        </select>
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
  