@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  
                <div class="card-body" >
                    <h4 class="card-title" id="tdActionId{{ $ppr['id'] }}" data-id="{{ $ppr['id'] }}">VIEW PERFORMANCE PLAN REVIEW (PPR) 
                        <button class="btn btn-primary btn-sm" onclick="printDiv('contentToPrint')">Print</button>
                        @if(auth()->user()->id == '1' || auth()->user()->id == '3873')

                            <button class="btn btn-warning btn-sm float-right mr-2" id="{{ $ppr['id'] }}" onclick="deletePPR(this.id)">Delete PPR</button>

                            @if($ppr['status'] != 'Draft')
                                <button class="btn btn-danger btn-sm float-right ml-2 mr-2" id="{{ $ppr['id'] }}" onclick="returnToDraft(this.id)">Return to Draft</button>
                            @endif
                        
                            @if($ppr['level'] > 0 && ($ppr['status'] == 'For Review' || $ppr['status'] == 'Approved'))
                                <button class="btn btn-secondary btn-sm float-right ml-2 mr-2" id="{{ $ppr['id'] }}" onclick="resetApprover(this.id)">Reset Approver Level</button>
                            @endif
                        @endif
                    </h4>
                    <div class="table-responsive" id="contentToPrint">
                        <table class="table-bordered" width="100%">
                            <tr>
                                <td align="center">Calendar Year</td>
                                <td align="center"> {{ $ppr['calendar_year']}} </td>
                                <td align="center">Review Date</td>
                                <td align="center"> {{ date('Y-m-d',strtotime($ppr['review_date'])) }}</td>
                                <td align="center">Period</td>
                                <td align="center" style="vertical-align: middle;">
                                    <label class="mt-2">
                                        <input disabled type="radio" name="period" value="MIDYEAR" {{ $ppr['period'] === 'MIDYEAR' ? 'checked' : '' }}>
                                        MIDYEAR
                                    </label>
                                    <label class="mt-2">
                                        <input disabled type="radio" name="period" value="ANNUAL" {{ $ppr['period'] === 'ANNUAL' ? 'checked' : '' }}>
                                        ANNUAL
                                    </label>
                                    <label class="mt-2">
                                        <input disabled type="radio" name="period" value="PROBATIONARY" {{ $ppr['period'] === 'PROBATIONARY' ? 'checked' : '' }}>
                                        PROBATIONARY
                                    </label>
                                    <label class="mt-2" >
                                        <input disabled type="radio" name="period" value="SPECIAL" {{ $ppr['period'] === 'SPECIAL' ? 'checked' : '' }}>
                                        SPECIAL
                                    </label>
                                </td>
                            </tr>
                        </table>

                        {{-- Page 1 --}}

                        <table class="table-bordered mt-1" width="100%">
                            <tr>
                                <td>Group/Business Unit</td>
                                <td>{{$ppr_details->employee->company->company_name}}</td>
                            </tr>
                            <tr>
                                <td>Department/unit</td>
                                <td>{{$ppr_details->employee->department ? $ppr_details->employee->department->name : ""}}</td>
                            </tr>
                            <tr>
                                <td>Employee Name</td>
                                <td>{{$ppr_details->employee->first_name . ' ' . $ppr_details->employee->last_name}}</td>
                            </tr>
                            <tr>
                                <td>Position Title</td>
                                <td>{{$ppr_details->employee->position }}</td>
                            </tr>
                            <tr>
                                <td>Employee Number</td>
                                <td>{{$ppr_details->employee->employee_number }}</td>
                            </tr>
                            <tr>
                                <td>Date Hired</td>
                                <td>{{$ppr_details->employee->original_date_hired }}</td>
                            </tr>
                        </table>

                        <table class="table-bordered mt-1" width="100%">
                            <tr>
                                <td align="center" style="background-color: rgb(240, 240, 240)"><strong>SUMMARY OF PERFORMANCE</strong> </td>
                                <td align="center" style="background-color: rgb(240, 240, 240)"><strong>PERFORMANCE DEFINITION</strong> </td>
                                <td align="center" style="background-color: rgb(240, 240, 240)"><strong>GRADE RATING</strong> </td>
                                <td align="center" style="background-color: rgb(240, 240, 240)"><strong>RATING SCALE</strong> </td>
                            </tr>
                            <tr>
                                <td align="center">OUTSTANDING</td>
                                <td align="center">Performance is consistently superior and sustained to the standards required for the job.</td>
                                <td align="center">5</td>
                                <td align="center">111% - 120%</td>
                            </tr>
                            <tr>
                                <td align="center">EXCEED EXPECTATIONS</td>
                                <td align="center">Performance exceeds/ is above the standard/ normal requirements of the position.</td>
                                <td align="center">4</td>
                                <td align="center">101% - 110%</td>
                            </tr>
                            <tr>
                                <td align="center">MEETS EXPECTATION</td>
                                <td align="center">Performance consistently meets the standards required of the position </td>
                                <td align="center">3</td>
                                <td align="center">76% - 100%</td>
                            </tr>
                            <tr>
                                <td align="center">NEEDS IMPROVEMENT</td>
                                <td align="center">Performance does not consistently meet the standards required of the position, objectives, and expectation.</td>
                                <td align="center">2</td>
                                <td align="center">51% - 75%</td>
                            </tr>
                            <tr>
                                <td align="center">UNSATISFACTORY</td>
                                <td align="center">Work performance is inadequate and inferior to the standards required of the position.  (Performance cannot be allowed to continue).</td>
                                <td align="center">1</td>
                                <td align="center">Below 50%</td>
                            </tr>
                        </table>

                        <table class="table-bordered mt-1" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center" rowspan="2"
                                        style="vertical-align: middle;background-color:rgb(240, 240, 240)">BSC PERSPECTIVE</th>
                                    <th class="text-center" rowspan="2" colspan="2"
                                        style="vertical-align: middle;background-color:rgb(240, 240, 240)">OBJECTIVE AND STRATEGY</th>

                                    <th class="text-center" colspan="2" style="background-color:rgb(240, 240, 240)">KPI</th>
                                    <th class="text-center" rowspan="2" colspan="2" style="background-color:rgb(240, 240, 240)">TARGET OF COMPLETION</th>
                                    <th class="text-center" rowspan="2" style="background-color:rgb(240, 240, 240)">WEIGHT</th>
                                    <th class="text-center" colspan="2" style="background-color:rgb(240, 240, 240)">REVIEW</th>
                                </tr>
                                <tr>

                                    <th class="text-center text-dark" style="background-color:rgb(240, 240, 240); ">
                                        <h4>Metric</h4>
                                    </th>
                                    <th class="text-center text-dark" style="background-color:rgb(240, 240, 240)">
                                        <h4>Target</h4>
                                    </th>
                                    <th class="text-center text-dark" style="background-color:rgb(240, 240, 240)">
                                        <h4>Actual</h4>
                                    </th>
                                    <th class="text-center text-dark" style="background-color:rgb(240, 240, 240)">
                                        <h4>Remarks</h4>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- 1. Financial Perspective --}}
                                <tr>
                                    <td style="text-align: center; width:200px!important;" rowspan="9" class="text-center">1. Financial Perspective</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-center">Objective 1. Revenue</td>
                                    <td colspan="7"></td>
                                </tr>
                                
                                <tr>
                                    <td style="width:200px!important;">Strat 1</td>
                                    <td style="text-align: center; width:300px!important;">{{$ppr['financial_perspective']['strat_1_objective_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['financial_perspective']['strat_1_metric_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['financial_perspective']['strat_1_target_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['financial_perspective']['strat_1_target_start_completion_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['financial_perspective']['strat_1_target_end_completion_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['financial_perspective']['strat_1_weight_1']) ? $ppr['financial_perspective']['strat_1_weight_1'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['financial_perspective']['strat_1_review_actual_1']) ? $ppr['financial_perspective']['strat_1_review_actual_1'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['financial_perspective']['strat_1_remarks_1']) ? $ppr['financial_perspective']['strat_1_remarks_1'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td>Strat 2</td>
                                    <td style="text-align: center; width:300px!important;">{{$ppr['financial_perspective']['strat_2_objective_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['financial_perspective']['strat_2_metric_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['financial_perspective']['strat_2_target_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['financial_perspective']['strat_2_target_start_completion_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['financial_perspective']['strat_2_target_end_completion_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['financial_perspective']['strat_2_weight_1']) ? $ppr['financial_perspective']['strat_2_weight_1'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['financial_perspective']['strat_2_review_actual_1']) ? $ppr['financial_perspective']['strat_2_review_actual_1'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['financial_perspective']['strat_2_remarks_1']) ? $ppr['financial_perspective']['strat_2_remarks_1'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td>Strat 3</td>
                                    <td style="text-align: center; width:300px!important;">{{$ppr['financial_perspective']['strat_3_objective_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['financial_perspective']['strat_3_metric_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['financial_perspective']['strat_3_target_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['financial_perspective']['strat_3_target_start_completion_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['financial_perspective']['strat_3_target_end_completion_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['financial_perspective']['strat_3_weight_1']) ? $ppr['financial_perspective']['strat_3_weight_1'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['financial_perspective']['strat_3_review_actual_1']) ? $ppr['financial_perspective']['strat_3_review_actual_1'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['financial_perspective']['strat_3_remarks_1']) ? $ppr['financial_perspective']['strat_3_remarks_1'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-center">Objective 2. Profit</td>
                                    <td colspan="7"></td>
                                </tr>
                                <tr>
                                    <td style="width:200px!important;">Strat 1</td>
                                    <td style="text-align: center; width:300px!important;">{{$ppr['financial_perspective']['strat_1_objective_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['financial_perspective']['strat_1_metric_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['financial_perspective']['strat_1_target_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['financial_perspective']['strat_1_target_start_completion_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['financial_perspective']['strat_1_target_end_completion_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['financial_perspective']['strat_1_weight_2']) ? $ppr['financial_perspective']['strat_1_weight_2'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['financial_perspective']['strat_1_review_actual_2']) ? $ppr['financial_perspective']['strat_1_review_actual_2'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['financial_perspective']['strat_1_remarks_2']) ? $ppr['financial_perspective']['strat_1_remarks_2'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td>Strat 2</td>
                                    <td style="text-align: center; width:300px!important;">{{$ppr['financial_perspective']['strat_2_objective_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['financial_perspective']['strat_2_metric_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['financial_perspective']['strat_2_target_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['financial_perspective']['strat_2_target_start_completion_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['financial_perspective']['strat_2_target_end_completion_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['financial_perspective']['strat_2_weight_2']) ? $ppr['financial_perspective']['strat_2_weight_2'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['financial_perspective']['strat_2_review_actual_2']) ? $ppr['financial_perspective']['strat_2_review_actual_2'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['financial_perspective']['strat_2_remarks_2']) ? $ppr['financial_perspective']['strat_2_remarks_2'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td>Strat 3</td>
                                    <td style="text-align: center; width:300px!important;">{{$ppr['financial_perspective']['strat_3_objective_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['financial_perspective']['strat_3_metric_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['financial_perspective']['strat_3_target_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['financial_perspective']['strat_3_target_start_completion_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['financial_perspective']['strat_3_target_end_completion_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['financial_perspective']['strat_3_weight_2']) ? $ppr['financial_perspective']['strat_3_weight_2'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['financial_perspective']['strat_3_review_actual_2']) ? $ppr['financial_perspective']['strat_3_review_actual_2'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['financial_perspective']['strat_3_remarks_2']) ? $ppr['financial_perspective']['strat_3_remarks_2'] : ""}}</td>
                                </tr>

                                <tr>
                                    <td colspan="10">&nbsp;</td>
                                </tr>

                                {{-- 2. Customer Focus --}}
                                <tr>
                                    <td rowspan="9" class="text-center">2. Customer Focus</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-center">Objective 1</td>
                                    <td colspan="7"></td>
                                </tr>
                                
                                <tr>
                                    <td style="width:200px!important;">Strat 1</td>
                                    <td style="text-align: center; width:300px!important;">{{$ppr['customer_focus']['strat_1_objective_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['customer_focus']['strat_1_metric_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['customer_focus']['strat_1_target_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['customer_focus']['strat_1_target_start_completion_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['customer_focus']['strat_1_target_end_completion_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['customer_focus']['strat_1_weight_1']) ? $ppr['customer_focus']['strat_1_weight_1'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['customer_focus']['strat_1_review_actual_1']) ? $ppr['customer_focus']['strat_1_review_actual_1'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['customer_focus']['strat_1_remarks_1']) ? $ppr['customer_focus']['strat_1_remarks_1'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td>Strat 2</td>
                                    <td style="text-align: center; width:300px!important;">{{$ppr['customer_focus']['strat_2_objective_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['customer_focus']['strat_2_metric_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['customer_focus']['strat_2_target_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['customer_focus']['strat_2_target_start_completion_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['customer_focus']['strat_2_target_end_completion_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['customer_focus']['strat_2_weight_1']) ? $ppr['customer_focus']['strat_2_weight_1'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['customer_focus']['strat_2_review_actual_1']) ? $ppr['customer_focus']['strat_2_review_actual_1'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['customer_focus']['strat_2_remarks_1']) ? $ppr['customer_focus']['strat_2_remarks_1'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td>Strat 3</td>
                                    <td style="text-align: center; width:300px!important;">{{$ppr['customer_focus']['strat_3_objective_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['customer_focus']['strat_3_metric_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['customer_focus']['strat_3_target_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['customer_focus']['strat_3_target_start_completion_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['customer_focus']['strat_3_target_end_completion_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['customer_focus']['strat_3_weight_1']) ? $ppr['customer_focus']['strat_3_weight_1'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['customer_focus']['strat_3_review_actual_1']) ? $ppr['customer_focus']['strat_3_review_actual_1'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['customer_focus']['strat_3_remarks_1']) ? $ppr['customer_focus']['strat_3_remarks_1'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-center">Objective 2</td>
                                    <td colspan="7"></td>
                                </tr>
                                <tr>
                                    <td style="width:200px!important;">Strat 1</td>
                                    <td style="text-align: center; width:300px!important;">{{$ppr['customer_focus']['strat_1_objective_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['customer_focus']['strat_1_metric_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['customer_focus']['strat_1_target_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['customer_focus']['strat_1_target_start_completion_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['customer_focus']['strat_1_target_end_completion_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['customer_focus']['strat_1_weight_2']) ? $ppr['customer_focus']['strat_1_weight_2'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['customer_focus']['strat_1_review_actual_2']) ? $ppr['customer_focus']['strat_1_review_actual_2'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['customer_focus']['strat_1_remarks_2']) ? $ppr['customer_focus']['strat_1_remarks_2'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td>Strat 2</td>
                                    <td style="text-align: center; width:300px!important;">{{$ppr['customer_focus']['strat_2_objective_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['customer_focus']['strat_2_metric_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['customer_focus']['strat_2_target_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['customer_focus']['strat_2_target_start_completion_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['customer_focus']['strat_2_target_end_completion_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['customer_focus']['strat_2_weight_2']) ? $ppr['customer_focus']['strat_2_weight_2'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['customer_focus']['strat_2_review_actual_2']) ? $ppr['customer_focus']['strat_2_review_actual_2'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['customer_focus']['strat_2_remarks_2']) ? $ppr['customer_focus']['strat_2_remarks_2'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td>Strat 3</td>
                                    <td style="text-align: center; width:300px!important;">{{$ppr['customer_focus']['strat_3_objective_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['customer_focus']['strat_3_metric_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['customer_focus']['strat_3_target_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['customer_focus']['strat_3_target_start_completion_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['customer_focus']['strat_3_target_end_completion_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['customer_focus']['strat_3_weight_2']) ? $ppr['customer_focus']['strat_3_weight_2'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['customer_focus']['strat_3_review_actual_2']) ? $ppr['customer_focus']['strat_3_review_actual_2'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['customer_focus']['strat_3_remarks_2']) ? $ppr['customer_focus']['strat_3_remarks_2'] : ""}}</td>
                                </tr>


                                <tr>
                                    <td colspan="10">&nbsp;</td>
                                </tr>

                                {{-- 3. Operational Efficiency --}}
                                <tr>
                                    <td rowspan="9" class="text-center">3. Operational Efficiency</td>
                                </tr>

                                <tr>
                                    <td colspan="2" class="text-center">Objective 1</td>
                                    <td colspan="7"></td>
                                </tr>
                                <tr>
                                    <td style="width:200px!important;">Strat 1</td>
                                    <td style="text-align: center; width:300px!important;">{{$ppr['operation_efficiency']['strat_1_objective_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['operation_efficiency']['strat_1_metric_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['operation_efficiency']['strat_1_target_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['operation_efficiency']['strat_1_target_start_completion_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['operation_efficiency']['strat_1_target_end_completion_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['operation_efficiency']['strat_1_weight_1']) ? $ppr['operation_efficiency']['strat_1_weight_1'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['operation_efficiency']['strat_1_review_actual_1']) ? $ppr['operation_efficiency']['strat_1_review_actual_1'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['operation_efficiency']['strat_1_remarks_1']) ? $ppr['operation_efficiency']['strat_1_remarks_1'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td>Strat 2</td>
                                    <td style="text-align: center; width:300px!important;">{{$ppr['operation_efficiency']['strat_2_objective_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['operation_efficiency']['strat_2_metric_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['operation_efficiency']['strat_2_target_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['operation_efficiency']['strat_2_target_start_completion_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['operation_efficiency']['strat_2_target_end_completion_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['operation_efficiency']['strat_2_weight_1']) ? $ppr['operation_efficiency']['strat_2_weight_1'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['operation_efficiency']['strat_2_review_actual_1']) ? $ppr['operation_efficiency']['strat_2_review_actual_1'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['operation_efficiency']['strat_2_remarks_1']) ? $ppr['operation_efficiency']['strat_2_remarks_1'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td>Strat 3</td>
                                    <td style="text-align: center; width:300px!important;">{{$ppr['operation_efficiency']['strat_3_objective_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['operation_efficiency']['strat_3_metric_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['operation_efficiency']['strat_3_target_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['operation_efficiency']['strat_3_target_start_completion_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['operation_efficiency']['strat_3_target_end_completion_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['operation_efficiency']['strat_3_weight_1']) ? $ppr['operation_efficiency']['strat_3_weight_1'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['operation_efficiency']['strat_3_review_actual_1']) ? $ppr['operation_efficiency']['strat_3_review_actual_1'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['operation_efficiency']['strat_3_remarks_1']) ? $ppr['operation_efficiency']['strat_3_remarks_1'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-center">Objective 2</td>
                                    <td colspan="7"></td>
                                </tr>
                                <tr>
                                    <td style="width:200px!important;">Strat 1</td>
                                    <td style="text-align: center; width:300px!important;">{{$ppr['operation_efficiency']['strat_1_objective_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['operation_efficiency']['strat_1_metric_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['operation_efficiency']['strat_1_target_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['operation_efficiency']['strat_1_target_start_completion_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['operation_efficiency']['strat_1_target_end_completion_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['operation_efficiency']['strat_1_weight_2']) ? $ppr['operation_efficiency']['strat_1_weight_2'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['operation_efficiency']['strat_1_review_actual_2']) ? $ppr['operation_efficiency']['strat_1_review_actual_2'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['operation_efficiency']['strat_1_remarks_2']) ? $ppr['operation_efficiency']['strat_1_remarks_2'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td>Strat 2</td>
                                    <td style="text-align: center; width:300px!important;">{{$ppr['operation_efficiency']['strat_2_objective_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['operation_efficiency']['strat_2_metric_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['operation_efficiency']['strat_2_target_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['operation_efficiency']['strat_2_target_start_completion_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['operation_efficiency']['strat_2_target_end_completion_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['operation_efficiency']['strat_2_weight_2']) ? $ppr['operation_efficiency']['strat_2_weight_2'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['operation_efficiency']['strat_2_review_actual_2']) ? $ppr['operation_efficiency']['strat_2_review_actual_2'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['operation_efficiency']['strat_2_remarks_2']) ? $ppr['operation_efficiency']['strat_2_remarks_2'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td>Strat 3</td>
                                    <td style="text-align: center; width:300px!important;">{{$ppr['operation_efficiency']['strat_3_objective_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['operation_efficiency']['strat_3_metric_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['operation_efficiency']['strat_3_target_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['operation_efficiency']['strat_3_target_start_completion_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['operation_efficiency']['strat_3_target_end_completion_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['operation_efficiency']['strat_3_weight_2']) ? $ppr['operation_efficiency']['strat_3_weight_2'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['operation_efficiency']['strat_3_review_actual_2']) ? $ppr['operation_efficiency']['strat_3_review_actual_2'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['operation_efficiency']['strat_3_remarks_2']) ? $ppr['operation_efficiency']['strat_3_remarks_2'] : ""}}</td>
                                </tr>

                                <tr>
                                    <td colspan="10">&nbsp;</td>
                                </tr>

                                {{-- 4. People --}}
                                <tr>
                                    <td rowspan="9" class="text-center">4. People</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-center">Objective 1</td>
                                    <td colspan="7"></td>
                                </tr>
                                
                                <tr>
                                    <td style="width:200px!important;">Strat 1</td>
                                    <td style="text-align: center; width:300px!important;">{{$ppr['people']['strat_1_objective_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['people']['strat_1_metric_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['people']['strat_1_target_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['people']['strat_1_target_start_completion_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['people']['strat_1_target_end_completion_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['people']['strat_1_weight_1']) ? $ppr['people']['strat_1_weight_1'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['people']['strat_1_review_actual_1']) ? $ppr['people']['strat_1_review_actual_1'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['people']['strat_1_remarks_1']) ? $ppr['people']['strat_1_remarks_1'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td>Strat 2</td>
                                    <td style="text-align: center; width:300px!important;">{{$ppr['people']['strat_2_objective_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['people']['strat_2_metric_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['people']['strat_2_target_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['people']['strat_2_target_start_completion_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['people']['strat_2_target_end_completion_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['people']['strat_2_weight_1']) ? $ppr['people']['strat_2_weight_1'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['people']['strat_2_review_actual_1']) ? $ppr['people']['strat_2_review_actual_1'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['people']['strat_2_remarks_1']) ? $ppr['people']['strat_2_remarks_1'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td>Strat 3</td>
                                    <td style="text-align: center; width:300px!important;">{{$ppr['people']['strat_3_objective_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['people']['strat_3_metric_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['people']['strat_3_target_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['people']['strat_3_target_start_completion_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['people']['strat_3_target_end_completion_1']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['people']['strat_3_weight_1']) ? $ppr['people']['strat_3_weight_1'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['people']['strat_3_review_actual_1']) ? $ppr['people']['strat_3_review_actual_1'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['people']['strat_3_remarks_1']) ? $ppr['people']['strat_3_remarks_1'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-center">Objective 2</td>
                                    <td colspan="7"></td>
                                </tr>
                                <tr>
                                    <td style="width:200px!important;">Strat 1</td>
                                    <td style="text-align: center; width:300px!important;">{{$ppr['people']['strat_1_objective_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['people']['strat_1_metric_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['people']['strat_1_target_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['people']['strat_1_target_start_completion_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['people']['strat_1_target_end_completion_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['people']['strat_1_weight_2']) ? $ppr['people']['strat_1_weight_2'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['people']['strat_1_review_actual_2']) ? $ppr['people']['strat_1_review_actual_2'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['people']['strat_1_remarks_2']) ? $ppr['people']['strat_1_remarks_2'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td>Strat 2</td>
                                    <td style="text-align: center; width:300px!important;">{{$ppr['people']['strat_2_objective_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['people']['strat_2_metric_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['people']['strat_2_target_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['people']['strat_2_target_start_completion_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['people']['strat_2_target_end_completion_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['people']['strat_2_weight_2']) ? $ppr['people']['strat_2_weight_2'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['people']['strat_2_review_actual_2']) ? $ppr['people']['strat_2_review_actual_2'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['people']['strat_2_remarks_2']) ? $ppr['people']['strat_2_remarks_2'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td>Strat 3</td>
                                    <td style="text-align: center; width:300px!important;">{{$ppr['people']['strat_3_objective_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['people']['strat_3_metric_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['people']['strat_3_target_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['people']['strat_3_target_start_completion_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{$ppr['people']['strat_3_target_end_completion_2']}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['people']['strat_3_weight_2']) ? $ppr['people']['strat_3_weight_2'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['people']['strat_3_review_actual_2']) ? $ppr['people']['strat_3_review_actual_2'] : ""}}</td>
                                    <td style="text-align: center; width:10px!important;">{{ isset($ppr['people']['strat_3_remarks_2']) ? $ppr['people']['strat_3_remarks_2'] : ""}}</td>
                                </tr>

                            </tbody>
                        </table>

                        {{-- Page 2 --}}
                        <table class="table-bordered mt-3" width="100%">
                            <tr>
                                <td width="200px" align="center" style="background-color: rgb(240, 240, 240)"><strong>SCALE</strong> </td>
                                <td width="800px" align="center" style="background-color: rgb(240, 240, 240)"><strong>COMPETENCY DESCRIPTION</strong> </td>
                                <td width="100px" align="center" style="background-color: rgb(240, 240, 240)"><strong>LEVEL</strong> </td>
                            </tr>
                            <tr>
                                <td align="center">EXCEED EXPECTATIONS</td>
                                <td align="center">Specialist/ authority level of the knowledge, understanding, and application of the competency required to be successful in the job and in the organization. Recognized by others as an expert in the competency and is sought by others throught the organization. Works across teams, departments, and organizational functions.  Applies the skill across multiple projects or functions. Able to explain issues in relation to broader organizational issues.  Has strategic focus.</td>
                                <td align="center">4</td>
                            </tr>
                            <tr>
                                <td align="center">MEETS EXPECTATION</td>
                                <td align="center">Highly developed knowledge, understanding, and application of the competency. Can apply the competency outside the scope of one's position. Able to coach or teach others on the competency. Has a long term perspective and helps develop materials and resources related to the competency.</td>
                                <td align="center">3</td>
                            </tr>
                            <tr>
                                <td align="center">NEEDS IMPROVEMENT</td>
                                <td align="center">Detailed knowledge, understanding, and application of the competency required to be successful in the job. Requires minimal guidance/ supervision. Capable of assisting others in the application of the competency.</td>
                                <td align="center">2</td>
                            </tr>
                            <tr>
                                <td align="center">UNSATIFACTORY</td>
                                <td align="center">Basic understanding. Requires guidance or supervision in applying the competency.  Understands and can discuss the terminaology and concept related to the competency.</td>
                                <td align="center">1</td>
                            </tr>
                        </table>

                        <table class="table-bordered mt-2" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center" rowspan="2"
                                        style="vertical-align: middle;background-color:rgb(240, 240, 240)">COMPETENCIES</th>
                                    <th class="text-center" rowspan="2"
                                        style="vertical-align: middle;background-color:rgb(240, 240, 240)">COMPETENCY DESCRIPTION</th>
                                    <th class="text-center" colspan="2" style="background-color:rgb(240, 240, 240)">REVIEW</th>
                                    <th class="text-center" rowspan="2" style="background-color:rgb(240, 240, 240)">WTD. SCORE</th>
                                </tr>
                                <tr>

                                    <th class="text-center text-dark" style="background-color:rgb(240, 240, 240); ">
                                        <h4>SELF RATING</h4>
                                    </th>
                                    <th class="text-center text-dark" style="background-color:rgb(240, 240, 240)">
                                        <h4>SUPERIO'S RATING</h4>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- 1 --}}
                                <tr>
                                    <td rowspan="3" class="text-center text-dark">1. INTEGRITY - Ability to do good and be good at all times, even if no one is looking </td>
                                    <td class="text-center text-dark">Demonstrates action that are honorable, deserving of respect, honest, and virtuous regardless of professional consequence and personal interest.</td>
                                    <td class="text-center text-dark">{{isset($ppr['integrity']['self_rating_1']) ? $ppr['integrity']['self_rating_1'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['integrity']['self_rating_1']) ? $ppr['integrity']['superios_rating_1'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['integrity']['self_rating_1']) ? $ppr['integrity']['wtd_score_1'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td class="text-center text-dark">Treats people with dignity, respect, and fairness; gives proper credit to others; stands up for others who are deserving and their ideas even in the face of resistance or challenge to fosters high standards of values and ethics.</td>
                                    <td class="text-center text-dark">{{isset($ppr['integrity']['self_rating_2']) ? $ppr['integrity']['self_rating_2'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['integrity']['superios_rating_2']) ? $ppr['integrity']['superios_rating_2'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['integrity']['wtd_score_2']) ? $ppr['integrity']['wtd_score_2'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td class="text-center text-dark">Acts as a role model for working with others with sincerity, cheerfulness, and trust worthy traits in carrying out the job/ task with minimal to on supervision.</td>
                                    <td class="text-center text-dark">{{isset($ppr['integrity']['self_rating_3']) ? $ppr['integrity']['self_rating_3'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['integrity']['superios_rating_3']) ? $ppr['integrity']['superios_rating_3'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['integrity']['wtd_score_3']) ? $ppr['integrity']['wtd_score_3'] : ""}}</td>
                                </tr>

                                <tr>
                                    <td colspan="6" class="text-center">&nbsp;</td>
                                </tr>

                                {{-- 2 --}}
                                <tr>
                                    <td rowspan="3" class="text-center text-dark">2. COMMITMENT - Ability to never make excuses, only results</td>
                                    <td class="text-center text-dark">Defines personal purpose, meaning, and challenges, and proactively set plans and strategies to overcome obstacles and meet current and future needs and targets.</td>
                                    <td class="text-center text-dark">{{isset($ppr['commitment']['self_rating_1']) ? $ppr['commitment']['self_rating_1'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['commitment']['self_rating_1']) ? $ppr['commitment']['superios_rating_1'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['commitment']['self_rating_1']) ? $ppr['commitment']['wtd_score_1'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td class="text-center text-dark">Diligently completes the assigned work efficiently, completely, accurately, and meets the standards of performance (e.g. KRA/ KPI, quality/ quantity of work, presence, timeliness) within an established time frame and within budget.</td>
                                    <td class="text-center text-dark">{{isset($ppr['commitment']['self_rating_2']) ? $ppr['commitment']['self_rating_2'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['commitment']['superios_rating_2']) ? $ppr['commitment']['superios_rating_2'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['commitment']['wtd_score_2']) ? $ppr['commitment']['wtd_score_2'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td class="text-center text-dark">Executes objectives, delivers/ exceed targets and sees tasks through to the end; while taking into consideration current responsibilities, work load, core values, and the trust, confidence and resources bestowed on him/ her, and the company-wide organizational goals.</td>
                                    <td class="text-center text-dark">{{isset($ppr['commitment']['self_rating_3']) ? $ppr['commitment']['self_rating_3'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['commitment']['superios_rating_3']) ? $ppr['commitment']['superios_rating_3'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['commitment']['wtd_score_3']) ? $ppr['commitment']['wtd_score_3'] : ""}}</td>
                                </tr>

                                <tr>
                                    <td colspan="6" class="text-center">&nbsp;</td>
                                </tr>

                                {{-- 3 --}}
                                <tr>
                                    <td rowspan="3" class="text-center text-dark">3. HUMILITY - Ability to be simple (no pretenses)</td>
                                    <td class="text-center text-dark">Recognizes personal strengths and gaps and seeks guidance or resources in laying out development and/or improvement plans.</td>
                                    <td class="text-center text-dark">{{isset($ppr['humility']['self_rating_1']) ? $ppr['humility']['self_rating_1'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['humility']['self_rating_1']) ? $ppr['humility']['superios_rating_1'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['humility']['self_rating_1']) ? $ppr['humility']['wtd_score_1'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td class="text-center text-dark">Asks for and uses feedback to improve performance, seeks additional training and development to improve his/ her knowledge and skills.</td>
                                    <td class="text-center text-dark">{{isset($ppr['humility']['self_rating_2']) ? $ppr['humility']['self_rating_2'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['humility']['superios_rating_2']) ? $ppr['humility']['superios_rating_2'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['humility']['wtd_score_2']) ? $ppr['humility']['wtd_score_2'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td class="text-center text-dark">Reads, understands, and abides/ faithfully comply and conform with the Company Code of Discipline, Policies, and Procedures; and in case of violations, accepts administrative and/or financial accountability, if any.</td>
                                    <td class="text-center text-dark">{{isset($ppr['humility']['self_rating_3']) ? $ppr['humility']['self_rating_3'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['humility']['superios_rating_3']) ? $ppr['humility']['superios_rating_3'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['humility']['wtd_score_3']) ? $ppr['humility']['wtd_score_3'] : ""}}</td>
                                </tr>

                                <tr>
                                    <td colspan="6" class="text-center">&nbsp;</td>
                                </tr>

                                {{-- 4 --}}
                                <tr>
                                    <td rowspan="3" class="text-center text-dark">4. GENUINE CONCERN - Ability to enrich the lives of people</td>
                                    <td class="text-center text-dark">Understand, assists and cares for the feelings and well-being (e.g. happy and safe, feel at ease and at home) of co-workers,  without hesitation or pretensions, directed by  his/ her attentiveness and sensitivety of their needs, difficulties, and changes in the mood of a room or emotions of those around.</td>
                                    <td class="text-center text-dark">{{isset($ppr['genuine_concern']['self_rating_1']) ? $ppr['genuine_concern']['self_rating_1'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['genuine_concern']['self_rating_1']) ? $ppr['genuine_concern']['superios_rating_1'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['genuine_concern']['self_rating_1']) ? $ppr['genuine_concern']['wtd_score_1'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td class="text-center text-dark">Listens to others and objectively considers others’ ideas and opinions, even when they conflict with one’s own, and addressing their potential impact organization-wide and across group when performing and helping others complete given tasks.</td>
                                    <td class="text-center text-dark">{{isset($ppr['genuine_concern']['self_rating_2']) ? $ppr['genuine_concern']['self_rating_2'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['genuine_concern']['superios_rating_2']) ? $ppr['genuine_concern']['superios_rating_2'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['genuine_concern']['wtd_score_2']) ? $ppr['genuine_concern']['wtd_score_2'] : ""}}</td>
                                <tr>
                                    <td class="text-center text-dark">Identifies opportunities for improving performance both for one's own area or responsibility and/or within the organization, and commits significant resources to improve performance while taking action.</td>
                                    <td class="text-center text-dark">{{isset($ppr['genuine_concern']['self_rating_3']) ? $ppr['genuine_concern']['self_rating_3'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['genuine_concern']['superios_rating_3']) ? $ppr['genuine_concern']['superios_rating_3'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['genuine_concern']['wtd_score_3']) ? $ppr['genuine_concern']['wtd_score_3'] : ""}}</td>
                                </tr>

                                <tr>
                                    <td colspan="6" class="text-center">&nbsp;</td>
                                </tr>

                                {{-- 5 --}}
                                <tr>
                                    <td rowspan="3" class="text-center text-dark">5. PREMIUM SERVICE - Ability to delivery quality service beyond expectation </td>
                                    <td class="text-center text-dark">Exceeds expectation in delivering a completed service/ task, with accurate and organized information, within the time line and standards set by the company or customer.</td>
                                    <td class="text-center text-dark">{{isset($ppr['premium_service']['self_rating_1']) ? $ppr['premium_service']['self_rating_1'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['premium_service']['self_rating_1']) ? $ppr['premium_service']['superios_rating_1'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['premium_service']['self_rating_1']) ? $ppr['premium_service']['wtd_score_1'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td class="text-center text-dark">Institutes a process/ system for monitoring and tracking individual and/or team results/ progress against standards; and modifies actions accordingly.</td>
                                    <td class="text-center text-dark">{{isset($ppr['premium_service']['self_rating_2']) ? $ppr['premium_service']['self_rating_2'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['premium_service']['superios_rating_2']) ? $ppr['premium_service']['superios_rating_2'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['premium_service']['wtd_score_2']) ? $ppr['premium_service']['wtd_score_2'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td class="text-center text-dark">Gives value to customers by knowing your product and service, actively listening, practicing honesty in attending to customer needs; before, during, and after an exchange/ transaction.</td>
                                    <td class="text-center text-dark">{{isset($ppr['premium_service']['self_rating_3']) ? $ppr['premium_service']['self_rating_3'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['premium_service']['superios_rating_3']) ? $ppr['premium_service']['superios_rating_3'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['premium_service']['wtd_score_3']) ? $ppr['premium_service']['wtd_score_3'] : ""}}</td>
                                </tr>

                                <tr>
                                    <td colspan="6" class="text-center">&nbsp;</td>
                                </tr>

                                {{-- 6 --}}
                                <tr>
                                    <td rowspan="3" class="text-center text-dark">6. INNOVATION - Ability to find a better way to do things better </td>
                                    <td class="text-center text-dark">Adjusts (adapt) thinking and behavior to be in step with new thrusts or changing priorities/ developments within the organization and the external environment with openness, acceptance (e.g. in assignments/ approaches even those not related to one's job), and recommendations for structural or operational changes.</td>
                                    <td class="text-center text-dark">{{isset($ppr['innovation']['self_rating_1']) ? $ppr['innovation']['self_rating_1'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['innovation']['self_rating_1']) ? $ppr['innovation']['superios_rating_1'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['innovation']['self_rating_1']) ? $ppr['innovation']['wtd_score_1'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td class="text-center text-dark">Acquires/ generate/ develop, introduce/ contribute, and implement new and useful work methods, ideas, approaches, and information for products/ technologies, to solve problems or improve efficiency and effectiveness on the job/ organizational activities and services.</td>
                                    <td class="text-center text-dark">{{isset($ppr['innovation']['self_rating_2']) ? $ppr['innovation']['self_rating_2'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['innovation']['superios_rating_2']) ? $ppr['innovation']['superios_rating_2'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['innovation']['wtd_score_2']) ? $ppr['innovation']['wtd_score_2'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td class="text-center text-dark">Acts as a change agent by promoting and embracing change in existing practices (i.e. challenge status quo, streamlining processes) in appropriate ways, across the entire organization.</td>
                                    <td class="text-center text-dark">{{isset($ppr['innovation']['self_rating_3']) ? $ppr['innovation']['self_rating_3'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['innovation']['superios_rating_3']) ? $ppr['innovation']['superios_rating_3'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['innovation']['wtd_score_3']) ? $ppr['innovation']['wtd_score_3'] : ""}}</td>
                                </tr>

                                <tr>
                                    <td colspan="6" class="text-center">&nbsp;</td>
                                </tr>


                                {{-- 7 --}}
                                <tr>
                                    <td rowspan="3" class="text-center text-dark">7. SYNERGY- Ability to work together/ with others for bigger results</td>
                                    <td class="text-center text-dark">Places higher priority on organization's goals than on one's own goals; anticipate the effects of one's own/ area's actions and decisions on the co-workers and partners to meet both areas' needs can be met.</td>
                                    <td class="text-center text-dark">{{isset($ppr['synergy']['self_rating_1']) ? $ppr['synergy']['self_rating_1'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['synergy']['self_rating_1']) ? $ppr['synergy']['superios_rating_1'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['synergy']['self_rating_1']) ? $ppr['synergy']['wtd_score_1'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td class="text-center text-dark">Instills mutual trust and confidence with/ among groups and individuals in the achievement of organizational shared goals, from the setting of meaningful and specific team performance goals, in the determination of courses of action, facilitation of agreements, and giving of mutual support.</td>
                                    <td class="text-center text-dark">{{isset($ppr['synergy']['self_rating_2']) ? $ppr['synergy']['self_rating_2'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['synergy']['superios_rating_2']) ? $ppr['synergy']['superios_rating_2'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['synergy']['wtd_score_2']) ? $ppr['synergy']['wtd_score_2'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td class="text-center text-dark">Acts as a role model in motivating others, fostering and maintaining inclusive (respecting and welcoming differences and diversity) and positive work environment to achieve the organization's goals/ targets.</td>
                                    <td class="text-center text-dark">{{isset($ppr['synergy']['self_rating_3']) ? $ppr['synergy']['self_rating_3'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['synergy']['superios_rating_3']) ? $ppr['synergy']['superios_rating_3'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['synergy']['wtd_score_3']) ? $ppr['synergy']['wtd_score_3'] : ""}}</td>
                                </tr>

                                <tr>
                                    <td colspan="6" class="text-center">&nbsp;</td>
                                </tr>

                                {{-- 8 --}}
                                <tr>
                                    <td rowspan="3" class="text-center text-dark">8. RESPONSIBILITY  - Ability to be grateful and to take responsibility and accountability for every task and resource entrusted to one's care</td>
                                    <td class="text-center text-dark">Practice habits that keeps the work place clean, safe, and secure; preventing accidents, losses, or damages of any kind.</td>
                                    <td class="text-center text-dark">{{isset($ppr['stewardship']['self_rating_1']) ? $ppr['stewardship']['self_rating_1'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['stewardship']['self_rating_1']) ? $ppr['stewardship']['superios_rating_1'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['stewardship']['self_rating_1']) ? $ppr['stewardship']['wtd_score_1'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td class="text-center text-dark">Exercise control in the use of company benefits, property, resources, supplies, materials, power, etc.; prevent unnecessary waste/ loss, within the large context of the organization.</td>
                                    <td class="text-center text-dark">{{isset($ppr['stewardship']['self_rating_2']) ? $ppr['stewardship']['self_rating_2'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['stewardship']['superios_rating_2']) ? $ppr['stewardship']['superios_rating_2'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['stewardship']['wtd_score_2']) ? $ppr['stewardship']['wtd_score_2'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td class="text-center text-dark">Seriously perform the roles as entrusted and accountable custodian of Company properties, brand/ reputation, and resources</td>
                                    <td class="text-center text-dark">{{isset($ppr['stewardship']['self_rating_3']) ? $ppr['stewardship']['self_rating_3'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['stewardship']['superios_rating_3']) ? $ppr['stewardship']['superios_rating_3'] : ""}}</td>
                                    <td class="text-center text-dark">{{isset($ppr['stewardship']['wtd_score_3']) ? $ppr['stewardship']['wtd_score_3'] : ""}}</td>
                                </tr>

                                <tr>
                                    <td colspan="6" class="text-center">&nbsp;</td>
                                </tr>


                            </tbody>
                        <table>

                        {{-- Page 3 --}}
                        <table class="table-bordered mt-3" width="100%">
                            <tr>
                                <td colspan="4" align="center" style="background-color: rgb(240, 240, 240)"><strong>PERFORMANCE & DEVELOPMENT SUMMARY REPORT</strong> </td>
                            </tr>
                        </table>
                        <table class="table-bordered mt-1" width="100%">
                            <tr>
                                <td colspan="4" align="center" style="background-color: rgb(240, 240, 240)"><strong>SUMMARY OF RATINGS</strong></td>
                            </tr>
                            <tr>
                                <td align="center">RATING COMPONENTS</td>
                                <td align="center">WEIGHT</td>
                                <td align="center">ACTUAL SCORE</td>
                                <td align="center">DESCRIPTION</td>
                            </tr>
                            <tr>
                                <td align="center">BSC</td>
                                <td align="center">{{$ppr['bsc_weight']}}</td>
                                <td align="center">{{$ppr['bsc_actual_score']}}</td>
                                <td align="center"></td>
                            </tr>
                            <tr>
                                <td align="center">COMPETENCY</td>
                                <td align="center">{{$ppr['competency_weight']}}</td>
                                <td align="center">{{$ppr['competency_actual_score']}}</td>
                                <td align="center"></td>
                            </tr>
                            <tr>
                                <td align="center">TOTAL</td>
                                <td align="center">{{$ppr['total_weight']}}</td>
                                <td align="center">{{$ppr['total_actual_score']}}</td>
                                <td align="center"></td>
                            </tr>
                        </table>

                        {{-- <table class="table-bordered mt-1" width="100%">
                            <tr>
                                <td width="50%" align="center" style="background-color: rgb(240, 240, 240)"><strong>AREAS OF STRENGTH</strong></td>
                                <td width="50%" align="center" style="background-color: rgb(240, 240, 240)"><strong>DEVELOPMENTAL NEEDS</strong></td>
                            </tr>
                            <tr>
                                <td align="center">{{$ppr['areas_of_strength']}}</td>
                                <td align="center">{{$ppr['developmental_needs']}}</td>
                            </tr>
                        
                            <tr>
                                <td width="50%" align="center" style="background-color: rgb(240, 240, 240)"><strong>AREAS FOR ENHANCEMENT</strong></td>
                                <td width="50%" align="center" style="background-color: rgb(240, 240, 240)"><strong>TRAINING & DEVELOPMENTAL PLANS</strong></td>
                            </tr>
                            <tr>
                                <td align="center">{{$ppr['areas_for_enhancement']}}</td>
                                <td align="center">{{$ppr['training_and_development_plans']}}</td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center" style="background-color: rgb(240, 240, 240)"><strong>RATEE'S COMMENTS</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">{{$ppr['ratees_comments'] ? $ppr['ratees_comments'] : ""}}</td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center" style="background-color: rgb(240, 240, 240)"><strong>SUMMARY OF RATER'S COMMENTS/RECOMMENDATIONS</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">{{isset($ppr['summary_ratees_comments_recommendation']) ? $ppr['summary_ratees_comments_recommendation'] : ""}}</td>
                            </tr>
                        </table> --}}


                        

                        </div>
                    </div>

                

                            
                            
                            
                </div>

                       
                   
                    
        
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('empAllowScript')
	<script>

        function returnToDraft(id) {
			var element = document.getElementById('tdActionId'+id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "You want to return this PPR to Draft?",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willCancel) => {
					if (willCancel) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "/return-to-draft/" + id,
							method: "GET",
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("PPR has been returned to Draft!", {
									icon: "success",
								}).then(function() {
									location.reload();
								});
							}
						})

					}
				});
		}

        function resetApprover(id) {
			var element = document.getElementById('tdActionId'+id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "You want to reset this PPR Approver Level?",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willCancel) => {
					if (willCancel) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "/reset-ppr-approver/" + id,
							method: "GET",
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("PPR has approver has been reset!", {
									icon: "success",
								}).then(function() {
									location.reload();
								});
							}
						})

					}
				});
		}

        function deletePPR(id) {
			var element = document.getElementById('tdActionId'+id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "You want to delete this PPR?",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willCancel) => {
					if (willCancel) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "/delete-ppr/" + id,
							method: "GET",
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("PPR has been Deleted!", {
									icon: "success",
								}).then(function() {
									window.location.href = '/hr-performance-plan-review';
								});
							}
						})
					}
				});
		}

		function submitForReview(id) {
			swal({
					title: "Are you sure?",
					text: "You want to submit this PPR for review?",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDisable) => {
					if (willDisable) {
						document.getElementById("loader").style.display = "block";
						window.location.href="/submit-ppr-for-view/" + id;
					}
				});
		}

        function printDiv(divId) {
            var printContents = document.getElementById(divId).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }

        

	</script>
@endsection


<style>
    .text-align-center {
        text-align: center;
    }
    .responsive-input {
        width: 100%;
        max-width: 1000px; /* Set a maximum width to prevent the input from becoming too wide on larger screens */
        padding: 1px;
        box-sizing: border-box;
    }

</style>
