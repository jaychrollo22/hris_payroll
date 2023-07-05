<!-- Modal -->
<div class="modal fade" id="edit_ob{{ $ob->id }}" tabindex="-1" role="dialog" aria-labelledby="editOBslabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editOBslabel">Edit Official Business</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method='POST' action='edit-ob/{{ $ob->id }}' onsubmit='show()' enctype="multipart/form-data">
				@csrf        
      <div class="modal-body text-right">
        <div class="form-group row">
          <div class='col-md-2'>
            Approver 
          </div>
          <div class='col-md-10 text-left'>
            @foreach($all_approvers as $approvers)
            {{$approvers->approver_info->name}}<br>
          @endforeach
          </div>
        </div>
        <div id="appOB{{$ob->id}}">
          <div class="form-group row">
            <div class='align-self-center col-md-2 text-right'>
              Date
            </div>
            <div class='col-md-4'>
              <input type="date" name='applied_date' class="form-control" value="{{ $ob->applied_date }}" v-model="ob_date"  @change="validateDates" required>
            </div>
          </div>
          <div class="form-group row">
            <div class='align-self-center col-md-2 text-right'>
              Date From
            </div>
            <div class='col-md-4'>
              <input type="datetime-local" name='date_from' class="form-control" value="{{ $ob->date_from }}" v-model="start_time" :min="min_date" :max="ob_max_date" class="form-control" @change="validateDates" :value="start_time" required>
            </div>
            <div class='align-self-center col-md-2 text-right'>
               Date To
            </div>
            <div class='col-md-4'>
              <input type="datetime-local" name='date_to' class="form-control" value="{{ $ob->date_to }}" v-model="end_time"  :min="start_time" :max="max_date" @change="validateDates"  class="form-control" :value="end_time" required>
            </div>
          </div>
          <div class="form-group row">
            <div class='col-md-2'>
               Destination
            </div>
            <div class='col-md-10'>
              <input type='text' name='destination' class="form-control" value="{{ $ob->destination }}" rows='4' required>
            </div>
          </div>
          <div class="form-group row">
            <div class='col-md-2'>
                Person/Company to See:
            </div>
            <div class='col-md-10'>
              <input type='text' name='persontosee' class="form-control" value="{{ $ob->persontosee }}" rows='4' required>
            </div>
          </div>
          <div class="form-group row">
            <div class='col-md-2'>
               Purpose/Remarks
            </div>
            <div class='col-md-10'>
              <textarea  name='remarks' class="form-control" rows='4' required>{{ $ob->remarks }}</textarea>
            </div>
          </div>
          <div class="form-group row">
            <div class='col-md-2'>
               Attachment
            </div>  
            <div class='col-md-10'>
              <input type="file" name="attachment" class="form-control"  placeholder="Upload Supporting Documents" multiple>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        @if($ob->attachment)
          <a href="{{url($ob->attachment)}}" target='_blank'><button type="button" class="btn btn-outline-info btn-fw ">View Attachment</button></a>  
        @endif
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
    </div>
  </div>
</div>

<script>
  var app = new Vue({
      el: '#appOB' + '<?php echo $ob->id; ?>',
      data() {
        return {
          btnDisable: false,
          isDisabled: true,
          allowed_overtime_hrs: '',
          start_time: '<?php echo $ob->date_from; ?>',
          end_time: '<?php echo $ob->date_to; ?>',
          ob_date: '<?php echo $ob->applied_date; ?>',
          ob_max_date: '',
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
          if (this.ob_date) {
            const obDate = new Date(this.ob_date);
            obDate.setDate(obDate.getDate() + 1);
            this.min_date = this.ob_date + ' 00:00:00';
            this.ob_max_date = this.ob_date + ' 23:00:00';
            this.max_date = obDate.toISOString().split('T')[0] + ' 23:00:00';
          }
        }
      },
  });
</script>