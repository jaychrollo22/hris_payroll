@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Payroll Register</h4>
                <p class="card-description">
                    <button type="button" class="btn btn-outline-primary btn-icon-text" data-toggle="modal" data-target="#generate_payroll_register">
                      <i class="ti-plus btn-icon-prepend"></i>                                                    
                      Generate Payroll Register
                    </button>
                    <a type="button" class="btn btn-outline-warning btn-icon-text" data-toggle="modal">
                      <i class="ti-plus btn-icon-prepend"></i>                                                    
                      Upload Payroll Register
                    </a>
                    <a type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal">
                      <i class="ti-plus btn-icon-prepend"></i>                                                    
                      Generate Payroll Attendances
                    </a>
                    <a type="button" class="btn btn-outline-danger btn-icon-text" data-toggle="modal">
                      <i class="ti-plus btn-icon-prepend"></i>                                                    
                      Salary Adjustments
                    </a>
                  </p>

                  <h4 class="card-title">Filter</h4>
                    <p class="card-description">
                    <form method='get' onsubmit='show();' enctype="multipart/form-data">
                      <div class=row>
                        <div class='col-md-4'>
                          <div class="form-group">
                            <select data-placeholder="Select Payroll Period" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='payroll_period' required>
                              <option value="">-- Select Payroll Period --</option>
                              @foreach($payroll_periods as $payroll_period_item)
                              <option value="{{$payroll_period_item->id}}" @if ($payroll_period_item->id == $payroll_period) selected @endif>{{$payroll_period_item->payroll_name}} ({{$payroll_period_item->start_date .'-'. $payroll_period_item->end_date}})</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class=row>
                        <div class='col-md-4'>
                          <div class="form-group">
                            <select data-placeholder="Select Company" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='company' required>
                              <option value="">-- Select Company --</option>
                              @foreach($companies as $comp)
                              <option value="{{$comp->id}}" @if ($comp->id == $company) selected @endif>{{$comp->company_name}} - {{$comp->company_code}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class='col-md-3'>
                          <div class="form-group">
                            <select data-placeholder="Select Department" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='department'>
                                <option value="">-- Select Department --</option>
                                @foreach($departments as $dep)
                                <option value="{{$dep->id}}" @if ($dep->id == $department) selected @endif>{{$dep->name}} - {{$dep->code}}</option>
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class='col-md-3'>
                          <button type="submit" class="btn btn-primary">Filter</button>
                          <a href="/pay-reg" class="btn btn-warning">Reset Filter</a>
                        </div>
                      </div>
                      
                    </form>
                  </p>
                    
                <div class="table-responsive">
                  <table id="table-payroll" class="table table-hover table-bordered">
                    <thead>
                        <tr>
                          <th>USER ID</th>
                          <th>BANK ACCOUNT #</th>
                          <th>NAME</th>
                          <th>POSITION</th>
                          <th>EMPLOYMENT STATUS</th>
                          <th>COMPANY</th>
                          <th>DEPARTMENT</th>
                          <th>PROJECT</th>
                          <th>DATE HIRED</th>
                          <th>CUT FROM</th>
                          <th>CUT TO</th>
                          <th>MONTHLY BASIC PAY</th>
                          <th>DAILY RATE</th>
                          <th>BASIC PAY</th>
                          <th>ABSENCES AMOUNT</th>
                          <th>LATES AMOUNT</th>
                          <th>UNDERTIME AMOUNT</th>
                          <th>SALARY ADJUSTMENT</th>
                          <th>OVERTIME PAY</th>
                          <th>MEAL ALLOWANCE</th>
                          <th>SALARY ALLOWANCE</th>
                          <th>OUT OF TOWN ALLOWANCE</th>
                          <th>INCENTIVES ALLOWANCE</th>
                          <th>RELOCATION ALLOWANCE</th>
                          <th>DISCRETIONARY ALLOWANCE</th>
                          <th>TRANSPORT ALLOWANCE</th>
                          <th>LOAD ALLOWANCE</th>
                          <th>GROSSPAY</th>
                          <th>TOTAL TAXABLE</th>
                          <th>MINIMUM WAGE</th>
                          <th>WITHHOLDING TAX</th>
                          <th>SSS REG EE</th>
                          <th>SSS MPF EE</th>
                          <th>PHIC EE</th>
                          <th>HMDF EE</th>
                          <th>HDMF SALARY LOAN</th>
                          <th>HDMF CALAMITY LOAN</th>
                          <th>SSS SALARY LOAN</th>
                          <th>SSS CALAMITY LOAN</th>
                          <th>SALARY DEDUCTION (Taxable)</th>
                          <th>SALARY DEDUCTION (Non-Taxable)</th>
                          <th>COMPANY LOAN</th>
                          <th>OMHAS (Advances from MAC)</th>
                          <th>COOP CBU</th>
                          <th>COOP REGULAR LOAN</th>
                          <th>COOP MESCCO</th>
                          <th>PETTY CASH MESCCO</th>
                          <th>OTHERS</th>
                          <th>TOTAL DEDUCTION</th>
                          <th>NETPAY</th>
                          <th>SSS REG ER</th>
                          <th>SSS MPF ER</th>
                          <th>SSS EC</th>
                          <th>PHIC ER</th>
                          <th>HDMF ER</th>
                          <th>BANK</th>
                          <th>STATUS</th>
                          <th>REMARKS</th>
                          <th>STATUS LAST PAYROLL</th>
                          <th>SSS NO.</th>
                          <th>PHILHEALTH NO.</th>
                          <th>PAG-IBIG NO.</th>
                          <th>TIN NO.</th>
                          <th>BIR TAGGING</th>
                          <th>MONTH 15</th>
                          <th>MONTH 30</th>
                          <th>ACCUMULATED</th>
                          <th>NUMBER</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach ($payroll_registers as $payroll)
                      <tr>
                          <td>{{ $payroll->user_id }}</td>
                          <td>{{ $payroll->bank_account }}</td>
                          <td>{{ $payroll->name }}</td>
                          <td>{{ $payroll->position }}</td>
                          <td>{{ $payroll->employment_status }}</td>
                          <td>{{ $payroll->company }}</td>
                          <td>{{ $payroll->department }}</td>
                          <td>{{ $payroll->project }}</td>
                          <td>{{ $payroll->date_hired }}</td>
                          <td>{{ $payroll->cut_from }}</td>
                          <td>{{ $payroll->cut_to }}</td>
                          <td>{{ number_format($payroll->monthly_basic_pay,2) }}</td>
                          <td>{{ number_format($payroll->daily_rate,2) }}</td>
                          <td>{{ number_format($payroll->basic_pay,2) }}</td>
                          <td>{{ $payroll->absences_amount }}</td>
                          <td>{{ $payroll->lates_amount }}</td>
                          <td>{{ $payroll->undertime_amount }}</td>
                          <td>{{ $payroll->salary_adjustment }}</td>
                          <td>{{ $payroll->overtime_pay }}</td>
                          <td>{{ $payroll->meal_allowance }}</td>
                          <td>{{ $payroll->salary_allowance }}</td>
                          <td>{{ $payroll->out_of_town_allowance }}</td>
                          <td>{{ $payroll->incentives_allowance }}</td>
                          <td>{{ $payroll->relocation_allowance }}</td>
                          <td>{{ $payroll->discretionary_allowance }}</td>
                          <td>{{ $payroll->transport_allowance }}</td>
                          <td>{{ $payroll->load_allowance }}</td>
                          <td>{{ $payroll->grosspay }}</td>
                          <td>{{ $payroll->total_taxable }}</td>
                          <td>{{ $payroll->minimum_wage }}</td>
                          <td>{{ $payroll->withholding_tax }}</td>
                          <td>{{ $payroll->sss_reg_ee_sept_15 }}</td>
                          <td>{{ $payroll->sss_mpf_ee_sept_15 }}</td>
                          <td>{{ $payroll->phic_ee_sept_15 }}</td>
                          <td>{{ $payroll->hmdf_ee_sept_15 }}</td>
                          <td>{{ $payroll->hdmf_salary_loan }}</td>
                          <td>{{ $payroll->hdmf_calamity_loan }}</td>
                          <td>{{ $payroll->sss_salary_loan }}</td>
                          <td>{{ $payroll->sss_calamity_loan }}</td>
                          <td>{{ $payroll->salary_deduction_taxable }}</td>
                          <td>{{ $payroll->salary_deduction_nontaxable }}</td>
                          <td>{{ $payroll->company_loan }}</td>
                          <td>{{ $payroll->omhas_loan }}</td>
                          <td>{{ $payroll->coop_cbu }}</td>
                          <td>{{ $payroll->coop_regular_loan }}</td>
                          <td>{{ $payroll->coop_mescco }}</td>
                          <td>{{ $payroll->petty_cash_mescco }}</td>
                          <td>{{ $payroll->others }}</td>
                          <td>{{ $payroll->total_deduction }}</td>
                          <td>{{ $payroll->netpay }}</td>
                          <td>{{ $payroll->sss_reg_er_sept_15 }}</td>
                          <td>{{ $payroll->sss_mpf_er_sept_15 }}</td>
                          <td>{{ $payroll->sss_ec_sept_15 }}</td>
                          <td>{{ $payroll->phic_er_sept_15 }}</td>
                          <td>{{ $payroll->hdmf_er_sept_15 }}</td>
                          <td>{{ $payroll->bank }}</td>
                          <td>{{ $payroll->status }}</td>
                          <td>{{ $payroll->remarks }}</td>
                          <td>{{ $payroll->status_last_payroll }}</td>
                          <td>{{ $payroll->sss_no }}</td>
                          <td>{{ $payroll->philhealth_no }}</td>
                          <td>{{ $payroll->pagibig_no }}</td>
                          <td>{{ $payroll->tin_no }}</td>
                          <td>{{ $payroll->bir_tagging }}</td>
                          <td>{{ $payroll->month_15 }}</td>
                          <td>{{ $payroll->month_30 }}</td>
                          <td>{{ $payroll->accumulated }}</td>
                          <td>{{ $payroll->number }}</td>
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

@include('pay_reg.generate_payroll_register') 

@endsection
