<!-- Modal -->
<div class="modal fade" id="ob" tabindex="-1" role="dialog" aria-labelledby="OBDATA" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="OBDATA">Apply Official Business</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
			<form method='POST' action='new-ob' onsubmit="btnOB.disabled = true; return true;"  enctype="multipart/form-data">
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
        <div id="appOB">

        
          <div class="form-group row">
            <div class='align-self-center col-md-2 text-right'>
              Date
            </div>
            <div class='col-md-4'>
              <input type="date" name='applied_date' class="form-control" v-model="applied_date" @change="validateDates" required>
            </div>
          </div>
          <div class="form-group row">
            <div class='align-self-center col-md-2 text-right'>
              Time in
            </div>
            <div class='col-md-4'>
              <input type="datetime-local" name='date_from' class="form-control" v-model="date_from" :min="min_date" :max="ob_max_date" @change="validateDates" required>
            </div>
            <div class='align-self-center col-md-2 text-right'>
               Time out
            </div>
            <div class='col-md-4'>
              <input type="datetime-local" name='date_to' class="form-control" v-model="date_to" :min="date_from" :max="max_date" @change="validateDates" required>
            </div>
          </div>
          <div class="form-group row">
            <div class='col-md-2'>
               Destination
            </div>
            <div class='col-md-10'>
              <input type='text' name='destination' class="form-control" rows='4' required>
            </div>
          </div>
          <div class="form-group row">
            <div class='col-md-2'>
                Person/Company to See:
            </div>
            <div class='col-md-10'>
              <input type='text' name='persontosee' class="form-control" rows='4' required>
            </div>
          </div>
          <div class="form-group row">
            <div class='col-md-2'>
               Purpose/Remarks
            </div>
            <div class='col-md-10'>
              <textarea  name='remarks' class="form-control" rows='4' required></textarea>
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="btnOB" class="btn btn-primary">Save</button>
      </div>
    </form>
    </div>
  </div>
</div>


<script>
  var app = new Vue({
      el: '#appOB',
      data() {
        return {
          date_from: '',
          date_to: '',
          applied_date: '',
          ob_max_date: '',
          min_date: '',
          max_date: '',
        };
      },
      methods: {
        validateDates() {
          if (this.applied_date) {
            const obDate = new Date(this.applied_date);
            obDate.setDate(obDate.getDate() + 1);
            this.min_date = this.applied_date + ' 00:00:00';
            this.ob_max_date = this.applied_date + ' 23:00:00';
            this.max_date = obDate.toISOString().split('T')[0] + ' 23:00:00';
          }
        }
      },
  });
</script>