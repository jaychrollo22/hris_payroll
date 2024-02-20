@extends('layouts.header')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class='row'>
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit User : {{$schedule->name}}</h4>
                    <div class="col-md-12">
                        <form method='POST' action='{{url('update-schedule'.$schedule->id)}}' onsubmit='show()' enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                              <div class='col-md-12 form-group'>
                                Schedule Name
                                <input type="text" name='schedule_name' class="form-control" placeholder="Schedule Name/Type" required>
                              </div>
                              <div class='col-md-12 form-group'>
                                Is Flexi
                                <input type="checkbox" name="is_flexi" class="form-controol" value="1" checked>
                              </div>
                            </div>
                        </form> 
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  
  
</script>
