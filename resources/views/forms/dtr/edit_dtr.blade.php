<!-- Modal -->
<div class="modal fade" id="edit_dtr{{ $dtr->id }}" tabindex="-1" role="dialog" aria-labelledby="editdtrslabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editdtrslabel">Edit DTR Correction</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method='POST' action='edit-dtr/{{ $dtr->id }}' onsubmit='show()' enctype="multipart/form-data">
                  @csrf       
        <div class="modal-body">
          <div class="form-group row">
            <div class='col-md-2'>
              Approver 
            </div>
            <div class='col-md-9'>
              <div class='col-md-9'>
                @foreach($all_approvers as $approvers)
                  {{$approvers->approver_info->name}}<br>
                @endforeach
              </div>
            </div>
            
          </div>
          <div id="appDTR{{$dtr->id}}">
            <div class="form-group row">
              <div class='col-md-2'>
                 Date
              </div>
              <div class='col-md-4'>
                <input type="date" name='dtr_date' class="form-control" value="{{ $dtr->dtr_date }}" v-model="dtr_date"  @change="validateDates" required>
              </div>
              <div class='col-md-2'>
                DTR Type
             </div>
             <div class='col-md-4'>
              <select class="form-control"  id="crction" name='correction' onchange="editDtr(this,{{$dtr->id}})" required>
                <option value="Both" {{ $dtr->correction == 'Both' ? 'selected' : ''}}>Both Time-In and Time-Out</option>                                    
                <option value="Time-in" {{ $dtr->correction == 'Time-in' ? 'selected' : ''}}>Time-in Only</option>
                <option value="Time-out" {{ $dtr->correction == 'Time-out' ? 'selected' : ''}}>Time-out Only</option>
            </select>
             </div>            
            </div>           
            <div class="form-group row" >
                  <div class='col-md-2'>
                    Time-In
                  </div>
                  <div class='col-md-4'>
                    <input type="datetime-local" name='time_in' id='timein{{$dtr->id}}' class="form-control" value="{{ isset($dtr->time_in) ? $dtr->time_in : '' }}" v-model="start_time" :min="min_date" :max="dtr_max_date" class="form-control" @change="validateDates" :value="start_time" required>
                  </div>
                  <div class='col-md-2'>
                    Time-out
                  </div>
                  <div class='col-md-4'>
                    <input type="datetime-local" name='time_out' id='timeout{{$dtr->id}}' class="form-control" value="{{ isset($dtr->time_out) ? $dtr->time_out : '' }}" v-model="end_time"  :min="start_time" :max="max_date" @change="validateDates"  class="form-control" :value="end_time" required>
                  </div>
            </div>
            <div class="form-group row">
              <div class='col-md-2'>
                 Reason
              </div>
              <div class='col-md-10'>
                <textarea  name='remarks' class="form-control" rows='4' required>{{ $dtr->remarks }}</textarea>
              </div>
            
            </div>
            <div class="form-group row">
              <div class='col-md-2'>
                 Attachment
              </div>
              <div class='col-md-10'>
                <input type="file" name="attachment" class="form-control"  placeholder="Upload Supporting Documents">
              </div>
            </div>
          </div>
        </div>
  
        <div class="modal-footer">
            @if($dtr->attachment)
              <a href="{{url($dtr->attachment)}}" target='_blank'><button type="button" class="btn btn-outline-info btn-fw ">View Attachment</button></a>
            @endif
            
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" {{ (auth()->user()->employee->immediate_sup_data != null) ? "" : 'disabled'}}>Save</button>
        </div>
      </form>      
      </div>
    </div>
  </div>

  <script>
    var app = new Vue({
        el: '#appDTR' + '<?php echo $dtr->id; ?>',
        data() {
          return {
            btnDisable: false,
            isDisabled: true,
            allowed_overtime_hrs: '',
            start_time: '<?php echo $dtr->time_in; ?>',
            end_time: '<?php echo $dtr->time_out; ?>',
            dtr_date: '<?php echo $dtr->dtr_date; ?>',
            dtr_max_date: '',
            min_date: '',
            max_date: '',
            // employee_number: '21000849',
            employee_number: '<?php echo auth()->user()->employee->employee_number; ?>',
  
            error : ''
          };
        },
        created () {
          this.validateDates();
        },
        methods: {
          validateDates() {
            if (this.dtr_date) {
              const obDate = new Date(this.dtr_date);
              obDate.setDate(obDate.getDate() + 1);
              this.min_date = this.dtr_date + ' 00:00:00';
              this.dtr_max_date = this.dtr_date + ' 23:00:00';
              this.max_date = obDate.toISOString().split('T')[0] + ' 23:00:00';
            }
          }
        },
    });
  </script>