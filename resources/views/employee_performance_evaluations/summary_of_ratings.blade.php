@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
            <div class="col-lg-12">
                <div class="card">
                  
                    <div class="card-body" >
                        <h4 class="card-title" id="tdActionId{{ $ppr['id'] }}" data-id="{{ $ppr['id'] }}">PREVIEW SUMMARY OF RATINGS 
                            <button class="btn btn-primary btn-sm" onclick="printDiv('contentToPrint')">Print</button>
                        </h4>
                        {{-- Page 3 --}}
                        <div class="table-responsive" id="contentToPrint" width="100%">

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
                                <tr>
                                    <td colspan="2" align="center" style="background-color: rgb(240, 240, 240)"><strong>RATEE'S COMMENTS</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="center" height="100px">{{$ppr_details['ppr_score'] ? $ppr_details['ppr_score']['ratees_comments'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="center" height="100px">I hereby certify that the performance review/ evaluation as summarized above has been meaningfully discused with me, by my Immediate Superior on the date indicated herein based on our agreed set goals and job objectives.</td>
                                </tr>
                                <tr>
                                    <td width="50%" height="100px">
                                        <strong>Certified and Conformed by:</strong> <br><br>
                                        <center>
                                            <hr width="60%"> <strong>Ratee</strong>  <br><br>
                                        </center>
                                    </td>
                                    <td width="50%" height="100px">
                                        <strong>Evaluated by:</strong>  <br><br>
                                        <center>
                                            <hr width="60%"> <strong>Immediate Head</strong>  <br><br>
                                        </center>
                                    </td>
                                </tr>
                            </table>
                            <table class="table-bordered mt-1" width="100%">
                                <tr>
                                    <td colspan="4" align="center" style="background-color: rgb(240, 240, 240)"><strong>SUMMARY OF RATINGS</strong></td>
                                </tr>
                                <tr>
                                    <td align="center">RATING COMPONENTS</td>
                                    <td align="center">WEIGHT</td>
                                    <td align="center">AVE ACTUAL SCORE</td>
                                    <td align="center">TOTAL WTD SCORE</td>
                                    <td align="center">DESCRIPTION</td>
                                </tr>
                                <tr>
                                    <td align="center">BSC</td>
                                    <td align="center">{{$ppr['bsc_weight']}}</td>
                                    <td align="center">{{ $ppr_score ? $ppr_score['manager_assessment_bsc_actual_score'] : ""}}</td>
                                    <td align="center">{{ $ppr_score ? $ppr_score['manager_assessment_bsc_wtd_rating'] : ""}}</td>
                       
                                    <td align="center" rowspan="3">
                                        <span id="manager_equivalent_rating_description_label">{{ $ppr_score ? $ppr_score['manager_equivalent_rating_description'] : ""}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">COMPETENCY</td>
                                    <td align="center">{{$ppr['competency_weight']}}</td>
                                    <td align="center">{{ $ppr_score ? $ppr_score['manager_assessment_competency_actual_score'] : ""}}</td>
                                    <td align="center">{{ $ppr_score ? $ppr_score['manager_assessment_competency_wtd_rating'] : ""}}</td>
                                    
                                </tr>
                                <tr>
                                    @php
                                        $bsc_actual_score = $ppr_score ? $ppr_score['manager_assessment_bsc_actual_score'] : 0;
                                        $competency_actual_score = $ppr_score ? $ppr_score['manager_assessment_competency_actual_score'] : 0;
                                        $total_ave_score = $bsc_actual_score + $competency_actual_score;

                                        $bsc_wtd_score = $ppr_score ? $ppr_score['manager_assessment_bsc_wtd_rating'] : 0;
                                        $competency_wtd_score = $ppr_score ? $ppr_score['manager_assessment_competency_wtd_rating'] : 0;
                                        $total_wtd_score = $bsc_wtd_score + $competency_wtd_score;
                                    @endphp
                                    <td align="center">TOTAL</td>
                                    <td align="center">{{$ppr['total_weight']}}</td>
                                    <td align="center">{{ number_format($total_ave_score,3) }}</td>
                                    <td align="center">{{ number_format($total_wtd_score,3) }}</td>
                                  
                                </tr>
                            </table>

                            <table class="table-bordered mt-1" width="100%">
                                <tr>
                                    <td colspan="2" width="100%" align="center" style="background-color: rgb(240, 240, 240)" ><strong>AREAS OF STRENGTH</strong></td>
                                
                                </tr>
                                <tr>
                                    <td colspan="2" align="center" height="100px">{{isset($ppr_details['ppr_score']) ? $ppr_details['ppr_score']['areas_of_strength'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" width="100%" align="center" style="background-color: rgb(240, 240, 240)" ><strong>DEVELOPMENTAL NEEDS</strong></td>
                                </tr>
                                <tr>
                                    
                                    <td colspan="2" align="center" height="100px">{{isset($ppr_details['ppr_score']) ? $ppr_details['ppr_score']['developmental_needs'] : ""}}</td>
                                </tr>
                        
                                <tr>
                                    <td colspan="2" width="100%" align="center" style="background-color: rgb(240, 240, 240)" ><strong>AREAS FOR ENHANCEMENT</strong></td>
                                    
                                </tr>
                                <tr>
                                    <td colspan="2" align="center" height="100px">{{isset($ppr_details['ppr_score']) ? $ppr_details['ppr_score']['areas_for_enhancements'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" width="100%" align="center" style="background-color: rgb(240, 240, 240)" ><strong>TRAINING & DEVELOPMENTAL PLANS</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="center" height="100px">{{ isset($ppr_details['ppr_score']) ? $ppr_details['ppr_score']['training_and_development_plans'] : ""}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="center" style="background-color: rgb(240, 240, 240)"><strong>SUMMARY OF RATER'S COMMENTS/RECOMMENDATIONS</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="center" height="100px">{{isset($ppr_details['ppr_score']) ? $ppr_details['ppr_score']['summary_of_ratee_comments_recommendations'] : ""}}</td>
                                </tr>

                                <tr>
                                    <td width="50%" height="100px">
                                        <strong>Approved by:</strong> <br><br>
                                        <center>
                                            <hr width="60%"> <strong>Department/Division/BU Head</strong>  <br><br>
                                        </center>
                                    </td>
                                    <td width="50%" height="100px">
                                        <strong>Noted by:</strong>  <br><br>
                                        <center>
                                            <hr width="60%"> <strong>HRD Head</strong> <br><br>
                                        </center>
                                    </td>
                                </tr>
                            </table>
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
