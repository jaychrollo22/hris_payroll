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
                            {{-- @if ($user_allowed_companies) --}}
                                <select data-placeholder="Select Employee" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='company[]' multiple>
                                    <option value="">-- Select Company --</option>
                                        @foreach($companies as $company)
                                        <option value="{{$company->id}}" @if (in_array($company->id,$user_allowed_companies)) selected @endif>{{$company->company_name}}</option>
                                        @endforeach
                                </select>
                            {{-- @else
                                <select data-placeholder="Select Employee" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='company[]' multiple required>
                                    <option value="">-- Select Company --</option>
                                        @foreach($companies as $company)
                                        <option value="{{$company->id}}">{{$company->company_name}}</option>
                                        @endforeach
                                </select>
                            @endif --}}
                 
                            
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
