<!-- Modal -->
<div class="modal fade" id="view_ob{{ $ob->id }}" tabindex="-1" role="dialog" aria-labelledby="viewOBslabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewOBslabel">View Official Business</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method='POST' action='view-ob/{{ $ob->id }}' onsubmit='show()' enctype="multipart/form-data">
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
                <input type="date" name='date_from' class="form-control" value="{{ $ob->date_from }}" disabled>
              </div>
              <div class='align-self-center col-md-2 text-right'>
                 Date To
              </div>
              <div class='col-md-4'>
                <input type="date" name='date_to' class="form-control" value="{{ $ob->date_to }}" disabled>
              </div>
            </div>
            <div class="form-group row">
              <div class='col-md-2'>
                 Destination
              </div>
              <div class='col-md-10'>
                <input type='text' name='destination' class="form-control" value="{{ $ob->destination }}" rows='4' disabled>
              </div>
            </div>
            <div class="form-group row">
              <div class='col-md-2'>
                  Person/Company to See:
              </div>
              <div class='col-md-10'>
                <input type='text' name='persontosee' class="form-control" value="{{ $ob->persontosee }}" rows='4' disabled>
              </div>
            </div>
            <div class="form-group row">
              <div class='col-md-2'>
                 Purpose/Remarks
              </div>
              <div class='col-md-10'>
                <textarea  name='remarks' class="form-control" rows='4' disabled>{{ $ob->remarks }}</textarea>
              </div>
            </div>
            <div class="form-group row">
              <div class='col-md-2'>
                 Attachment
              </div>  
              <div class='col-md-2'>
                <a href="{{url($ob->attachment)}}" target='_blank'><button type="button" class="btn btn-outline-info btn-fw ">View Attachment</button></a>
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