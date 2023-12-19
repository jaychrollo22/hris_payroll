@extends('layouts.header')
@section('content')



  
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <form id="myForm" method='POST' action='save-performance-plan-review' onsubmit="btnDtr.disabled = true; return true;"  enctype="multipart/form-data">
                    @csrf      
                    <div class="card-body">
                            <h4 class="card-title">CREATE PERFORMANCE PLAN</h4>
                                <div class="table-responsive">
                                    <table class="table-bordered" width="100%">
                                        <tr>
                                            <td align="center">Calendar Year</td>
                                            <td align="center"> <input type="text" placeholder="Calendar Year" width="100%" class="responsive-input" name="calendar_year" value="January 2024 - December 2024" required></td>
                                            <td align="center">Review Date</td>
                                            <td align="center"> <input type="date" name="review_date"></td>
                                            <td align="center">Period</td>
                                            <td align="center" style="vertical-align: middle;">
                                                <label class="mt-2">
                                                    <input type="radio" name="period" value="MIDYEAR" required>
                                                    MIDYEAR
                                                </label>
                                                <label class="mt-2">
                                                    <input type="radio" name="period" value="ANNUAL" required>
                                                    ANNUAL
                                                </label>
                                                <label class="mt-2">
                                                    <input type="radio" name="period" value="PROBATIONARY" required>
                                                    PROBATIONARY
                                                </label>
                                                <label class="mt-2" >
                                                    <input type="radio" name="period" value="SPECIAL" required>
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
                                            <td align="center">Performance is consistently superior and sustained to the standards  for the job.</td>
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
                                            <td align="center">Performance consistently meets the standards  of the position </td>
                                            <td align="center">3</td>
                                            <td align="center">76% - 100%</td>
                                        </tr>
                                        <tr>
                                            <td align="center">NEEDS IMPROVEMENT</td>
                                            <td align="center">Performance does not consistently meet the standards  of the position, objectives, and expectation.</td>
                                            <td align="center">2</td>
                                            <td align="center">51% - 75%</td>
                                        </tr>
                                        <tr>
                                            <td align="center">UNSATISFACTORY</td>
                                            <td align="center">Work performance is inadequate and inferior to the standards  of the position.  (Performance cannot be allowed to continue).</td>
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
                                                <td style="text-align: center; width:300px!important;"><input type="text" name="financial_perspective[strat_1_objective_1]" id="financial_perspective[strat_1_objective_1]" width="100%" class="responsive-input" placeholder="Input Here"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="financial_perspective[strat_1_metric_1]" id="financial_perspective[strat_1_metric_1]" placeholder="Metric"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="financial_perspective[strat_1_target_1]"  id="financial_perspective[strat_1_target_1]"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="financial_perspective[strat_1_target_start_completion_1]" placeholder="Start" id="financial_perspective[strat_1_target_start_completion_1]"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="financial_perspective[strat_1_target_end_completion_1]" placeholder="End" id="financial_perspective[strat_1_target_end_completion_1]"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="financial_perspective[strat_1_weight_1]" id="financial_perspective[strat_1_weight_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="5" name="financial_perspective[strat_1_review_actual_1]" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="financial_perspective[strat_1_remarks_2]" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 2</td>
                                                <td style="text-align: center; width:300px!important;"><input type="text" name="financial_perspective[strat_2_objective_1]" id="" width="100%" class="responsive-input" placeholder="Input Here"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="financial_perspective[strat_2_metric_1]" placeholder="Metric"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="financial_perspective[strat_2_target_1]"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="financial_perspective[strat_2_target_start_completion_1]" placeholder="Start"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="financial_perspective[strat_2_target_end_completion_1]" placeholder="End"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="financial_perspective[strat_2_weight_1]" id="financial_perspective[strat_2_weight_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="5" name="financial_perspective[strat_2_review_actual_1]" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="financial_perspective[strat_2_remarks_1]" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 3</td>
                                                <td style="text-align: center; width:300px!important;"><input type="text" name="financial_perspective[strat_3_objective_1]" id="" width="100%" class="responsive-input" placeholder="Input Here"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="financial_perspective[strat_3_metric_1]" placeholder="Metric"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="financial_perspective[strat_3_target_1]"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" width="100%" name="financial_perspective[strat_3_target_start_completion_1]" placeholder="Start"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" width="100%" name="financial_perspective[strat_3_target_end_completion_1]" placeholder="End"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="financial_perspective[strat_3_weight_1]" id="financial_perspective[strat_3_weight_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="5" name="financial_perspective[strat_3_review_actual_1]" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="financial_perspective[strat_3_remarks_1]" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-center">Objective 2 Profit</td>
                                                <td colspan="7"></td>
                                            </tr>
                                            <tr>
                                                <td style="width:200px!important;">Strat 1</td>
                                                <td style="text-align: center; width:300px!important;"><input type="text" name="financial_perspective[strat_1_objective_2]" id="" width="100%" class="responsive-input" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="financial_perspective[strat_1_metric_2]" placeholder="Metric" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="financial_perspective[strat_1_target_2]" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="financial_perspective[strat_1_target_start_completion_2]" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="financial_perspective[strat_1_target_end_completion_2]" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="financial_perspective[strat_1_weight_2]" id="financial_perspective[strat_1_weight_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="5" name="financial_perspective[strat_1_review_actual_2]" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="financial_perspective[strat_1_remarks_2]" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 2</td>
                                                <td style="text-align: center; width:300px!important;"><input type="text" name="financial_perspective[strat_2_objective_2]" id="" width="100%" class="responsive-input" placeholder="Input Here"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="financial_perspective[strat_2_metric_2]" placeholder="Metric"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="financial_perspective[strat_2_target_2]"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="financial_perspective[strat_2_target_start_completion_2]" placeholder="Start"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="financial_perspective[strat_2_target_end_completion_2]" placeholder="End"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="financial_perspective[strat_2_weight_2]" id="financial_perspective[strat_2_weight_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="5" name="financial_perspective[strat_2_review_actual_2]" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="financial_perspective[strat_2_remarks_2]" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 3</td>
                                                <td style="text-align: center; width:300px!important;"><input type="text" name="financial_perspective[strat_3_objective_2]" id="" width="100%" class="responsive-input" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="financial_perspective[strat_3_metric_2]" placeholder="Metric"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="financial_perspective[strat_3_target_2]"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" width="100%" name="financial_perspective[strat_3_target_start_completion_2]" placeholder="Start"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" width="100%" name="financial_perspective[strat_3_target_end_completion_2]" placeholder="End"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="financial_perspective[strat_3_weight_2]" id="financial_perspective[strat_3_weight_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="5" name="financial_perspective[strat_3_review_actual_2]" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="financial_perspective[strat_3_remarks_2]" id="" width="100%" class="responsive-input" disabled></td>
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
                                                <td style="text-align: center; width:300px!important;"><input type="text" name="customer_focus[strat_1_objective_1]" id="" width="100%" class="responsive-input" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="customer_focus[strat_1_metric_1]" placeholder="Metric" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="customer_focus[strat_1_target_1]" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="customer_focus[strat_1_target_start_completion_1]" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="customer_focus[strat_1_target_end_completion_1]" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="customer_focus[strat_1_weight_1]" id="customer_focus[strat_1_weight_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="5" name="customer_focus[strat_1_review_actual_1]" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="customer_focus[strat_1_remarks_2]" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 2</td>
                                                <td style="text-align: center; width:300px!important;"><input type="text" name="customer_focus[strat_2_objective_1]" id="" width="100%" class="responsive-input" placeholder="Input Here"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="customer_focus[strat_2_metric_1]" placeholder="Metric"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="customer_focus[strat_2_target_1]"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="customer_focus[strat_2_target_start_completion_1]" placeholder="Start"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="customer_focus[strat_2_target_end_completion_1]" placeholder="End"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="customer_focus[strat_2_weight_1]" id="customer_focus[strat_2_weight_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="5" name="customer_focus[strat_2_review_actual_1]" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="customer_focus[strat_2_remarks_1]" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 3</td>
                                                <td style="text-align: center; width:300px!important;"><input type="text" name="customer_focus[strat_3_objective_1]" id="" width="100%" class="responsive-input" placeholder="Input Here"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="customer_focus[strat_3_metric_1]" placeholder="Metric"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="customer_focus[strat_3_target_1]"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" width="100%" name="customer_focus[strat_3_target_start_completion_1]" placeholder="Start"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" width="100%" name="customer_focus[strat_3_target_end_completion_1]" placeholder="End"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="customer_focus[strat_3_weight_1]" id="customer_focus[strat_3_weight_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="5" name="customer_focus[strat_3_review_actual_1]" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="customer_focus[strat_3_remarks_1]" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-center">Objective 2</td>
                                                <td colspan="7"></td>
                                            </tr>
                                            <tr>
                                                <td style="width:200px!important;">Strat 1</td>
                                                <td style="text-align: center; width:300px!important;"><input type="text" name="customer_focus[strat_1_objective_2]" id="" width="100%" class="responsive-input" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="customer_focus[strat_1_metric_2]" placeholder="Metric" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="customer_focus[strat_1_target_2]" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="customer_focus[strat_1_target_start_completion_2]" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="customer_focus[strat_1_target_end_completion_2]" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="customer_focus[strat_1_weight_2]" id="customer_focus[strat_1_weight_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="5" name="customer_focus[strat_1_review_actual_2]" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="customer_focus[strat_1_remarks_2]" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 2</td>
                                                <td style="text-align: center; width:300px!important;"><input type="text" name="customer_focus[strat_2_objective_2]" id="" width="100%" class="responsive-input" placeholder="Input Here"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="customer_focus[strat_2_metric_2]" placeholder="Metric"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="customer_focus[strat_2_target_2]"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="customer_focus[strat_2_target_start_completion_2]" placeholder="Start"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="customer_focus[strat_2_target_end_completion_2]" placeholder="End"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="customer_focus[strat_2_weight_2]" id="customer_focus[strat_2_weight_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="5" name="customer_focus[strat_2_review_actual_2]" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="customer_focus[strat_2_remarks_2]" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 3</td>
                                                <td style="text-align: center; width:300px!important;"><input type="text" name="customer_focus[strat_3_objective_2]" id="" width="100%" class="responsive-input" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="customer_focus[strat_3_metric_2]" placeholder="Metric"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="customer_focus[strat_3_target_2]"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" width="100%" name="customer_focus[strat_3_target_start_completion_2]" placeholder="Start"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" width="100%" name="customer_focus[strat_3_target_end_completion_2]" placeholder="End"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="customer_focus[strat_3_weight_2]" id="customer_focus[strat_3_weight_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="5" name="customer_focus[strat_3_review_actual_2]" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="customer_focus[strat_3_remarks_2]" id="" width="100%" class="responsive-input" disabled></td>
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
                                                <td style="text-align: center; width:300px!important;"><input type="text" name="operation_efficiency[strat_1_objective_1]" id="" width="100%" class="responsive-input" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="operation_efficiency[strat_1_metric_1]" placeholder="Metric" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="operation_efficiency[strat_1_target_1]" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="operation_efficiency[strat_1_target_start_completion_1]" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="operation_efficiency[strat_1_target_end_completion_1]" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="operation_efficiency[strat_1_weight_1]" id="operation_efficiency[strat_1_weight_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="5" name="operation_efficiency[strat_1_review_actual_1]" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="operation_efficiency[strat_1_remarks_2]" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 2</td>
                                                <td style="text-align: center; width:300px!important;"><input type="text" name="operation_efficiency[strat_2_objective_1]" id="" width="100%" class="responsive-input" placeholder="Input Here"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="operation_efficiency[strat_2_metric_1]" placeholder="Metric"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="operation_efficiency[strat_2_target_1]"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="operation_efficiency[strat_2_target_start_completion_1]" placeholder="Start"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="operation_efficiency[strat_2_target_end_completion_1]" placeholder="End"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="operation_efficiency[strat_2_weight_1]" id="operation_efficiency[strat_2_weight_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="5" name="operation_efficiency[strat_2_review_actual_1]" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="operation_efficiency[strat_2_remarks_1]" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 3</td>
                                                <td style="text-align: center; width:300px!important;"><input type="text" name="operation_efficiency[strat_3_objective_1]" id="" width="100%" class="responsive-input" placeholder="Input Here"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="operation_efficiency[strat_3_metric_1]" placeholder="Metric"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="operation_efficiency[strat_3_target_1]"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" width="100%" name="operation_efficiency[strat_3_target_start_completion_1]" placeholder="Start"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" width="100%" name="operation_efficiency[strat_3_target_end_completion_1]" placeholder="End"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="operation_efficiency[strat_3_weight_1]" id="operation_efficiency[strat_3_weight_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="5" name="operation_efficiency[strat_3_review_actual_1]" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="operation_efficiency[strat_3_remarks_1]" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-center">Objective 2</td>
                                                <td colspan="7"></td>
                                            </tr>
                                            <tr>
                                                <td style="width:200px!important;">Strat 1</td>
                                                <td style="text-align: center; width:300px!important;"><input type="text" name="operation_efficiency[strat_1_objective_2]" id="" width="100%" class="responsive-input" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="operation_efficiency[strat_1_metric_2]" placeholder="Metric" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="operation_efficiency[strat_1_target_2]" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="operation_efficiency[strat_1_target_start_completion_2]" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="operation_efficiency[strat_1_target_end_completion_2]" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="operation_efficiency[strat_1_weight_2]" id="operation_efficiency[strat_1_weight_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="5" name="operation_efficiency[strat_1_review_actual_2]" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="operation_efficiency[strat_1_remarks_2]" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 2</td>
                                                <td style="text-align: center; width:300px!important;"><input type="text" name="operation_efficiency[strat_2_objective_2]" id="" width="100%" class="responsive-input" placeholder="Input Here"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="operation_efficiency[strat_2_metric_2]" placeholder="Metric"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="operation_efficiency[strat_2_target_2]"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="operation_efficiency[strat_2_target_start_completion_2]" placeholder="Start"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="operation_efficiency[strat_2_target_end_completion_2]" placeholder="End"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="operation_efficiency[strat_2_weight_2]" id="operation_efficiency[strat_2_weight_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="5" name="operation_efficiency[strat_2_review_actual_2]" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="operation_efficiency[strat_2_remarks_2]" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 3</td>
                                                <td style="text-align: center; width:300px!important;"><input type="text" name="operation_efficiency[strat_3_objective_2]" id="" width="100%" class="responsive-input" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="operation_efficiency[strat_3_metric_2]" placeholder="Metric"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="operation_efficiency[strat_3_target_2]"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" width="100%" name="operation_efficiency[strat_3_target_start_completion_2]" placeholder="Start"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" width="100%" name="operation_efficiency[strat_3_target_end_completion_2]" placeholder="End"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="operation_efficiency[strat_3_weight_2]" id="operation_efficiency[strat_3_weight_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="5" name="operation_efficiency[strat_3_review_actual_2]" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="operation_efficiency[strat_3_remarks_2]" id="" width="100%" class="responsive-input" disabled></td>
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
                                                <td style="text-align: center; width:300px!important;"><input type="text" name="people[strat_1_objective_1]" id="" width="100%" class="responsive-input" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="people[strat_1_metric_1]" placeholder="Metric" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="people[strat_1_target_1]" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="people[strat_1_target_start_completion_1]" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="people[strat_1_target_end_completion_1]" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="people[strat_1_weight_1]" id="people[strat_1_weight_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="5" name="people[strat_1_review_actual_1]" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="people[strat_1_remarks_2]" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 2</td>
                                                <td style="text-align: center; width:300px!important;"><input type="text" name="people[strat_2_objective_1]" id="" width="100%" class="responsive-input" placeholder="Input Here"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="people[strat_2_metric_1]" placeholder="Metric"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="people[strat_2_target_1]"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="people[strat_2_target_start_completion_1]" placeholder="Start"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="people[strat_2_target_end_completion_1]" placeholdaer="End"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="people[strat_2_weight_1]" id="people[strat_2_weight_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="5" name="people[strat_2_review_actual_1]" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="people[strat_2_remarks_1]" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 3</td>
                                                <td style="text-align: center; width:300px!important;"><input type="text" name="people[strat_3_objective_1]" id="" width="100%" class="responsive-input" placeholder="Input Here"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="people[strat_3_metric_1]" placeholder="Metric"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="people[strat_3_target_1]"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" width="100%" name="people[strat_3_target_start_completion_1]" placeholder="Start"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" width="100%" name="people[strat_3_target_end_completion_1]" placeholder="End"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="people[strat_3_weight_1]" id="people[strat_3_weight_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="5" name="people[strat_3_review_actual_1]" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="people[strat_3_remarks_1]" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-center">Objective 2</td>
                                                <td colspan="7"></td>
                                            </tr>
                                            <tr>
                                                <td style="width:200px!important;">Strat 1</td>
                                                <td style="text-align: center; width:300px!important;"><input type="text" name="people[strat_1_objective_2]" id="" width="100%" class="responsive-input" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="people[strat_1_metric_2]" placeholder="Metric" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="people[strat_1_target_2]" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="people[strat_1_target_start_completion_2]" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="people[strat_1_target_end_completion_2]" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="people[strat_1_weight_2]" id="people[strat_1_weight_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="5" name="people[strat_1_review_actual_2]" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="people[strat_1_remarks_2]" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 2</td>
                                                <td style="text-align: center; width:300px!important;"><input type="text" name="people[strat_2_objective_2]" id="" width="100%" class="responsive-input" placeholder="Input Here"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="people[strat_2_metric_2]" placeholder="Metric"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="people[strat_2_target_2]"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="people[strat_2_target_start_completion_2]" placeholder="Start"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" name="people[strat_2_target_end_completion_2]" placeholder="End"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="people[strat_2_weight_2]" id="people[strat_2_weight_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="5" name="people[strat_2_review_actual_2]" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="people[strat_2_remarks_2]" id="" width="100%" class="responsive-input" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Strat 3</td>
                                                <td style="text-align: center; width:300px!important;"><input type="text" name="people[strat_3_objective_2]" id="" width="100%" class="responsive-input" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="people[strat_3_metric_2]" placeholder="Metric"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="people[strat_3_target_2]"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" width="100%" name="people[strat_3_target_start_completion_2]" placeholder="Start"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="date" width="100%" name="people[strat_3_target_end_completion_2]" placeholder="End"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="100" name="people[strat_3_weight_2]" id="people[strat_3_weight_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;"><input type="number" class="text-align-center" min="1" max="5" name="people[strat_3_review_actual_2]" disabled></td>
                                                <td style="text-align: center; width:10px!important;"><input type="text" name="people[strat_3_remarks_2]" id="" width="100%" class="responsive-input" disabled></td>
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
                                            <td align="center">Specialist/ authority level of the knowledge, understanding, and application of the competency  to be successful in the job and in the organization. Recognized by others as an expert in the competency and is sought by others throught the organization. Works across teams, departments, and organizational functions.  Applies the skill across multiple projects or functions. Able to explain issues in relation to broader organizational issues.  Has strategic focus.</td>
                                            <td align="center">4</td>
                                        </tr>
                                        <tr>
                                            <td align="center">MEETS EXPECTATION</td>
                                            <td align="center">Highly developed knowledge, understanding, and application of the competency. Can apply the competency outside the scope of one's position. Able to coach or teach others on the competency. Has a long term perspective and helps develop materials and resources related to the competency.</td>
                                            <td align="center">3</td>
                                        </tr>
                                        <tr>
                                            <td align="center">NEEDS IMPROVEMENT</td>
                                            <td align="center">Detailed knowledge, understanding, and application of the competency  to be successful in the job. Requires minimal guidance/ supervision. Capable of assisting others in the application of the competency.</td>
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
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="integrity[self_rating_1]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="integrity[superios_rating_1]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="integrity[wtd_score_1]" min="1" max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Treats people with dignity, respect, and fairness; gives proper credit to others; stands up for others who are deserving and their ideas even in the face of resistance or challenge to fosters high standards of values and ethics.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="integrity[self_rating_2]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="integrity[superios_rating_2]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="integrity[wtd_score_2]" min="1" max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Acts as a role model for working with others with sincerity, cheerfulness, and trust worthy traits in carrying out the job/ task with minimal to on supervision.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="integrity[self_rating_3]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="integrity[superios_rating_3]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="integrity[wtd_score_3]" min="1" max="5" readonly></td>
                                            </tr>

                                            <tr>
                                                <td colspan="6" class="text-center">&nbsp;</td>
                                            </tr>

                                            {{-- 2 --}}
                                            <tr>
                                                <td rowspan="3" class="text-center text-dark">2. COMMITMENT - Ability to never make excuses, only results</td>
                                                <td class="text-center text-dark">Recognizes personal strengths and gaps and seeks guidance or resources in laying out development and/or improvement plans.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="commitment[self_rating_1]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="commitment[superios_rating_1]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="commitment[wtd_score_1]" min="1" max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Asks for and uses feedback to improve performance, seeks additional training and development to improve his/ her knowledge and skills.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="commitment[self_rating_2]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="commitment[superios_rating_2]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="commitment[wtd_score_2]" min="1" max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Reads, understands, and abides/ faithfully comply and conform with the Company Code of Discipline, Policies, and Procedures; and in case of violations, accepts administrative and/or financial accountability, if any.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="commitment[self_rating_3]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="commitment[superios_rating_3]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="commitment[wtd_score_3]" min="1" max="5" readonly></td>
                                            </tr>

                                            <tr>
                                                <td colspan="6" class="text-center">&nbsp;</td>
                                            </tr>

                                            {{-- 3 --}}
                                            <tr>
                                                <td rowspan="3" class="text-center text-dark">3. HUMILITY - Ability to be simple (no pretenses)</td>
                                                <td class="text-center text-dark">Recognizes personal strengths and gaps and seeks guidance or resources in laying out development and/or improvement plans.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="humility[self_rating_1]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="humility[superios_rating_1]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="humility[wtd_score_1]" min="1" max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Asks for and uses feedback to improve performance, seeks additional training and development to improve his/ her knowledge and skills.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="humility[self_rating_2]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="humility[superios_rating_2]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="humility[wtd_score_2]" min="1" max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Reads, understands, and abides/ faithfully comply and conform with the Company Code of Discipline, Policies, and Procedures; and in case of violations, accepts administrative and/or financial accountability, if any.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="humility[self_rating_3]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="humility[superios_rating_3]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="humility[wtd_score_3]" min="1" max="5" readonly></td>
                                            </tr>

                                            <tr>
                                                <td colspan="6" class="text-center">&nbsp;</td>
                                            </tr>

                                            {{-- 4 --}}
                                            <tr>
                                                <td rowspan="3" class="text-center text-dark">4. GENUINE CONCERN - Ability to enrich the lives of people</td>
                                                <td class="text-center text-dark">Understand, assists and cares for the feelings and well-being (e.g. happy and safe, feel at ease and at home) of co-workers,  without hesitation or pretensions, directed by  his/ her attentiveness and sensitivety of their needs, difficulties, and changes in the mood of a room or emotions of those around.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="genuine_concern[self_rating_1]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="genuine_concern[superios_rating_1]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="genuine_concern[wtd_score_1]" min="1" max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Listens to others and objectively considers others’ ideas and opinions, even when they conflict with one’s own, and addressing their potential impact organization-wide and across group when performing and helping others complete given tasks.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="genuine_concern[self_rating_2]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="genuine_concern[superios_rating_2]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="genuine_concern[wtd_score_2]" min="1" max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Identifies opportunities for improving performance both for one's own area or responsibility and/or within the organization, and commits significant resources to improve performance while taking action.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="genuine_concern[self_rating_3]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="genuine_concern[superios_rating_3]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="genuine_concern[wtd_score_3]" min="1" max="5" readonly></td>
                                            </tr>

                                            <tr>
                                                <td colspan="6" class="text-center">&nbsp;</td>
                                            </tr>

                                            {{-- 5 --}}
                                            <tr>
                                                <td rowspan="3" class="text-center text-dark">5. PREMIUM SERVICE - Ability to delivery quality service beyond expectation </td>
                                                <td class="text-center text-dark">Exceeds expectation in delivering a completed service/ task, with accurate and organized information, within the time line and standards set by the company or customer.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="premium_service[self_rating_1]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="premium_service[superios_rating_1]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="premium_service[wtd_score_1]" min="1" max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Institutes a process/ system for monitoring and tracking individual and/or team results/ progress against standards; and modifies actions accordingly.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="premium_service[self_rating_2]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="premium_service[superios_rating_2]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="premium_service[wtd_score_2]" min="1" max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Gives value to customers by knowing your product and service, actively listening, practicing honesty in attending to customer needs; before, during, and after an exchange/ transaction.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="premium_service[self_rating_3]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="premium_service[superios_rating_3]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="premium_service[wtd_score_3]" min="1" max="5" readonly></td>
                                            </tr>

                                            <tr>
                                                <td colspan="6" class="text-center">&nbsp;</td>
                                            </tr>

                                            {{-- 6 --}}
                                            <tr>
                                                <td rowspan="3" class="text-center text-dark">6. INNOVATION - Ability to find a better way to do things better </td>
                                                <td class="text-center text-dark">Adjusts (adapt) thinking and behavior to be in step with new thrusts or changing priorities/ developments within the organization and the external environment with openness, acceptance (e.g. in assignments/ approaches even those not related to one's job), and recommendations for structural or operational changes.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="innovation[self_rating_1]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="innovation[superios_rating_1]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="innovation[wtd_score_1]" min="1" max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Acquires/ generate/ develop, introduce/ contribute, and implement new and useful work methods, ideas, approaches, and information for products/ technologies, to solve problems or improve efficiency and effectiveness on the job/ organizational activities and services.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="innovation[self_rating_2]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="innovation[superios_rating_2]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="innovation[wtd_score_2]" min="1" max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Acts as a change agent by promoting and embracing change in existing practices (i.e. challenge status quo, streamlining processes) in appropriate ways, across the entire organization.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="innovation[self_rating_3]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="innovation[superios_rating_3]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="innovation[wtd_score_3]" min="1" max="5" readonly></td>
                                            </tr>

                                            <tr>
                                                <td colspan="6" class="text-center">&nbsp;</td>
                                            </tr>


                                            {{-- 7 --}}
                                            <tr>
                                                <td rowspan="3" class="text-center text-dark">7. SYNERGY- Ability to work together/ with others for bigger results</td>
                                                <td class="text-center text-dark">Places higher priority on organization's goals than on one's own goals; anticipate the effects of one's own/ area's actions and decisions on the co-workers and partners to meet both areas' needs can be met.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="synergy[self_rating_1]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="synergy[superios_rating_1]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="synergy[wtd_score_1]" min="1" max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Instills mutual trust and confidence with/ among groups and individuals in the achievement of organizational shared goals, from the setting of meaningful and specific team performance goals, in the determination of courses of action, facilitation of agreements, and giving of mutual support.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="synergy[self_rating_2]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="synergy[superios_rating_2]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="synergy[wtd_score_2]" min="1" max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Acts as a role model in motivating others, fostering and maintaining inclusive (respecting and welcoming differences and diversity) and positive work environment to achieve the organization's goals/ targets.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="synergy[self_rating_3]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="synergy[superios_rating_3]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="synergy[wtd_score_3]" min="1" max="5" readonly></td>
                                            </tr>

                                            <tr>
                                                <td colspan="6" class="text-center">&nbsp;</td>
                                            </tr>

                                            {{-- 8 --}}
                                            <tr>
                                                <td rowspan="3" class="text-center text-dark">8. STEWARDSHIP - Ability to be grateful and to take responsibility and accountability for every task and resource entrusted to one's care</td>
                                                <td class="text-center text-dark">Practice habits that keeps the work place clean, safe, and secure; preventing accidents, losses, or damages of any kind.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="stewardship[self_rating_1]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="stewardship[superios_rating_1]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="stewardship[wtd_score_1]" min="1" max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Exercise control in the use of company benefits, property, resources, supplies, materials, power, etc.; prevent unnecessary waste/ loss, within the large context of the organization.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="stewardship[self_rating_2]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="stewardship[superios_rating_2]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="stewardship[wtd_score_2]" min="1" max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Seriously perform the roles as entrusted and accountable custodian of Company properties, brand/ reputation, and resources</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="stewardship[self_rating_3]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="stewardship[superios_rating_3]" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="stewardship[wtd_score_3]" min="1" max="5" readonly></td>
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
                                            <td align="center"><input type="number" class="text-align-center" id="bsc_weight" min="1" max="100" name="bsc_weight" readonly></td>
                                            <td align="center"><input type="number" class="text-align-center" min="1" max="100" name="bsc_actual_score" disabled></td>
                                            <td align="center"><input type="text" name="bsc_description" width="100%" class="responsive-input" disabled></td>
                                        </tr>
                                        <tr>
                                            <td align="center">COMPETENCY</td>
                                            <td align="center"><input type="number" class="text-align-center" id="competency_weight" min="1" max="100" name="competency_weight" readonly></td>
                                            <td align="center"><input type="number" class="text-align-center" min="1" max="100" name="competency_actual_score" disabled></td>
                                            <td align="center"><input type="text" name="competency_description" width="100%" class="responsive-input" disabled></td>
                                        </tr>
                                        <tr>
                                            <td align="center">TOTAL</td>
                                            <td align="center"><input type="number" class="text-align-center" id="total_weight" min="1" max="100" name="total_weight" readonly></span></td>
                                            <td align="center"><input type="number" class="text-align-center" min="1" max="100" name="total_actual_score" disabled></td>
                                            <td align="center"></td>
                                        </tr>
                                    </table>

                                    {{-- <table class="table-bordered mt-1" width="100%">
                                        <tr>
                                            <td width="50%" align="center" style="background-color: rgb(240, 240, 240)"><strong>AREAS OF STRENGTH</strong></td>
                                            <td width="50%" align="center" style="background-color: rgb(240, 240, 240)"><strong>DEVELOPMENTAL NEEDS</strong></td>
                                        </tr>
                                        <tr>
                                            <td align="center"><textarea width="100%" class="responsive-input" name="areas_of_strength" cols="30" rows="7" placeholder="Input here" disabled></textarea></td>
                                            <td align="center"><textarea width="100%" class="responsive-input" name="developmental_needs" cols="30" rows="7" placeholder="Input here" disabled></textarea></td>
                                        </tr>
                                   
                                        <tr>
                                            <td width="50%" align="center" style="background-color: rgb(240, 240, 240)"><strong>AREAS FOR ENHANCEMENT</strong></td>
                                            <td width="50%" align="center" style="background-color: rgb(240, 240, 240)"><strong>TRAINING & DEVELOPMENTAL PLANS</strong></td>
                                        </tr>
                                        <tr>
                                            <td align="center"><textarea width="100%" class="responsive-input" name="areas_for_enhancement" cols="30" rows="7" placeholder="Input here" disabled></textarea></td>
                                            <td align="center"><textarea width="100%" class="responsive-input" name="training_and_development_plans" cols="30" rows="7" placeholder="Input here" disabled></textarea></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" align="center" style="background-color: rgb(240, 240, 240)"><strong>RATEE'S COMMENTS</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" align="center"><textarea style="max-width:2000px!important;" class="responsive-input" name="ratees_comments" cols="30" rows="7" placeholder="Input here" disabled></textarea></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" align="center" style="background-color: rgb(240, 240, 240)"><strong>SUMMARY OF RATER'S COMMENTS/RECOMMENDATIONS</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" align="center"><textarea style="max-width:2000px!important;" class="responsive-input" name="summary_ratees_comments_recommendation" cols="30" rows="7" placeholder="Input here" disabled></textarea></td>
                                        </tr>
                                    </table> --}}


                                    

                                </div>
                            </div>

                            <div class="text-center mt-5">
                                <input type="checkbox" id="myCheckbox"> <i>I agree to the set objectives and strategies discussed with me by my superior.</i> <br><br>
                                <button id="submitButton" type="submit" class="btn btn-primary" disabled>Submit Changes</button>
                            </div>
                            
                        </div>

                       
                   
                    
                </form>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    function updateSumTotalSummaryofRatingsWeight() {

        //BSC 

        const fp_strat_1_weight_1 = parseFloat(document.getElementById('financial_perspective[strat_1_weight_1]').value) || 0;
        const fp_strat_2_weight_1 = parseFloat(document.getElementById('financial_perspective[strat_2_weight_1]').value) || 0;
        const fp_strat_3_weight_1 = parseFloat(document.getElementById('financial_perspective[strat_3_weight_1]').value) || 0;
        const fp_strat_1_weight_2 = parseFloat(document.getElementById('financial_perspective[strat_1_weight_2]').value) || 0;
        const fp_strat_2_weight_2 = parseFloat(document.getElementById('financial_perspective[strat_2_weight_2]').value) || 0;
        const fp_strat_3_weight_2 = parseFloat(document.getElementById('financial_perspective[strat_3_weight_2]').value) || 0;
        const sum_fp = fp_strat_1_weight_1 + fp_strat_2_weight_1 + fp_strat_3_weight_1 + fp_strat_1_weight_2 + fp_strat_2_weight_2 + fp_strat_3_weight_2;

        const cf_strat_1_weight_1 = parseFloat(document.getElementById('customer_focus[strat_1_weight_1]').value) || 0;
        const cf_strat_2_weight_1 = parseFloat(document.getElementById('customer_focus[strat_2_weight_1]').value) || 0;
        const cf_strat_3_weight_1 = parseFloat(document.getElementById('customer_focus[strat_3_weight_1]').value) || 0;
        const cf_strat_1_weight_2 = parseFloat(document.getElementById('customer_focus[strat_1_weight_2]').value) || 0;
        const cf_strat_2_weight_2 = parseFloat(document.getElementById('customer_focus[strat_2_weight_2]').value) || 0;
        const cf_strat_3_weight_2 = parseFloat(document.getElementById('customer_focus[strat_3_weight_2]').value) || 0;
        const sum_cf = cf_strat_1_weight_1 + cf_strat_2_weight_1 + cf_strat_3_weight_1 + cf_strat_1_weight_2 + cf_strat_2_weight_2 + cf_strat_3_weight_2;

        const oe_strat_1_weight_1 = parseFloat(document.getElementById('operation_efficiency[strat_1_weight_1]').value) || 0;
        const oe_strat_2_weight_1 = parseFloat(document.getElementById('operation_efficiency[strat_2_weight_1]').value) || 0;
        const oe_strat_3_weight_1 = parseFloat(document.getElementById('operation_efficiency[strat_3_weight_1]').value) || 0;
        const oe_strat_1_weight_2 = parseFloat(document.getElementById('operation_efficiency[strat_1_weight_2]').value) || 0;
        const oe_strat_2_weight_2 = parseFloat(document.getElementById('operation_efficiency[strat_2_weight_2]').value) || 0;
        const oe_strat_3_weight_2 = parseFloat(document.getElementById('operation_efficiency[strat_3_weight_2]').value) || 0;
        const sum_oe = oe_strat_1_weight_1 + oe_strat_2_weight_1 + oe_strat_3_weight_1 + oe_strat_1_weight_2 + oe_strat_2_weight_2 + oe_strat_3_weight_2;

        const p_strat_1_weight_1 = parseFloat(document.getElementById('people[strat_1_weight_1]').value) || 0;
        const p_strat_2_weight_1 = parseFloat(document.getElementById('people[strat_2_weight_1]').value) || 0;
        const p_strat_3_weight_1 = parseFloat(document.getElementById('people[strat_3_weight_1]').value) || 0;
        const p_strat_1_weight_2 = parseFloat(document.getElementById('people[strat_1_weight_2]').value) || 0;
        const p_strat_2_weight_2 = parseFloat(document.getElementById('people[strat_2_weight_2]').value) || 0;
        const p_strat_3_weight_2 = parseFloat(document.getElementById('people[strat_3_weight_2]').value) || 0;
        const sum_op = p_strat_1_weight_1 + p_strat_2_weight_1 + p_strat_3_weight_1 + p_strat_1_weight_2 + p_strat_2_weight_2 + p_strat_3_weight_2;

        const bsc_weight = sum_fp + sum_cf + sum_oe + sum_op;


        const competency_weight = 10;

        const total_weight = bsc_weight + competency_weight;

        document.getElementById('bsc_weight').value = bsc_weight;
        document.getElementById('competency_weight').value = competency_weight;
        document.getElementById('total_weight').value = total_weight;
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Function to enable/disable the submit button based on checkbox state
        function updateSubmitButtonState() {
            var checkbox = document.getElementById("myCheckbox");
            var submitButton = document.getElementById("submitButton");

            // Enable the submit button if the checkbox is checked, otherwise disable it
            submitButton.disabled = !checkbox.checked;
        }

        // Attach the updateSubmitButtonState function to the checkbox change event
        document.getElementById("myCheckbox").addEventListener("change", updateSubmitButtonState);

        // Optionally, you can prevent the form submission if the button is disabled
        document.getElementById("myForm").addEventListener("submit", function(event) {
            var submitButton = document.getElementById("submitButton");

            if (submitButton.disabled) {
                // Prevent form submission if the button is disabled
                event.preventDefault();
            }
        });
    });
</script>

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

<style>

    .tabs-container {
      background-color: #fff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      width: 400px;
    }

    .tabs {
      display: flex;
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .tab {
      flex: 1;
      text-align: center;
      padding: 15px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .tab:hover {
      background-color: #f0f0f0;
    }

    .tab-content {
      display: none;
      padding: 20px;
    }

    .tab-content.active {
      display: block;
    }

    /* Additional Styles for Aesthetic Purposes */
    .tab {
      background-color: #3498db;
      color: #fff;
      border-bottom: 2px solid #2980b9;
    }

    .tab:hover {
      background-color: #2980b9;
    }

    .tab-content {
      background-color: #ecf0f1;
      border: 1px solid #bdc3c7;
      border-radius: 0 0 8px 8px;
    }
  </style>
