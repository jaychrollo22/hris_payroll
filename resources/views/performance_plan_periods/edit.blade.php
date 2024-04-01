<div class="modal fade" id="edit_performance_plan_period{{$performance_plan_period->id}}" tabindex="-1" role="dialog" aria-labelledby="editPerformancePlanPeriodlabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editPerformancePlanPeriodlabel">Edit Performance Plan Period</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form  method='POST' action='update-performance-plan-period/{{$performance_plan_period->id}}' onsubmit='show()'>
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class='col-md-12 form-group'>
                Performance Plan Period
                <input type="text" name='period' class="form-control" placeholder="Performance Plan Period" required value="{{ $performance_plan_period->period }}">
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