<div class="modal fade" id="editSchedule{{$schedule->id}}" tabindex="-1" role="dialog" aria-labelledby="editScheduleLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editScheduleLabel">Edit Schedule</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form  method='POST' action='edit-schedule/{{$schedule->id}}' onsubmit='show()' >
          @csrf
          <div class="modal-body">
              <div class="row">
                <div class='col-md-12 form-group'>
                  Schedule Name
                  <input value="{{$schedule->schedule_name}}" type="text" name='schedule_name' class="form-control" placeholder="Schedule Name/Type" required>
                </div>
              </div>
              <div class="row border text-center">
                <div class='col-md-3 border'>
                </div>
                <div class='col-md-3 border'>
                  Start Time
                </div>
                <div class='col-md-3 border'>
                  End Time
                </div>
                <div class='col-md-3 border'>
                  Total working hours <br> <i>(including breaktime hours)</i>
                </div>
              </div>
              <div class="row border text-center">
                <div class='col-md-3 align-self-center'>
                  Sunday <br>
                  <label class="form-check-label ">
                    <input type="checkbox" name="restday[Sunday]" onclick='restday("Sunday")' checked class="form-check-input">
                    Restday
                </label>
                </div>
                <div class='col-md-3 border'>
                  <div class="row mt-1 ">
                    <label  class="col-sm-2 col-form-label align-self-center">From</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_in_from[Sunday]" class="form-control form-control-sm"  readonly>
                    </div>
                  </div>
                  <div class="row">
                    <label  class="col-sm-2 col-form-label align-self-center">To</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_in_to[Sunday" class="form-control form-control-sm"  readonly>
                    </div>
                  </div>
                </div>
                <div class='col-md-3 border'>
                  <div class="row mt-1 ">
                    <label  class="col-sm-2 col-form-label align-self-center">From</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_out_from[Sunday]" class="form-control form-control-sm"  readonly>
                    </div>
                    <label  class="col-sm-2 col-form-label align-self-center">To</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_out_to[Sunday]"  class="form-control form-control-sm"  readonly>
                    </div>
                  </div>
                </div>
                <div class='col-md-3  align-self-center'>
                    <input type='number' class='form-control form-control-sm align-self-center' name='working_hours[Sunday]' step='.5' placeholder="10.5" readonly>
                </div>
            </div>
              <div class="row border text-center">
                <div class='col-md-3 align-self-center'>
                  Monday <br>
                  <label class="form-check-label ">
                    <input type="checkbox" name="restday[Monday]" class="form-check-input">
                    Restday
                </label>
                </div>
                <div class='col-md-3 border'>
                  <div class="row mt-1 ">
                    <label  class="col-sm-2 col-form-label align-self-center">From</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_in_from[Monday]" class="form-control form-control-sm" value='07:00'  required >
                    </div>
                  </div>
                  <div class="row">
                    <label  class="col-sm-2 col-form-label align-self-center">To</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_in_to[Monday]"  class="form-control form-control-sm" value='10:00' required>
                    </div>
                  </div>
                </div>
                <div class='col-md-3 border'>
                  <div class="row mt-1 ">
                    <label  class="col-sm-2 col-form-label align-self-center">From</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_out_from[Monday]" class="form-control form-control-sm" value='17:30'  required>
                    </div>
                    <label  class="col-sm-2 col-form-label align-self-center">To</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_out_to[Monday]" class="form-control form-control-sm"  value='20:30'  required>
                    </div>
                  </div>
                </div>
                <div class='col-md-3  align-self-center'>
                  <input type='number' class='form-control form-control-sm align-self-center' name='working_hours[Monday]' step='.5' placeholder="10.5" required>
              </div>
            </div>
              <div class="row border text-center">
                <div class='col-md-3 align-self-center'>
                  Tuesday <br>
                  <label class="form-check-label ">
                    <input type="checkbox" name="restday[Tuesday]" class="form-check-input">
                    Restday
                </label>
                </div>
                <div class='col-md-3 border'>
                  <div class="row mt-1 ">
                    <label  class="col-sm-2 col-form-label align-self-center">From</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_in_from[Tuesday]" class="form-control form-control-sm" value='07:00' required >
                    </div>
                  </div>
                  <div class="row">
                    <label  class="col-sm-2 col-form-label align-self-center">To</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_in_to[Tuesday]"  class="form-control form-control-sm" value='10:00' required>
                    </div>
                  </div>
                </div>
                <div class='col-md-3 border'>
                  <div class="row mt-1 ">
                    <label  class="col-sm-2 col-form-label align-self-center">From</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_out_from[Tuesday]" class="form-control form-control-sm" value='17:30'  required>
                    </div>
                    <label  class="col-sm-2 col-form-label align-self-center">To</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_out_to[Tuesday]" class="form-control form-control-sm"  value='20:30'  required>
                    </div>
                  </div>
                </div>
                <div class='col-md-3  align-self-center'>
                  <input type='number' class='form-control form-control-sm align-self-center' name='working_hours[Tuesday]' step='.5' placeholder="10.5" required>
              </div>
            </div>
            <div class="row border text-center">
                <div class='col-md-3 align-self-center'>
                  Wednesday <br>
                  <label class="form-check-label ">
                    <input type="checkbox" name="restday[Wednesday]" class="form-check-input">
                    Restday
                </label>
                </div>
                <div class='col-md-3 border'>
                  <div class="row mt-1 ">
                    <label  class="col-sm-2 col-form-label align-self-center">From</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_in_from[Wednesday]" class="form-control form-control-sm" value='07:00' required >
                    </div>
                  </div>
                  <div class="row">
                    <label  class="col-sm-2 col-form-label align-self-center">To</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_in_to[Wednesday]"  class="form-control form-control-sm" value='10:00' required>
                    </div>
                  </div>
                </div>
                <div class='col-md-3 border'>
                  <div class="row mt-1 ">
                    <label  class="col-sm-2 col-form-label align-self-center">From</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_out_from[Wednesday]" class="form-control form-control-sm" value='17:30'  required>
                    </div>
                    <label  class="col-sm-2 col-form-label align-self-center">To</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_out_to[Wednesday]" class="form-control form-control-sm"  value='20:30'  required>
                    </div>
                  </div>
                </div>
                <div class='col-md-3  align-self-center'>
                  <input type='number' class='form-control form-control-sm align-self-center' name='working_hours[Wednesday]' step='.5' placeholder="10.5" required>
              </div>
            </div>
            <div class="row border text-center">
                <div class='col-md-3 align-self-center'>
                  Thursday <br>
                  <label class="form-check-label ">
                    <input type="checkbox" name="restday[Thursday]" class="form-check-input">
                    Restday
                </label>
                </div>
                <div class='col-md-3 border'>
                  <div class="row mt-1 ">
                    <label  class="col-sm-2 col-form-label align-self-center">From</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_in_from[Thursday]" class="form-control form-control-sm" value='07:00' required >
                    </div>
                  </div>
                  <div class="row">
                    <label  class="col-sm-2 col-form-label align-self-center">To</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_in_to[Thursday]"  class="form-control form-control-sm" value='10:00' required>
                    </div>
                  </div>
                </div>
                <div class='col-md-3 border'>
                  <div class="row mt-1 ">
                    <label  class="col-sm-2 col-form-label align-self-center">From</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_out_from[Thursday]" class="form-control form-control-sm" value='17:30'  required>
                    </div>
                    <label  class="col-sm-2 col-form-label align-self-center">To</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_out_to[Thursday]" class="form-control form-control-sm"  value='20:30'  required>
                    </div>
                  </div>
                </div>
                <div class='col-md-3  align-self-center'>
                  <input type='number' class='form-control form-control-sm align-self-center' name='working_hours[Thursday]' step='.5' placeholder="10.5" required>
              </div>
            </div>
            <div class="row border text-center">
                <div class='col-md-3 align-self-center'>
                  Friday <br>
                  <label class="form-check-label ">
                    <input type="checkbox" name="restday[Friday]" class="form-check-input">
                    Restday
                </label>
                </div>
                <div class='col-md-3 border'>
                  <div class="row mt-1 ">
                    <label  class="col-sm-2 col-form-label align-self-center">From</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_in_from[Friday]" class="form-control form-control-sm" value='07:00' required >
                    </div>
                  </div>
                  <div class="row">
                    <label  class="col-sm-2 col-form-label align-self-center">To</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_in_to[Friday]"  class="form-control form-control-sm" value='10:00' required>
                    </div>
                  </div>
                </div>
                <div class='col-md-3 border'>
                  <div class="row mt-1 ">
                    <label  class="col-sm-2 col-form-label align-self-center">From</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_out_from[Friday]" class="form-control form-control-sm" value='17:30'  required>
                    </div>
                    <label  class="col-sm-2 col-form-label align-self-center">To</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_out_to[Friday]" class="form-control form-control-sm"  value='20:30'  required>
                    </div>
                  </div>
                </div>
                <div class='col-md-3  align-self-center'>
                  <input type='number' class='form-control form-control-sm align-self-center' name='working_hours[Friday]' step='.5' placeholder="10.5" required>
              </div>
            </div>
            <div class="row border text-center">
              <div class='col-md-3 align-self-center'>
                Saturday <br>
                <label class="form-check-label ">
                  <input type="checkbox" name="restday[Saturday]" checked class="form-check-input">
                  Restday
              </label>
              </div>
              <div class='col-md-3 border'>
                <div class="row mt-1 ">
                  <label  class="col-sm-2 col-form-label align-self-center">From</label>
                  <div class="col-sm-10">
                    <input type="time" name="time_in_from[Saturday]" class="form-control form-control-sm"  readonly>
                  </div>
                </div>
                <div class="row">
                  <label  class="col-sm-2 col-form-label align-self-center">To</label>
                  <div class="col-sm-10">
                    <input type="time" name="time_in_to[Saturday]" class="form-control form-control-sm"  readonly>
                  </div>
                </div>
              </div>
              <div class='col-md-3 border'>
                <div class="row mt-1 ">
                  <label  class="col-sm-2 col-form-label align-self-center">From</label>
                  <div class="col-sm-10">
                    <input type="time" name="time_out_from[Saturday]" class="form-control form-control-sm"  readonly>
                  </div>
                  <label  class="col-sm-2 col-form-label align-self-center">To</label>
                  <div class="col-sm-10">
                    <input type="time" name="time_out_to[Saturday]"  class="form-control form-control-sm"  readonly>
                  </div>
                </div>
              </div>
              <div class='col-md-3  align-self-center'>
                  <input type='number' class='form-control form-control-sm align-self-center' name='working_hours[Saturday]' step='.5' placeholder="10.5" readonly>
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
  
  
</script>
