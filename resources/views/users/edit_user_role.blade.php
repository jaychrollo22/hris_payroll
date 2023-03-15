<div class="modal fade" id="editUserRole{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="editUserRoledata" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserRoledata">Edit User Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method='POST' action='update-user-role/{{$user->id}}' onsubmit='show()' enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class='col-md-12 form-group'>
                            Role
                            <select class="form-control" name="role">
                                <option value="">Choose Role</option>
                                <option value="Admin" {{ $user->role == 'Admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>

                        <div class="col-md-12 form-group">
                            Company
                            @php
                                $user_allowed_companies = $user->user_allowed_company ? json_decode($user->user_allowed_company->company_ids) : [];
                            @endphp
                            <select data-placeholder="Select Employee" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='company[]' multiple>
                                <option value="">-- Select Company --</option>
                                    @foreach($companies as $company)
                                    <option value="{{$company->id}}" @if (in_array($company->id,$user_allowed_companies)) selected @endif>{{$company->company_name}}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 form-group">
                            <h5>Employees</h5>
                            @if($user->user_privilege)
                                @if($user->user_privilege->employees_view == 'on')
                                    <input type="checkbox" name="employees_view" id="employees_view{{$user->id}}" value="{{ $user->user_privilege->employees_view }}" checked>
                                @else
                                    <input type="checkbox" name="employees_view" id="employees_view{{$user->id}}">
                                @endif
                            @else
                                <input type="checkbox" name="employees_view" id="employees_view{{$user->id}}">
                            @endif
                            View
                            <br>
                            <br>
                            @if($user->user_privilege)
                                @if($user->user_privilege->employees_edit == 'on')
                                    <input type="checkbox" name="employees_edit" id="employees_edit{{$user->id}}" value="{{ $user->user_privilege->employees_edit }}" checked>
                                @else
                                    <input type="checkbox" name="employees_edit" id="employees_edit{{$user->id}}">
                                @endif
                            @else
                                <input type="checkbox" name="employees_edit" id="employees_edit{{$user->id}}">
                            @endif
                            Edit
                            <br>
                            <br>
                            @if($user->user_privilege)
                                @if($user->user_privilege->employees_add == 'on')
                                    <input type="checkbox" name="employees_add" id="employees_add{{$user->id}}" value="{{ $user->user_privilege->employees_add }}" checked>
                                @else
                                    <input type="checkbox" name="employees_add" id="employees_add{{$user->id}}">
                                @endif
                            @else
                                <input type="checkbox" name="employees_add" id="employees_add{{$user->id}}">
                            @endif
                            New
                            <br>
                            <br>
                            @if($user->user_privilege)
                                @if($user->user_privilege->employees_export == 'on')
                                    <input type="checkbox" name="employees_export" id="employees_export{{$user->id}}" value="{{ $user->user_privilege->employees_export }}" checked>
                                @else
                                    <input type="checkbox" name="employees_export" id="employees_export{{$user->id}}">
                                @endif
                            @else
                                <input type="checkbox" name="employees_export" id="employees_export{{$user->id}}">
                            @endif
                            Export
                            <br>
                            <br>
                            @if($user->user_privilege)
                                @if($user->user_privilege->employees_rate == 'on')
                                    <input type="checkbox" name="employees_rate" id="employees_rate{{$user->id}}" value="{{ $user->user_privilege->employees_rate }}" checked>
                                @else
                                    <input type="checkbox" name="employees_rate" id="employees_rate{{$user->id}}">
                                @endif
                            @else
                                <input type="checkbox" name="employees_rate" id="employees_rate{{$user->id}}">
                            @endif
                            Rate
                            <br>
                            <br>
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
