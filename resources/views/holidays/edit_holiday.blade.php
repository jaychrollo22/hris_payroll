{{-- New Laborer --}}
<div class="modal fade" id="edit_holiday{{$holiday->id}}" tabindex="-1" role="dialog" aria-labelledby="EditHoldayData" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='row'>
                    <div class='col-md-12'>
                        <h5 class="modal-title" id="EditHoldayData">Edit Holiday</h5>
                    </div>
                </div>
            </div>
            <form  method='POST' action='edit-holiday/{{$holiday->id}}' onsubmit='show()' >
                <div class="modal-body">
                    {{ csrf_field() }}
                    <label>Holiday Name:</label>
                    <input type="text" name="holiday_name" placeholder='Holiday Name' value='{{$holiday->holiday_name}}' class="form-control" required>
                    <label >Holiday Type:</label>
                    <select class='form-control' name = 'holiday_type' required>
                        <option ></option>
                        <option @if($holiday->holiday_type == "Legal Holiday") selected @endif value = 'Legal Holiday'>Legal Holiday</option>
                        <option @if($holiday->holiday_type == "Special Holiday") selected @endif value = 'Special Holiday'>Special Holiday</option>
                    </select>
                    <label >Holiday Date:</label>
                    <input type="date" name="holiday_date" placeholder='' value='{{$holiday->holiday_date}}' class="form-control" required>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id='submit1' class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>