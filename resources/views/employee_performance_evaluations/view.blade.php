@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <form method='POST' action='{{url('update-performance-plan-review/'.$ppr['id'])}}' onsubmit="btnDtr.disabled = true; return true;"  enctype="multipart/form-data">
                    @csrf      
                    <div class="card-body">
                            <h4 class="card-title">VIEW PERFORMANCE PLAN REVIEW (PPR)</h4>
                                <div class="table-responsive">
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

                                    {{-- <table class="table-bordered mt-1" width="100%">
                                        <tr>
                                            <td>Group/Business Unit</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Department/unit</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Employee Name</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Position Title</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Employee Number</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Date Hired</td>
                                            <td></td>
                                        </tr>
                                    </table> --}}

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
                                            <td align="center">109% - 120%</td>
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
                                            <td align="center">UNSATIFACTORY</td>
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
                                                <td style="text-align: center; width:300px!important;"><input disabled type="text" name="financial_perspective[strat_1_objective_1]" value="{{$ppr['financial_perspective']['strat_1_objective_1']}}" id="" width="100%" class="responsive-input" placeholder="Input Here" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="financial_perspective[strat_1_metric_1]" value="{{$ppr['financial_perspective']['strat_1_metric_1']}}" placeholder="Metric" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="financial_perspective[strat_1_target_1]" value="{{$ppr['financial_perspective']['strat_1_target_1']}}" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="financial_perspective[strat_1_target_start_completion_1]" value="{{$ppr['financial_perspective']['strat_1_target_start_completion_1']}}" placeholder="Start" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="financial_perspective[strat_1_target_end_completion_1]" value="{{$ppr['financial_perspective']['strat_1_target_end_completion_1']}}" placeholder="End" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="financial_perspective[strat_1_weight_1]" value="{{ isset($ppr['financial_perspective']['strat_1_weight_1']) ? $ppr['financial_perspective']['strat_1_weight_1'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="5" name="financial_perspective[strat_1_review_actual_1]" value="{{ isset($ppr['financial_perspective']['strat_1_review_actual_1']) ? $ppr['financial_perspective']['strat_1_review_actual_1'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="financial_perspective[strat_1_remarks_1]" value="{{ isset($ppr['financial_perspective']['strat_1_remarks_1']) ? $ppr['financial_perspective']['strat_1_remarks_1'] : ""}}" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 2</td>
                                                <td style="text-align: center; width:300px!important;"><input disabled type="text" name="financial_perspective[strat_2_objective_1]" value="{{$ppr['financial_perspective']['strat_2_objective_1']}}" id="" width="100%" class="responsive-input" placeholder="Input Here" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="financial_perspective[strat_2_metric_1]" value="{{$ppr['financial_perspective']['strat_2_metric_1']}}" placeholder="Metric" required ></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="financial_perspective[strat_2_target_1]" value="{{$ppr['financial_perspective']['strat_2_target_1']}}" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="financial_perspective[strat_2_target_start_completion_1]" value="{{$ppr['financial_perspective']['strat_2_target_start_completion_1']}}" placeholder="Start" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="financial_perspective[strat_2_target_end_completion_1]" value="{{$ppr['financial_perspective']['strat_2_target_end_completion_1']}}" placeholder="End" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="financial_perspective[strat_2_weight_1]" value="{{ isset($ppr['financial_perspective']['strat_2_weight_1']) ? $ppr['financial_perspective']['strat_2_weight_1'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="5" name="financial_perspective[strat_2_review_actual_1]" value="{{ isset($ppr['financial_perspective']['strat_2_review_actual_1']) ? $ppr['financial_perspective']['strat_2_review_actual_1'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="financial_perspective[strat_2_remarks_1]" value="{{ isset($ppr['financial_perspective']['strat_2_remarks_1']) ? $ppr['financial_perspective']['strat_2_remarks_1'] : ""}}" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 3</td>
                                                <td style="text-align: center; width:300px!important;"><input disabled type="text" name="financial_perspective[strat_3_objective_1]" value="{{$ppr['financial_perspective']['strat_3_objective_1']}}" id="" width="100%" class="responsive-input" placeholder="Input Here" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="financial_perspective[strat_3_metric_1]" value="{{$ppr['financial_perspective']['strat_3_metric_1']}}" placeholder="Metric" required ></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="financial_perspective[strat_3_target_1]" value="{{$ppr['financial_perspective']['strat_3_target_1']}}" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" width="100%" name="financial_perspective[strat_3_target_start_completion_1]" value="{{$ppr['financial_perspective']['strat_3_target_start_completion_1']}}" placeholder="Start" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" width="100%" name="financial_perspective[strat_3_target_end_completion_1]" value="{{$ppr['financial_perspective']['strat_3_target_end_completion_1']}}" placeholder="End" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="financial_perspective[strat_3_weight_1]" value="{{ isset($ppr['financial_perspective']['strat_3_weight_1']) ? $ppr['financial_perspective']['strat_3_weight_1'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="5" name="financial_perspective[strat_3_review_actual_1]" value="{{ isset($ppr['financial_perspective']['strat_3_review_actual_1']) ? $ppr['financial_perspective']['strat_3_review_actual_1'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="financial_perspective[strat_3_remarks_1]" value="{{ isset($ppr['financial_perspective']['strat_3_remarks_1']) ? $ppr['financial_perspective']['strat_3_remarks_1'] : ""}}" id="" width="100%"  class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-center">Objective 2. Profit</td>
                                                <td colspan="7"></td>
                                            </tr>
                                            <tr>
                                                <td style="width:200px!important;">Strat 1</td>
                                                <td style="text-align: center; width:300px!important;"><input disabled type="text" name="financial_perspective[strat_1_objective_2]" value="{{$ppr['financial_perspective']['strat_1_objective_2']}}" id="" width="100%" class="responsive-input" placeholder="Input Here" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="financial_perspective[strat_1_metric_2]" value="{{$ppr['financial_perspective']['strat_1_metric_2']}}" placeholder="Metric" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="financial_perspective[strat_1_target_2]" value="{{$ppr['financial_perspective']['strat_1_target_2']}}" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="financial_perspective[strat_1_target_start_completion_2]" value="{{$ppr['financial_perspective']['strat_1_target_start_completion_2']}}" placeholder="Start" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="financial_perspective[strat_1_target_end_completion_2]" value="{{$ppr['financial_perspective']['strat_1_target_end_completion_2']}}" placeholder="End" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="financial_perspective[strat_1_weight_2]" value="{{ isset($ppr['financial_perspective']['strat_1_weight_2']) ? $ppr['financial_perspective']['strat_1_weight_2'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="5" name="financial_perspective[strat_1_review_actual_2]" value="{{ isset($ppr['financial_perspective']['strat_1_review_actual_2']) ? $ppr['financial_perspective']['strat_1_review_actual_2'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="financial_perspective[strat_1_remarks_2]" value="{{ isset($ppr['financial_perspective']['strat_1_remarks_2']) ? $ppr['financial_perspective']['strat_1_remarks_2'] : ""}}" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 2</td>
                                                <td style="text-align: center; width:300px!important;"><input disabled type="text" name="financial_perspective[strat_2_objective_2]" value="{{$ppr['financial_perspective']['strat_2_objective_2']}}" id="" width="100%" class="responsive-input" placeholder="Input Here" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="financial_perspective[strat_2_metric_2]" value="{{$ppr['financial_perspective']['strat_2_metric_2']}}" placeholder="Metric" required ></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="financial_perspective[strat_2_target_2]" value="{{$ppr['financial_perspective']['strat_2_target_2']}}" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="financial_perspective[strat_2_target_start_completion_2]" value="{{$ppr['financial_perspective']['strat_2_target_start_completion_2']}}" placeholder="Start" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="financial_perspective[strat_2_target_end_completion_2]" value="{{$ppr['financial_perspective']['strat_2_target_end_completion_2']}}" placeholder="End" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="financial_perspective[strat_2_weight_2]" value="{{ isset($ppr['financial_perspective']['strat_2_weight_2']) ? $ppr['financial_perspective']['strat_2_weight_2'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="5" name="financial_perspective[strat_2_review_actual_2]" value="{{ isset($ppr['financial_perspective']['strat_2_review_actual_2']) ? $ppr['financial_perspective']['strat_2_review_actual_2'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="financial_perspective[strat_2_remarks_2]" value="{{ isset($ppr['financial_perspective']['strat_2_remarks_2']) ? $ppr['financial_perspective']['strat_2_remarks_2'] : ""}}" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 3</td>
                                                <td style="text-align: center; width:300px!important;"><input disabled type="text" name="financial_perspective[strat_3_objective_2]" value="{{$ppr['financial_perspective']['strat_3_objective_2']}}" id="" width="100%" class="responsive-input" placeholder="Input Here" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="financial_perspective[strat_3_metric_2]" value="{{$ppr['financial_perspective']['strat_3_metric_2']}}" placeholder="Metric" required ></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="financial_perspective[strat_3_target_2]" value="{{$ppr['financial_perspective']['strat_3_target_2']}}" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" width="100%" name="financial_perspective[strat_3_target_start_completion_2]" value="{{$ppr['financial_perspective']['strat_3_target_start_completion_2']}}" placeholder="Start" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" width="100%" name="financial_perspective[strat_3_target_end_completion_2]" value="{{$ppr['financial_perspective']['strat_3_target_end_completion_2']}}" placeholder="End" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="financial_perspective[strat_3_weight_2]" value="{{ isset($ppr['financial_perspective']['strat_3_weight_2']) ? $ppr['financial_perspective']['strat_3_weight_2'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="5" name="financial_perspective[strat_3_review_actual_2]" value="{{ isset($ppr['financial_perspective']['strat_3_review_actual_2']) ? $ppr['financial_perspective']['strat_3_review_actual_2'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="financial_perspective[strat_3_remarks_2]" value="{{ isset($ppr['financial_perspective']['strat_3_remarks_2']) ? $ppr['financial_perspective']['strat_3_remarks_2'] : ""}}" id="" width="100%"  class="responsive-input" disabled></td>
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
                                                <td style="text-align: center; width:300px!important;"><input disabled type="text" name="customer_focus[strat_1_objective_1]" value="{{$ppr['customer_focus']['strat_1_objective_1']}}" id="" width="100%" class="responsive-input" placeholder="Input Here" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="customer_focus[strat_1_metric_1]" value="{{$ppr['customer_focus']['strat_1_metric_1']}}" placeholder="Metric" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="customer_focus[strat_1_target_1]" value="{{$ppr['customer_focus']['strat_1_target_1']}}" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="customer_focus[strat_1_target_start_completion_1]" value="{{$ppr['customer_focus']['strat_1_target_start_completion_1']}}" placeholder="Start" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="customer_focus[strat_1_target_end_completion_1]" value="{{$ppr['customer_focus']['strat_1_target_end_completion_1']}}" placeholder="End" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="customer_focus[strat_1_weight_1]" value="{{ isset($ppr['customer_focus']['strat_1_weight_1']) ? $ppr['customer_focus']['strat_1_weight_1'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="5" name="customer_focus[strat_1_review_actual_1]" value="{{ isset($ppr['customer_focus']['strat_1_review_actual_1']) ? $ppr['customer_focus']['strat_1_review_actual_1'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="customer_focus[strat_1_remarks_1]" value="{{ isset($ppr['customer_focus']['strat_1_remarks_1']) ? $ppr['customer_focus']['strat_1_remarks_1'] : ""}}" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 2</td>
                                                <td style="text-align: center; width:300px!important;"><input disabled type="text" name="customer_focus[strat_2_objective_1]" value="{{$ppr['customer_focus']['strat_2_objective_1']}}" id="" width="100%" class="responsive-input" placeholder="Input Here" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="customer_focus[strat_2_metric_1]" value="{{$ppr['customer_focus']['strat_2_metric_1']}}" placeholder="Metric" required ></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="customer_focus[strat_2_target_1]" value="{{$ppr['customer_focus']['strat_2_target_1']}}" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="customer_focus[strat_2_target_start_completion_1]" value="{{$ppr['customer_focus']['strat_2_target_start_completion_1']}}" placeholder="Start" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="customer_focus[strat_2_target_end_completion_1]" value="{{$ppr['customer_focus']['strat_2_target_end_completion_1']}}" placeholder="End" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="customer_focus[strat_2_weight_1]" value="{{ isset($ppr['customer_focus']['strat_2_weight_1']) ? $ppr['customer_focus']['strat_2_weight_1'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="5" name="customer_focus[strat_2_review_actual_1]" value="{{ isset($ppr['customer_focus']['strat_2_review_actual_1']) ? $ppr['customer_focus']['strat_2_review_actual_1'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="customer_focus[strat_2_remarks_1]" value="{{ isset($ppr['customer_focus']['strat_2_remarks_1']) ? $ppr['customer_focus']['strat_2_remarks_1'] : ""}}" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 3</td>
                                                <td style="text-align: center; width:300px!important;"><input disabled type="text" name="customer_focus[strat_3_objective_1]" value="{{$ppr['customer_focus']['strat_3_objective_1']}}" id="" width="100%" class="responsive-input" placeholder="Input Here" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="customer_focus[strat_3_metric_1]" value="{{$ppr['customer_focus']['strat_3_metric_1']}}" placeholder="Metric" required ></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="customer_focus[strat_3_target_1]" value="{{$ppr['customer_focus']['strat_3_target_1']}}" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" width="100%" name="customer_focus[strat_3_target_start_completion_1]" value="{{$ppr['customer_focus']['strat_3_target_start_completion_1']}}" placeholder="Start" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" width="100%" name="customer_focus[strat_3_target_end_completion_1]" value="{{$ppr['customer_focus']['strat_3_target_end_completion_1']}}" placeholder="End" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="customer_focus[strat_3_weight_1]" value="{{ isset($ppr['customer_focus']['strat_3_weight_1']) ? $ppr['customer_focus']['strat_3_weight_1'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="5" name="customer_focus[strat_3_review_actual_1]" value="{{ isset($ppr['customer_focus']['strat_3_review_actual_1']) ? $ppr['customer_focus']['strat_3_review_actual_1'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="customer_focus[strat_3_remarks_1]" value="{{ isset($ppr['customer_focus']['strat_3_remarks_1']) ? $ppr['customer_focus']['strat_3_remarks_1'] : ""}}" id="" width="100%"  class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-center">Objective 2</td>
                                                <td colspan="7"></td>
                                            </tr>
                                            <tr>
                                                <td style="width:200px!important;">Strat 1</td>
                                                <td style="text-align: center; width:300px!important;"><input disabled type="text" name="customer_focus[strat_1_objective_2]" value="{{$ppr['customer_focus']['strat_1_objective_2']}}" id="" width="100%" class="responsive-input" placeholder="Input Here" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="customer_focus[strat_1_metric_2]" value="{{$ppr['customer_focus']['strat_1_metric_2']}}" placeholder="Metric" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="customer_focus[strat_1_target_2]" value="{{$ppr['customer_focus']['strat_1_target_2']}}" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="customer_focus[strat_1_target_start_completion_2]" value="{{$ppr['customer_focus']['strat_1_target_start_completion_2']}}" placeholder="Start" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="customer_focus[strat_1_target_end_completion_2]" value="{{$ppr['customer_focus']['strat_1_target_end_completion_2']}}" placeholder="End" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="customer_focus[strat_1_weight_2]" value="{{ isset($ppr['customer_focus']['strat_1_weight_2']) ? $ppr['customer_focus']['strat_1_weight_2'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="5" name="customer_focus[strat_1_review_actual_2]" value="{{ isset($ppr['customer_focus']['strat_1_review_actual_2']) ? $ppr['customer_focus']['strat_1_review_actual_2'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="customer_focus[strat_1_remarks_2]" value="{{ isset($ppr['customer_focus']['strat_1_remarks_2']) ? $ppr['customer_focus']['strat_1_remarks_2'] : ""}}" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 2</td>
                                                <td style="text-align: center; width:300px!important;"><input disabled type="text" name="customer_focus[strat_2_objective_2]" value="{{$ppr['customer_focus']['strat_2_objective_2']}}" id="" width="100%" class="responsive-input" placeholder="Input Here" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="customer_focus[strat_2_metric_2]" value="{{$ppr['customer_focus']['strat_2_metric_2']}}" placeholder="Metric" required ></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="customer_focus[strat_2_target_2]" value="{{$ppr['customer_focus']['strat_2_target_2']}}" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="customer_focus[strat_2_target_start_completion_2]" value="{{$ppr['customer_focus']['strat_2_target_start_completion_2']}}" placeholder="Start" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="customer_focus[strat_2_target_end_completion_2]" value="{{$ppr['customer_focus']['strat_2_target_end_completion_2']}}" placeholder="End" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="customer_focus[strat_2_weight_2]" value="{{ isset($ppr['customer_focus']['strat_2_weight_2']) ? $ppr['customer_focus']['strat_2_weight_2'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="5" name="customer_focus[strat_2_review_actual_2]" value="{{ isset($ppr['customer_focus']['strat_2_review_actual_2']) ? $ppr['customer_focus']['strat_2_review_actual_2'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="customer_focus[strat_2_remarks_2]" value="{{ isset($ppr['customer_focus']['strat_2_remarks_2']) ? $ppr['customer_focus']['strat_2_remarks_2'] : ""}}" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 3</td>
                                                <td style="text-align: center; width:300px!important;"><input disabled type="text" name="customer_focus[strat_3_objective_2]" value="{{$ppr['customer_focus']['strat_3_objective_2']}}" id="" width="100%" class="responsive-input" placeholder="Input Here" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="customer_focus[strat_3_metric_2]" value="{{$ppr['customer_focus']['strat_3_metric_2']}}" placeholder="Metric" required ></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="customer_focus[strat_3_target_2]" value="{{$ppr['customer_focus']['strat_3_target_2']}}" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" width="100%" name="customer_focus[strat_3_target_start_completion_2]" value="{{$ppr['customer_focus']['strat_3_target_start_completion_2']}}" placeholder="Start" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" width="100%" name="customer_focus[strat_3_target_end_completion_2]" value="{{$ppr['customer_focus']['strat_3_target_end_completion_2']}}" placeholder="End" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="customer_focus[strat_3_weight_2]" value="{{ isset($ppr['customer_focus']['strat_3_weight_2']) ? $ppr['customer_focus']['strat_3_weight_2'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="5" name="customer_focus[strat_3_review_actual_2]" value="{{ isset($ppr['customer_focus']['strat_3_review_actual_2']) ? $ppr['customer_focus']['strat_3_review_actual_2'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="customer_focus[strat_3_remarks_2]" value="{{ isset($ppr['customer_focus']['strat_3_remarks_2']) ? $ppr['customer_focus']['strat_3_remarks_2'] : ""}}" id="" width="100%"  class="responsive-input" disabled></td>
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
                                                <td style="text-align: center; width:300px!important;"><input disabled type="text" name="operation_efficiency[strat_1_objective_1]" value="{{$ppr['operation_efficiency']['strat_1_objective_1']}}" id="" width="100%" class="responsive-input" placeholder="Input Here" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="operation_efficiency[strat_1_metric_1]" value="{{$ppr['operation_efficiency']['strat_1_metric_1']}}" placeholder="Metric" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="operation_efficiency[strat_1_target_1]" value="{{$ppr['operation_efficiency']['strat_1_target_1']}}" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="operation_efficiency[strat_1_target_start_completion_1]" value="{{$ppr['operation_efficiency']['strat_1_target_start_completion_1']}}" placeholder="Start" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="operation_efficiency[strat_1_target_end_completion_1]" value="{{$ppr['operation_efficiency']['strat_1_target_end_completion_1']}}" placeholder="End" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="operation_efficiency[strat_1_weight_1]" value="{{ isset($ppr['operation_efficiency']['strat_1_weight_1']) ? $ppr['operation_efficiency']['strat_1_weight_1'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="5" name="operation_efficiency[strat_1_review_actual_1]" value="{{ isset($ppr['operation_efficiency']['strat_1_review_actual_1']) ? $ppr['operation_efficiency']['strat_1_review_actual_1'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="operation_efficiency[strat_1_remarks_1]" value="{{ isset($ppr['operation_efficiency']['strat_1_remarks_1']) ? $ppr['operation_efficiency']['strat_1_remarks_1'] : ""}}" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 2</td>
                                                <td style="text-align: center; width:300px!important;"><input disabled type="text" name="operation_efficiency[strat_2_objective_1]" value="{{$ppr['operation_efficiency']['strat_2_objective_1']}}" id="" width="100%" class="responsive-input" placeholder="Input Here" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="operation_efficiency[strat_2_metric_1]" value="{{$ppr['operation_efficiency']['strat_2_metric_1']}}" placeholder="Metric" required ></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="operation_efficiency[strat_2_target_1]" value="{{$ppr['operation_efficiency']['strat_2_target_1']}}" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="operation_efficiency[strat_2_target_start_completion_1]" value="{{$ppr['operation_efficiency']['strat_2_target_start_completion_1']}}" placeholder="Start" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="operation_efficiency[strat_2_target_end_completion_1]" value="{{$ppr['operation_efficiency']['strat_2_target_end_completion_1']}}" placeholder="End" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="operation_efficiency[strat_2_weight_1]" value="{{ isset($ppr['operation_efficiency']['strat_2_weight_1']) ? $ppr['operation_efficiency']['strat_2_weight_1'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="5" name="operation_efficiency[strat_2_review_actual_1]" value="{{ isset($ppr['operation_efficiency']['strat_2_review_actual_1']) ? $ppr['operation_efficiency']['strat_2_review_actual_1'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="operation_efficiency[strat_2_remarks_1]" value="{{ isset($ppr['operation_efficiency']['strat_2_remarks_1']) ? $ppr['operation_efficiency']['strat_2_remarks_1'] : ""}}" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 3</td>
                                                <td style="text-align: center; width:300px!important;"><input disabled type="text" name="operation_efficiency[strat_3_objective_1]" value="{{$ppr['operation_efficiency']['strat_3_objective_1']}}" id="" width="100%" class="responsive-input" placeholder="Input Here" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="operation_efficiency[strat_3_metric_1]" value="{{$ppr['operation_efficiency']['strat_3_metric_1']}}" placeholder="Metric" required ></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="operation_efficiency[strat_3_target_1]" value="{{$ppr['operation_efficiency']['strat_3_target_1']}}" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" width="100%" name="operation_efficiency[strat_3_target_start_completion_1]" value="{{$ppr['operation_efficiency']['strat_3_target_start_completion_1']}}" placeholder="Start" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" width="100%" name="operation_efficiency[strat_3_target_end_completion_1]" value="{{$ppr['operation_efficiency']['strat_3_target_end_completion_1']}}" placeholder="End" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="operation_efficiency[strat_3_weight_1]" value="{{ isset($ppr['operation_efficiency']['strat_3_weight_1']) ? $ppr['operation_efficiency']['strat_3_weight_1'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="5" name="operation_efficiency[strat_3_review_actual_1]" value="{{ isset($ppr['operation_efficiency']['strat_3_review_actual_1']) ? $ppr['operation_efficiency']['strat_3_review_actual_1'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="operation_efficiency[strat_3_remarks_1]" value="{{ isset($ppr['operation_efficiency']['strat_3_remarks_1']) ? $ppr['operation_efficiency']['strat_3_remarks_1'] : ""}}" id="" width="100%"  class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-center">Objective 2</td>
                                                <td colspan="7"></td>
                                            </tr>
                                            <tr>
                                                <td style="width:200px!important;">Strat 1</td>
                                                <td style="text-align: center; width:300px!important;"><input disabled type="text" name="operation_efficiency[strat_1_objective_2]" value="{{$ppr['operation_efficiency']['strat_1_objective_2']}}" id="" width="100%" class="responsive-input" placeholder="Input Here" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="operation_efficiency[strat_1_metric_2]" value="{{$ppr['operation_efficiency']['strat_1_metric_2']}}" placeholder="Metric" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="operation_efficiency[strat_1_target_2]" value="{{$ppr['operation_efficiency']['strat_1_target_2']}}" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="operation_efficiency[strat_1_target_start_completion_2]" value="{{$ppr['operation_efficiency']['strat_1_target_start_completion_2']}}" placeholder="Start" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="operation_efficiency[strat_1_target_end_completion_2]" value="{{$ppr['operation_efficiency']['strat_1_target_end_completion_2']}}" placeholder="End" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="operation_efficiency[strat_1_weight_2]" value="{{ isset($ppr['operation_efficiency']['strat_1_weight_2']) ? $ppr['operation_efficiency']['strat_1_weight_2'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="5" name="operation_efficiency[strat_1_review_actual_2]" value="{{ isset($ppr['operation_efficiency']['strat_1_review_actual_2']) ? $ppr['operation_efficiency']['strat_1_review_actual_2'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="operation_efficiency[strat_1_remarks_2]" value="{{ isset($ppr['operation_efficiency']['strat_1_remarks_2']) ? $ppr['operation_efficiency']['strat_1_remarks_2'] : ""}}" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 2</td>
                                                <td style="text-align: center; width:300px!important;"><input disabled type="text" name="operation_efficiency[strat_2_objective_2]" value="{{$ppr['operation_efficiency']['strat_2_objective_2']}}" id="" width="100%" class="responsive-input" placeholder="Input Here" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="operation_efficiency[strat_2_metric_2]" value="{{$ppr['operation_efficiency']['strat_2_metric_2']}}" placeholder="Metric" required ></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="operation_efficiency[strat_2_target_2]" value="{{$ppr['operation_efficiency']['strat_2_target_2']}}" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="operation_efficiency[strat_2_target_start_completion_2]" value="{{$ppr['operation_efficiency']['strat_2_target_start_completion_2']}}" placeholder="Start" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="operation_efficiency[strat_2_target_end_completion_2]" value="{{$ppr['operation_efficiency']['strat_2_target_end_completion_2']}}" placeholder="End" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="operation_efficiency[strat_2_weight_2]" value="{{ isset($ppr['operation_efficiency']['strat_2_weight_2']) ? $ppr['operation_efficiency']['strat_2_weight_2'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="5" name="operation_efficiency[strat_2_review_actual_2]" value="{{ isset($ppr['operation_efficiency']['strat_2_review_actual_2']) ? $ppr['operation_efficiency']['strat_2_review_actual_2'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="operation_efficiency[strat_2_remarks_2]" value="{{ isset($ppr['operation_efficiency']['strat_2_remarks_2']) ? $ppr['operation_efficiency']['strat_2_remarks_2'] : ""}}" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 3</td>
                                                <td style="text-align: center; width:300px!important;"><input disabled type="text" name="operation_efficiency[strat_3_objective_2]" value="{{$ppr['operation_efficiency']['strat_3_objective_2']}}" id="" width="100%" class="responsive-input" placeholder="Input Here" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="operation_efficiency[strat_3_metric_2]" value="{{$ppr['operation_efficiency']['strat_3_metric_2']}}" placeholder="Metric" required ></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="operation_efficiency[strat_3_target_2]" value="{{$ppr['operation_efficiency']['strat_3_target_2']}}" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" width="100%" name="operation_efficiency[strat_3_target_start_completion_2]" value="{{$ppr['operation_efficiency']['strat_3_target_start_completion_2']}}" placeholder="Start" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" width="100%" name="operation_efficiency[strat_3_target_end_completion_2]" value="{{$ppr['operation_efficiency']['strat_3_target_end_completion_2']}}" placeholder="End" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="operation_efficiency[strat_3_weight_2]" value="{{ isset($ppr['operation_efficiency']['strat_3_weight_2']) ? $ppr['operation_efficiency']['strat_3_weight_2'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="5" name="operation_efficiency[strat_3_review_actual_2]" value="{{ isset($ppr['operation_efficiency']['strat_3_review_actual_2']) ? $ppr['operation_efficiency']['strat_3_review_actual_2'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="operation_efficiency[strat_3_remarks_2]" value="{{ isset($ppr['operation_efficiency']['strat_3_remarks_2']) ? $ppr['operation_efficiency']['strat_3_remarks_2'] : ""}}" id="" width="100%"  class="responsive-input" disabled></td>
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
                                                <td style="text-align: center; width:300px!important;"><input disabled type="text" name="people[strat_1_objective_1]" value="{{$ppr['people']['strat_1_objective_1']}}" id="" width="100%" class="responsive-input" placeholder="Input Here" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="people[strat_1_metric_1]" value="{{$ppr['people']['strat_1_metric_1']}}" placeholder="Metric" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="people[strat_1_target_1]" value="{{$ppr['people']['strat_1_target_1']}}" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="people[strat_1_target_start_completion_1]" value="{{$ppr['people']['strat_1_target_start_completion_1']}}" placeholder="Start" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="people[strat_1_target_end_completion_1]" value="{{$ppr['people']['strat_1_target_end_completion_1']}}" placeholder="End" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="people[strat_1_weight_1]" value="{{ isset($ppr['people']['strat_1_weight_1']) ? $ppr['people']['strat_1_weight_1'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="5" name="people[strat_1_review_actual_1]" value="{{ isset($ppr['people']['strat_1_review_actual_1']) ? $ppr['people']['strat_1_review_actual_1'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="people[strat_1_remarks_1]" value="{{ isset($ppr['people']['strat_1_remarks_1']) ? $ppr['people']['strat_1_remarks_1'] : ""}}" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 2</td>
                                                <td style="text-align: center; width:300px!important;"><input disabled type="text" name="people[strat_2_objective_1]" value="{{$ppr['people']['strat_2_objective_1']}}" id="" width="100%" class="responsive-input" placeholder="Input Here" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="people[strat_2_metric_1]" value="{{$ppr['people']['strat_2_metric_1']}}" placeholder="Metric" required ></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="people[strat_2_target_1]" value="{{$ppr['people']['strat_2_target_1']}}" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="people[strat_2_target_start_completion_1]" value="{{$ppr['people']['strat_2_target_start_completion_1']}}" placeholder="Start" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="people[strat_2_target_end_completion_1]" value="{{$ppr['people']['strat_2_target_end_completion_1']}}" placeholder="End" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="people[strat_2_weight_1]" value="{{ isset($ppr['people']['strat_2_weight_1']) ? $ppr['people']['strat_2_weight_1'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="5" name="people[strat_2_review_actual_1]" value="{{ isset($ppr['people']['strat_2_review_actual_1']) ? $ppr['people']['strat_2_review_actual_1'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="people[strat_2_remarks_1]" value="{{ isset($ppr['people']['strat_2_remarks_1']) ? $ppr['people']['strat_2_remarks_1'] : ""}}" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 3</td>
                                                <td style="text-align: center; width:300px!important;"><input disabled type="text" name="people[strat_3_objective_1]" value="{{$ppr['people']['strat_3_objective_1']}}" id="" width="100%" class="responsive-input" placeholder="Input Here" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="people[strat_3_metric_1]" value="{{$ppr['people']['strat_3_metric_1']}}" placeholder="Metric" required ></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="people[strat_3_target_1]" value="{{$ppr['people']['strat_3_target_1']}}" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" width="100%" name="people[strat_3_target_start_completion_1]" value="{{$ppr['people']['strat_3_target_start_completion_1']}}" placeholder="Start" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" width="100%" name="people[strat_3_target_end_completion_1]" value="{{$ppr['people']['strat_3_target_end_completion_1']}}" placeholder="End" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="people[strat_3_weight_1]" value="{{ isset($ppr['people']['strat_3_weight_1']) ? $ppr['people']['strat_3_weight_1'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="5" name="people[strat_3_review_actual_1]" value="{{ isset($ppr['people']['strat_3_review_actual_1']) ? $ppr['people']['strat_3_review_actual_1'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="people[strat_3_remarks_1]" value="{{ isset($ppr['people']['strat_3_remarks_1']) ? $ppr['people']['strat_3_remarks_1'] : ""}}" id="" width="100%"  class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-center">Objective 2</td>
                                                <td colspan="7"></td>
                                            </tr>
                                            <tr>
                                                <td style="width:200px!important;">Strat 1</td>
                                                <td style="text-align: center; width:300px!important;"><input disabled type="text" name="people[strat_1_objective_2]" value="{{$ppr['people']['strat_1_objective_2']}}" id="" width="100%" class="responsive-input" placeholder="Input Here" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="people[strat_1_metric_2]" value="{{$ppr['people']['strat_1_metric_2']}}" placeholder="Metric" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="people[strat_1_target_2]" value="{{$ppr['people']['strat_1_target_2']}}" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="people[strat_1_target_start_completion_2]" value="{{$ppr['people']['strat_1_target_start_completion_2']}}" placeholder="Start" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="people[strat_1_target_end_completion_2]" value="{{$ppr['people']['strat_1_target_end_completion_2']}}" placeholder="End" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="people[strat_1_weight_2]" value="{{ isset($ppr['people']['strat_1_weight_2']) ? $ppr['people']['strat_1_weight_2'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="5" name="people[strat_1_review_actual_2]" value="{{ isset($ppr['people']['strat_1_review_actual_2']) ? $ppr['people']['strat_1_review_actual_2'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="people[strat_1_remarks_2]" value="{{ isset($ppr['people']['strat_1_remarks_2']) ? $ppr['people']['strat_1_remarks_2'] : ""}}" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 2</td>
                                                <td style="text-align: center; width:300px!important;"><input disabled type="text" name="people[strat_2_objective_2]" value="{{$ppr['people']['strat_2_objective_2']}}" id="" width="100%" class="responsive-input" placeholder="Input Here" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="people[strat_2_metric_2]" value="{{$ppr['people']['strat_2_metric_2']}}" placeholder="Metric" required ></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="people[strat_2_target_2]" value="{{$ppr['people']['strat_2_target_2']}}" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="people[strat_2_target_start_completion_2]" value="{{$ppr['people']['strat_2_target_start_completion_2']}}" placeholder="Start" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="people[strat_2_target_end_completion_2]" value="{{$ppr['people']['strat_2_target_end_completion_2']}}" placeholder="End" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="people[strat_2_weight_2]" value="{{ isset($ppr['people']['strat_2_weight_2']) ? $ppr['people']['strat_2_weight_2'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="5" name="people[strat_2_review_actual_2]" value="{{ isset($ppr['people']['strat_2_review_actual_2']) ? $ppr['people']['strat_2_review_actual_2'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="people[strat_2_remarks_2]" value="{{ isset($ppr['people']['strat_2_remarks_2']) ? $ppr['people']['strat_2_remarks_2'] : ""}}" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 3</td>
                                                <td style="text-align: center; width:300px!important;"><input disabled type="text" name="people[strat_3_objective_2]" value="{{$ppr['people']['strat_3_objective_2']}}" id="" width="100%" class="responsive-input" placeholder="Input Here" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="people[strat_3_metric_2]" value="{{$ppr['people']['strat_3_metric_2']}}" placeholder="Metric" required ></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="people[strat_3_target_2]" value="{{$ppr['people']['strat_3_target_2']}}" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" width="100%" name="people[strat_3_target_start_completion_2]" value="{{$ppr['people']['strat_3_target_start_completion_2']}}" placeholder="Start" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" width="100%" name="people[strat_3_target_end_completion_2]" value="{{$ppr['people']['strat_3_target_end_completion_2']}}" placeholder="End" required></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="100" name="people[strat_3_weight_2]" value="{{ isset($ppr['people']['strat_3_weight_2']) ? $ppr['people']['strat_3_weight_2'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="number" min="1" max="5" name="people[strat_3_review_actual_2]" value="{{ isset($ppr['people']['strat_3_review_actual_2']) ? $ppr['people']['strat_3_review_actual_2'] : ""}}" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input disabled type="text" name="people[strat_3_remarks_2]" value="{{ isset($ppr['people']['strat_3_remarks_2']) ? $ppr['people']['strat_3_remarks_2'] : ""}}" id="" width="100%"  class="responsive-input" disabled></td>
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
                                                <th class="text-center" rowspan="2" style="background-color:rgb(240, 240, 240)">WEIGHTS</th>
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
                                                <td class="text-center text-dark"><input disabled type="number" name="integrity[weights_1]" value="{{$ppr['integrity']['weights_1']}}" min="1" max="100" required></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="integrity[self_rating_1]" value="{{isset($ppr['integrity']['self_rating_1']) ? $ppr['integrity']['self_rating_1'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="integrity[superios_rating_1]" value="{{isset($ppr['integrity']['self_rating_1']) ? $ppr['integrity']['superios_rating_1'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="integrity[wtd_score_1]" value="{{isset($ppr['integrity']['self_rating_1']) ? $ppr['integrity']['wtd_score_1'] : ""}}" min="1" max="5" disabled></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Treats people with dignity, respect, and fairness; gives proper credit to others; stands up for others who are deserving and their ideas even in the face of resistance or challenge to fosters high standards of values and ethics.</td>
                                                <td class="text-center text-dark"><input disabled type="number" name="integrity[weights_2]" value="{{$ppr['integrity']['weights_2']}}" min="1" max="100" required></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="integrity[self_rating_2]" value="{{isset($ppr['integrity']['self_rating_2']) ? $ppr['integrity']['self_rating_2'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="integrity[superios_rating_2]" value="{{isset($ppr['integrity']['superios_rating_2']) ? $ppr['integrity']['superios_rating_2'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="integrity[wtd_score_2]" value="{{isset($ppr['integrity']['wtd_score_2']) ? $ppr['integrity']['wtd_score_2'] : ""}}" min="1"  max="5" disabled></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Acts as a role model for working with others with sincerity, cheerfulness, and trust worthy traits in carrying out the job/ task with minimal to on supervision.</td>
                                                <td class="text-center text-dark"><input disabled type="number" name="integrity[weights_3]" value="{{$ppr['integrity']['weights_3']}}" min="1" max="100" required></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="integrity[self_rating_3]" value="{{isset($ppr['integrity']['self_rating_3']) ? $ppr['integrity']['self_rating_3'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="integrity[superios_rating_3]" value="{{isset($ppr['integrity']['superios_rating_3']) ? $ppr['integrity']['superios_rating_3'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="integrity[wtd_score_3]" value="{{isset($ppr['integrity']['wtd_score_3']) ? $ppr['integrity']['wtd_score_3'] : ""}}" min="1" max="5" disabled></td>
                                            </tr>

                                            <tr>
                                                <td colspan="6" class="text-center">&nbsp;</td>
                                            </tr>

                                            {{-- 2 --}}
                                            <tr>
                                                <td rowspan="3" class="text-center text-dark">2. COMMITMENT - Ability to never make excuses, only results</td>
                                                <td class="text-center text-dark">Defines personal purpose, meaning, and challenges, and proactively set plans and strategies to overcome obstacles and meet current and future needs and targets.</td>
                                                <td class="text-center text-dark"><input disabled type="number" name="commitment[weights_1]" value="{{$ppr['commitment']['weights_1']}}" min="1" max="100" required></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="commitment[self_rating_1]" value="{{isset($ppr['commitment']['self_rating_1']) ? $ppr['commitment']['self_rating_1'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="commitment[superios_rating_1]" value="{{isset($ppr['commitment']['self_rating_1']) ? $ppr['commitment']['superios_rating_1'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="commitment[wtd_score_1]" value="{{isset($ppr['commitment']['self_rating_1']) ? $ppr['commitment']['wtd_score_1'] : ""}}" min="1" max="5" disabled></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Diligently completes the assigned work efficiently, completely, accurately, and meets the standards of performance (e.g. KRA/ KPI, quality/ quantity of work, presence, timeliness) within an established time frame and within budget.</td>
                                                <td class="text-center text-dark"><input disabled type="number" name="commitment[weights_2]" value="{{$ppr['commitment']['weights_2']}}" min="1" max="100" required></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="commitment[self_rating_2]" value="{{isset($ppr['commitment']['self_rating_2']) ? $ppr['commitment']['self_rating_2'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="commitment[superios_rating_2]" value="{{isset($ppr['commitment']['superios_rating_2']) ? $ppr['commitment']['superios_rating_2'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="commitment[wtd_score_2]" value="{{isset($ppr['commitment']['wtd_score_2']) ? $ppr['commitment']['wtd_score_2'] : ""}}" min="1"  max="5" disabled></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Executes objectives, delivers/ exceed targets and sees tasks through to the end; while taking into consideration current responsibilities, work load, core values, and the trust, confidence and resources bestowed on him/ her, and the company-wide organizational goals.</td>
                                                <td class="text-center text-dark"><input disabled type="number" name="commitment[weights_3]" value="{{$ppr['commitment']['weights_3']}}" min="1" max="100" required></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="commitment[self_rating_3]" value="{{isset($ppr['commitment']['self_rating_3']) ? $ppr['commitment']['self_rating_3'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="commitment[superios_rating_3]" value="{{isset($ppr['commitment']['superios_rating_3']) ? $ppr['commitment']['superios_rating_3'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="commitment[wtd_score_3]" value="{{isset($ppr['commitment']['wtd_score_3']) ? $ppr['commitment']['wtd_score_3'] : ""}}" min="1" max="5" disabled></td>
                                            </tr>

                                            <tr>
                                                <td colspan="6" class="text-center">&nbsp;</td>
                                            </tr>

                                            {{-- 3 --}}
                                            <tr>
                                                <td rowspan="3" class="text-center text-dark">3. HUMILITY - Ability to be simple (no pretenses)</td>
                                                <td class="text-center text-dark">Recognizes personal strengths and gaps and seeks guidance or resources in laying out development and/or improvement plans.</td>
                                                <td class="text-center text-dark"><input disabled type="number" name="humility[weights_1]" value="{{$ppr['humility']['weights_1']}}" min="1" max="100" required></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="humility[self_rating_1]" value="{{isset($ppr['humility']['self_rating_1']) ? $ppr['humility']['self_rating_1'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="humility[superios_rating_1]" value="{{isset($ppr['humility']['self_rating_1']) ? $ppr['humility']['superios_rating_1'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="humility[wtd_score_1]" value="{{isset($ppr['humility']['self_rating_1']) ? $ppr['humility']['wtd_score_1'] : ""}}" min="1" max="5" disabled></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Asks for and uses feedback to improve performance, seeks additional training and development to improve his/ her knowledge and skills.</td>
                                                <td class="text-center text-dark"><input disabled type="number" name="humility[weights_2]" value="{{$ppr['humility']['weights_2']}}" min="1" max="100" required></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="humility[self_rating_2]" value="{{isset($ppr['humility']['self_rating_2']) ? $ppr['humility']['self_rating_2'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="humility[superios_rating_2]" value="{{isset($ppr['humility']['superios_rating_2']) ? $ppr['humility']['superios_rating_2'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="humility[wtd_score_2]" value="{{isset($ppr['humility']['wtd_score_2']) ? $ppr['humility']['wtd_score_2'] : ""}}" min="1"  max="5" disabled></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Reads, understands, and abides/ faithfully comply and conform with the Company Code of Discipline, Policies, and Procedures; and in case of violations, accepts administrative and/or financial accountability, if any.</td>
                                                <td class="text-center text-dark"><input disabled type="number" name="humility[weights_3]" value="{{$ppr['humility']['weights_3']}}" min="1" max="100" required></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="humility[self_rating_3]" value="{{isset($ppr['humility']['self_rating_3']) ? $ppr['humility']['self_rating_3'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="humility[superios_rating_3]" value="{{isset($ppr['humility']['superios_rating_3']) ? $ppr['humility']['superios_rating_3'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="humility[wtd_score_3]" value="{{isset($ppr['humility']['wtd_score_3']) ? $ppr['humility']['wtd_score_3'] : ""}}" min="1" max="5" disabled></td>
                                            </tr>

                                            <tr>
                                                <td colspan="6" class="text-center">&nbsp;</td>
                                            </tr>

                                            {{-- 4 --}}
                                            <tr>
                                                <td rowspan="3" class="text-center text-dark">4. GENUINE CONCERN - Ability to enrich the lives of people</td>
                                                <td class="text-center text-dark">Understand, assists and cares for the feelings and well-being (e.g. happy and safe, feel at ease and at home) of co-workers,  without hesitation or pretensions, directed by  his/ her attentiveness and sensitivety of their needs, difficulties, and changes in the mood of a room or emotions of those around.</td>
                                                <td class="text-center text-dark"><input disabled type="number" name="genuine_concern[weights_1]" value="{{$ppr['genuine_concern']['weights_1']}}" min="1" max="100" required></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="genuine_concern[self_rating_1]" value="{{isset($ppr['genuine_concern']['self_rating_1']) ? $ppr['genuine_concern']['self_rating_1'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="genuine_concern[superios_rating_1]" value="{{isset($ppr['genuine_concern']['self_rating_1']) ? $ppr['genuine_concern']['superios_rating_1'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="genuine_concern[wtd_score_1]" value="{{isset($ppr['genuine_concern']['self_rating_1']) ? $ppr['genuine_concern']['wtd_score_1'] : ""}}" min="1" max="5" disabled></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Listens to others and objectively considers others’ ideas and opinions, even when they conflict with one’s own, and addressing their potential impact organization-wide and across group when performing and helping others complete given tasks.</td>
                                                <td class="text-center text-dark"><input disabled type="number" name="genuine_concern[weights_2]" value="{{$ppr['genuine_concern']['weights_2']}}" min="1" max="100" required></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="genuine_concern[self_rating_2]" value="{{isset($ppr['genuine_concern']['self_rating_2']) ? $ppr['genuine_concern']['self_rating_2'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="genuine_concern[superios_rating_2]" value="{{isset($ppr['genuine_concern']['superios_rating_2']) ? $ppr['genuine_concern']['superios_rating_2'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="genuine_concern[wtd_score_2]" value="{{isset($ppr['genuine_concern']['wtd_score_2']) ? $ppr['genuine_concern']['wtd_score_2'] : ""}}" min="1"  max="5" disabled></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Identifies opportunities for improving performance both for one's own area or responsibility and/or within the organization, and commits significant resources to improve performance while taking action.</td>
                                                <td class="text-center text-dark"><input disabled type="number" name="genuine_concern[weights_3]" value="{{$ppr['genuine_concern']['weights_3']}}" min="1" max="100" required></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="genuine_concern[self_rating_3]" value="{{isset($ppr['genuine_concern']['self_rating_3']) ? $ppr['genuine_concern']['self_rating_3'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="genuine_concern[superios_rating_3]" value="{{isset($ppr['genuine_concern']['superios_rating_3']) ? $ppr['genuine_concern']['superios_rating_3'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="genuine_concern[wtd_score_3]" value="{{isset($ppr['genuine_concern']['wtd_score_3']) ? $ppr['genuine_concern']['wtd_score_3'] : ""}}" min="1" max="5" disabled></td>
                                            </tr>

                                            <tr>
                                                <td colspan="6" class="text-center">&nbsp;</td>
                                            </tr>

                                            {{-- 5 --}}
                                            <tr>
                                                <td rowspan="3" class="text-center text-dark">5. PREMIUM SERVICE - Ability to delivery quality service beyond expectation </td>
                                                <td class="text-center text-dark">Exceeds expectation in delivering a completed service/ task, with accurate and organized information, within the time line and standards set by the company or customer.</td>
                                                <td class="text-center text-dark"><input disabled type="number" name="premium_service[weights_1]" value="{{$ppr['premium_service']['weights_1']}}" min="1" max="100" required></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="premium_service[self_rating_1]" value="{{isset($ppr['premium_service']['self_rating_1']) ? $ppr['premium_service']['self_rating_1'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="premium_service[superios_rating_1]" value="{{isset($ppr['premium_service']['self_rating_1']) ? $ppr['premium_service']['superios_rating_1'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="premium_service[wtd_score_1]" value="{{isset($ppr['premium_service']['self_rating_1']) ? $ppr['premium_service']['wtd_score_1'] : ""}}" min="1" max="5" disabled></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Institutes a process/ system for monitoring and tracking individual and/or team results/ progress against standards; and modifies actions accordingly.</td>
                                                <td class="text-center text-dark"><input disabled type="number" name="premium_service[weights_2]" value="{{$ppr['premium_service']['weights_2']}}" min="1" max="100" required></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="premium_service[self_rating_2]" value="{{isset($ppr['premium_service']['self_rating_2']) ? $ppr['premium_service']['self_rating_2'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="premium_service[superios_rating_2]" value="{{isset($ppr['premium_service']['superios_rating_2']) ? $ppr['premium_service']['superios_rating_2'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="premium_service[wtd_score_2]" value="{{isset($ppr['premium_service']['wtd_score_2']) ? $ppr['premium_service']['wtd_score_2'] : ""}}" min="1"  max="5" disabled></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Gives value to customers by knowing your product and service, actively listening, practicing honesty in attending to customer needs; before, during, and after an exchange/ transaction.</td>
                                                <td class="text-center text-dark"><input disabled type="number" name="premium_service[weights_3]" value="{{$ppr['premium_service']['weights_3']}}" min="1" max="100" required></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="premium_service[self_rating_3]" value="{{isset($ppr['premium_service']['self_rating_3']) ? $ppr['premium_service']['self_rating_3'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="premium_service[superios_rating_3]" value="{{isset($ppr['premium_service']['superios_rating_3']) ? $ppr['premium_service']['superios_rating_3'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="premium_service[wtd_score_3]" value="{{isset($ppr['premium_service']['wtd_score_3']) ? $ppr['premium_service']['wtd_score_3'] : ""}}" min="1" max="5" disabled></td>
                                            </tr>

                                            <tr>
                                                <td colspan="6" class="text-center">&nbsp;</td>
                                            </tr>

                                            {{-- 6 --}}
                                            <tr>
                                                <td rowspan="3" class="text-center text-dark">6. INNOVATION - Ability to find a better way to do things better </td>
                                                <td class="text-center text-dark">Adjusts (adapt) thinking and behavior to be in step with new thrusts or changing priorities/ developments within the organization and the external environment with openness, acceptance (e.g. in assignments/ approaches even those not related to one's job), and recommendations for structural or operational changes.</td>
                                                <td class="text-center text-dark"><input disabled type="number" name="innovation[weights_1]" value="{{$ppr['innovation']['weights_1']}}" min="1" max="100" required></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="innovation[self_rating_1]" value="{{isset($ppr['innovation']['self_rating_1']) ? $ppr['innovation']['self_rating_1'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="innovation[superios_rating_1]" value="{{isset($ppr['innovation']['self_rating_1']) ? $ppr['innovation']['superios_rating_1'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="innovation[wtd_score_1]" value="{{isset($ppr['innovation']['self_rating_1']) ? $ppr['innovation']['wtd_score_1'] : ""}}" min="1" max="5" disabled></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Acquires/ generate/ develop, introduce/ contribute, and implement new and useful work methods, ideas, approaches, and information for products/ technologies, to solve problems or improve efficiency and effectiveness on the job/ organizational activities and services.</td>
                                                <td class="text-center text-dark"><input disabled type="number" name="innovation[weights_2]" value="{{$ppr['innovation']['weights_2']}}" min="1" max="100" required></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="innovation[self_rating_2]" value="{{isset($ppr['innovation']['self_rating_2']) ? $ppr['innovation']['self_rating_2'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="innovation[superios_rating_2]" value="{{isset($ppr['innovation']['superios_rating_2']) ? $ppr['innovation']['superios_rating_2'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="innovation[wtd_score_2]" value="{{isset($ppr['innovation']['wtd_score_2']) ? $ppr['innovation']['wtd_score_2'] : ""}}" min="1"  max="5" disabled></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Acts as a change agent by promoting and embracing change in existing practices (i.e. challenge status quo, streamlining processes) in appropriate ways, across the entire organization.</td>
                                                <td class="text-center text-dark"><input disabled type="number" name="innovation[weights_3]" value="{{$ppr['innovation']['weights_3']}}" min="1" max="100" required></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="innovation[self_rating_3]" value="{{isset($ppr['innovation']['self_rating_3']) ? $ppr['innovation']['self_rating_3'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="innovation[superios_rating_3]" value="{{isset($ppr['innovation']['superios_rating_3']) ? $ppr['innovation']['superios_rating_3'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="innovation[wtd_score_3]" value="{{isset($ppr['innovation']['wtd_score_3']) ? $ppr['innovation']['wtd_score_3'] : ""}}" min="1" max="5" disabled></td>
                                            </tr>

                                            <tr>
                                                <td colspan="6" class="text-center">&nbsp;</td>
                                            </tr>


                                            {{-- 7 --}}
                                            <tr>
                                                <td rowspan="3" class="text-center text-dark">7. SYNERGY- Ability to work together/ with others for bigger results</td>
                                                <td class="text-center text-dark">Places higher priority on organization's goals than on one's own goals; anticipate the effects of one's own/ area's actions and decisions on the co-workers and partners to meet both areas' needs can be met.</td>
                                                <td class="text-center text-dark"><input disabled type="number" name="synergy[weights_1]" value="{{$ppr['synergy']['weights_1']}}" min="1" max="100" required></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="synergy[self_rating_1]" value="{{isset($ppr['synergy']['self_rating_1']) ? $ppr['synergy']['self_rating_1'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="synergy[superios_rating_1]" value="{{isset($ppr['synergy']['self_rating_1']) ? $ppr['synergy']['superios_rating_1'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="synergy[wtd_score_1]" value="{{isset($ppr['synergy']['self_rating_1']) ? $ppr['synergy']['wtd_score_1'] : ""}}" min="1" max="5" disabled></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Instills mutual trust and confidence with/ among groups and individuals in the achievement of organizational shared goals, from the setting of meaningful and specific team performance goals, in the determination of courses of action, facilitation of agreements, and giving of mutual support.</td>
                                                <td class="text-center text-dark"><input disabled type="number" name="synergy[weights_2]" value="{{$ppr['synergy']['weights_2']}}" min="1" max="100" required></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="synergy[self_rating_2]" value="{{isset($ppr['synergy']['self_rating_2']) ? $ppr['synergy']['self_rating_2'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="synergy[superios_rating_2]" value="{{isset($ppr['synergy']['superios_rating_2']) ? $ppr['synergy']['superios_rating_2'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="synergy[wtd_score_2]" value="{{isset($ppr['synergy']['wtd_score_2']) ? $ppr['synergy']['wtd_score_2'] : ""}}" min="1"  max="5" disabled></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Acts as a role model in motivating others, fostering and maintaining inclusive (respecting and welcoming differences and diversity) and positive work environment to achieve the organization's goals/ targets.</td>
                                                <td class="text-center text-dark"><input disabled type="number" name="synergy[weights_3]" value="{{$ppr['synergy']['weights_3']}}" min="1" max="100" required></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="synergy[self_rating_3]" value="{{isset($ppr['synergy']['self_rating_3']) ? $ppr['synergy']['self_rating_3'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="synergy[superios_rating_3]" value="{{isset($ppr['synergy']['superios_rating_3']) ? $ppr['synergy']['superios_rating_3'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="synergy[wtd_score_3]" value="{{isset($ppr['synergy']['wtd_score_3']) ? $ppr['synergy']['wtd_score_3'] : ""}}" min="1" max="5" disabled></td>
                                            </tr>

                                            <tr>
                                                <td colspan="6" class="text-center">&nbsp;</td>
                                            </tr>

                                            {{-- 8 --}}
                                            <tr>
                                                <td rowspan="3" class="text-center text-dark">8. STEWARDSHIP - Ability to be grateful and to take responsibility and accountability for every task and resource entrusted to one's care</td>
                                                <td class="text-center text-dark">Practice habits that keeps the work place clean, safe, and secure; preventing accidents, losses, or damages of any kind.</td>
                                                <td class="text-center text-dark"><input disabled type="number" name="stewardship[weights_1]" value="{{$ppr['stewardship']['weights_1']}}" min="1" max="100" required></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="stewardship[self_rating_1]" value="{{isset($ppr['stewardship']['self_rating_1']) ? $ppr['stewardship']['self_rating_1'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="stewardship[superios_rating_1]" value="{{isset($ppr['stewardship']['self_rating_1']) ? $ppr['stewardship']['superios_rating_1'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="stewardship[wtd_score_1]" value="{{isset($ppr['stewardship']['self_rating_1']) ? $ppr['stewardship']['wtd_score_1'] : ""}}" min="1" max="5" disabled></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Exercise control in the use of company benefits, property, resources, supplies, materials, power, etc.; prevent unnecessary waste/ loss, within the large context of the organization.</td>
                                                <td class="text-center text-dark"><input disabled type="number" name="stewardship[weights_2]" value="{{$ppr['stewardship']['weights_2']}}" min="1" max="100" required></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="stewardship[self_rating_2]" value="{{isset($ppr['stewardship']['self_rating_2']) ? $ppr['stewardship']['self_rating_2'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="stewardship[superios_rating_2]" value="{{isset($ppr['stewardship']['superios_rating_2']) ? $ppr['stewardship']['superios_rating_2'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="stewardship[wtd_score_2]" value="{{isset($ppr['stewardship']['wtd_score_2']) ? $ppr['stewardship']['wtd_score_2'] : ""}}" min="1"  max="5" disabled></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Seriously perform the roles as entrusted and accountable custodian of Company properties, brand/ reputation, and resources</td>
                                                <td class="text-center text-dark"><input disabled type="number" name="stewardship[weights_3]" value="{{$ppr['stewardship']['weights_3']}}" min="1" max="100" required></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="stewardship[self_rating_3]" value="{{isset($ppr['stewardship']['self_rating_3']) ? $ppr['stewardship']['self_rating_3'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="stewardship[superios_rating_3]" value="{{isset($ppr['stewardship']['superios_rating_3']) ? $ppr['stewardship']['superios_rating_3'] : ""}}" min="1" max="5" disabled></td>
                                                <td class="text-center text-dark"><input disabled type="number" name="stewardship[wtd_score_3]" value="{{isset($ppr['stewardship']['wtd_score_3']) ? $ppr['stewardship']['wtd_score_3'] : ""}}" min="1" max="5" disabled></td>
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
                                            <td align="center"><input disabled type="number" min="1" max="100" name="bsc_weight" value="{{$ppr['bsc_weight']}}"></td>
                                            <td align="center"><input disabled type="number" min="1" max="100" name="bsc_actual_score" value="{{$ppr['bsc_actual_score']}}" disabled></td>
                                            <td align="center"><input disabled type="text" name="bsc_description" width="100%" class="responsive-input" disabled></td>
                                        </tr>
                                        <tr>
                                            <td align="center">COMPETENCY</td>
                                            <td align="center"><input disabled type="number" min="1" max="100" name="competency_weight" value="{{$ppr['competency_weight']}}"></td>
                                            <td align="center"><input disabled type="number" min="1" max="100" name="competency_actual_score" value="{{$ppr['competency_actual_score']}}" disabled></td>
                                            <td align="center"><input disabled type="text" name="competency_description" width="100%" class="responsive-input" disabled></td>
                                        </tr>
                                        <tr>
                                            <td align="center">TOTAL</td>
                                            <td align="center"><input disabled type="number" min="1" max="100" name="total_weight" value="{{$ppr['total_weight']}}" disabled></td>
                                            <td align="center"><input disabled type="number" min="1" max="100" name="total_actual_score" value="{{$ppr['total_actual_score']}}" disabled></td>
                                            <td align="center"></td>
                                        </tr>
                                    </table>

                                    <table class="table-bordered mt-1" width="100%">
                                        <tr>
                                            <td width="50%" align="center" style="background-color: rgb(240, 240, 240)"><strong>AREAS OF STRENGTH</strong></td>
                                            <td width="50%" align="center" style="background-color: rgb(240, 240, 240)"><strong>DEVELOPMENTAL NEEDS</strong></td>
                                        </tr>
                                        <tr>
                                            <td align="center"><textarea width="100%" class="responsive-input" name="areas_of_strength" cols="30" rows="7" placeholder="Input here" disabled>{{$ppr['areas_of_strength']}}</textarea></td>
                                            <td align="center"><textarea width="100%" class="responsive-input" name="developmental_needs" cols="30" rows="7" placeholder="Input here" disabled>{{$ppr['developmental_needs']}}</textarea></td>
                                        </tr>
                                   
                                        <tr>
                                            <td width="50%" align="center" style="background-color: rgb(240, 240, 240)"><strong>AREAS FOR ENHANCEMENT</strong></td>
                                            <td width="50%" align="center" style="background-color: rgb(240, 240, 240)"><strong>TRAINING & DEVELOPMENTAL PLANS</strong></td>
                                        </tr>
                                        <tr>
                                            <td align="center"><textarea width="100%" class="responsive-input" name="areas_for_enhancement" cols="30" rows="7" placeholder="Input here" disabled>{{$ppr['areas_for_enhancement']}}</textarea></td>
                                            <td align="center"><textarea width="100%" class="responsive-input" name="training_and_development_plans" cols="30" rows="7" placeholder="Input here" disabled>{{$ppr['training_and_development_plans']}}</textarea></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" align="center" style="background-color: rgb(240, 240, 240)"><strong>RATEE'S COMMENTS</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" align="center"><textarea style="max-width:2000px!important;" class="responsive-input" name="ratees_comments" cols="30" rows="7" placeholder="Input here" disabled>{{$ppr['ratees_comments'] ? $ppr['ratees_comments'] : ""}}</textarea></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" align="center" style="background-color: rgb(240, 240, 240)"><strong>SUMMARY OF RATER'S COMMENTS/RECOMMENDATIONS</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" align="center"><textarea style="max-width:2000px!important;" class="responsive-input" name="summary_ratees_comments_recommendation" cols="30" rows="7" placeholder="Input here" disabled>{{isset($ppr['summary_ratees_comments_recommendation']) ? $ppr['summary_ratees_comments_recommendation'] : ""}}</textarea></td>
                                        </tr>
                                    </table>


                                    

                                </div>
                            </div>

                            {{-- @if($ppr['status'] == 'Draft')
                                <div class="text-center mt-5">
                                    <button type="submit" class="btn btn-primary">Submit Changes</button>
                                    <span id="{{ $ppr['id'] }}" onclick="submitForReview(this.id)" class="btn btn-success">Submit For Review</span>
                                </div>
                            @elseif($ppr['status'] == 'For Review')
                                <div class="text-center mt-5">
                                    <button type="button" class="btn btn-success" disabled>For Review</button>
                                </div>
                            @elseif($ppr['status'] == 'Approved')
                                <div class="text-center mt-5">
                                    <button type="button" class="btn btn-success" disabled>Approved</button>
                                </div>
                            @endif --}}
                            
                            
                        </div>

                       
                   
                    
                </form>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('empAllowScript')
	<script>
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
	</script>
@endsection

<style>

    .responsive-input {
        width: 100%;
        max-width: 1000px; /* Set a maximum width to prevent the input from becoming too wide on larger screens */
        padding: 1px;
        box-sizing: border-box;
    }

</style>
