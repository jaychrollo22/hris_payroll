<div class="modal fade" id="edit_wfh{{ $wfh->id }}" tabindex="-1" role="dialog" aria-labelledby="editWFHslabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editWFHslabel">Edit WFH</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form method='POST' action='edit-wfh/{{ $wfh->id }}' onsubmit='show()' enctype="multipart/form-data">
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
          <div class="form-group row">
            <div class='align-self-center col-md-2 text-right'>
              Date From
            </div>
            <div class='col-md-4'>
              <input type="date" name='date_from' value="{{$wfh->date_from}}" class="form-control" required>
            </div>
            <div class='align-self-center col-md-2 text-right'>
               Date To
            </div>
            <div class='col-md-4'>
              <input type="date" name='date_to' value="{{$wfh->date_to}}" class="form-control" required>
            </div>
          </div>
          <div class="form-group task">
            <div class='row mb-2' id='task_1'>
              <div class='col-md-2 text-right'  >
                <button type="button" onclick='add_task();' class="btn btn-success btn-rounded btn-icon">
                  <i class="ti-plus"></i>
                </button>                
                Task
              </div>
              <div class='col-md-10'>
                @foreach($tasks->where('wfh_id',$wfh->id) as $task)
                <input extarea  name='task{{$task->id}}' id="task{{$task->id}}" class="form-control mb-2" rows='4' value="{{$task->task_desc}}"required>
                @endforeach
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class='col-md-2'>
               Remarks
            </div>
            <div class='col-md-10'>
              <textarea  name='remarks' class="form-control" rows='4' required>{{$wfh->remarks}}</textarea>
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
      <div class="modal-footer">
        <a href="{{url($wfh->attachment)}}" target='_blank'><button type="button" class="btn btn-outline-info btn-fw ">View Attachment</button></a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>      
    </div>
  </div>
</div>
<script>
  function add_task()
  {
    var lastItemID = $('.task').children().last().attr('id');
    var last_id = lastItemID.split("_");
        finalLastId = parseInt(last_id[1]) + 1;

        // console.log(finalLastId);
        var item = " <div class='form-group row mb-2' id='task_"+finalLastId+"'>";
         item += " <div class='col-md-2 text-right'  >";
         item += " <button type='button' onclick='remove_task("+finalLastId+");' class='btn btn-danger btn-rounded btn-icon'>";
         item += "<i class='ti-minus'></i>";
         item += "</button>";
         item += " Task";
         item += "</div>";
         item += "<div class='col-md-10'>";
         item += "<input extarea  name='task[]' class='form-control' rows='4' required>";
         item += "</div>";
         item += "</div>";
          $(".task").append(item);
  }

  function remove_task(id)
    {
        $("#task_"+id).remove();
    }

</script>