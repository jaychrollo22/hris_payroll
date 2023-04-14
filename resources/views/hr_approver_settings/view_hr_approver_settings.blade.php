@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">HR APPROVER</h4>
                <p class="card-description">
                  @if (checkUserPrivilege('settings_add',auth()->user()->id) == 'yes')
                  <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#new_hr_approver">
                    <i class="ti-plus btn-icon-prepend"></i>                                                    
                    New
                  </button>
                  @endif
                </p>
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                      <tr>
                        <th>HR APPROVER</th>
                        <th>Company</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($hr_approvers as $hr_approver)
                        <tr>
                            <td>{{$hr_approver->user->name}}</td>
                            <td>{{$hr_approver->company->company_name}}</td>
                            <td id="tdActionId{{ $hr_approver->id }}" data-id="{{ $hr_approver->id }}">
                              @if (checkUserPrivilege('settings_delete',auth()->user()->id) == 'yes')
                                <button title='Remove' id="{{ $hr_approver->id }}" onclick="remove({{$hr_approver->id}})"
                                    class="btn btn-rounded btn-danger btn-icon">
                                    <i class="fa fa-ban"></i>
                                </button>
                              @endif
                            </td>
                        </tr>
                        @endforeach  
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>
@include('hr_approver_settings.new_hr_approver_setting') 
<script>
    function remove(id) {
        var element = document.getElementById('tdActionId'+id);
        var dataID = element.getAttribute('data-id');
        swal({
                title: "Are you sure?",
                text: "You want to remove this HR Approver?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willCancel) => {
                if (willCancel) {
                    document.getElementById("loader").style.display = "block";
                    $.ajax({
                        url: "remove-hr-approver/" + id,
                        method: "GET",
                        data: {
                            id: id
                        },
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            document.getElementById("loader").style.display = "none";
                            swal("HR Approver has been removed!", {
                                icon: "success",
                            }).then(function() {
                                location.reload();
                            });
                        }
                    })

                }
            });
    }

</script>
@endsection