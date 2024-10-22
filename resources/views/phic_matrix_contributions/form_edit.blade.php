<div class="modal fade" id="editPhicMatrixContribution{{$contribution->id}}" tabindex="-1" role="dialog" aria-labelledby="editPhicMatrixContributionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newHolidaylabel">Edit Phic Matrix Contribution</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form  method='POST' action='edit-phic-matrix-contribution/{{$contribution->id}}' onsubmit='show()' >
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class='col-md-12 form-group'>
                Minimum Salary
                <input type="number" class="form-control form-control-sm" name="min_salary" id="min_salary"  required min="1" placeholder="0.00" value="{{$contribution->min_salary}}">
              </div>
            </div>
            <div class="row">
                <div class='col-md-12 form-group'>
                    <input type="checkbox" name="is_no_limit" id="is_no_limit"> No Limit ?<br>
                    Maximum Salary
                    <input type="number" class="form-control form-control-sm" name="max_salary" id="max_salary"  required min="1" placeholder="0.00" value="{{$contribution->max_salary}}">
                </div>
            </div>
            <div class="row">
                <div class='col-md-12 form-group'>
                    Employee Share(EE)
                    <input type="number" class="form-control form-control-sm" name="employee_share_ee" id="employee_share_ee"  required min="1" placeholder="0.00" value="{{$contribution->employee_share_ee}}">
                </div>
            </div>
            <div class="row">
                <div class='col-md-12 form-group'>
                    Employer Share(ER)
                    <input type="number" class="form-control form-control-sm" name="employer_share_er" id="employer_share_er"  required min="1" placeholder="0.00" value="{{$contribution->employer_share_er}}">
                </div>
            </div>
            <div class="row">
                <div class='col-md-12 form-group'>
                    Total Contribution
                    <input type="number" class="form-control form-control-sm" name="total_contribution" id="total_contribution"  required min="1" placeholder="0.00" value="{{$contribution->total_contribution}}">
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