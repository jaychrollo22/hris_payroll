@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <form method='POST' action='{{url('manager-assessment-performance-plan-review/'.$ppr['id'])}}' onsubmit="btnDtr.disabled = true; return true;"  enctype="multipart/form-data">
                    @csrf      
                    <div class="card-body">
                            <h4 class="card-title">Take Self Assesment</h4>
                                <div class="table-responsive">
                                    <table class="table-bordered" width="100%">
                                        <tr>
                                            <td align="center">Calendar Year</td>
                                            <td align="center"> 
                                                <select class='form-control form-control-sm ' name='calendar_year' disabled>
                                                    <option value=''>--Select Calendar Year--</option>
                                                    @foreach ($performance_plan_period as $calendar_year)
                                                        <option value='{{ $calendar_year->period}}' @if($calendar_year->period == $ppr['calendar_year']) selected @endif>{{ $calendar_year->period }}</option>
                                                    @endforeach
                                                </select

                                            </td>
                                            <td align="center">Review Date</td>
                                            <td align="center"> <input type="date" class="form-control" name="review_date" value="{{ $ppr['review_date'] ? date('Y-m-d',strtotime($ppr['review_date'])) : "" }}" disabled> </td>
                                            <td align="center">Period</td>
                                            <td align="center" style="vertical-align: middle;">
                                                <label class="mt-2">
                                                    <input type="radio" name="period" value="MIDYEAR" {{ $ppr['period'] === 'MIDYEAR' ? 'checked' : '' }} required disabled>
                                                    MIDYEAR
                                                </label>
                                                <label class="mt-2">
                                                    <input type="radio" name="period" value="ANNUAL" {{ $ppr['period'] === 'ANNUAL' ? 'checked' : '' }} required disabled>
                                                    ANNUAL
                                                </label>
                                                <label class="mt-2">
                                                    <input type="radio" name="period" value="PROBATIONARY" {{ $ppr['period'] === 'PROBATIONARY' ? 'checked' : '' }} required disabled>
                                                    PROBATIONARY
                                                </label>
                                                <label class="mt-2" >
                                                    <input type="radio" name="period" value="SPECIAL" {{ $ppr['period'] === 'SPECIAL' ? 'checked' : '' }} required disabled>
                                                    SPECIAL
                                                </label>
                                            </td>
                                        </tr>
                                    </table>

                                    {{-- Page 1 --}}
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
                                                <th class="text-center" colspan="4" style="background-color:rgb(240, 240, 240)">REVIEW</th>
                                            </tr>
                                            <tr>

                                                <th class="text-center text-dark" style="background-color:rgb(240, 240, 240); ">
                                                    <h4>Metric</h4>
                                                </th>
                                                <th class="text-center text-dark" style="background-color:rgb(240, 240, 240)">
                                                    <h4>Target</h4>
                                                </th>
                                                <th class="text-center text-dark" style="background-color:rgb(240, 240, 240)">
                                                    <h4>Self Rating</h4>
                                                </th>
                                                <th class="text-center text-dark" style="background-color:rgb(240, 240, 240)">
                                                    <h4>SUPERIO'S RATING</h4>
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
                                                <td colspan="8"></td>
                                            </tr>
                                            
                                            <tr>
                                                <td style="width:200px!important;">Strat 1</td>
                                                <td style="text-align: center; width:300px!important;">
                                                    {{$ppr['financial_perspective']['strat_1_objective_1']}}
                                                    <input type="hidden" name="financial_perspective[strat_1_objective_1]" value="{{$ppr['financial_perspective']['strat_1_objective_1']}}" id="financial_perspective[strat_1_objective_1]" width="100%" class="responsive-input myinput" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{$ppr['financial_perspective']['strat_1_metric_1']}}
                                                    <input type="hidden" name="financial_perspective[strat_1_metric_1]" value="{{$ppr['financial_perspective']['strat_1_metric_1']}}"  id="financial_perspective[strat_1_metric_1]" placeholder="Metric" class="myinput" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['financial_perspective']['strat_1_target_1']) ? $ppr['financial_perspective']['strat_1_target_1'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="financial_perspective[strat_1_target_1]" value="{{ isset($ppr['financial_perspective']['strat_1_target_1']) ? $ppr['financial_perspective']['strat_1_target_1'] : ""}}" id="financial_perspective[strat_1_target_1]"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['financial_perspective']['strat_1_target_start_completion_1']) ? $ppr['financial_perspective']['strat_1_target_start_completion_1'] : ""}}
                                                    <input type="hidden" name="financial_perspective[strat_1_target_start_completion_1]" value="{{ isset($ppr['financial_perspective']['strat_1_target_start_completion_1']) ? $ppr['financial_perspective']['strat_1_target_start_completion_1'] : ""}}" placeholder="Start" id="financial_perspective[strat_1_target_start_completion_1]"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['financial_perspective']['strat_1_target_end_completion_1']) ? $ppr['financial_perspective']['strat_1_target_end_completion_1'] : ""}}
                                                    <input type="hidden" name="financial_perspective[strat_1_target_end_completion_1]" value="{{ isset($ppr['financial_perspective']['strat_1_target_end_completion_1']) ? $ppr['financial_perspective']['strat_1_target_end_completion_1'] : ""}}" placeholder="End" id="financial_perspective[strat_1_target_end_completion_1]"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['financial_perspective']['strat_1_weight_1']) ? $ppr['financial_perspective']['strat_1_weight_1'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="financial_perspective[strat_1_weight_1]" value="{{ isset($ppr['financial_perspective']['strat_1_weight_1']) ? $ppr['financial_perspective']['strat_1_weight_1'] : ""}}" id="financial_perspective[strat_1_weight_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['financial_perspective']['strat_1_objective_1'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="financial_perspective[strat_1_review_actual_1]" value="{{ isset($ppr['financial_perspective']['strat_1_review_actual_1']) ? $ppr['financial_perspective']['strat_1_review_actual_1'] : ""}}" id="financial_perspective[strat_1_review_actual_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()" readonly>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['financial_perspective']['strat_1_objective_1'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="financial_perspective[strat_1_review_actual_1_actual_grade]" value="{{ isset($ppr['financial_perspective']['strat_1_review_actual_1_actual_grade']) ? $ppr['financial_perspective']['strat_1_review_actual_1_actual_grade'] : ""}}" id="financial_perspective[strat_1_review_actual_1_actual_grade]" @if($enable_edit == false) disabled @endif>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['financial_perspective']['strat_1_objective_1'])
                                                        <input type="text" name="financial_perspective[strat_1_remarks_1]" value="{{ isset($ppr['financial_perspective']['strat_1_remarks_1']) ? $ppr['financial_perspective']['strat_1_remarks_1'] : ""}}" id="" width="100%" class="responsive-input myinput">
                                                    @endif
                                                </td>   
                                            </tr>
                                            <tr>
                                                <td>Strat 2</td>
                                                <td style="text-align: center; width:300px!important;">
                                                    {{$ppr['financial_perspective']['strat_2_objective_1']}}
                                                    <input type="hidden" name="financial_perspective[strat_2_objective_1]" value="{{$ppr['financial_perspective']['strat_2_objective_1']}}" id="" width="100%" class="responsive-input myinput" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{$ppr['financial_perspective']['strat_2_metric_1']}}
                                                    <input type="hidden" name="financial_perspective[strat_2_metric_1]" value="{{$ppr['financial_perspective']['strat_2_metric_1']}}" placeholder="Metric" class="myinput"  ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['financial_perspective']['strat_2_target_1']) ? $ppr['financial_perspective']['strat_2_target_1'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="financial_perspective[strat_2_target_1]" value="{{ isset($ppr['financial_perspective']['strat_2_target_1']) ? $ppr['financial_perspective']['strat_2_target_1'] : ""}}" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['financial_perspective']['strat_2_target_start_completion_1']) ? $ppr['financial_perspective']['strat_2_target_start_completion_1'] : ""}}
                                                    <input type="hidden" name="financial_perspective[strat_2_target_start_completion_1]" value="{{ isset($ppr['financial_perspective']['strat_2_target_start_completion_1']) ? $ppr['financial_perspective']['strat_2_target_start_completion_1'] : ""}}" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['financial_perspective']['strat_2_target_end_completion_1']) ? $ppr['financial_perspective']['strat_2_target_end_completion_1'] : ""}}
                                                    <input type="hidden" name="financial_perspective[strat_2_target_end_completion_1]" value="{{ isset($ppr['financial_perspective']['strat_2_target_end_completion_1']) ? $ppr['financial_perspective']['strat_2_target_end_completion_1'] : ""}}" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['financial_perspective']['strat_2_weight_1']) ? $ppr['financial_perspective']['strat_2_weight_1'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="financial_perspective[strat_2_weight_1]" value="{{ isset($ppr['financial_perspective']['strat_2_weight_1']) ? $ppr['financial_perspective']['strat_2_weight_1'] : ""}}" id="financial_perspective[strat_2_weight_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['financial_perspective']['strat_2_objective_1'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="financial_perspective[strat_2_review_actual_1]" value="{{ isset($ppr['financial_perspective']['strat_2_review_actual_1']) ? $ppr['financial_perspective']['strat_2_review_actual_1'] : ""}}" id="financial_perspective[strat_2_review_actual_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()" @if($enable_edit == false) disabled @endif>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['financial_perspective']['strat_2_objective_1'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="financial_perspective[strat_2_review_actual_1_actual_grade]" value="{{ isset($ppr['financial_perspective']['strat_2_review_actual_1_actual_grade']) ? $ppr['financial_perspective']['strat_2_review_actual_1_actual_grade'] : ""}}" id="financial_perspective[strat_2_review_actual_1_actual_grade]" readonly>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['financial_perspective']['strat_2_objective_1'])
                                                        <input type="text" name="financial_perspective[strat_2_remarks_1]" value="{{ isset($ppr['financial_perspective']['strat_2_remarks_1']) ? $ppr['financial_perspective']['strat_2_remarks_1'] : ""}}" id="" width="100%" class="responsive-input myinput">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Strat 3</td>
                                                <td style="text-align: center; width:300px!important;">
                                                    {{$ppr['financial_perspective']['strat_3_objective_1']}}
                                                    <input type="hidden" name="financial_perspective[strat_3_objective_1]" value="{{$ppr['financial_perspective']['strat_3_objective_1']}}" id="" width="100%" class="responsive-input myinput" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{$ppr['financial_perspective']['strat_3_metric_1']}}
                                                    <input type="hidden" name="financial_perspective[strat_3_metric_1]" value="{{$ppr['financial_perspective']['strat_3_metric_1']}}" placeholder="Metric" class="myinput"  ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['financial_perspective']['strat_3_target_1']) ? $ppr['financial_perspective']['strat_3_target_1'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="financial_perspective[strat_3_target_1]" value="{{ isset($ppr['financial_perspective']['strat_3_target_1']) ? $ppr['financial_perspective']['strat_3_target_1'] : ""}}" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['financial_perspective']['strat_3_target_start_completion_1']) ? $ppr['financial_perspective']['strat_3_target_start_completion_1'] : ""}}
                                                    <input type="hidden" width="100%" name="financial_perspective[strat_3_target_start_completion_1]" value="{{ isset($ppr['financial_perspective']['strat_3_target_start_completion_1']) ? $ppr['financial_perspective']['strat_3_target_start_completion_1'] : ""}}" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['financial_perspective']['strat_3_target_end_completion_1']) ? $ppr['financial_perspective']['strat_3_target_end_completion_1'] : ""}}
                                                    <input type="hidden" width="100%" name="financial_perspective[strat_3_target_end_completion_1]" value="{{ isset($ppr['financial_perspective']['strat_3_target_end_completion_1']) ? $ppr['financial_perspective']['strat_3_target_end_completion_1'] : ""}}" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['financial_perspective']['strat_3_weight_1']) ? $ppr['financial_perspective']['strat_3_weight_1'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="financial_perspective[strat_3_weight_1]" value="{{ isset($ppr['financial_perspective']['strat_3_weight_1']) ? $ppr['financial_perspective']['strat_3_weight_1'] : ""}}" id="financial_perspective[strat_3_weight_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['financial_perspective']['strat_3_objective_1'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="financial_perspective[strat_3_review_actual_1]" value="{{ isset($ppr['financial_perspective']['strat_3_review_actual_1']) ? $ppr['financial_perspective']['strat_3_review_actual_1'] : ""}}" id="financial_perspective[strat_3_review_actual_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()" @if($enable_edit == false) disabled @endif>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['financial_perspective']['strat_3_objective_1'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="financial_perspective[strat_3_review_actual_1_actual_grade]" value="{{ isset($ppr['financial_perspective']['strat_3_review_actual_1_actual_grade']) ? $ppr['financial_perspective']['strat_3_review_actual_1_actual_grade'] : ""}}" id="financial_perspective[strat_3_review_actual_1_actual_grade]" readonly>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['financial_perspective']['strat_3_objective_1'])
                                                        <input type="text" name="financial_perspective[strat_1_remarks_1]" value="{{ isset($ppr['financial_perspective']['strat_3_remarks_1']) ? $ppr['financial_perspective']['strat_3_remarks_1'] : ""}}" id="" width="100%" class="responsive-input myinput">
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" class="text-center">Objective 2. Profit</td>
                                                <td colspan="8"></td>
                                            </tr>
                                            <tr>
                                                <td style="width:200px!important;">Strat 1</td>
                                                <td style="text-align: center; width:300px!important;">
                                                    {{$ppr['financial_perspective']['strat_1_objective_2']}}
                                                    <input type="hidden" name="financial_perspective[strat_1_objective_2]" value="{{$ppr['financial_perspective']['strat_1_objective_2']}}" id="" width="100%" class="responsive-input myinput" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{$ppr['financial_perspective']['strat_1_metric_2']}}
                                                    <input type="hidden" name="financial_perspective[strat_1_metric_2]" value="{{$ppr['financial_perspective']['strat_1_metric_2']}}" placeholder="Metric" class="myinput" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['financial_perspective']['strat_1_target_2']) ? $ppr['financial_perspective']['strat_1_target_2'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="financial_perspective[strat_1_target_2]" value="{{ isset($ppr['financial_perspective']['strat_1_target_2']) ? $ppr['financial_perspective']['strat_1_target_2'] : ""}}" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['financial_perspective']['strat_1_target_start_completion_2']) ? $ppr['financial_perspective']['strat_1_target_start_completion_2'] : ""}}
                                                    <input type="hidden" name="financial_perspective[strat_1_target_start_completion_2]" value="{{ isset($ppr['financial_perspective']['strat_1_target_start_completion_2']) ? $ppr['financial_perspective']['strat_1_target_start_completion_2'] : ""}}" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['financial_perspective']['strat_1_target_end_completion_2']) ? $ppr['financial_perspective']['strat_1_target_end_completion_2'] : ""}}
                                                    <input type="hidden" name="financial_perspective[strat_1_target_end_completion_2]" value="{{ isset($ppr['financial_perspective']['strat_1_target_end_completion_2']) ? $ppr['financial_perspective']['strat_1_target_end_completion_2'] : ""}}" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['financial_perspective']['strat_1_weight_2']) ? $ppr['financial_perspective']['strat_1_weight_2'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="financial_perspective[strat_1_weight_2]" value="{{ isset($ppr['financial_perspective']['strat_1_weight_2']) ? $ppr['financial_perspective']['strat_1_weight_2'] : ""}}" id="financial_perspective[strat_1_weight_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>

                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['financial_perspective']['strat_1_objective_2'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="financial_perspective[strat_1_review_actual_2]" value="{{ isset($ppr['financial_perspective']['strat_1_review_actual_2']) ? $ppr['financial_perspective']['strat_1_review_actual_2'] : ""}}" id="financial_perspective[strat_1_review_actual_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()" @if($enable_edit == false) disabled @endif>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['financial_perspective']['strat_1_objective_2'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="financial_perspective[strat_1_review_actual_2_actual_grade]" value="{{ isset($ppr['financial_perspective']['strat_1_review_actual_2_actual_grade']) ? $ppr['financial_perspective']['strat_1_review_actual_2_actual_grade'] : ""}}" id="financial_perspective[strat_1_review_actual_2_actual_grade]" readonly>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['financial_perspective']['strat_1_objective_2'])
                                                        <input type="text" name="financial_perspective[strat_1_remarks_2]" value="{{ isset($ppr['financial_perspective']['strat_1_remarks_2']) ? $ppr['financial_perspective']['strat_1_remarks_2'] : ""}}" id="" width="100%" class="responsive-input myinput">
                                                    @endif
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>Strat 2</td>
                                                <td style="text-align: center; width:300px!important;">
                                                    {{$ppr['financial_perspective']['strat_2_objective_2']}}
                                                    <input type="hidden" name="financial_perspective[strat_2_objective_2]" value="{{$ppr['financial_perspective']['strat_2_objective_2']}}" id="" width="100%" class="responsive-input myinput" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{$ppr['financial_perspective']['strat_2_metric_2']}}
                                                    <input type="hidden" name="financial_perspective[strat_2_metric_2]" value="{{$ppr['financial_perspective']['strat_2_metric_2']}}" placeholder="Metric" class="myinput"  ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['financial_perspective']['strat_2_target_2']) ? $ppr['financial_perspective']['strat_2_target_2'] : "" }}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="financial_perspective[strat_2_target_2]" value="{{ isset($ppr['financial_perspective']['strat_2_target_2']) ? $ppr['financial_perspective']['strat_2_target_2'] : "" }}" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{isset($ppr['financial_perspective']['strat_2_target_start_completion_2']) ? $ppr['financial_perspective']['strat_2_target_start_completion_2'] : ""}}
                                                    <input type="hidden" name="financial_perspective[strat_2_target_start_completion_2]" value="{{isset($ppr['financial_perspective']['strat_2_target_start_completion_2']) ? $ppr['financial_perspective']['strat_2_target_start_completion_2'] : ""}}" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['financial_perspective']['strat_2_target_end_completion_2']) ? $ppr['financial_perspective']['strat_2_target_end_completion_2'] : ""}}
                                                    <input type="hidden" name="financial_perspective[strat_2_target_end_completion_2]" value="{{ isset($ppr['financial_perspective']['strat_2_target_end_completion_2']) ? $ppr['financial_perspective']['strat_2_target_end_completion_2'] : ""}}" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['financial_perspective']['strat_2_weight_2']) ? $ppr['financial_perspective']['strat_2_weight_2'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="financial_perspective[strat_2_weight_2]" value="{{ isset($ppr['financial_perspective']['strat_2_weight_2']) ? $ppr['financial_perspective']['strat_2_weight_2'] : ""}}" id="financial_perspective[strat_2_weight_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['financial_perspective']['strat_2_objective_2'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="financial_perspective[strat_2_review_actual_2]" value="{{ isset($ppr['financial_perspective']['strat_2_review_actual_2']) ? $ppr['financial_perspective']['strat_2_review_actual_2'] : ""}}" id="financial_perspective[strat_2_review_actual_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()" @if($enable_edit == false) disabled @endif>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['financial_perspective']['strat_2_objective_2'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="financial_perspective[strat_2_review_actual_2_actual_grade]" value="{{ isset($ppr['financial_perspective']['strat_2_review_actual_2_actual_grade']) ? $ppr['financial_perspective']['strat_2_review_actual_2_actual_grade'] : ""}}" id="financial_perspective[strat_2_review_actual_2_actual_grade]" readonly>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['financial_perspective']['strat_2_objective_2'])
                                                        <input type="text" name="financial_perspective[strat_2_remarks_2]" value="{{ isset($ppr['financial_perspective']['strat_2_remarks_2']) ? $ppr['financial_perspective']['strat_2_remarks_2'] : ""}}" id="" width="100%" class="responsive-input myinput">
                                                    @endif
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>Strat 3</td>
                                                <td style="text-align: center; width:300px!important;">
                                                    {{$ppr['financial_perspective']['strat_3_objective_2']}}
                                                    <input type="hidden" name="financial_perspective[strat_3_objective_2]" value="{{$ppr['financial_perspective']['strat_3_objective_2']}}" id="" width="100%" class="responsive-input myinput" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{$ppr['financial_perspective']['strat_3_metric_2']}}
                                                    <input type="hidden" name="financial_perspective[strat_3_metric_2]" value="{{$ppr['financial_perspective']['strat_3_metric_2']}}" placeholder="Metric" class="myinput"  ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['financial_perspective']['strat_3_target_2']) ? $ppr['financial_perspective']['strat_3_target_2'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="financial_perspective[strat_3_target_2]" value="{{ isset($ppr['financial_perspective']['strat_3_target_2']) ? $ppr['financial_perspective']['strat_3_target_2'] : ""}}" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['financial_perspective']['strat_3_target_start_completion_2']) ? $ppr['financial_perspective']['strat_3_target_start_completion_2'] : ""}}
                                                    <input type="hidden" width="100%" name="financial_perspective[strat_3_target_start_completion_2]" value="{{ isset($ppr['financial_perspective']['strat_3_target_start_completion_2']) ? $ppr['financial_perspective']['strat_3_target_start_completion_2'] : ""}}" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['financial_perspective']['strat_3_target_end_completion_2']) ? $ppr['financial_perspective']['strat_3_target_end_completion_2'] : ""}}
                                                    <input type="hidden" width="100%" name="financial_perspective[strat_3_target_end_completion_2]" value="{{ isset($ppr['financial_perspective']['strat_3_target_end_completion_2']) ? $ppr['financial_perspective']['strat_3_target_end_completion_2'] : ""}}" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['financial_perspective']['strat_3_weight_2']) ? $ppr['financial_perspective']['strat_3_weight_2'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="financial_perspective[strat_3_weight_2]" value="{{ isset($ppr['financial_perspective']['strat_3_weight_2']) ? $ppr['financial_perspective']['strat_3_weight_2'] : ""}}" id="financial_perspective[strat_3_weight_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['financial_perspective']['strat_3_objective_2'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="financial_perspective[strat_3_review_actual_2]" value="{{ isset($ppr['financial_perspective']['strat_3_review_actual_2']) ? $ppr['financial_perspective']['strat_3_review_actual_2'] : ""}}" id="financial_perspective[strat_3_review_actual_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()" @if($enable_edit == false) disabled @endif>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['financial_perspective']['strat_3_objective_2'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="financial_perspective[strat_3_review_actual_2_actual_grade]" value="{{ isset($ppr['financial_perspective']['strat_3_review_actual_2_actual_grade']) ? $ppr['financial_perspective']['strat_3_review_actual_2_actual_grade'] : ""}}" id="financial_perspective[strat_3_review_actual_2_actual_grade]" readonly>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['financial_perspective']['strat_3_objective_2'])
                                                        <input type="text" name="financial_perspective[strat_3_remarks_2]" value="{{ isset($ppr['financial_perspective']['strat_3_remarks_2']) ? $ppr['financial_perspective']['strat_3_remarks_2'] : ""}}" id="" width="100%" class="responsive-input myinput">
                                                    @endif
                                                </td>
                                
                                            </tr>

                                            <tr>
                                                <td colspan="11">&nbsp;</td>
                                            </tr>

                                            {{-- 2. Customer Focus --}}
                                            <tr>
                                                <td rowspan="9" class="text-center">2. Customer Focus</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-center">Objective 1</td>
                                                <td colspan="8"></td>
                                            </tr>
                                            
                                            <tr>
                                                <td style="width:200px!important;">Strat 1</td>
                                                <td style="text-align: center; width:300px!important;">
                                                    {{$ppr['customer_focus']['strat_1_objective_1']}}
                                                    <input type="hidden" name="customer_focus[strat_1_objective_1]" value="{{$ppr['customer_focus']['strat_1_objective_1']}}" id="customer_focus[strat_1_objective_1]" width="100%" class="responsive-input myinput" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{$ppr['customer_focus']['strat_1_metric_1']}}
                                                    <input type="hidden" name="customer_focus[strat_1_metric_1]" value="{{$ppr['customer_focus']['strat_1_metric_1']}}"  id="customer_focus[strat_1_metric_1]" placeholder="Metric" class="myinput" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['customer_focus']['strat_1_target_1']) ? $ppr['customer_focus']['strat_1_target_1'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="customer_focus[strat_1_target_1]" value="{{ isset($ppr['customer_focus']['strat_1_target_1']) ? $ppr['customer_focus']['strat_1_target_1'] : ""}}" id="customer_focus[strat_1_target_1]"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['customer_focus']['strat_1_target_start_completion_1']) ? $ppr['customer_focus']['strat_1_target_start_completion_1'] : ""}}
                                                    <input type="hidden" name="customer_focus[strat_1_target_start_completion_1]" value="{{ isset($ppr['customer_focus']['strat_1_target_start_completion_1']) ? $ppr['customer_focus']['strat_1_target_start_completion_1'] : ""}}" placeholder="Start" id="customer_focus[strat_1_target_start_completion_1]"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['customer_focus']['strat_1_target_end_completion_1']) ? $ppr['customer_focus']['strat_1_target_end_completion_1'] : ""}}
                                                    <input type="hidden" name="customer_focus[strat_1_target_end_completion_1]" value="{{ isset($ppr['customer_focus']['strat_1_target_end_completion_1']) ? $ppr['customer_focus']['strat_1_target_end_completion_1'] : ""}}" placeholder="End" id="customer_focus[strat_1_target_end_completion_1]"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['customer_focus']['strat_1_weight_1']) ? $ppr['customer_focus']['strat_1_weight_1'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="customer_focus[strat_1_weight_1]" value="{{ isset($ppr['customer_focus']['strat_1_weight_1']) ? $ppr['customer_focus']['strat_1_weight_1'] : ""}}" id="customer_focus[strat_1_weight_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['customer_focus']['strat_1_objective_1'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="customer_focus[strat_1_review_actual_1]" value="{{ isset($ppr['customer_focus']['strat_1_review_actual_1']) ? $ppr['customer_focus']['strat_1_review_actual_1'] : ""}}" id="customer_focus[strat_1_review_actual_1]" onkeyup="computeActualGradeCustomerFocus()" @if($enable_edit == false) disabled @endif>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['customer_focus']['strat_1_objective_1'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="customer_focus[strat_1_review_actual_1_actual_grade]" value="{{ isset($ppr['customer_focus']['strat_1_review_actual_1_actual_grade']) ? $ppr['customer_focus']['strat_1_review_actual_1_actual_grade'] : ""}}" id="customer_focus[strat_1_review_actual_1_actual_grade]" readonly>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['customer_focus']['strat_1_objective_1'])
                                                        <input type="text" name="customer_focus[strat_1_remarks_1]" value="{{ isset($ppr['customer_focus']['strat_1_remarks_1']) ? $ppr['customer_focus']['strat_1_remarks_1'] : ""}}" id="" width="100%" class="responsive-input myinput">
                                                    @endif
                                                </td>   
                                            </tr>
                                            <tr>
                                                <td>Strat 2</td>
                                                <td style="text-align: center; width:300px!important;">
                                                    {{$ppr['customer_focus']['strat_2_objective_1']}}
                                                    <input type="hidden" name="customer_focus[strat_2_objective_1]" value="{{$ppr['customer_focus']['strat_2_objective_1']}}" id="" width="100%" class="responsive-input myinput" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{$ppr['customer_focus']['strat_2_metric_1']}}
                                                    <input type="hidden" name="customer_focus[strat_2_metric_1]" value="{{$ppr['customer_focus']['strat_2_metric_1']}}" placeholder="Metric" class="myinput"  ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['customer_focus']['strat_2_target_1']) ? $ppr['customer_focus']['strat_2_target_1'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="customer_focus[strat_2_target_1]" value="{{ isset($ppr['customer_focus']['strat_2_target_1']) ? $ppr['customer_focus']['strat_2_target_1'] : ""}}" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['customer_focus']['strat_2_target_start_completion_1']) ? $ppr['customer_focus']['strat_2_target_start_completion_1'] : ""}}
                                                    <input type="hidden" name="customer_focus[strat_2_target_start_completion_1]" value="{{ isset($ppr['customer_focus']['strat_2_target_start_completion_1']) ? $ppr['customer_focus']['strat_2_target_start_completion_1'] : ""}}" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['customer_focus']['strat_2_target_end_completion_1']) ? $ppr['customer_focus']['strat_2_target_end_completion_1'] : ""}}
                                                    <input type="hidden" name="customer_focus[strat_2_target_end_completion_1]" value="{{ isset($ppr['customer_focus']['strat_2_target_end_completion_1']) ? $ppr['customer_focus']['strat_2_target_end_completion_1'] : ""}}" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['customer_focus']['strat_2_weight_1']) ? $ppr['customer_focus']['strat_2_weight_1'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="customer_focus[strat_2_weight_1]" value="{{ isset($ppr['customer_focus']['strat_2_weight_1']) ? $ppr['customer_focus']['strat_2_weight_1'] : ""}}" id="customer_focus[strat_2_weight_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['customer_focus']['strat_2_objective_1'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="customer_focus[strat_2_review_actual_1]" value="{{ isset($ppr['customer_focus']['strat_2_review_actual_1']) ? $ppr['customer_focus']['strat_2_review_actual_1'] : ""}}" id="customer_focus[strat_2_review_actual_1]" onkeyup="computeActualGradeCustomerFocus()" @if($enable_edit == false) disabled @endif>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['customer_focus']['strat_2_objective_1'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="customer_focus[strat_2_review_actual_1_actual_grade]" value="{{ isset($ppr['customer_focus']['strat_2_review_actual_1_actual_grade']) ? $ppr['customer_focus']['strat_2_review_actual_1_actual_grade'] : ""}}" id="customer_focus[strat_2_review_actual_1_actual_grade]" readonly>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['customer_focus']['strat_2_objective_1'])
                                                        <input type="text" name="customer_focus[strat_2_remarks_1]" value="{{ isset($ppr['customer_focus']['strat_2_remarks_1']) ? $ppr['customer_focus']['strat_2_remarks_1'] : ""}}" id="" width="100%" class="responsive-input myinput">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Strat 3</td>
                                                <td style="text-align: center; width:300px!important;">
                                                    {{$ppr['customer_focus']['strat_3_objective_1']}}
                                                    <input type="hidden" name="customer_focus[strat_3_objective_1]" value="{{$ppr['customer_focus']['strat_3_objective_1']}}" id="" width="100%" class="responsive-input myinput" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{$ppr['customer_focus']['strat_3_metric_1']}}
                                                    <input type="hidden" name="customer_focus[strat_3_metric_1]" value="{{$ppr['customer_focus']['strat_3_metric_1']}}" placeholder="Metric" class="myinput"  ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['customer_focus']['strat_3_target_1']) ? $ppr['customer_focus']['strat_3_target_1'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="customer_focus[strat_3_target_1]" value="{{ isset($ppr['customer_focus']['strat_3_target_1']) ? $ppr['customer_focus']['strat_3_target_1'] : ""}}" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['customer_focus']['strat_3_target_start_completion_1']) ? $ppr['customer_focus']['strat_3_target_start_completion_1'] : ""}}
                                                    <input type="hidden" width="100%" name="customer_focus[strat_3_target_start_completion_1]" value="{{ isset($ppr['customer_focus']['strat_3_target_start_completion_1']) ? $ppr['customer_focus']['strat_3_target_start_completion_1'] : ""}}" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['customer_focus']['strat_3_target_end_completion_1']) ? $ppr['customer_focus']['strat_3_target_end_completion_1'] : ""}}
                                                    <input type="hidden" width="100%" name="customer_focus[strat_3_target_end_completion_1]" value="{{ isset($ppr['customer_focus']['strat_3_target_end_completion_1']) ? $ppr['customer_focus']['strat_3_target_end_completion_1'] : ""}}" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['customer_focus']['strat_3_weight_1']) ? $ppr['customer_focus']['strat_3_weight_1'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="customer_focus[strat_3_weight_1]" value="{{ isset($ppr['customer_focus']['strat_3_weight_1']) ? $ppr['customer_focus']['strat_3_weight_1'] : ""}}" id="customer_focus[strat_3_weight_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['customer_focus']['strat_3_objective_1'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="customer_focus[strat_3_review_actual_1]" value="{{ isset($ppr['customer_focus']['strat_3_review_actual_1']) ? $ppr['customer_focus']['strat_3_review_actual_1'] : ""}}" id="customer_focus[strat_3_review_actual_1]" onkeyup="computeActualGradeCustomerFocus()" @if($enable_edit == false) disabled @endif>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['customer_focus']['strat_3_objective_1'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="customer_focus[strat_3_review_actual_1_actual_grade]" value="{{ isset($ppr['customer_focus']['strat_3_review_actual_1_actual_grade']) ? $ppr['customer_focus']['strat_3_review_actual_1_actual_grade'] : ""}}" id="customer_focus[strat_3_review_actual_1_actual_grade]" readonly>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['customer_focus']['strat_3_objective_1'])
                                                        <input type="text" name="customer_focus[strat_1_remarks_1]" value="{{ isset($ppr['customer_focus']['strat_3_remarks_1']) ? $ppr['customer_focus']['strat_3_remarks_1'] : ""}}" id="" width="100%" class="responsive-input myinput">
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" class="text-center">Objective 2</td>
                                                <td colspan="8"></td>
                                            </tr>
                                            <tr>
                                                <td style="width:200px!important;">Strat 1</td>
                                                <td style="text-align: center; width:300px!important;">
                                                    {{$ppr['customer_focus']['strat_1_objective_2']}}
                                                    <input type="hidden" name="customer_focus[strat_1_objective_2]" value="{{$ppr['customer_focus']['strat_1_objective_2']}}" id="" width="100%" class="responsive-input myinput" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{$ppr['customer_focus']['strat_1_metric_2']}}
                                                    <input type="hidden" name="customer_focus[strat_1_metric_2]" value="{{$ppr['customer_focus']['strat_1_metric_2']}}" placeholder="Metric" class="myinput" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['customer_focus']['strat_1_target_2']) ? $ppr['customer_focus']['strat_1_target_2'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="customer_focus[strat_1_target_2]" value="{{ isset($ppr['customer_focus']['strat_1_target_2']) ? $ppr['customer_focus']['strat_1_target_2'] : ""}}" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['customer_focus']['strat_1_target_start_completion_2']) ? $ppr['customer_focus']['strat_1_target_start_completion_2'] : ""}}
                                                    <input type="hidden" name="customer_focus[strat_1_target_start_completion_2]" value="{{ isset($ppr['customer_focus']['strat_1_target_start_completion_2']) ? $ppr['customer_focus']['strat_1_target_start_completion_2'] : ""}}" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['customer_focus']['strat_1_target_end_completion_2']) ? $ppr['customer_focus']['strat_1_target_end_completion_2'] : ""}}
                                                    <input type="hidden" name="customer_focus[strat_1_target_end_completion_2]" value="{{ isset($ppr['customer_focus']['strat_1_target_end_completion_2']) ? $ppr['customer_focus']['strat_1_target_end_completion_2'] : ""}}" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['customer_focus']['strat_1_weight_2']) ? $ppr['customer_focus']['strat_1_weight_2'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="customer_focus[strat_1_weight_2]" value="{{ isset($ppr['customer_focus']['strat_1_weight_2']) ? $ppr['customer_focus']['strat_1_weight_2'] : ""}}" id="customer_focus[strat_1_weight_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>

                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['customer_focus']['strat_1_objective_2'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="customer_focus[strat_1_review_actual_2]" value="{{ isset($ppr['customer_focus']['strat_1_review_actual_2']) ? $ppr['customer_focus']['strat_1_review_actual_2'] : ""}}" id="customer_focus[strat_1_review_actual_2]" onkeyup="computeActualGradeCustomerFocus()" @if($enable_edit == false) disabled @endif>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['customer_focus']['strat_1_objective_2'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="customer_focus[strat_1_review_actual_2_actual_grade]" value="{{ isset($ppr['customer_focus']['strat_1_review_actual_2_actual_grade']) ? $ppr['customer_focus']['strat_1_review_actual_2_actual_grade'] : ""}}" id="customer_focus[strat_1_review_actual_2_actual_grade]" readonly>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['customer_focus']['strat_1_objective_2'])
                                                        <input type="text" name="customer_focus[strat_1_remarks_2]" value="{{ isset($ppr['customer_focus']['strat_1_remarks_2']) ? $ppr['customer_focus']['strat_1_remarks_2'] : ""}}" id="" width="100%" class="responsive-input myinput">
                                                    @endif
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>Strat 2</td>
                                                <td style="text-align: center; width:300px!important;">
                                                    {{$ppr['customer_focus']['strat_2_objective_2']}}
                                                    <input type="hidden" name="customer_focus[strat_2_objective_2]" value="{{$ppr['customer_focus']['strat_2_objective_2']}}" id="" width="100%" class="responsive-input myinput" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{$ppr['customer_focus']['strat_2_metric_2']}}
                                                    <input type="hidden" name="customer_focus[strat_2_metric_2]" value="{{$ppr['customer_focus']['strat_2_metric_2']}}" placeholder="Metric" class="myinput"  ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['customer_focus']['strat_2_target_2']) ? $ppr['customer_focus']['strat_2_target_2'] : "" }}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="customer_focus[strat_2_target_2]" value="{{ isset($ppr['customer_focus']['strat_2_target_2']) ? $ppr['customer_focus']['strat_2_target_2'] : "" }}" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{isset($ppr['customer_focus']['strat_2_target_start_completion_2']) ? $ppr['customer_focus']['strat_2_target_start_completion_2'] : ""}}
                                                    <input type="hidden" name="customer_focus[strat_2_target_start_completion_2]" value="{{isset($ppr['customer_focus']['strat_2_target_start_completion_2']) ? $ppr['customer_focus']['strat_2_target_start_completion_2'] : ""}}" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['customer_focus']['strat_2_target_end_completion_2']) ? $ppr['customer_focus']['strat_2_target_end_completion_2'] : ""}}
                                                    <input type="hidden" name="customer_focus[strat_2_target_end_completion_2]" value="{{ isset($ppr['customer_focus']['strat_2_target_end_completion_2']) ? $ppr['customer_focus']['strat_2_target_end_completion_2'] : ""}}" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['customer_focus']['strat_2_weight_2']) ? $ppr['customer_focus']['strat_2_weight_2'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="customer_focus[strat_2_weight_2]" value="{{ isset($ppr['customer_focus']['strat_2_weight_2']) ? $ppr['customer_focus']['strat_2_weight_2'] : ""}}" id="customer_focus[strat_2_weight_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['customer_focus']['strat_2_objective_2'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="customer_focus[strat_2_review_actual_2]" value="{{ isset($ppr['customer_focus']['strat_2_review_actual_2']) ? $ppr['customer_focus']['strat_2_review_actual_2'] : ""}}" id="customer_focus[strat_2_review_actual_2]" onkeyup="computeActualGradeCustomerFocus()" @if($enable_edit == false) disabled @endif>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['customer_focus']['strat_2_objective_2'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="customer_focus[strat_2_review_actual_2_actual_grade]" value="{{ isset($ppr['customer_focus']['strat_2_review_actual_2_actual_grade']) ? $ppr['customer_focus']['strat_2_review_actual_2_actual_grade'] : ""}}" id="customer_focus[strat_2_review_actual_2_actual_grade]" readonly>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['customer_focus']['strat_2_objective_2'])
                                                        <input type="text" name="customer_focus[strat_2_remarks_2]" value="{{ isset($ppr['customer_focus']['strat_2_remarks_2']) ? $ppr['customer_focus']['strat_2_remarks_2'] : ""}}" id="" width="100%" class="responsive-input myinput">
                                                    @endif
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>Strat 3</td>
                                                <td style="text-align: center; width:300px!important;">
                                                    {{$ppr['customer_focus']['strat_3_objective_2']}}
                                                    <input type="hidden" name="customer_focus[strat_3_objective_2]" value="{{$ppr['customer_focus']['strat_3_objective_2']}}" id="" width="100%" class="responsive-input myinput" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{$ppr['customer_focus']['strat_3_metric_2']}}
                                                    <input type="hidden" name="customer_focus[strat_3_metric_2]" value="{{$ppr['customer_focus']['strat_3_metric_2']}}" placeholder="Metric" class="myinput"  ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['customer_focus']['strat_3_target_2']) ? $ppr['customer_focus']['strat_3_target_2'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="customer_focus[strat_3_target_2]" value="{{ isset($ppr['customer_focus']['strat_3_target_2']) ? $ppr['customer_focus']['strat_3_target_2'] : ""}}" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['customer_focus']['strat_3_target_start_completion_2']) ? $ppr['customer_focus']['strat_3_target_start_completion_2'] : ""}}
                                                    <input type="hidden" width="100%" name="customer_focus[strat_3_target_start_completion_2]" value="{{ isset($ppr['customer_focus']['strat_3_target_start_completion_2']) ? $ppr['customer_focus']['strat_3_target_start_completion_2'] : ""}}" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['customer_focus']['strat_3_target_end_completion_2']) ? $ppr['customer_focus']['strat_3_target_end_completion_2'] : ""}}
                                                    <input type="hidden" width="100%" name="customer_focus[strat_3_target_end_completion_2]" value="{{ isset($ppr['customer_focus']['strat_3_target_end_completion_2']) ? $ppr['customer_focus']['strat_3_target_end_completion_2'] : ""}}" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['customer_focus']['strat_3_weight_2']) ? $ppr['customer_focus']['strat_3_weight_2'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="customer_focus[strat_3_weight_2]" value="{{ isset($ppr['customer_focus']['strat_3_weight_2']) ? $ppr['customer_focus']['strat_3_weight_2'] : ""}}" id="customer_focus[strat_3_weight_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['customer_focus']['strat_3_objective_2'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="customer_focus[strat_3_review_actual_2]" value="{{ isset($ppr['customer_focus']['strat_3_review_actual_2']) ? $ppr['customer_focus']['strat_3_review_actual_2'] : ""}}" id="customer_focus[strat_3_review_actual_2]" onkeyup="computeActualGradeCustomerFocus()" @if($enable_edit == false) disabled @endif>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['customer_focus']['strat_3_objective_2'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="customer_focus[strat_3_review_actual_2_actual_grade]" value="{{ isset($ppr['customer_focus']['strat_3_review_actual_2_actual_grade']) ? $ppr['customer_focus']['strat_3_review_actual_2_actual_grade'] : ""}}" id="customer_focus[strat_3_review_actual_2_actual_grade]" readonly>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['customer_focus']['strat_3_objective_2'])
                                                        <input type="text" name="customer_focus[strat_3_remarks_2]" value="{{ isset($ppr['customer_focus']['strat_3_remarks_2']) ? $ppr['customer_focus']['strat_3_remarks_2'] : ""}}" id="" width="100%" class="responsive-input myinput">
                                                    @endif
                                                </td>
                                
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
                                                <td style="text-align: center; width:300px!important;">
                                                    {{$ppr['operation_efficiency']['strat_1_objective_1']}}
                                                    <input type="hidden" name="operation_efficiency[strat_1_objective_1]" value="{{$ppr['operation_efficiency']['strat_1_objective_1']}}" id="operation_efficiency[strat_1_objective_1]" width="100%" class="responsive-input myinput" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{$ppr['operation_efficiency']['strat_1_metric_1']}}
                                                    <input type="hidden" name="operation_efficiency[strat_1_metric_1]" value="{{$ppr['operation_efficiency']['strat_1_metric_1']}}"  id="operation_efficiency[strat_1_metric_1]" placeholder="Metric" class="myinput" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['operation_efficiency']['strat_1_target_1']) ? $ppr['operation_efficiency']['strat_1_target_1'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="operation_efficiency[strat_1_target_1]" value="{{ isset($ppr['operation_efficiency']['strat_1_target_1']) ? $ppr['operation_efficiency']['strat_1_target_1'] : ""}}" id="operation_efficiency[strat_1_target_1]"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['operation_efficiency']['strat_1_target_start_completion_1']) ? $ppr['operation_efficiency']['strat_1_target_start_completion_1'] : ""}}
                                                    <input type="hidden" name="operation_efficiency[strat_1_target_start_completion_1]" value="{{ isset($ppr['operation_efficiency']['strat_1_target_start_completion_1']) ? $ppr['operation_efficiency']['strat_1_target_start_completion_1'] : ""}}" placeholder="Start" id="operation_efficiency[strat_1_target_start_completion_1]"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['operation_efficiency']['strat_1_target_end_completion_1']) ? $ppr['operation_efficiency']['strat_1_target_end_completion_1'] : ""}}
                                                    <input type="hidden" name="operation_efficiency[strat_1_target_end_completion_1]" value="{{ isset($ppr['operation_efficiency']['strat_1_target_end_completion_1']) ? $ppr['operation_efficiency']['strat_1_target_end_completion_1'] : ""}}" placeholder="End" id="operation_efficiency[strat_1_target_end_completion_1]"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['operation_efficiency']['strat_1_weight_1']) ? $ppr['operation_efficiency']['strat_1_weight_1'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="operation_efficiency[strat_1_weight_1]" value="{{ isset($ppr['operation_efficiency']['strat_1_weight_1']) ? $ppr['operation_efficiency']['strat_1_weight_1'] : ""}}" id="operation_efficiency[strat_1_weight_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['operation_efficiency']['strat_1_objective_1'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="operation_efficiency[strat_1_review_actual_1]" value="{{ isset($ppr['operation_efficiency']['strat_1_review_actual_1']) ? $ppr['operation_efficiency']['strat_1_review_actual_1'] : ""}}" id="operation_efficiency[strat_1_review_actual_1]" onkeyup="computeActualGradeOperationEfficiency()" @if($enable_edit == false) disabled @endif>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['operation_efficiency']['strat_1_objective_1'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="operation_efficiency[strat_1_review_actual_1_actual_grade]" value="{{ isset($ppr['operation_efficiency']['strat_1_review_actual_1_actual_grade']) ? $ppr['operation_efficiency']['strat_1_review_actual_1_actual_grade'] : ""}}" id="operation_efficiency[strat_1_review_actual_1_actual_grade]" readonly>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['operation_efficiency']['strat_1_objective_1'])
                                                        <input type="text" name="operation_efficiency[strat_1_remarks_1]" value="{{ isset($ppr['operation_efficiency']['strat_1_remarks_1']) ? $ppr['operation_efficiency']['strat_1_remarks_1'] : ""}}" id="" width="100%" class="responsive-input myinput">
                                                    @endif
                                                </td>   
                                            </tr>
                                            <tr>
                                                <td>Strat 2</td>
                                                <td style="text-align: center; width:300px!important;">
                                                    {{$ppr['operation_efficiency']['strat_2_objective_1']}}
                                                    <input type="hidden" name="operation_efficiency[strat_2_objective_1]" value="{{$ppr['operation_efficiency']['strat_2_objective_1']}}" id="" width="100%" class="responsive-input myinput" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{$ppr['operation_efficiency']['strat_2_metric_1']}}
                                                    <input type="hidden" name="operation_efficiency[strat_2_metric_1]" value="{{$ppr['operation_efficiency']['strat_2_metric_1']}}" placeholder="Metric" class="myinput"  ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['operation_efficiency']['strat_2_target_1']) ? $ppr['operation_efficiency']['strat_2_target_1'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="operation_efficiency[strat_2_target_1]" value="{{ isset($ppr['operation_efficiency']['strat_2_target_1']) ? $ppr['operation_efficiency']['strat_2_target_1'] : ""}}" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['operation_efficiency']['strat_2_target_start_completion_1']) ? $ppr['operation_efficiency']['strat_2_target_start_completion_1'] : ""}}
                                                    <input type="hidden" name="operation_efficiency[strat_2_target_start_completion_1]" value="{{ isset($ppr['operation_efficiency']['strat_2_target_start_completion_1']) ? $ppr['operation_efficiency']['strat_2_target_start_completion_1'] : ""}}" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['operation_efficiency']['strat_2_target_end_completion_1']) ? $ppr['operation_efficiency']['strat_2_target_end_completion_1'] : ""}}
                                                    <input type="hidden" name="operation_efficiency[strat_2_target_end_completion_1]" value="{{ isset($ppr['operation_efficiency']['strat_2_target_end_completion_1']) ? $ppr['operation_efficiency']['strat_2_target_end_completion_1'] : ""}}" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['operation_efficiency']['strat_2_weight_1']) ? $ppr['operation_efficiency']['strat_2_weight_1'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="operation_efficiency[strat_2_weight_1]" value="{{ isset($ppr['operation_efficiency']['strat_2_weight_1']) ? $ppr['operation_efficiency']['strat_2_weight_1'] : ""}}" id="operation_efficiency[strat_2_weight_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['operation_efficiency']['strat_2_objective_1'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="operation_efficiency[strat_2_review_actual_1]" value="{{ isset($ppr['operation_efficiency']['strat_2_review_actual_1']) ? $ppr['operation_efficiency']['strat_2_review_actual_1'] : ""}}" id="operation_efficiency[strat_2_review_actual_1]" onkeyup="computeActualGradeOperationEfficiency()" @if($enable_edit == false) disabled @endif>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['operation_efficiency']['strat_2_objective_1'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="operation_efficiency[strat_2_review_actual_1_actual_grade]" value="{{ isset($ppr['operation_efficiency']['strat_2_review_actual_1_actual_grade']) ? $ppr['operation_efficiency']['strat_2_review_actual_1_actual_grade'] : ""}}" id="operation_efficiency[strat_2_review_actual_1_actual_grade]" readonly>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['operation_efficiency']['strat_2_objective_1'])
                                                        <input type="text" name="operation_efficiency[strat_2_remarks_1]" value="{{ isset($ppr['operation_efficiency']['strat_2_remarks_1']) ? $ppr['operation_efficiency']['strat_2_remarks_1'] : ""}}" id="" width="100%" class="responsive-input myinput">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Strat 3</td>
                                                <td style="text-align: center; width:300px!important;">
                                                    {{$ppr['operation_efficiency']['strat_3_objective_1']}}
                                                    <input type="hidden" name="operation_efficiency[strat_3_objective_1]" value="{{$ppr['operation_efficiency']['strat_3_objective_1']}}" id="" width="100%" class="responsive-input myinput" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{$ppr['operation_efficiency']['strat_3_metric_1']}}
                                                    <input type="hidden" name="operation_efficiency[strat_3_metric_1]" value="{{$ppr['operation_efficiency']['strat_3_metric_1']}}" placeholder="Metric" class="myinput"  ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['operation_efficiency']['strat_3_target_1']) ? $ppr['operation_efficiency']['strat_3_target_1'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="operation_efficiency[strat_3_target_1]" value="{{ isset($ppr['operation_efficiency']['strat_3_target_1']) ? $ppr['operation_efficiency']['strat_3_target_1'] : ""}}" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['operation_efficiency']['strat_3_target_start_completion_1']) ? $ppr['operation_efficiency']['strat_3_target_start_completion_1'] : ""}}
                                                    <input type="hidden" width="100%" name="operation_efficiency[strat_3_target_start_completion_1]" value="{{ isset($ppr['operation_efficiency']['strat_3_target_start_completion_1']) ? $ppr['operation_efficiency']['strat_3_target_start_completion_1'] : ""}}" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['operation_efficiency']['strat_3_target_end_completion_1']) ? $ppr['operation_efficiency']['strat_3_target_end_completion_1'] : ""}}
                                                    <input type="hidden" width="100%" name="operation_efficiency[strat_3_target_end_completion_1]" value="{{ isset($ppr['operation_efficiency']['strat_3_target_end_completion_1']) ? $ppr['operation_efficiency']['strat_3_target_end_completion_1'] : ""}}" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['operation_efficiency']['strat_3_weight_1']) ? $ppr['operation_efficiency']['strat_3_weight_1'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="operation_efficiency[strat_3_weight_1]" value="{{ isset($ppr['operation_efficiency']['strat_3_weight_1']) ? $ppr['operation_efficiency']['strat_3_weight_1'] : ""}}" id="operation_efficiency[strat_3_weight_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['operation_efficiency']['strat_3_objective_1'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="operation_efficiency[strat_3_review_actual_1]" value="{{ isset($ppr['operation_efficiency']['strat_3_review_actual_1']) ? $ppr['operation_efficiency']['strat_3_review_actual_1'] : ""}}" id="operation_efficiency[strat_3_review_actual_1]" onkeyup="computeActualGradeOperationEfficiency()" @if($enable_edit == false) disabled @endif>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['operation_efficiency']['strat_3_objective_1'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="operation_efficiency[strat_3_review_actual_1_actual_grade]" value="{{ isset($ppr['operation_efficiency']['strat_3_review_actual_1_actual_grade']) ? $ppr['operation_efficiency']['strat_3_review_actual_1_actual_grade'] : ""}}" id="operation_efficiency[strat_3_review_actual_1_actual_grade]" readonly>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['operation_efficiency']['strat_3_objective_1'])
                                                        <input type="text" name="operation_efficiency[strat_1_remarks_1]" value="{{ isset($ppr['operation_efficiency']['strat_3_remarks_1']) ? $ppr['operation_efficiency']['strat_3_remarks_1'] : ""}}" id="" width="100%" class="responsive-input myinput">
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" class="text-center">Objective 2</td>
                                                <td colspan="8"></td>
                                            </tr>
                                            <tr>
                                                <td style="width:200px!important;">Strat 1</td>
                                                <td style="text-align: center; width:300px!important;">
                                                    {{$ppr['operation_efficiency']['strat_1_objective_2']}}
                                                    <input type="hidden" name="operation_efficiency[strat_1_objective_2]" value="{{$ppr['operation_efficiency']['strat_1_objective_2']}}" id="" width="100%" class="responsive-input myinput" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{$ppr['operation_efficiency']['strat_1_metric_2']}}
                                                    <input type="hidden" name="operation_efficiency[strat_1_metric_2]" value="{{$ppr['operation_efficiency']['strat_1_metric_2']}}" placeholder="Metric" class="myinput" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['operation_efficiency']['strat_1_target_2']) ? $ppr['operation_efficiency']['strat_1_target_2'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="operation_efficiency[strat_1_target_2]" value="{{ isset($ppr['operation_efficiency']['strat_1_target_2']) ? $ppr['operation_efficiency']['strat_1_target_2'] : ""}}" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['operation_efficiency']['strat_1_target_start_completion_2']) ? $ppr['operation_efficiency']['strat_1_target_start_completion_2'] : ""}}
                                                    <input type="hidden" name="operation_efficiency[strat_1_target_start_completion_2]" value="{{ isset($ppr['operation_efficiency']['strat_1_target_start_completion_2']) ? $ppr['operation_efficiency']['strat_1_target_start_completion_2'] : ""}}" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['operation_efficiency']['strat_1_target_end_completion_2']) ? $ppr['operation_efficiency']['strat_1_target_end_completion_2'] : ""}}
                                                    <input type="hidden" name="operation_efficiency[strat_1_target_end_completion_2]" value="{{ isset($ppr['operation_efficiency']['strat_1_target_end_completion_2']) ? $ppr['operation_efficiency']['strat_1_target_end_completion_2'] : ""}}" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['operation_efficiency']['strat_1_weight_2']) ? $ppr['operation_efficiency']['strat_1_weight_2'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="operation_efficiency[strat_1_weight_2]" value="{{ isset($ppr['operation_efficiency']['strat_1_weight_2']) ? $ppr['operation_efficiency']['strat_1_weight_2'] : ""}}" id="operation_efficiency[strat_1_weight_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>

                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['operation_efficiency']['strat_1_objective_2'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="operation_efficiency[strat_1_review_actual_2]" value="{{ isset($ppr['operation_efficiency']['strat_1_review_actual_2']) ? $ppr['operation_efficiency']['strat_1_review_actual_2'] : ""}}" id="operation_efficiency[strat_1_review_actual_2]" onkeyup="computeActualGradeOperationEfficiency()" @if($enable_edit == false) disabled @endif>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['operation_efficiency']['strat_1_objective_2'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="operation_efficiency[strat_1_review_actual_2_actual_grade]" value="{{ isset($ppr['operation_efficiency']['strat_1_review_actual_2_actual_grade']) ? $ppr['operation_efficiency']['strat_1_review_actual_2_actual_grade'] : ""}}" id="operation_efficiency[strat_1_review_actual_2_actual_grade]" readonly>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['operation_efficiency']['strat_1_objective_2'])
                                                        <input type="text" name="operation_efficiency[strat_1_remarks_2]" value="{{ isset($ppr['operation_efficiency']['strat_1_remarks_2']) ? $ppr['operation_efficiency']['strat_1_remarks_2'] : ""}}" id="" width="100%" class="responsive-input myinput">
                                                    @endif
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>Strat 2</td>
                                                <td style="text-align: center; width:300px!important;">
                                                    {{$ppr['operation_efficiency']['strat_2_objective_2']}}
                                                    <input type="hidden" name="operation_efficiency[strat_2_objective_2]" value="{{$ppr['operation_efficiency']['strat_2_objective_2']}}" id="" width="100%" class="responsive-input myinput" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{$ppr['operation_efficiency']['strat_2_metric_2']}}
                                                    <input type="hidden" name="operation_efficiency[strat_2_metric_2]" value="{{$ppr['operation_efficiency']['strat_2_metric_2']}}" placeholder="Metric" class="myinput"  ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['operation_efficiency']['strat_2_target_2']) ? $ppr['operation_efficiency']['strat_2_target_2'] : "" }}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="operation_efficiency[strat_2_target_2]" value="{{ isset($ppr['operation_efficiency']['strat_2_target_2']) ? $ppr['operation_efficiency']['strat_2_target_2'] : "" }}" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{isset($ppr['operation_efficiency']['strat_2_target_start_completion_2']) ? $ppr['operation_efficiency']['strat_2_target_start_completion_2'] : ""}}
                                                    <input type="hidden" name="operation_efficiency[strat_2_target_start_completion_2]" value="{{isset($ppr['operation_efficiency']['strat_2_target_start_completion_2']) ? $ppr['operation_efficiency']['strat_2_target_start_completion_2'] : ""}}" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['operation_efficiency']['strat_2_target_end_completion_2']) ? $ppr['operation_efficiency']['strat_2_target_end_completion_2'] : ""}}
                                                    <input type="hidden" name="operation_efficiency[strat_2_target_end_completion_2]" value="{{ isset($ppr['operation_efficiency']['strat_2_target_end_completion_2']) ? $ppr['operation_efficiency']['strat_2_target_end_completion_2'] : ""}}" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['operation_efficiency']['strat_2_weight_2']) ? $ppr['operation_efficiency']['strat_2_weight_2'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="operation_efficiency[strat_2_weight_2]" value="{{ isset($ppr['operation_efficiency']['strat_2_weight_2']) ? $ppr['operation_efficiency']['strat_2_weight_2'] : ""}}" id="operation_efficiency[strat_2_weight_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['operation_efficiency']['strat_2_objective_2'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="operation_efficiency[strat_2_review_actual_2]" value="{{ isset($ppr['operation_efficiency']['strat_2_review_actual_2']) ? $ppr['operation_efficiency']['strat_2_review_actual_2'] : ""}}" id="operation_efficiency[strat_2_review_actual_2]" onkeyup="computeActualGradeOperationEfficiency()" @if($enable_edit == false) disabled @endif>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['operation_efficiency']['strat_2_objective_2'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="operation_efficiency[strat_2_review_actual_2_actual_grade]" value="{{ isset($ppr['operation_efficiency']['strat_2_review_actual_2_actual_grade']) ? $ppr['operation_efficiency']['strat_2_review_actual_2_actual_grade'] : ""}}" id="operation_efficiency[strat_2_review_actual_2_actual_grade]" readonly>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['operation_efficiency']['strat_2_objective_2'])
                                                        <input type="text" name="operation_efficiency[strat_2_remarks_2]" value="{{ isset($ppr['operation_efficiency']['strat_2_remarks_2']) ? $ppr['operation_efficiency']['strat_2_remarks_2'] : ""}}" id="" width="100%" class="responsive-input myinput">
                                                    @endif
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>Strat 3</td>
                                                <td style="text-align: center; width:300px!important;">
                                                    {{$ppr['operation_efficiency']['strat_3_objective_2']}}
                                                    <input type="hidden" name="operation_efficiency[strat_3_objective_2]" value="{{$ppr['operation_efficiency']['strat_3_objective_2']}}" id="" width="100%" class="responsive-input myinput" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{$ppr['operation_efficiency']['strat_3_metric_2']}}
                                                    <input type="hidden" name="operation_efficiency[strat_3_metric_2]" value="{{$ppr['operation_efficiency']['strat_3_metric_2']}}" placeholder="Metric" class="myinput"  ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['operation_efficiency']['strat_3_target_2']) ? $ppr['operation_efficiency']['strat_3_target_2'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="operation_efficiency[strat_3_target_2]" value="{{ isset($ppr['operation_efficiency']['strat_3_target_2']) ? $ppr['operation_efficiency']['strat_3_target_2'] : ""}}" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['operation_efficiency']['strat_3_target_start_completion_2']) ? $ppr['operation_efficiency']['strat_3_target_start_completion_2'] : ""}}
                                                    <input type="hidden" width="100%" name="operation_efficiency[strat_3_target_start_completion_2]" value="{{ isset($ppr['operation_efficiency']['strat_3_target_start_completion_2']) ? $ppr['operation_efficiency']['strat_3_target_start_completion_2'] : ""}}" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['operation_efficiency']['strat_3_target_end_completion_2']) ? $ppr['operation_efficiency']['strat_3_target_end_completion_2'] : ""}}
                                                    <input type="hidden" width="100%" name="operation_efficiency[strat_3_target_end_completion_2]" value="{{ isset($ppr['operation_efficiency']['strat_3_target_end_completion_2']) ? $ppr['operation_efficiency']['strat_3_target_end_completion_2'] : ""}}" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['operation_efficiency']['strat_3_weight_2']) ? $ppr['operation_efficiency']['strat_3_weight_2'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="operation_efficiency[strat_3_weight_2]" value="{{ isset($ppr['operation_efficiency']['strat_3_weight_2']) ? $ppr['operation_efficiency']['strat_3_weight_2'] : ""}}" id="operation_efficiency[strat_3_weight_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['operation_efficiency']['strat_3_objective_2'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="operation_efficiency[strat_3_review_actual_2]" value="{{ isset($ppr['operation_efficiency']['strat_3_review_actual_2']) ? $ppr['operation_efficiency']['strat_3_review_actual_2'] : ""}}" id="operation_efficiency[strat_3_review_actual_2]" onkeyup="computeActualGradeOperationEfficiency()" @if($enable_edit == false) disabled @endif>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['operation_efficiency']['strat_3_objective_2'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="operation_efficiency[strat_3_review_actual_2_actual_grade]" value="{{ isset($ppr['operation_efficiency']['strat_3_review_actual_2_actual_grade']) ? $ppr['operation_efficiency']['strat_3_review_actual_2_actual_grade'] : ""}}" id="operation_efficiency[strat_3_review_actual_2_actual_grade]" readonly>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['operation_efficiency']['strat_3_objective_2'])
                                                        <input type="text" name="operation_efficiency[strat_3_remarks_2]" value="{{ isset($ppr['operation_efficiency']['strat_3_remarks_2']) ? $ppr['operation_efficiency']['strat_3_remarks_2'] : ""}}" id="" width="100%" class="responsive-input myinput">
                                                    @endif
                                                </td>
                                
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
                                                <td style="text-align: center; width:300px!important;">
                                                    {{$ppr['people']['strat_1_objective_1']}}
                                                    <input type="hidden" name="people[strat_1_objective_1]" value="{{$ppr['people']['strat_1_objective_1']}}" id="people[strat_1_objective_1]" width="100%" class="responsive-input myinput" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{$ppr['people']['strat_1_metric_1']}}
                                                    <input type="hidden" name="people[strat_1_metric_1]" value="{{$ppr['people']['strat_1_metric_1']}}"  id="people[strat_1_metric_1]" placeholder="Metric" class="myinput" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['people']['strat_1_target_1']) ? $ppr['people']['strat_1_target_1'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="people[strat_1_target_1]" value="{{ isset($ppr['people']['strat_1_target_1']) ? $ppr['people']['strat_1_target_1'] : ""}}" id="people[strat_1_target_1]"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['people']['strat_1_target_start_completion_1']) ? $ppr['people']['strat_1_target_start_completion_1'] : ""}}
                                                    <input type="hidden" name="people[strat_1_target_start_completion_1]" value="{{ isset($ppr['people']['strat_1_target_start_completion_1']) ? $ppr['people']['strat_1_target_start_completion_1'] : ""}}" placeholder="Start" id="people[strat_1_target_start_completion_1]"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['people']['strat_1_target_end_completion_1']) ? $ppr['people']['strat_1_target_end_completion_1'] : ""}}
                                                    <input type="hidden" name="people[strat_1_target_end_completion_1]" value="{{ isset($ppr['people']['strat_1_target_end_completion_1']) ? $ppr['people']['strat_1_target_end_completion_1'] : ""}}" placeholder="End" id="people[strat_1_target_end_completion_1]"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['people']['strat_1_weight_1']) ? $ppr['people']['strat_1_weight_1'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="people[strat_1_weight_1]" value="{{ isset($ppr['people']['strat_1_weight_1']) ? $ppr['people']['strat_1_weight_1'] : ""}}" id="people[strat_1_weight_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['people']['strat_1_objective_1'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="people[strat_1_review_actual_1]" value="{{ isset($ppr['people']['strat_1_review_actual_1']) ? $ppr['people']['strat_1_review_actual_1'] : ""}}" id="people[strat_1_review_actual_1]" onkeyup="computeActualGradePeople()" @if($enable_edit == false) disabled @endif>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['people']['strat_1_objective_1'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="people[strat_1_review_actual_1_actual_grade]" value="{{ isset($ppr['people']['strat_1_review_actual_1_actual_grade']) ? $ppr['people']['strat_1_review_actual_1_actual_grade'] : ""}}" id="people[strat_1_review_actual_1_actual_grade]" readonly>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['people']['strat_1_objective_1'])
                                                        <input type="text" name="people[strat_1_remarks_1]" value="{{ isset($ppr['people']['strat_1_remarks_1']) ? $ppr['people']['strat_1_remarks_1'] : ""}}" id="" width="100%" class="responsive-input myinput">
                                                    @endif
                                                </td>   
                                            </tr>
                                            <tr>
                                                <td>Strat 2</td>
                                                <td style="text-align: center; width:300px!important;">
                                                    {{$ppr['people']['strat_2_objective_1']}}
                                                    <input type="hidden" name="people[strat_2_objective_1]" value="{{$ppr['people']['strat_2_objective_1']}}" id="" width="100%" class="responsive-input myinput" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{$ppr['people']['strat_2_metric_1']}}
                                                    <input type="hidden" name="people[strat_2_metric_1]" value="{{$ppr['people']['strat_2_metric_1']}}" placeholder="Metric" class="myinput"  ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['people']['strat_2_target_1']) ? $ppr['people']['strat_2_target_1'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="people[strat_2_target_1]" value="{{ isset($ppr['people']['strat_2_target_1']) ? $ppr['people']['strat_2_target_1'] : ""}}" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['people']['strat_2_target_start_completion_1']) ? $ppr['people']['strat_2_target_start_completion_1'] : ""}}
                                                    <input type="hidden" name="people[strat_2_target_start_completion_1]" value="{{ isset($ppr['people']['strat_2_target_start_completion_1']) ? $ppr['people']['strat_2_target_start_completion_1'] : ""}}" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['people']['strat_2_target_end_completion_1']) ? $ppr['people']['strat_2_target_end_completion_1'] : ""}}
                                                    <input type="hidden" name="people[strat_2_target_end_completion_1]" value="{{ isset($ppr['people']['strat_2_target_end_completion_1']) ? $ppr['people']['strat_2_target_end_completion_1'] : ""}}" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['people']['strat_2_weight_1']) ? $ppr['people']['strat_2_weight_1'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="people[strat_2_weight_1]" value="{{ isset($ppr['people']['strat_2_weight_1']) ? $ppr['people']['strat_2_weight_1'] : ""}}" id="people[strat_2_weight_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['people']['strat_2_objective_1'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="people[strat_2_review_actual_1]" value="{{ isset($ppr['people']['strat_2_review_actual_1']) ? $ppr['people']['strat_2_review_actual_1'] : ""}}" id="people[strat_2_review_actual_1]" onkeyup="computeActualGradePeople()" @if($enable_edit == false) disabled @endif>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['people']['strat_2_objective_1'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="people[strat_2_review_actual_1_actual_grade]" value="{{ isset($ppr['people']['strat_2_review_actual_1_actual_grade']) ? $ppr['people']['strat_2_review_actual_1_actual_grade'] : ""}}" id="people[strat_2_review_actual_1_actual_grade]" readonly>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['people']['strat_2_objective_1'])
                                                        <input type="text" name="people[strat_2_remarks_1]" value="{{ isset($ppr['people']['strat_2_remarks_1']) ? $ppr['people']['strat_2_remarks_1'] : ""}}" id="" width="100%" class="responsive-input myinput">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Strat 3</td>
                                                <td style="text-align: center; width:300px!important;">
                                                    {{$ppr['people']['strat_3_objective_1']}}
                                                    <input type="hidden" name="people[strat_3_objective_1]" value="{{$ppr['people']['strat_3_objective_1']}}" id="" width="100%" class="responsive-input myinput" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{$ppr['people']['strat_3_metric_1']}}
                                                    <input type="hidden" name="people[strat_3_metric_1]" value="{{$ppr['people']['strat_3_metric_1']}}" placeholder="Metric" class="myinput"  ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['people']['strat_3_target_1']) ? $ppr['people']['strat_3_target_1'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="people[strat_3_target_1]" value="{{ isset($ppr['people']['strat_3_target_1']) ? $ppr['people']['strat_3_target_1'] : ""}}" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['people']['strat_3_target_start_completion_1']) ? $ppr['people']['strat_3_target_start_completion_1'] : ""}}
                                                    <input type="hidden" width="100%" name="people[strat_3_target_start_completion_1]" value="{{ isset($ppr['people']['strat_3_target_start_completion_1']) ? $ppr['people']['strat_3_target_start_completion_1'] : ""}}" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['people']['strat_3_target_end_completion_1']) ? $ppr['people']['strat_3_target_end_completion_1'] : ""}}
                                                    <input type="hidden" width="100%" name="people[strat_3_target_end_completion_1]" value="{{ isset($ppr['people']['strat_3_target_end_completion_1']) ? $ppr['people']['strat_3_target_end_completion_1'] : ""}}" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['people']['strat_3_weight_1']) ? $ppr['people']['strat_3_weight_1'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="people[strat_3_weight_1]" value="{{ isset($ppr['people']['strat_3_weight_1']) ? $ppr['people']['strat_3_weight_1'] : ""}}" id="people[strat_3_weight_1]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['people']['strat_3_objective_1'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="people[strat_3_review_actual_1]" value="{{ isset($ppr['people']['strat_3_review_actual_1']) ? $ppr['people']['strat_3_review_actual_1'] : ""}}" id="people[strat_3_review_actual_1]" onkeyup="computeActualGradePeople()" @if($enable_edit == false) disabled @endif>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['people']['strat_3_objective_1'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="people[strat_3_review_actual_1_actual_grade]" value="{{ isset($ppr['people']['strat_3_review_actual_1_actual_grade']) ? $ppr['people']['strat_3_review_actual_1_actual_grade'] : ""}}" id="people[strat_3_review_actual_1_actual_grade]" readonly>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['people']['strat_3_objective_1'])
                                                        <input type="text" name="people[strat_1_remarks_1]" value="{{ isset($ppr['people']['strat_3_remarks_1']) ? $ppr['people']['strat_3_remarks_1'] : ""}}" id="" width="100%" class="responsive-input myinput">
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" class="text-center">Objective 2</td>
                                                <td colspan="8"></td>
                                            </tr>
                                            <tr>
                                                <td style="width:200px!important;">Strat 1</td>
                                                <td style="text-align: center; width:300px!important;">
                                                    {{$ppr['people']['strat_1_objective_2']}}
                                                    <input type="hidden" name="people[strat_1_objective_2]" value="{{$ppr['people']['strat_1_objective_2']}}" id="" width="100%" class="responsive-input myinput" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{$ppr['people']['strat_1_metric_2']}}
                                                    <input type="hidden" name="people[strat_1_metric_2]" value="{{$ppr['people']['strat_1_metric_2']}}" placeholder="Metric" class="myinput" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['people']['strat_1_target_2']) ? $ppr['people']['strat_1_target_2'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="people[strat_1_target_2]" value="{{ isset($ppr['people']['strat_1_target_2']) ? $ppr['people']['strat_1_target_2'] : ""}}" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['people']['strat_1_target_start_completion_2']) ? $ppr['people']['strat_1_target_start_completion_2'] : ""}}
                                                    <input type="hidden" name="people[strat_1_target_start_completion_2]" value="{{ isset($ppr['people']['strat_1_target_start_completion_2']) ? $ppr['people']['strat_1_target_start_completion_2'] : ""}}" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['people']['strat_1_target_end_completion_2']) ? $ppr['people']['strat_1_target_end_completion_2'] : ""}}
                                                    <input type="hidden" name="people[strat_1_target_end_completion_2]" value="{{ isset($ppr['people']['strat_1_target_end_completion_2']) ? $ppr['people']['strat_1_target_end_completion_2'] : ""}}" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['people']['strat_1_weight_2']) ? $ppr['people']['strat_1_weight_2'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="people[strat_1_weight_2]" value="{{ isset($ppr['people']['strat_1_weight_2']) ? $ppr['people']['strat_1_weight_2'] : ""}}" id="people[strat_1_weight_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>

                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['people']['strat_1_objective_2'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="people[strat_1_review_actual_2]" value="{{ isset($ppr['people']['strat_1_review_actual_2']) ? $ppr['people']['strat_1_review_actual_2'] : ""}}" id="people[strat_1_review_actual_2]" onkeyup="computeActualGradePeople()" @if($enable_edit == false) disabled @endif>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['people']['strat_1_objective_2'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="people[strat_1_review_actual_2_actual_grade]" value="{{ isset($ppr['people']['strat_1_review_actual_2_actual_grade']) ? $ppr['people']['strat_1_review_actual_2_actual_grade'] : ""}}" id="people[strat_1_review_actual_2_actual_grade]" readonly>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['people']['strat_1_objective_2'])
                                                        <input type="text" name="people[strat_1_remarks_2]" value="{{ isset($ppr['people']['strat_1_remarks_2']) ? $ppr['people']['strat_1_remarks_2'] : ""}}" id="" width="100%" class="responsive-input myinput">
                                                    @endif
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>Strat 2</td>
                                                <td style="text-align: center; width:300px!important;">
                                                    {{$ppr['people']['strat_2_objective_2']}}
                                                    <input type="hidden" name="people[strat_2_objective_2]" value="{{$ppr['people']['strat_2_objective_2']}}" id="" width="100%" class="responsive-input myinput" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{$ppr['people']['strat_2_metric_2']}}
                                                    <input type="hidden" name="people[strat_2_metric_2]" value="{{$ppr['people']['strat_2_metric_2']}}" placeholder="Metric" class="myinput"  ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['people']['strat_2_target_2']) ? $ppr['people']['strat_2_target_2'] : "" }}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="people[strat_2_target_2]" value="{{ isset($ppr['people']['strat_2_target_2']) ? $ppr['people']['strat_2_target_2'] : "" }}" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{isset($ppr['people']['strat_2_target_start_completion_2']) ? $ppr['people']['strat_2_target_start_completion_2'] : ""}}
                                                    <input type="hidden" name="people[strat_2_target_start_completion_2]" value="{{isset($ppr['people']['strat_2_target_start_completion_2']) ? $ppr['people']['strat_2_target_start_completion_2'] : ""}}" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['people']['strat_2_target_end_completion_2']) ? $ppr['people']['strat_2_target_end_completion_2'] : ""}}
                                                    <input type="hidden" name="people[strat_2_target_end_completion_2]" value="{{ isset($ppr['people']['strat_2_target_end_completion_2']) ? $ppr['people']['strat_2_target_end_completion_2'] : ""}}" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['people']['strat_2_weight_2']) ? $ppr['people']['strat_2_weight_2'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="people[strat_2_weight_2]" value="{{ isset($ppr['people']['strat_2_weight_2']) ? $ppr['people']['strat_2_weight_2'] : ""}}" id="people[strat_2_weight_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['people']['strat_2_objective_2'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="people[strat_2_review_actual_2]" value="{{ isset($ppr['people']['strat_2_review_actual_2']) ? $ppr['people']['strat_2_review_actual_2'] : ""}}" id="people[strat_2_review_actual_2]" onkeyup="computeActualGradePeople()" @if($enable_edit == false) disabled @endif>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['people']['strat_2_objective_2'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="people[strat_2_review_actual_2_actual_grade]" value="{{ isset($ppr['people']['strat_2_review_actual_2_actual_grade']) ? $ppr['people']['strat_2_review_actual_2_actual_grade'] : ""}}" id="people[strat_2_review_actual_2_actual_grade]" readonly>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['people']['strat_2_objective_2'])
                                                        <input type="text" name="people[strat_2_remarks_2]" value="{{ isset($ppr['people']['strat_2_remarks_2']) ? $ppr['people']['strat_2_remarks_2'] : ""}}" id="" width="100%" class="responsive-input myinput">
                                                    @endif
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>Strat 3</td>
                                                <td style="text-align: center; width:300px!important;">
                                                    {{$ppr['people']['strat_3_objective_2']}}
                                                    <input type="hidden" name="people[strat_3_objective_2]" value="{{$ppr['people']['strat_3_objective_2']}}" id="" width="100%" class="responsive-input myinput" placeholder="Input Here" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{$ppr['people']['strat_3_metric_2']}}
                                                    <input type="hidden" name="people[strat_3_metric_2]" value="{{$ppr['people']['strat_3_metric_2']}}" placeholder="Metric" class="myinput"  ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['people']['strat_3_target_2']) ? $ppr['people']['strat_3_target_2'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="people[strat_3_target_2]" value="{{ isset($ppr['people']['strat_3_target_2']) ? $ppr['people']['strat_3_target_2'] : ""}}" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['people']['strat_3_target_start_completion_2']) ? $ppr['people']['strat_3_target_start_completion_2'] : ""}}
                                                    <input type="hidden" width="100%" name="people[strat_3_target_start_completion_2]" value="{{ isset($ppr['people']['strat_3_target_start_completion_2']) ? $ppr['people']['strat_3_target_start_completion_2'] : ""}}" placeholder="Start" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['people']['strat_3_target_end_completion_2']) ? $ppr['people']['strat_3_target_end_completion_2'] : ""}}
                                                    <input type="hidden" width="100%" name="people[strat_3_target_end_completion_2]" value="{{ isset($ppr['people']['strat_3_target_end_completion_2']) ? $ppr['people']['strat_3_target_end_completion_2'] : ""}}" placeholder="End" ></td>
                                                <td style="text-align: center; width:10px!important;">
                                                    {{ isset($ppr['people']['strat_3_weight_2']) ? $ppr['people']['strat_3_weight_2'] : ""}}
                                                    <input type="hidden" class="text-align-center" min="1" max="100" name="people[strat_3_weight_2]" value="{{ isset($ppr['people']['strat_3_weight_2']) ? $ppr['people']['strat_3_weight_2'] : ""}}" id="people[strat_3_weight_2]" onkeyup="updateSumTotalSummaryofRatingsWeight()"></td>
                                                
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['people']['strat_3_objective_2'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="people[strat_3_review_actual_2]" value="{{ isset($ppr['people']['strat_3_review_actual_2']) ? $ppr['people']['strat_3_review_actual_2'] : ""}}" id="people[strat_3_review_actual_2]" onkeyup="computeActualGradePeople()" @if($enable_edit == false) disabled @endif>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['people']['strat_3_objective_2'])
                                                        <input type="number" class="text-align-center" min="1" max="5" name="people[strat_3_review_actual_2_actual_grade]" value="{{ isset($ppr['people']['strat_3_review_actual_2_actual_grade']) ? $ppr['people']['strat_3_review_actual_2_actual_grade'] : ""}}" id="people[strat_3_review_actual_2_actual_grade]" readonly>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10px!important;">
                                                    @if($ppr['people']['strat_3_objective_2'])
                                                        <input type="text" name="people[strat_3_remarks_2]" value="{{ isset($ppr['people']['strat_3_remarks_2']) ? $ppr['people']['strat_3_remarks_2'] : ""}}" id="" width="100%" class="responsive-input myinput">
                                                    @endif
                                                </td>
                                
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
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="integrity[self_rating_1]" id="integrity[self_rating_1]" value="{{isset($ppr['integrity']['self_rating_1']) ? $ppr['integrity']['self_rating_1'] : ""}}"  min="1" max="4" onkeyup="computeWTDScore()" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="integrity[superios_rating_1]" id="integrity[superios_rating_1]" value="{{isset($ppr['integrity']['self_rating_1']) ? $ppr['integrity']['superios_rating_1'] : ""}}"  min="1" max="4" @if($enable_edit == false) disabled @endif></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="integrity[wtd_score_1]" id="integrity[wtd_score_1]" value="{{isset($ppr['integrity']['self_rating_1']) ? $ppr['integrity']['wtd_score_1'] : ""}}" min="1" max="10"  readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Treats people with dignity, respect, and fairness; gives proper credit to others; stands up for others who are deserving and their ideas even in the face of resistance or challenge to fosters high standards of values and ethics.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="integrity[self_rating_2]" id="integrity[self_rating_2]" value="{{isset($ppr['integrity']['self_rating_2']) ? $ppr['integrity']['self_rating_2'] : ""}}" min="1" max="4" onkeyup="computeWTDScore()" ></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="integrity[superios_rating_2]" id="integrity[superios_rating_2]" value="{{isset($ppr['integrity']['superios_rating_2']) ? $ppr['integrity']['superios_rating_2'] : ""}}" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="integrity[wtd_score_2]" id="integrity[wtd_score_2]" value="{{isset($ppr['integrity']['wtd_score_2']) ? $ppr['integrity']['wtd_score_2'] : ""}}" min="1"  max="10" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Acts as a role model for working with others with sincerity, cheerfulness, and trust worthy traits in carrying out the job/ task with minimal to on supervision.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="integrity[self_rating_3]" id="integrity[self_rating_3]" value="{{isset($ppr['integrity']['self_rating_3']) ? $ppr['integrity']['self_rating_3'] : ""}}" min="1" max="4" onkeyup="computeWTDScore()" @if($enable_edit == false) disabled @endif></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="integrity[superios_rating_3]" id="integrity[superios_rating_3]" value="{{isset($ppr['integrity']['superios_rating_3']) ? $ppr['integrity']['superios_rating_3'] : ""}}" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="integrity[wtd_score_3]" id="integrity[wtd_score_3]" value="{{isset($ppr['integrity']['wtd_score_3']) ? $ppr['integrity']['wtd_score_3'] : ""}}" min="1" max="10" readonly></td>
                                            </tr>

                                            <tr>
                                                <td colspan="6" class="text-center">&nbsp;</td>
                                            </tr>

                                            {{-- 2 --}}
                                            <tr>
                                                <td rowspan="3" class="text-center text-dark">2. COMMITMENT - Ability to never make excuses, only results</td>
                                                <td class="text-center text-dark">Defines personal purpose, meaning, and challenges, and proactively set plans and strategies to overcome obstacles and meet current and future needs and targets.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="commitment[self_rating_1]" id="commitment[self_rating_1]" value="{{isset($ppr['commitment']['self_rating_1']) ? $ppr['commitment']['self_rating_1'] : ""}}" min="1" max="4" onkeyup="computeWTDScore()" @if($enable_edit == false) disabled @endif></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="commitment[superios_rating_1]" id="commitment[superios_rating_1]" value="{{isset($ppr['commitment']['self_rating_1']) ? $ppr['commitment']['superios_rating_1'] : ""}}" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="commitment[wtd_score_1]" id="commitment[wtd_score_1]" value="{{isset($ppr['commitment']['self_rating_1']) ? $ppr['commitment']['wtd_score_1'] : ""}}" min="1" max="10" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Diligently completes the assigned work efficiently, completely, accurately, and meets the standards of performance (e.g. KRA/ KPI, quality/ quantity of work, presence, timeliness) within an established time frame and within budget.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="commitment[self_rating_2]" id="commitment[self_rating_2]" value="{{isset($ppr['commitment']['self_rating_2']) ? $ppr['commitment']['self_rating_2'] : ""}}" min="1" max="4" onkeyup="computeWTDScore()" @if($enable_edit == false) disabled @endif></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="commitment[superios_rating_2]" id="commitment[superios_rating_2]" value="{{isset($ppr['commitment']['superios_rating_2']) ? $ppr['commitment']['superios_rating_2'] : ""}}" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="commitment[wtd_score_2]" id="commitment[wtd_score_2]" value="{{isset($ppr['commitment']['wtd_score_2']) ? $ppr['commitment']['wtd_score_2'] : ""}}" min="1"  max="10" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Executes objectives, delivers/ exceed targets and sees tasks through to the end; while taking into consideration current responsibilities, work load, core values, and the trust, confidence and resources bestowed on him/ her, and the company-wide organizational goals.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="commitment[self_rating_3]" id="commitment[self_rating_3]" value="{{isset($ppr['commitment']['self_rating_3']) ? $ppr['commitment']['self_rating_3'] : ""}}" min="1" max="4" onkeyup="computeWTDScore()" @if($enable_edit == false) disabled @endif></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="commitment[superios_rating_3]" id="commitment[superios_rating_3]" value="{{isset($ppr['commitment']['superios_rating_3']) ? $ppr['commitment']['superios_rating_3'] : ""}}" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="commitment[wtd_score_3]" id="commitment[wtd_score_3]" value="{{isset($ppr['commitment']['wtd_score_3']) ? $ppr['commitment']['wtd_score_3'] : ""}}" min="1" max="10" readonly></td>
                                            </tr>

                                            <tr>
                                                <td colspan="6" class="text-center">&nbsp;</td>
                                            </tr>

                                            {{-- 3 --}}
                                            <tr>
                                                <td rowspan="3" class="text-center text-dark">3. HUMILITY - Ability to be simple (no pretenses)</td>
                                                <td class="text-center text-dark">Recognizes personal strengths and gaps and seeks guidance or resources in laying out development and/or improvement plans.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="humility[self_rating_1]" id="humility[self_rating_1]" value="{{isset($ppr['humility']['self_rating_1']) ? $ppr['humility']['self_rating_1'] : ""}}" min="1" max="4" onkeyup="computeWTDScore()" @if($enable_edit == false) disabled @endif></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="humility[superios_rating_1]" id="humility[superios_rating_1]" value="{{isset($ppr['humility']['self_rating_1']) ? $ppr['humility']['superios_rating_1'] : ""}}" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="humility[wtd_score_1]" id="humility[wtd_score_1]" value="{{isset($ppr['humility']['self_rating_1']) ? $ppr['humility']['wtd_score_1'] : ""}}" min="1" max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Asks for and uses feedback to improve performance, seeks additional training and development to improve his/ her knowledge and skills.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="humility[self_rating_2]" id="humility[self_rating_2]" value="{{isset($ppr['humility']['self_rating_2']) ? $ppr['humility']['self_rating_2'] : ""}}" min="1" max="4" onkeyup="computeWTDScore()" @if($enable_edit == false) disabled @endif></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="humility[superios_rating_2]" id="humility[superios_rating_2]" value="{{isset($ppr['humility']['superios_rating_2']) ? $ppr['humility']['superios_rating_2'] : ""}}" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="humility[wtd_score_2]" id="humility[wtd_score_2]" value="{{isset($ppr['humility']['wtd_score_2']) ? $ppr['humility']['wtd_score_2'] : ""}}" min="1"  max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Reads, understands, and abides/ faithfully comply and conform with the Company Code of Discipline, Policies, and Procedures; and in case of violations, accepts administrative and/or financial accountability, if any.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="humility[self_rating_3]" id="humility[self_rating_3]" value="{{isset($ppr['humility']['self_rating_3']) ? $ppr['humility']['self_rating_3'] : ""}}" min="1" max="4" onkeyup="computeWTDScore()" @if($enable_edit == false) disabled @endif></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="humility[superios_rating_3]" id="humility[superios_rating_3]" value="{{isset($ppr['humility']['superios_rating_3']) ? $ppr['humility']['superios_rating_3'] : ""}}" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="humility[wtd_score_3]" id="humility[wtd_score_3]" value="{{isset($ppr['humility']['wtd_score_3']) ? $ppr['humility']['wtd_score_3'] : ""}}" min="1" max="5" readonly></td>
                                            </tr>

                                            <tr>
                                                <td colspan="6" class="text-center">&nbsp;</td>
                                            </tr>

                                            {{-- 4 --}}
                                            <tr>
                                                <td rowspan="3" class="text-center text-dark">4. GENUINE CONCERN - Ability to enrich the lives of people</td>
                                                <td class="text-center text-dark">Understand, assists and cares for the feelings and well-being (e.g. happy and safe, feel at ease and at home) of co-workers,  without hesitation or pretensions, directed by  his/ her attentiveness and sensitivety of their needs, difficulties, and changes in the mood of a room or emotions of those around.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="genuine_concern[self_rating_1]" id="genuine_concern[self_rating_1]" value="{{isset($ppr['genuine_concern']['self_rating_1']) ? $ppr['genuine_concern']['self_rating_1'] : ""}}" min="1" max="4" onkeyup="computeWTDScore()" @if($enable_edit == false) disabled @endif></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="genuine_concern[superios_rating_1]" id="genuine_concern[superios_rating_1]" value="{{isset($ppr['genuine_concern']['self_rating_1']) ? $ppr['genuine_concern']['superios_rating_1'] : ""}}" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="genuine_concern[wtd_score_1]" id="genuine_concern[wtd_score_1]" value="{{isset($ppr['genuine_concern']['self_rating_1']) ? $ppr['genuine_concern']['wtd_score_1'] : ""}}" min="1" max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Listens to others and objectively considers others’ ideas and opinions, even when they conflict with one’s own, and addressing their potential impact organization-wide and across group when performing and helping others complete given tasks.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="genuine_concern[self_rating_2]" id="genuine_concern[self_rating_2]" value="{{isset($ppr['genuine_concern']['self_rating_2']) ? $ppr['genuine_concern']['self_rating_2'] : ""}}" min="1" max="4" onkeyup="computeWTDScore()" @if($enable_edit == false) disabled @endif></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="genuine_concern[superios_rating_2]" id="genuine_concern[superios_rating_2]" value="{{isset($ppr['genuine_concern']['superios_rating_2']) ? $ppr['genuine_concern']['superios_rating_2'] : ""}}" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="genuine_concern[wtd_score_2]" id="genuine_concern[wtd_score_2]" value="{{isset($ppr['genuine_concern']['wtd_score_2']) ? $ppr['genuine_concern']['wtd_score_2'] : ""}}" min="1"  max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Identifies opportunities for improving performance both for one's own area or responsibility and/or within the organization, and commits significant resources to improve performance while taking action.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="genuine_concern[self_rating_3]" id="genuine_concern[self_rating_3]" value="{{isset($ppr['genuine_concern']['self_rating_3']) ? $ppr['genuine_concern']['self_rating_3'] : ""}}" min="1" max="4" onkeyup="computeWTDScore()" @if($enable_edit == false) disabled @endif></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="genuine_concern[superios_rating_3]" id="genuine_concern[superios_rating_3]" value="{{isset($ppr['genuine_concern']['superios_rating_3']) ? $ppr['genuine_concern']['superios_rating_3'] : ""}}" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="genuine_concern[wtd_score_3]" id="genuine_concern[wtd_score_3]" value="{{isset($ppr['genuine_concern']['wtd_score_3']) ? $ppr['genuine_concern']['wtd_score_3'] : ""}}" min="1" max="5" readonly></td>
                                            </tr>

                                            <tr>
                                                <td colspan="6" class="text-center">&nbsp;</td>
                                            </tr>

                                            {{-- 5 --}}
                                            <tr>
                                                <td rowspan="3" class="text-center text-dark">5. PREMIUM SERVICE - Ability to delivery quality service beyond expectation </td>
                                                <td class="text-center text-dark">Exceeds expectation in delivering a completed service/ task, with accurate and organized information, within the time line and standards set by the company or customer.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="premium_service[self_rating_1]" id="premium_service[self_rating_1]" value="{{isset($ppr['premium_service']['self_rating_1']) ? $ppr['premium_service']['self_rating_1'] : ""}}" min="1" max="4" onkeyup="computeWTDScore()" @if($enable_edit == false) disabled @endif></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="premium_service[superios_rating_1]" id="premium_service[superios_rating_1]" value="{{isset($ppr['premium_service']['self_rating_1']) ? $ppr['premium_service']['superios_rating_1'] : ""}}" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="premium_service[wtd_score_1]" id="premium_service[wtd_score_1]" value="{{isset($ppr['premium_service']['self_rating_1']) ? $ppr['premium_service']['wtd_score_1'] : ""}}" min="1" max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Institutes a process/ system for monitoring and tracking individual and/or team results/ progress against standards; and modifies actions accordingly.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="premium_service[self_rating_2]" id="premium_service[self_rating_2]" value="{{isset($ppr['premium_service']['self_rating_2']) ? $ppr['premium_service']['self_rating_2'] : ""}}" min="1" max="4" onkeyup="computeWTDScore()" @if($enable_edit == false) disabled @endif></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="premium_service[superios_rating_2]" id="premium_service[superios_rating_2]" value="{{isset($ppr['premium_service']['superios_rating_2']) ? $ppr['premium_service']['superios_rating_2'] : ""}}" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="premium_service[wtd_score_2]" id="premium_service[wtd_score_2]" value="{{isset($ppr['premium_service']['wtd_score_2']) ? $ppr['premium_service']['wtd_score_2'] : ""}}" min="1"  max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Gives value to customers by knowing your product and service, actively listening, practicing honesty in attending to customer needs; before, during, and after an exchange/ transaction.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="premium_service[self_rating_3]" id="premium_service[self_rating_3]" value="{{isset($ppr['premium_service']['self_rating_3']) ? $ppr['premium_service']['self_rating_3'] : ""}}" min="1" max="4" onkeyup="computeWTDScore()" @if($enable_edit == false) disabled @endif></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="premium_service[superios_rating_3]" id="premium_service[superios_rating_3]" value="{{isset($ppr['premium_service']['superios_rating_3']) ? $ppr['premium_service']['superios_rating_3'] : ""}}" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="premium_service[wtd_score_3]" id="premium_service[wtd_score_3]"  value="{{isset($ppr['premium_service']['wtd_score_3']) ? $ppr['premium_service']['wtd_score_3'] : ""}}" min="1" max="5" readonly></td>
                                            </tr>

                                            <tr>
                                                <td colspan="6" class="text-center">&nbsp;</td>
                                            </tr>

                                            {{-- 6 --}}
                                            <tr>
                                                <td rowspan="3" class="text-center text-dark">6. INNOVATION - Ability to find a better way to do things better </td>
                                                <td class="text-center text-dark">Adjusts (adapt) thinking and behavior to be in step with new thrusts or changing priorities/ developments within the organization and the external environment with openness, acceptance (e.g. in assignments/ approaches even those not related to one's job), and recommendations for structural or operational changes.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="innovation[self_rating_1]" id="innovation[self_rating_1]" value="{{isset($ppr['innovation']['self_rating_1']) ? $ppr['innovation']['self_rating_1'] : ""}}" min="1" max="4" onkeyup="computeWTDScore()" @if($enable_edit == false) disabled @endif></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="innovation[superios_rating_1]" id="innovation[superios_rating_1]" value="{{isset($ppr['innovation']['self_rating_1']) ? $ppr['innovation']['superios_rating_1'] : ""}}" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="innovation[wtd_score_1]" id="innovation[wtd_score_1]" value="{{isset($ppr['innovation']['self_rating_1']) ? $ppr['innovation']['wtd_score_1'] : ""}}" min="1" max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Acquires/ generate/ develop, introduce/ contribute, and implement new and useful work methods, ideas, approaches, and information for products/ technologies, to solve problems or improve efficiency and effectiveness on the job/ organizational activities and services.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="innovation[self_rating_2]" id="innovation[self_rating_2]" value="{{isset($ppr['innovation']['self_rating_2']) ? $ppr['innovation']['self_rating_2'] : ""}}" min="1" max="4" onkeyup="computeWTDScore()" @if($enable_edit == false) disabled @endif></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="innovation[superios_rating_2]" id="innovation[superios_rating_2]" value="{{isset($ppr['innovation']['superios_rating_2']) ? $ppr['innovation']['superios_rating_2'] : ""}}" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="innovation[wtd_score_2]" id="innovation[wtd_score_2]" value="{{isset($ppr['innovation']['wtd_score_2']) ? $ppr['innovation']['wtd_score_2'] : ""}}" min="1"  max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Acts as a change agent by promoting and embracing change in existing practices (i.e. challenge status quo, streamlining processes) in appropriate ways, across the entire organization.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="innovation[self_rating_3]" id="innovation[self_rating_3]" value="{{isset($ppr['innovation']['self_rating_3']) ? $ppr['innovation']['self_rating_3'] : ""}}" min="1" max="4" onkeyup="computeWTDScore()" @if($enable_edit == false) disabled @endif></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="innovation[superios_rating_3]" id="innovation[superios_rating_3]" value="{{isset($ppr['innovation']['superios_rating_3']) ? $ppr['innovation']['superios_rating_3'] : ""}}" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="innovation[wtd_score_3]" id="innovation[wtd_score_3]" value="{{isset($ppr['innovation']['wtd_score_3']) ? $ppr['innovation']['wtd_score_3'] : ""}}" min="1" max="5" readonly></td>
                                            </tr>

                                            <tr>
                                                <td colspan="6" class="text-center">&nbsp;</td>
                                            </tr>


                                            {{-- 7 --}}
                                            <tr>
                                                <td rowspan="3" class="text-center text-dark">7. SYNERGY- Ability to work together/ with others for bigger results</td>
                                                <td class="text-center text-dark">Places higher priority on organization's goals than on one's own goals; anticipate the effects of one's own/ area's actions and decisions on the co-workers and partners to meet both areas' needs can be met.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="synergy[self_rating_1]" id="synergy[self_rating_1]" value="{{isset($ppr['synergy']['self_rating_1']) ? $ppr['synergy']['self_rating_1'] : ""}}" min="1" max="4" onkeyup="computeWTDScore()" @if($enable_edit == false) disabled @endif></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="synergy[superios_rating_1]" id="synergy[superios_rating_1]" value="{{isset($ppr['synergy']['self_rating_1']) ? $ppr['synergy']['superios_rating_1'] : ""}}" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="synergy[wtd_score_1]" id="synergy[wtd_score_1]" value="{{isset($ppr['synergy']['self_rating_1']) ? $ppr['synergy']['wtd_score_1'] : ""}}" min="1" max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Instills mutual trust and confidence with/ among groups and individuals in the achievement of organizational shared goals, from the setting of meaningful and specific team performance goals, in the determination of courses of action, facilitation of agreements, and giving of mutual support.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="synergy[self_rating_2]" id="synergy[self_rating_2]" value="{{isset($ppr['synergy']['self_rating_2']) ? $ppr['synergy']['self_rating_2'] : ""}}" min="1" max="4" onkeyup="computeWTDScore()" @if($enable_edit == false) disabled @endif></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="synergy[superios_rating_2]" id="synergy[superios_rating_2]" value="{{isset($ppr['synergy']['superios_rating_2']) ? $ppr['synergy']['superios_rating_2'] : ""}}" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="synergy[wtd_score_2]" id="synergy[wtd_score_2]" value="{{isset($ppr['synergy']['wtd_score_2']) ? $ppr['synergy']['wtd_score_2'] : ""}}" min="1"  max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Acts as a role model in motivating others, fostering and maintaining inclusive (respecting and welcoming differences and diversity) and positive work environment to achieve the organization's goals/ targets.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="synergy[self_rating_3]" id="synergy[self_rating_3]" value="{{isset($ppr['synergy']['self_rating_3']) ? $ppr['synergy']['self_rating_3'] : ""}}" min="1" max="4" onkeyup="computeWTDScore()" @if($enable_edit == false) disabled @endif></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="synergy[superios_rating_3]" id="synergy[superios_rating_3]" value="{{isset($ppr['synergy']['superios_rating_3']) ? $ppr['synergy']['superios_rating_3'] : ""}}" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="synergy[wtd_score_3]" id="synergy[wtd_score_3]" value="{{isset($ppr['synergy']['wtd_score_3']) ? $ppr['synergy']['wtd_score_3'] : ""}}" min="1" max="5" readonly></td>
                                            </tr>

                                            <tr>
                                                <td colspan="6" class="text-center">&nbsp;</td>
                                            </tr>

                                            {{-- 8 --}}
                                            <tr>
                                                <td rowspan="3" class="text-center text-dark">8. RESPONSIBILITY - Ability to be grateful and to take responsibility and accountability for every task and resource entrusted to one's care</td>
                                                <td class="text-center text-dark">Practice habits that keeps the work place clean, safe, and secure; preventing accidents, losses, or damages of any kind.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="stewardship[self_rating_1]" id="stewardship[self_rating_1]" value="{{isset($ppr['stewardship']) ? $ppr['stewardship']['self_rating_1'] : ""}}" min="1" max="4" onkeyup="computeWTDScore()" @if($enable_edit == false) disabled @endif></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="stewardship[superios_rating_1]" id="stewardship[superios_rating_1]" value="{{isset($ppr['stewardship']) ? $ppr['stewardship']['superios_rating_1'] : ""}}" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="stewardship[wtd_score_1]" id="stewardship[wtd_score_1]" value="{{isset($ppr['stewardship']) ? $ppr['stewardship']['wtd_score_1'] : ""}}" min="1" max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Exercise control in the use of company benefits, property, resources, supplies, materials, power, etc.; prevent unnecessary waste/ loss, within the large context of the organization.</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="stewardship[self_rating_2]" id="stewardship[self_rating_2]" value="{{isset($ppr['stewardship']) ? $ppr['stewardship']['self_rating_2'] : ""}}" min="1" max="4" onkeyup="computeWTDScore()" @if($enable_edit == false) disabled @endif></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="stewardship[superios_rating_2]" id="stewardship[superios_rating_2]" value="{{isset($ppr['stewardship']) ? $ppr['stewardship']['superios_rating_2'] : ""}}" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="stewardship[wtd_score_2]" id="stewardship[wtd_score_2]" value="{{isset($ppr['stewardship']) ? $ppr['stewardship']['wtd_score_2'] : ""}}" min="1"  max="5" readonly></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-dark">Seriously perform the roles as entrusted and accountable custodian of Company properties, brand/ reputation, and resources</td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="stewardship[self_rating_3]" id="stewardship[self_rating_3]" value="{{isset($ppr['stewardship']) ? $ppr['stewardship']['self_rating_3'] : ""}}" min="1" max="4" onkeyup="computeWTDScore()" @if($enable_edit == false) disabled @endif></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="stewardship[superios_rating_3]" id="stewardship[superios_rating_3]" value="{{isset($ppr['stewardship']) ? $ppr['stewardship']['superios_rating_3'] : ""}}" min="1" max="5" readonly></td>
                                                <td class="text-center text-dark"><input type="number" class="text-align-center" name="stewardship[wtd_score_3]" id="stewardship[wtd_score_3]" value="{{isset($ppr['stewardship']) ? $ppr['stewardship']['wtd_score_3'] : ""}}" min="1" max="5" readonly></td>
                                            </tr>

                                            <tr>
                                                <td colspan="6" class="text-center">&nbsp;</td>
                                            </tr>


                                        </tbody>
                                    <table>

                                    {{-- Page 3 --}}

                                    @if($ppr_details->employee)
                                        <table class="table-bordered mt-1" width="100%">
                                            <tr>
                                                <td>Group/Business Unit</td>
                                                <td>{{ $ppr_details->employee->company ? $ppr_details->employee->company->company_name : "" }}</td>
                                            </tr>
                                            <tr>
                                                <td>Department/unit</td>
                                                <td>{{ $ppr_details->employee->department ? $ppr_details->employee->department->name : "" }}</td>
                                            </tr>
                                            <tr>
                                                <td>Employee Name</td>
                                                <td>{{ $ppr_details->employee->first_name . " " . $ppr_details->employee->last_name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Position Title</td>
                                                <td>{{ $ppr_details->employee->position}} </td>
                                            </tr>
                                            <tr>
                                                <td>Employee Number</td>
                                                <td>{{ $ppr_details->employee->employee_number}}</td>
                                            </tr>
                                            <tr>
                                                <td>Date Hired</td>
                                                <td>{{ $ppr_details->employee->original_date_hired }}</td>
                                            </tr>
                                        </table>
                                    @endif
                                    
                                    <table class="table-bordered mt-3" width="100%">
                                        <tr>
                                            <td colspan="4" align="center" style="background-color: rgb(240, 240, 240)"><strong>PERFORMANCE & DEVELOPMENT SUMMARY REPORT</strong> </td>
                                        </tr>
                                    </table>
                                    <table class="table-bordered mt-1" width="100%">
                                        <tr>
                                            <td colspan="2" align="center" style="background-color: rgb(240, 240, 240)"><strong>RATEE'S COMMENTS</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" align="center"><textarea style="max-width:2000px!important;" class="responsive-input myinput" name="ratees_comments" cols="30" rows="7" placeholder="Input here" readonly>{{$ppr_details['ppr_score'] ? $ppr_details['ppr_score']['ratees_comments'] : ""}}</textarea></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" align="center" style="background-color: rgb(240, 240, 240)"><strong>SUMMARY OF RATER'S COMMENTS/RECOMMENDATIONS</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" align="center"><textarea style="max-width:2000px!important;" class="responsive-input myinput" name="summary_ratees_comments_recommendation" cols="30" rows="7" placeholder="Input here" @if($enable_edit == false) disabled @endif>{{isset($ppr_details['ppr_score']) ? $ppr_details['ppr_score']['summary_ratees_comments_recommendation'] : ""}}</textarea></td>
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
                                            <td align="center"><input type="number" class="text-align-center" class="text-align-center" id="bsc_weight" min="1" max="100" name="bsc_weight" value="{{$ppr['bsc_weight']}}" readonly></td>
                                            <td align="center"><input type="number" class="text-align-center" class="text-align-center" min="1" max="100" id="bsc_actual_score" name="bsc_actual_score" value="{{ $ppr_details['ppr_score'] ? $ppr_details['ppr_score']['self_assessment_bsc_actual_score']  : ""}}" readonly></td>
                                            <td align="center"><input type="text" class="text-align-center" name="bsc_description" width="100%" class="responsive-input myinput" readonly></td>
                                        </tr>
                                        <tr>
                                            <td align="center">COMPETENCY</td>
                                            <td align="center"><input type="number" class="text-align-center" class="text-align-center" id="competency_weight" min="1" max="100" name="competency_weight" value="{{$ppr['competency_weight']}}" readonly></td>
                                            <td align="center"><input type="number" class="text-align-center" class="text-align-center" min="1" max="100" id="competency_actual_score" name="competency_actual_score" value="{{ $ppr_details['ppr_score'] ? $ppr_details['ppr_score']['self_assessment_competency_actual_score']  : ""}}" readonly></td>
                                            <td align="center"><input type="text" class="text-align-center" name="competency_description" width="100%" class="responsive-input myinput" readonly></td>
                                        </tr>
                                        <tr>
                                            <td align="center">TOTAL</td>
                                            <td align="center"><input type="number" class="text-align-center" class="text-align-center" id="total_weight" min="1" max="100" name="total_weight" readonly value="{{$ppr['total_weight']}}"></td>
                                            <td align="center"><input type="number" class="text-align-center" class="text-align-center" id="total_actual_score" name="total_actual_score" min="1" max="100" value="{{ $ppr_details['ppr_score'] ? $ppr_details['ppr_score']['self_assessment_total_actual_score']  : ""}}" readonly></td>
                                            <td align="center"></td>
                                        </tr>
                                    </table>

                                    <table class="table-bordered mt-1" width="100%">
                                        <tr>
                                            <td width="50%" align="center" style="background-color: rgb(240, 240, 240)"><strong>AREAS OF STRENGTH</strong></td>
                                            <td width="50%" align="center" style="background-color: rgb(240, 240, 240)"><strong>DEVELOPMENTAL NEEDS</strong></td>
                                        </tr>
                                        <tr>
                                            <td align="center"><textarea width="100%" class="responsive-input myinput" name="areas_of_strength" cols="30" rows="7" placeholder="Input here" @if($enable_edit == false) disabled @endif>{{$ppr['areas_of_strength']}}</textarea></td>
                                            <td align="center"><textarea width="100%" class="responsive-input myinput" name="developmental_needs" cols="30" rows="7" placeholder="Input here" @if($enable_edit == false) disabled @endif>{{$ppr['developmental_needs']}}</textarea></td>
                                        </tr>
                                   
                                        <tr>
                                            <td width="50%" align="center" style="background-color: rgb(240, 240, 240)"><strong>AREAS FOR ENHANCEMENT</strong></td>
                                            <td width="50%" align="center" style="background-color: rgb(240, 240, 240)"><strong>TRAINING & DEVELOPMENTAL PLANS</strong></td>
                                        </tr>
                                        <tr>
                                            <td align="center"><textarea width="100%" class="responsive-input myinput" name="areas_for_enhancement" cols="30" rows="7" placeholder="Input here" @if($enable_edit == false) disabled @endif>{{$ppr['areas_for_enhancement']}}</textarea></td>
                                            <td align="center"><textarea width="100%" class="responsive-input myinput" name="training_and_development_plans" cols="30" rows="7" placeholder="Input here" @if($enable_edit == false) disabled @endif>{{$ppr['training_and_development_plans']}}</textarea></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            
                            <div class="text-center mt-5">

                                
                                
                                @if($ppr_details['ppr_score'])
                                    @if($ppr_details['ppr_score']['status'] == 'Approved')
                                        <button type="submit" class="btn btn-success" disabled>Posted</button>
                                    @else
                                        <button type="submit" class="btn btn-primary">Submit Changes</button>
                                        <span id="{{ $ppr['id'] }}" onclick="submitForPosting(this.id)" class="btn btn-success">Submit For Posting</span>
                                    @endif
                                @endif
                                
                            </div>
                        
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

        var inputFields = document.querySelectorAll('.myinput');

        inputFields.forEach(function(inputField) {
        inputField.addEventListener("input", function(event) {
            var currentValue = this.value;
            if (currentValue.includes('"')) {
                this.value = currentValue.slice(0, -1);
            }
            if (currentValue.includes('\\')) {
                this.value = currentValue.slice(0, -1);
            }
        });

        inputField.addEventListener("paste", function(event) {
                var pastedText = (event.clipboardData || window.clipboardData).getData('text');
                if (pastedText.includes('"')) {
                    event.preventDefault();
                }
                if (pastedText.includes('\\')) {
                    event.preventDefault();
                }
            });
        });

		function submitForPosting(id) {
           
            swal({
                title: "Are you sure?",
                text: "You want to submit this Self Assessment for posting?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDisable) => {
                if (willDisable) {
                    document.getElementById("loader").style.display = "block";
                    window.location.href="/submit-self-assessment-for-posting/" + id;
                }
            });
            
		}

      
	</script>
@endsection

<script>

    function computeWTDScore(){
        const integrity_self_rating_1 = parseFloat(document.getElementById('integrity[self_rating_1]').value);
        if(integrity_self_rating_1){
            const wtd_score_1 = Number(integrity_self_rating_1) / 9.6;
            document.getElementById('integrity[wtd_score_1]').value = wtd_score_1.toFixed(2);
        }

        const integrity_self_rating_2 = parseFloat(document.getElementById('integrity[self_rating_2]').value);
        if(integrity_self_rating_2){
            const wtd_score_2 = Number(integrity_self_rating_2) / 9.6;
            document.getElementById('integrity[wtd_score_2]').value = wtd_score_2.toFixed(2);
        }

        const integrity_self_rating_3 = parseFloat(document.getElementById('integrity[self_rating_3]').value);
        if(integrity_self_rating_3){
            const wtd_score_3 = Number(integrity_self_rating_3) / 9.6;
            document.getElementById('integrity[wtd_score_3]').value = wtd_score_3.toFixed(2);
        }

        const commitment_self_rating_1 = parseFloat(document.getElementById('commitment[self_rating_1]').value);
        if(commitment_self_rating_1){
            const wtd_score_1 = Number(commitment_self_rating_1) / 9.6;
            document.getElementById('commitment[wtd_score_1]').value = wtd_score_1.toFixed(2);
        }

        const commitment_self_rating_2 = parseFloat(document.getElementById('commitment[self_rating_2]').value);
        if(commitment_self_rating_2){
            const wtd_score_2 = Number(commitment_self_rating_2) / 9.6;
            document.getElementById('commitment[wtd_score_2]').value = wtd_score_2.toFixed(2);
        }

        const commitment_self_rating_3 = parseFloat(document.getElementById('commitment[self_rating_3]').value);
        if(commitment_self_rating_3){
            const wtd_score_3 = Number(commitment_self_rating_3) / 9.6;
            document.getElementById('commitment[wtd_score_3]').value = wtd_score_3.toFixed(2);
        }

        //-----------------------------

        const humility_self_rating_1 = parseFloat(document.getElementById('humility[self_rating_1]').value);
        if(humility_self_rating_1){
            const wtd_score_1 = Number(humility_self_rating_1) / 9.6;
            document.getElementById('humility[wtd_score_1]').value = wtd_score_1.toFixed(2);
        }

        const humility_self_rating_2 = parseFloat(document.getElementById('humility[self_rating_2]').value);
        if(humility_self_rating_2){
            const wtd_score_2 = Number(humility_self_rating_2) / 9.6;
            document.getElementById('humility[wtd_score_2]').value = wtd_score_2.toFixed(2);
        }

        const humility_self_rating_3 = parseFloat(document.getElementById('humility[self_rating_3]').value);
        if(humility_self_rating_3){
            const wtd_score_3 = Number(humility_self_rating_3) / 9.6;
            document.getElementById('humility[wtd_score_3]').value = wtd_score_3.toFixed(2);
        }

        const genuine_concern_self_rating_1 = parseFloat(document.getElementById('genuine_concern[self_rating_1]').value);
        if(genuine_concern_self_rating_1){
            const wtd_score_1 = Number(genuine_concern_self_rating_1) / 9.6;
            document.getElementById('genuine_concern[wtd_score_1]').value = wtd_score_1.toFixed(2);
        }

        const genuine_concern_self_rating_2 = parseFloat(document.getElementById('genuine_concern[self_rating_2]').value);
        if(genuine_concern_self_rating_2){
            const wtd_score_2 = Number(genuine_concern_self_rating_2) / 9.6;
            document.getElementById('genuine_concern[wtd_score_2]').value = wtd_score_2.toFixed(2);
        }

        const genuine_concern_self_rating_3 = parseFloat(document.getElementById('genuine_concern[self_rating_3]').value);
        if(genuine_concern_self_rating_3){
            const wtd_score_3 = Number(genuine_concern_self_rating_3) / 9.6;
            document.getElementById('genuine_concern[wtd_score_3]').value = wtd_score_3.toFixed(2);
        }

        const premium_service_self_rating_1 = parseFloat(document.getElementById('premium_service[self_rating_1]').value);
        if(premium_service_self_rating_1){
            const wtd_score_1 = Number(premium_service_self_rating_1) / 9.6;
            document.getElementById('premium_service[wtd_score_1]').value = wtd_score_1.toFixed(2);
        }

        const premium_service_self_rating_2 = parseFloat(document.getElementById('premium_service[self_rating_2]').value);
        if(premium_service_self_rating_2){
            const wtd_score_2 = Number(premium_service_self_rating_2) / 9.6;
            document.getElementById('premium_service[wtd_score_2]').value = wtd_score_2.toFixed(2);
        }

        const premium_service_self_rating_3 = parseFloat(document.getElementById('premium_service[self_rating_3]').value);
        if(premium_service_self_rating_3){
            const wtd_score_3 = Number(premium_service_self_rating_3) / 9.6;
            document.getElementById('premium_service[wtd_score_3]').value = wtd_score_3.toFixed(2);
        }

        const innovation_self_rating_1 = parseFloat(document.getElementById('innovation[self_rating_1]').value);
        if(innovation_self_rating_1){
            const wtd_score_1 = Number(innovation_self_rating_1) / 9.6;
            document.getElementById('innovation[wtd_score_1]').value = wtd_score_1.toFixed(2);
        }

        const innovation_self_rating_2 = parseFloat(document.getElementById('innovation[self_rating_2]').value);
        if(innovation_self_rating_2){
            const wtd_score_2 = Number(innovation_self_rating_2) / 9.6;
            document.getElementById('innovation[wtd_score_2]').value = wtd_score_2.toFixed(2);
        }

        const innovation_self_rating_3 = parseFloat(document.getElementById('innovation[self_rating_3]').value);
        if(innovation_self_rating_3){
            const wtd_score_3 = Number(innovation_self_rating_3) / 9.6;
            document.getElementById('innovation[wtd_score_3]').value = wtd_score_3.toFixed(2);
        }

        const synergy_self_rating_1 = parseFloat(document.getElementById('synergy[self_rating_1]').value);
        if(synergy_self_rating_1){
            const wtd_score_1 = Number(synergy_self_rating_1) / 9.6;
            document.getElementById('synergy[wtd_score_1]').value = wtd_score_1.toFixed(2);
        }

        const synergy_self_rating_2 = parseFloat(document.getElementById('synergy[self_rating_2]').value);
        if(synergy_self_rating_2){
            const wtd_score_2 = Number(synergy_self_rating_2) / 9.6;
            document.getElementById('synergy[wtd_score_2]').value = wtd_score_2.toFixed(2);
        }

        const synergy_self_rating_3 = parseFloat(document.getElementById('synergy[self_rating_3]').value);
        if(synergy_self_rating_3){
            const wtd_score_3 = Number(synergy_self_rating_3) / 9.6;
            document.getElementById('synergy[wtd_score_3]').value = wtd_score_3.toFixed(2);
        }

        const stewardship_self_rating_1 = parseFloat(document.getElementById('stewardship[self_rating_1]').value);
        if(stewardship_self_rating_1){
            const wtd_score_1 = Number(stewardship_self_rating_1) / 9.6;
            document.getElementById('stewardship[wtd_score_1]').value = wtd_score_1.toFixed(2);
        }

        const stewardship_self_rating_2 = parseFloat(document.getElementById('stewardship[self_rating_2]').value);
        if(stewardship_self_rating_2){
            const wtd_score_2 = Number(stewardship_self_rating_2) / 9.6;
            document.getElementById('stewardship[wtd_score_2]').value = wtd_score_2.toFixed(2);
        }

        const stewardship_self_rating_3 = parseFloat(document.getElementById('stewardship[self_rating_3]').value);
        if(stewardship_self_rating_3){
            const wtd_score_3 = Number(stewardship_self_rating_3) / 9.6;
            document.getElementById('stewardship[wtd_score_3]').value = wtd_score_3.toFixed(2);
        }

        updateSumTotalSummaryofRatingsWeightActualScore();


    }

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

    function updateSumTotalSummaryofRatingsWeightActualScore() {

        //BSC 
        const fp_strat_1_review_actual_1_actual_grade = getValueById('financial_perspective[strat_1_review_actual_1_actual_grade]');
        const fp_strat_2_review_actual_1_actual_grade = getValueById('financial_perspective[strat_2_review_actual_1_actual_grade]');
        const fp_strat_3_review_actual_1_actual_grade = getValueById('financial_perspective[strat_3_review_actual_1_actual_grade]');
        const fp_strat_1_review_actual_2_actual_grade = getValueById('financial_perspective[strat_1_review_actual_2_actual_grade]');
        const fp_strat_2_review_actual_2_actual_grade = getValueById('financial_perspective[strat_2_review_actual_2_actual_grade]');
        const fp_strat_3_review_actual_2_actual_grade = getValueById('financial_perspective[strat_3_review_actual_3_actual_grade]');
        const sum_fp = fp_strat_1_review_actual_1_actual_grade + fp_strat_2_review_actual_1_actual_grade + fp_strat_3_review_actual_1_actual_grade + fp_strat_1_review_actual_2_actual_grade +fp_strat_2_review_actual_2_actual_grade +fp_strat_3_review_actual_2_actual_grade;
        
        const cf_strat_1_review_actual_1_actual_grade = getValueById('customer_focus[strat_1_review_actual_1_actual_grade]');
        const cf_strat_2_review_actual_1_actual_grade = getValueById('customer_focus[strat_2_review_actual_1_actual_grade]');
        const cf_strat_3_review_actual_1_actual_grade = getValueById('customer_focus[strat_3_review_actual_1_actual_grade]');
        const cf_strat_1_review_actual_2_actual_grade = getValueById('customer_focus[strat_1_review_actual_2_actual_grade]');
        const cf_strat_2_review_actual_2_actual_grade = getValueById('customer_focus[strat_2_review_actual_2_actual_grade]');
        const cf_strat_3_review_actual_2_actual_grade = getValueById('customer_focus[strat_3_review_actual_2_actual_grade]');
        const sum_cf = cf_strat_1_review_actual_1_actual_grade + cf_strat_2_review_actual_1_actual_grade + cf_strat_3_review_actual_1_actual_grade + cf_strat_1_review_actual_2_actual_grade + cf_strat_2_review_actual_2_actual_grade + cf_strat_3_review_actual_2_actual_grade;

        const oe_strat_1_review_actual_1_actual_grade = getValueById('operation_efficiency[strat_1_review_actual_1_actual_grade]');
        const oe_strat_2_review_actual_1_actual_grade = getValueById('operation_efficiency[strat_2_review_actual_1_actual_grade]');
        const oe_strat_3_review_actual_1_actual_grade = getValueById('operation_efficiency[strat_3_review_actual_1_actual_grade]');
        const oe_strat_1_review_actual_2_actual_grade = getValueById('operation_efficiency[strat_1_review_actual_2_actual_grade]');
        const oe_strat_2_review_actual_2_actual_grade = getValueById('operation_efficiency[strat_2_review_actual_2_actual_grade]');
        const oe_strat_3_review_actual_2_actual_grade = getValueById('operation_efficiency[strat_3_review_actual_2_actual_grade]');
        const sum_oe = oe_strat_1_review_actual_1_actual_grade + oe_strat_2_review_actual_1_actual_grade + oe_strat_3_review_actual_1_actual_grade + oe_strat_1_review_actual_2_actual_grade + oe_strat_2_review_actual_2_actual_grade + oe_strat_3_review_actual_2_actual_grade;


        const p_strat_1_review_actual_1_actual_grade = getValueById('people[strat_1_review_actual_1_actual_grade]');
        const p_strat_2_review_actual_1_actual_grade = getValueById('people[strat_2_review_actual_1_actual_grade]');
        const p_strat_3_review_actual_1_actual_grade = getValueById('people[strat_3_review_actual_1_actual_grade]');
        const p_strat_1_review_actual_2_actual_grade = getValueById('people[strat_1_review_actual_2_actual_grade]');
        const p_strat_2_review_actual_2_actual_grade = getValueById('people[strat_2_review_actual_2_actual_grade]');
        const p_strat_3_review_actual_2_actual_grade = getValueById('people[strat_3_review_actual_2_actual_grade]');
        const sum_p = p_strat_1_review_actual_1_actual_grade + p_strat_2_review_actual_1_actual_grade + p_strat_3_review_actual_1_actual_grade + p_strat_1_review_actual_2_actual_grade + p_strat_2_review_actual_2_actual_grade + p_strat_3_review_actual_2_actual_grade;


        const bsc_actual_score = sum_fp + sum_cf + sum_oe + sum_p;


        const integrity_wtd_score_1 = getValueById('integrity[wtd_score_1]');
        const integrity_wtd_score_2 = getValueById('integrity[wtd_score_2]');
        const integrity_wtd_score_3 = getValueById('integrity[wtd_score_3]');

        const commitment_wtd_score_1 = getValueById('commitment[wtd_score_1]');
        const commitment_wtd_score_2 = getValueById('commitment[wtd_score_2]');
        const commitment_wtd_score_3 = getValueById('commitment[wtd_score_3]');

        const humility_wtd_score_1 = getValueById('humility[wtd_score_1]');
        const humility_wtd_score_2 = getValueById('humility[wtd_score_2]');
        const humility_wtd_score_3 = getValueById('humility[wtd_score_3]');

        const genuine_concern_wtd_score_1 = getValueById('genuine_concern[wtd_score_1]');
        const genuine_concern_wtd_score_2 = getValueById('genuine_concern[wtd_score_2]');
        const genuine_concern_wtd_score_3 = getValueById('genuine_concern[wtd_score_3]');

        const premium_service_wtd_score_1 = getValueById('premium_service[wtd_score_1]');
        const premium_service_wtd_score_2 = getValueById('premium_service[wtd_score_2]');
        const premium_service_wtd_score_3 = getValueById('premium_service[wtd_score_3]');

        const innovation_wtd_score_1 = getValueById('innovation[wtd_score_1]');
        const innovation_wtd_score_2 = getValueById('innovation[wtd_score_2]');
        const innovation_wtd_score_3 = getValueById('innovation[wtd_score_3]');

        const synergy_wtd_score_1 = getValueById('synergy[wtd_score_1]');
        const synergy_wtd_score_2 = getValueById('synergy[wtd_score_2]');
        const synergy_wtd_score_3 = getValueById('synergy[wtd_score_3]');

        const stewardship_wtd_score_1 = getValueById('stewardship[wtd_score_1]');
        const stewardship_wtd_score_2 = getValueById('stewardship[wtd_score_2]');
        const stewardship_wtd_score_3 = getValueById('stewardship[wtd_score_3]');

        const competency_actual_score = integrity_wtd_score_1 + integrity_wtd_score_2 + integrity_wtd_score_3 +
                                        commitment_wtd_score_1 + commitment_wtd_score_2 + commitment_wtd_score_3 +
                                        humility_wtd_score_1 + humility_wtd_score_2 + humility_wtd_score_3 +
                                        genuine_concern_wtd_score_1 + genuine_concern_wtd_score_2 + genuine_concern_wtd_score_3 +
                                        premium_service_wtd_score_1 + premium_service_wtd_score_2 + premium_service_wtd_score_3 +
                                        innovation_wtd_score_1 + innovation_wtd_score_2 + innovation_wtd_score_3 +
                                        synergy_wtd_score_1 + synergy_wtd_score_2 + synergy_wtd_score_3 +
                                        stewardship_wtd_score_1 + stewardship_wtd_score_2 + stewardship_wtd_score_3;

        const total_actual_score = bsc_actual_score + competency_actual_score;

        document.getElementById('bsc_actual_score').value = Math.floor(bsc_actual_score);
        document.getElementById('competency_actual_score').value = Math.floor(competency_actual_score);
        document.getElementById('total_actual_score').value = Math.floor(total_actual_score);

    }

    function getValueById(id) {
        var element = document.getElementById(id);
        if (element) {
            if (element.value) {
                return parseFloat(element.value) || 0;
            } else {
                return 0;
            }
        }else{
            return 0;
        }
    }

    function computeActualGradeFinancialPerspective(){
        
        const strat_1_weight_1 = parseFloat(document.getElementById('financial_perspective[strat_1_weight_1]').value);
        if(strat_1_weight_1){
            const strat_1_review_actual_1 = parseFloat(document.getElementById('financial_perspective[strat_1_review_actual_1]').value);
            const strat_1_review_actual_1_actual_grade = (Number(strat_1_review_actual_1) / 5) * Number(strat_1_weight_1);
            document.getElementById('financial_perspective[strat_1_review_actual_1_actual_grade]').value = strat_1_review_actual_1_actual_grade;
        }

        const strat_2_weight_1 = parseFloat(document.getElementById('financial_perspective[strat_2_weight_1]').value);
        if(strat_2_weight_1){
            const strat_2_review_actual_1 = parseFloat(document.getElementById('financial_perspective[strat_2_review_actual_1]').value);
            const strat_2_review_actual_1_actual_grade = (Number(strat_2_review_actual_1) / 5) * Number(strat_2_weight_1);
            document.getElementById('financial_perspective[strat_2_review_actual_1_actual_grade]').value = strat_2_review_actual_1_actual_grade;
        }

        const strat_3_weight_1 = parseFloat(document.getElementById('financial_perspective[strat_3_weight_1]').value);
        if(strat_3_weight_1){
            const strat_3_review_actual_1 = parseFloat(document.getElementById('financial_perspective[strat_3_review_actual_1]').value);
            const strat_3_review_actual_1_actual_grade = (Number(strat_3_review_actual_1) / 5) * Number(strat_3_weight_1);
            document.getElementById('financial_perspective[strat_3_review_actual_1_actual_grade]').value = strat_3_review_actual_1_actual_grade;
        }

        const strat_1_weight_2= parseFloat(document.getElementById('financial_perspective[strat_1_weight_2]').value);
        if(strat_1_weight_2){
            const strat_1_review_actual_2 = parseFloat(document.getElementById('financial_perspective[strat_1_review_actual_2]').value);
            const strat_1_review_actual_2_actual_grade = (Number(strat_1_review_actual_2) / 5) * Number(strat_1_weight_2);
            document.getElementById('financial_perspective[strat_1_review_actual_2_actual_grade]').value = strat_1_review_actual_2_actual_grade;
        }

        const strat_2_weight_2 = parseFloat(document.getElementById('financial_perspective[strat_2_weight_2]').value);
        if(strat_2_weight_2){
            const strat_2_review_actual_2 = parseFloat(document.getElementById('financial_perspective[strat_2_review_actual_2]').value);
            const strat_2_review_actual_2_actual_grade = (Number(strat_2_review_actual_2) / 5) * Number(strat_2_weight_2);
            document.getElementById('financial_perspective[strat_2_review_actual_2_actual_grade]').value = strat_2_review_actual_2_actual_grade;
        }

        const strat_3_weight_2 = parseFloat(document.getElementById('financial_perspective[strat_3_weight_2]').value);
        if(strat_3_weight_2){
            const strat_3_review_actual_2 = parseFloat(document.getElementById('financial_perspective[strat_3_review_actual_2]').value);
            const strat_3_review_actual_2_actual_grade = (Number(strat_3_review_actual_2) / 5) * Number(strat_3_weight_2);
            document.getElementById('financial_perspective[strat_3_review_actual_2_actual_grade]').value = strat_3_review_actual_2_actual_grade;
        }

        updateSumTotalSummaryofRatingsWeightActualScore();
    }

    function computeActualGradeCustomerFocus(){
        const strat_1_weight_1 = parseFloat(document.getElementById('customer_focus[strat_1_weight_1]').value);
        if(strat_1_weight_1){
            const strat_1_review_actual_1 = parseFloat(document.getElementById('customer_focus[strat_1_review_actual_1]').value);
            const strat_1_review_actual_1_actual_grade = (Number(strat_1_review_actual_1) / 5) * Number(strat_1_weight_1);
            document.getElementById('customer_focus[strat_1_review_actual_1_actual_grade]').value = strat_1_review_actual_1_actual_grade;
        }

        const strat_2_weight_1 = parseFloat(document.getElementById('customer_focus[strat_2_weight_1]').value);
        if(strat_2_weight_1){
            const strat_2_review_actual_1 = parseFloat(document.getElementById('customer_focus[strat_2_review_actual_1]').value);
            const strat_2_review_actual_1_actual_grade = (Number(strat_2_review_actual_1) / 5) * Number(strat_2_weight_1);
            document.getElementById('customer_focus[strat_2_review_actual_1_actual_grade]').value = strat_2_review_actual_1_actual_grade;
        }

        const strat_3_weight_1 = parseFloat(document.getElementById('customer_focus[strat_3_weight_1]').value);
        if(strat_3_weight_1){
            const strat_3_review_actual_1 = parseFloat(document.getElementById('customer_focus[strat_3_review_actual_1]').value);
            const strat_3_review_actual_1_actual_grade = (Number(strat_3_review_actual_1) / 5) * Number(strat_3_weight_1);
            document.getElementById('customer_focus[strat_3_review_actual_1_actual_grade]').value = strat_3_review_actual_1_actual_grade;
        }

        const strat_1_weight_2= parseFloat(document.getElementById('customer_focus[strat_1_weight_2]').value);
        if(strat_1_weight_2){
            const strat_1_review_actual_2 = parseFloat(document.getElementById('customer_focus[strat_1_review_actual_2]').value);
            const strat_1_review_actual_2_actual_grade = (Number(strat_1_review_actual_2) / 5) * Number(strat_1_weight_2);
            document.getElementById('customer_focus[strat_1_review_actual_2_actual_grade]').value = strat_1_review_actual_2_actual_grade;
        }

        const strat_2_weight_2 = parseFloat(document.getElementById('customer_focus[strat_2_weight_2]').value);
        if(strat_2_weight_2){
            const strat_2_review_actual_2 = parseFloat(document.getElementById('customer_focus[strat_2_review_actual_2]').value);
            const strat_2_review_actual_2_actual_grade = (Number(strat_2_review_actual_2) / 5) * Number(strat_2_weight_2);
            document.getElementById('customer_focus[strat_2_review_actual_2_actual_grade]').value = strat_2_review_actual_2_actual_grade;
        }

        const strat_3_weight_2 = parseFloat(document.getElementById('customer_focus[strat_3_weight_2]').value);
        if(strat_3_weight_2){
            const strat_3_review_actual_2 = parseFloat(document.getElementById('customer_focus[strat_3_review_actual_2]').value);
            const strat_3_review_actual_2_actual_grade = (Number(strat_3_review_actual_2) / 5) * Number(strat_3_weight_2);
            document.getElementById('customer_focus[strat_3_review_actual_2_actual_grade]').value = strat_3_review_actual_2_actual_grade;
        }

        updateSumTotalSummaryofRatingsWeightActualScore();
    }

    function computeActualGradeOperationEfficiency(){
        const strat_1_weight_1 = parseFloat(document.getElementById('operation_efficiency[strat_1_weight_1]').value);
        if(strat_1_weight_1){
            const strat_1_review_actual_1 = parseFloat(document.getElementById('operation_efficiency[strat_1_review_actual_1]').value);
            const strat_1_review_actual_1_actual_grade = (Number(strat_1_review_actual_1) / 5) * Number(strat_1_weight_1);
            document.getElementById('operation_efficiency[strat_1_review_actual_1_actual_grade]').value = strat_1_review_actual_1_actual_grade;
        }

        const strat_2_weight_1 = parseFloat(document.getElementById('operation_efficiency[strat_2_weight_1]').value);
        if(strat_2_weight_1){
            const strat_2_review_actual_1 = parseFloat(document.getElementById('operation_efficiency[strat_2_review_actual_1]').value);
            const strat_2_review_actual_1_actual_grade = (Number(strat_2_review_actual_1) / 5) * Number(strat_2_weight_1);
            document.getElementById('operation_efficiency[strat_2_review_actual_1_actual_grade]').value = strat_2_review_actual_1_actual_grade;
        }

        const strat_3_weight_1 = parseFloat(document.getElementById('operation_efficiency[strat_3_weight_1]').value);
        if(strat_3_weight_1){
            const strat_3_review_actual_1 = parseFloat(document.getElementById('operation_efficiency[strat_3_review_actual_1]').value);
            const strat_3_review_actual_1_actual_grade = (Number(strat_3_review_actual_1) / 5) * Number(strat_3_weight_1);
            document.getElementById('operation_efficiency[strat_3_review_actual_1_actual_grade]').value = strat_3_review_actual_1_actual_grade;
        }

        const strat_1_weight_2= parseFloat(document.getElementById('operation_efficiency[strat_1_weight_2]').value);
        if(strat_1_weight_2){
            const strat_1_review_actual_2 = parseFloat(document.getElementById('operation_efficiency[strat_1_review_actual_2]').value);
            const strat_1_review_actual_2_actual_grade = (Number(strat_1_review_actual_2) / 5) * Number(strat_1_weight_2);
            document.getElementById('operation_efficiency[strat_1_review_actual_2_actual_grade]').value = strat_1_review_actual_2_actual_grade;
        }

        const strat_2_weight_2 = parseFloat(document.getElementById('operation_efficiency[strat_2_weight_2]').value);
        if(strat_2_weight_2){
            const strat_2_review_actual_2 = parseFloat(document.getElementById('operation_efficiency[strat_2_review_actual_2]').value);
            const strat_2_review_actual_2_actual_grade = (Number(strat_2_review_actual_2) / 5) * Number(strat_2_weight_2);
            document.getElementById('operation_efficiency[strat_2_review_actual_2_actual_grade]').value = strat_2_review_actual_2_actual_grade;
        }

        const strat_3_weight_2 = parseFloat(document.getElementById('operation_efficiency[strat_3_weight_2]').value);
        if(strat_3_weight_2){
            const strat_3_review_actual_2 = parseFloat(document.getElementById('operation_efficiency[strat_3_review_actual_2]').value);
            const strat_3_review_actual_2_actual_grade = (Number(strat_3_review_actual_2) / 5) * Number(strat_3_weight_2);
            document.getElementById('operation_efficiency[strat_3_review_actual_2_actual_grade]').value = strat_3_review_actual_2_actual_grade;
        }

        updateSumTotalSummaryofRatingsWeightActualScore();
    }

    function computeActualGradePeople(){
        const strat_1_weight_1 = parseFloat(document.getElementById('people[strat_1_weight_1]').value);
        if(strat_1_weight_1){
            const strat_1_review_actual_1 = parseFloat(document.getElementById('people[strat_1_review_actual_1]').value);
            const strat_1_review_actual_1_actual_grade = (Number(strat_1_review_actual_1) / 5) * Number(strat_1_weight_1);
            document.getElementById('people[strat_1_review_actual_1_actual_grade]').value = strat_1_review_actual_1_actual_grade;
        }

        const strat_2_weight_1 = parseFloat(document.getElementById('people[strat_2_weight_1]').value);
        if(strat_2_weight_1){
            const strat_2_review_actual_1 = parseFloat(document.getElementById('people[strat_2_review_actual_1]').value);
            const strat_2_review_actual_1_actual_grade = (Number(strat_2_review_actual_1) / 5) * Number(strat_2_weight_1);
            document.getElementById('people[strat_2_review_actual_1_actual_grade]').value = strat_2_review_actual_1_actual_grade;
        }

        const strat_3_weight_1 = parseFloat(document.getElementById('people[strat_3_weight_1]').value);
        if(strat_3_weight_1){
            const strat_3_review_actual_1 = parseFloat(document.getElementById('people[strat_3_review_actual_1]').value);
            const strat_3_review_actual_1_actual_grade = (Number(strat_3_review_actual_1) / 5) * Number(strat_3_weight_1);
            document.getElementById('people[strat_3_review_actual_1_actual_grade]').value = strat_3_review_actual_1_actual_grade;
        }

        const strat_1_weight_2= parseFloat(document.getElementById('people[strat_1_weight_2]').value);
        if(strat_1_weight_2){
            const strat_1_review_actual_2 = parseFloat(document.getElementById('people[strat_1_review_actual_2]').value);
            const strat_1_review_actual_2_actual_grade = (Number(strat_1_review_actual_2) / 5) * Number(strat_1_weight_2);
            document.getElementById('people[strat_1_review_actual_2_actual_grade]').value = strat_1_review_actual_2_actual_grade;
        }

        const strat_2_weight_2 = parseFloat(document.getElementById('people[strat_2_weight_2]').value);
        if(strat_2_weight_2){
            const strat_2_review_actual_2 = parseFloat(document.getElementById('people[strat_2_review_actual_2]').value);
            const strat_2_review_actual_2_actual_grade = (Number(strat_2_review_actual_2) / 5) * Number(strat_2_weight_2);
            document.getElementById('people[strat_2_review_actual_2_actual_grade]').value = strat_2_review_actual_2_actual_grade;
        }

        const strat_3_weight_2 = parseFloat(document.getElementById('people[strat_3_weight_2]').value);
        if(strat_3_weight_2){
            const strat_3_review_actual_2 = parseFloat(document.getElementById('people[strat_3_review_actual_2]').value);
            const strat_3_review_actual_2_actual_grade = (Number(strat_3_review_actual_2) / 5) * Number(strat_3_weight_2);
            document.getElementById('people[strat_3_review_actual_2_actual_grade]').value = strat_3_review_actual_2_actual_grade;
        }

        updateSumTotalSummaryofRatingsWeightActualScore();
    }

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
