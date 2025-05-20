<!-- Modal -->
<div class="modal fade" id="generate_payroll_register" tabindex="-1" role="dialog" aria-labelledby="generatePayrollRegister" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="generatePayrollRegister">Generate Payroll Register</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method='POST' action='generate-payroll-register' onsubmit="btnDtr.disabled = true; return true;"  enctype="multipart/form-data">
            @csrf      
        <div class="modal-body">
            <div class=row>
                <div class='col-md-12'>
                    <div class="form-group">
                    <label for="payroll_register">Payroll Period:</label>
                    <select data-placeholder="Select Payroll Period" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='payroll_period' required>
                        <option value="">-- Select Payroll Period --</option>
                        @foreach($payroll_periods as $payroll_period_item)
                        <option value="{{$payroll_period_item->id}}" @if ($payroll_period_item->id == $payroll_period) selected @endif>{{$payroll_period_item->payroll_name}} ({{$payroll_period_item->start_date .'-'. $payroll_period_item->end_date}})</option>
                        @endforeach
                    </select>
                    </div>
                </div>
                <div class='col-md-12'>
                    <div class="form-group">
                    <label for="payroll_register">Company:</label>
                    <select data-placeholder="Select Company" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='company' required onchange="changeCompany(this.value)">
                        <option value="">-- Select Company --</option>
                        <option value="All" @if ($company == "All") selected @endif>All</option>
                        @foreach($companies as $comp)
                        <option value="{{$comp->id}}" @if ($comp->id == $company) selected @endif>{{$comp->company_name}} - {{$comp->company_code}}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
                <div class='col-md-12'>
                    <div class="form-group">
                    <label for="payroll_register">Department:</label>
                      <select data-placeholder="Select Department" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='department'>
                        <option value="">-- Select Department --</option>
                        @foreach($departments as $dep)
                        <option value="{{$dep->id}}" @if ($dep->id == $department) selected @endif>{{$dep->name}} - {{$dep->code}}</option>
                        @endforeach
                    </select>
                    </div>
                </div>

                <div class='col-md-12 form-group'>
                    <label>
                        <input type="radio" name="target_type" value="employee" {{ old('target_type') == 'employee' ? 'checked' : '' }} onchange="changeTargetType(this.value)" required>
                        Per Employee
                    </label>

                    <label>
                        <input type="radio" name="target_type" value="level" {{ old('target_type') == 'level' ? 'checked' : '' }} onchange="changeTargetType(this.value)">
                        Per Level
                    </label>
                </div>

                <div class='col-md-12' style="display: none" id="employeeFields">
                    <div class="form-group">
                    <label for="payroll_register">Employee:</label>
                      <select data-placeholder="Select Employee" class="form-control form-control-sm js-example-basic-single" style='width:100%;' name='employee' id="employee">
                          <option value="">-- Select Employee --</option>
                          @foreach($employees as $emp)
                          <option value="{{$emp->user_id}}" @if ($emp->id == $employee) selected @endif>{{ $emp->last_name .', '. $emp->first_name . ' ' . $emp->middle_name }}</option>
                          @endforeach
                      </select>
                    </div>
                </div>

                <div class='col-md-12' style="display: none" id="levelFields">
                  <div class="form-group">
                    <label for="payroll_register">Level:</label>
                    <select data-placeholder="Select Level" class="form-control form-control-sm js-example-basic-single" style='width:100%;' name='level' id="level">
                      <option value="">-- Select Level --</option>
                      <option value="All" @if ($level == "All") selected @endif>All</option>
                      @foreach($levels as $level_item)
                      <option value="{{$level_item->id}}" @if ($level_item->id == $level) selected @endif>{{$level_item->name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
            </div>
        </div>
  
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button name="btnDtr" type="submit" class="btn btn-primary">Generate</button>
        </div>
      </form>      
      </div>
    </div>
  </div>

<script>
function changeTargetType(radio) {
    const employeeFields = document.getElementById('employeeFields');
    const levelFields = document.getElementById('levelFields');
    const employeeSelect = document.getElementById('employee');
    const levelSelect = document.getElementById('level');

    if (radio == 'employee') {
        employeeFields.style.display = 'block';
        levelFields.style.display = 'none';
        employeeSelect.required = true;
        levelSelect.required = false;
    } else if (radio == 'level') {
        employeeFields.style.display = 'none';
        levelFields.style.display = 'block';
        employeeSelect.required = false;
        levelSelect.required = true;
    }
}

function changeCompany(company){
    document.getElementById('level').innerHTML = '<option value="">No levels available</option>';
    document.getElementById('employee').innerHTML = '<option value="">No Employees available</option>';

    $.ajax({
          url: '{{ route('get.levels') }}',
          method: 'POST',
          data: {
              company: company,
              _token: '{{ csrf_token() }}'
          },
          success: function(response) {
              if (response.levels.length > 0) {
                // Once data is returned, populate the levels dropdown
                let levelOptions = '<option value="">Select a level</option>';
                levelOptions += `<option value="All">All</option>`;
              
                // Populate the levels dynamically
                response.levels.forEach(function(level) {
                    levelOptions += `<option value="${level.id}">${level.name}</option>`;
                });

                document.getElementById('level').innerHTML = levelOptions;
              } else {
                  document.getElementById('level').innerHTML = '<option value="">No employees available</option>';
              }

              if (response.employees.length > 0) {
                // Once data is returned, populate the employees dropdown
                let employeeOptions = '<option value="">Select a Employee</option>';
              
                // Populate the employees dynamically
                response.employees.forEach(function(employee) {
                    employeeOptions += `<option value="${employee.user_id}">${employee.last_name +', '+ employee.first_name + ' ' + employee.middle_name}</option>`;
                });

                document.getElementById('employee').innerHTML = employeeOptions;
              } else {
                  document.getElementById('employee').innerHTML = '<option value="">No employees available</option>';
              }
          }
      });
}
</script>