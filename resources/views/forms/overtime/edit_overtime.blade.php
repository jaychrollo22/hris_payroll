<!-- Modal -->
<div class="modal fade" id="edit_overtime{{ $overtime->id }}" tabindex="-1" role="dialog" aria-labelledby="editOTslabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editOTslabel">Edit Overtime</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>


      <form method='POST' action='edit-overtime/{{ $overtime->id }}' onsubmit='show()' enctype="multipart/form-data">
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
          <div id="appOT{{$overtime->id}}">
            <div class="form-group row">
              <div class='col-md-2'>
                Date
              </div>
              <div class='col-md-4'>
                <input id="ot_date{{$overtime->id}}" v-model="ot_date" type="date" name='ot_date' value="{{$overtime->ot_date}}" class="form-control" @change="validateDates" required>
              </div>
            </div>
            
            <div class="form-group row">
              <div class='col-md-2'>
                Start Time
              </div>
              <div class='col-md-4'>
                <input id="start_time{{$overtime->id}}" type="datetime-local" name='start_time' v-model="start_time" :min="min_date" :max="ot_max_date" class="form-control" @change="validateDates" :value="start_time" required>
              </div>
              <div class='col-md-2'>
                End Time
              </div>
              <div class='col-md-4'>
                <input id="end_time{{$overtime->id}}" type="datetime-local" name='end_time' v-model="end_time"  :min="start_time" :max="max_date" @change="validateDates"  class="form-control" :value="end_time" required>
              </div>
            </div>

            <div class="form-group row">
              <div class='col-md-2'>
                Break (Hrs)
              </div>
              <div class='col-md-4'>
                <input type="number" step="0.01" min="0" max="3" name='break_hrs' class="form-control" placeholder="0.00" value="{{$overtime->break_hrs}}">
              </div>
            </div>

            <div class="form-group row">
              <div class='col-md-2'>
                Remarks
              </div>
              <div class='col-md-10'>
                <textarea  name='remarks' class="form-control" rows='4' required>{{$overtime->remarks}}</textarea>
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
        
        @if($overtime->attachment)
          <a href="{{url($overtime->attachment)}}" target='_blank'><button type="button" class="btn btn-outline-info btn-fw ">View Attachment</button></a>
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
      el: '#appOT' + '<?php echo $overtime->id; ?>',
      data() {
        return {
          btnDisable: false,
          isDisabled: true,
          allowed_overtime_hrs: '',
          start_time: '<?php echo $overtime->start_time; ?>',
          end_time: '<?php echo $overtime->end_time; ?>',
          ot_date: '<?php echo $overtime->ot_date; ?>',
          ot_max_date: '',
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
        validateOvertimeDate() {
          this.btnDisable = true;
          var startTime = document.getElementById('startTime');
          var endTime = document.getElementById('endTime');
          var allowedOvertime = document.getElementById('allowedOvertime');
          var errorMessage = document.getElementById('errorMessage');

          var start_time = document.getElementById('start_time');
          var end_time = document.getElementById('end_time');

          axios.get('/check-valid-overtime?employee_number='+this.employee_number+'&date='+this.ot_date)
          .then(function(response) {
            if(response.data.allowed_overtime_hrs > 0){
              startTime.textContent = response.data.start_time;
              endTime.textContent = response.data.end_time;
              allowedOvertime.textContent = response.data.allowed_overtime_hrs;

              
              start_time.value = response.data.start_time;
              end_time.value = response.data.end_time;

              errorMessage.textContent = "";

              document.getElementById("btnOT").disabled = false;
            }else{
              startTime.textContent = 'No Time in';
              endTime.textContent = 'No Time out';
              allowedOvertime.textContent = '0';
              errorMessage.textContent = 'Not allowed to file OT due to no Attendance detected.';

              document.getElementById("btnOT").disabled = true;
            }
          })
          .catch(function(error) {
            this.btnDisable = false;
          })
          .finally(() => {
            this.btnDisable = false; // Enable the button after the request
          });;
        },
        validateDates() {
          if (this.ot_date) {
            const otDate = new Date(this.ot_date);
            otDate.setDate(otDate.getDate() + 1);
            this.min_date = this.ot_date + ' 00:00:00';
            this.ot_max_date = this.ot_date + ' 23:00:00';
            this.max_date = otDate.toISOString().split('T')[0] + ' 23:00:00';
          }
        }
      },
  });
</script>