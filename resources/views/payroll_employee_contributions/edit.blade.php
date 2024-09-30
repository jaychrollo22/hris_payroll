<div class="modal fade" id="edit_payroll_employee_contribution{{$contribution->id}}" tabindex="-1" role="dialog" aria-labelledby="EditPayrollPeriodData" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='row'>
                    <div class='col-md-12'>
                        <h5 class="modal-title" id="EditPayrollPeriodData">Edit Payroll Period</h5>
                    </div>
                </div>
            </div>
            <form  method='POST' action='update-payroll-employee-contribution/{{$contribution->id}}' onsubmit='show()' >
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="sss_reg_ee">SSS REG EE</label>
                        <input type="number" class="form-control" name="sss_reg_ee" id="sss_reg_ee" value="{{ old('sss_reg_ee', $contribution->sss_reg_ee ?? '') }}" step="0.01">
                        @if ($errors->has('sss_reg_ee'))
                            <span class="text-danger">{{ $errors->first('sss_reg_ee') }}</span>
                        @endif
                    </div>
            
                    <div class="form-group">
                        <label for="sss_mpf_ee">SSS MPF EE</label>
                        <input type="number" class="form-control" name="sss_mpf_ee" id="sss_mpf_ee" value="{{ old('sss_mpf_ee', $contribution->sss_mpf_ee ?? '') }}" step="0.01">
                        @if ($errors->has('sss_mpf_ee'))
                            <span class="text-danger">{{ $errors->first('sss_mpf_ee') }}</span>
                        @endif
                    </div>
            
                    <div class="form-group">
                        <label for="phic_ee">PHIC EE</label>
                        <input type="number" class="form-control" name="phic_ee" id="phic_ee" value="{{ old('phic_ee', $contribution->phic_ee ?? '') }}" step="0.01">
                        @if ($errors->has('phic_ee'))
                            <span class="text-danger">{{ $errors->first('phic_ee') }}</span>
                        @endif
                    </div>
            
                    <div class="form-group">
                        <label for="hdmf_ee">HDMF EE</label>
                        <input type="number" class="form-control" name="hdmf_ee" id="hdmf_ee" value="{{ old('hdmf_ee', $contribution->hdmf_ee ?? '') }}" step="0.01">
                        @if ($errors->has('hdmf_ee'))
                            <span class="text-danger">{{ $errors->first('hdmf_ee') }}</span>
                        @endif
                    </div>
            
                    <div class="form-group">
                        <label for="sss_reg_er">SSS REG ER</label>
                        <input type="number" class="form-control" name="sss_reg_er" id="sss_reg_er" value="{{ old('sss_reg_er', $contribution->sss_reg_er ?? '') }}" step="0.01">
                        @if ($errors->has('sss_reg_er'))
                            <span class="text-danger">{{ $errors->first('sss_reg_er') }}</span>
                        @endif
                    </div>
            
                    <div class="form-group">
                        <label for="sss_mpf_er">SSS MPF ER</label>
                        <input type="number" class="form-control" name="sss_mpf_er" id="sss_mpf_er" value="{{ old('sss_mpf_er', $contribution->sss_mpf_er ?? '') }}" step="0.01">
                        @if ($errors->has('sss_mpf_er'))
                            <span class="text-danger">{{ $errors->first('sss_mpf_er') }}</span>
                        @endif
                    </div>
            
                    <div class="form-group">
                        <label for="sss_ec">SSS EC</label>
                        <input type="number" class="form-control" name="sss_ec" id="sss_ec" value="{{ old('sss_ec', $contribution->sss_ec ?? '') }}" step="0.01">
                        @if ($errors->has('sss_ec'))
                            <span class="text-danger">{{ $errors->first('sss_ec') }}</span>
                        @endif
                    </div>
            
                    <div class="form-group">
                        <label for="phic_er">PHIC ER</label>
                        <input type="number" class="form-control" name="phic_er" id="phic_er" value="{{ old('phic_er', $contribution->phic_er ?? '') }}" step="0.01">
                        @if ($errors->has('phic_er'))
                            <span class="text-danger">{{ $errors->first('phic_er') }}</span>
                        @endif
                    </div>
            
                    <div class="form-group">
                        <label for="hdmf_er">HDMF ER</label>
                        <input type="number" class="form-control" name="hdmf_er" id="hdmf_er" value="{{ old('hdmf_er', $contribution->hdmf_er ?? '') }}" step="0.01">
                        @if ($errors->has('hdmf_er'))
                            <span class="text-danger">{{ $errors->first('hdmf_er') }}</span>
                        @endif
                    </div>
            
                    <div class="form-group">
                        <label for="payment_schedule">Payment Schedule</label>
                        <select name="payment_schedule" id="payment_schedule" class="form-control" required>
                            <option value="First Cut-Off" {{ old('payment_schedule', $contribution->payment_schedule ?? '') == 'First Cut-Off' ? 'selected' : '' }}>First Cut-Off</option>
                            <option value="Second Cut-Off" {{ old('payment_schedule', $contribution->payment_schedule ?? '') == 'Second Cut-Off' ? 'selected' : '' }}>Second Cut-Off</option>
                        </select>
                        @if ($errors->has('payment_schedule'))
                            <span class="text-danger">{{ $errors->first('payment_schedule') }}</span>
                        @endif
                    </div>
            
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                  </div>
            </form>
        </div>
    </div>
</div>