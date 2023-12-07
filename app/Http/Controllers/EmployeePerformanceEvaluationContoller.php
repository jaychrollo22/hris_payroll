<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\EmployeePerformanceEvaluation;
use App\Company;

use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeePerformanceEvaluationContoller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status = $request->status ? $request->status : "";
        $employee_performance_evaluation = EmployeePerformanceEvaluation::with('user')
                                                                                ->where('user_id',Auth::user()->id)
                                                                                ->when(!empty($status),function($q) use($status){
                                                                                    $q->where('status',$status);
                                                                                })
                                                                                ->orderBy('review_date','DESC')
                                                                                ->get();

        return view('employee_performance_evaluations.index',array(
            'header' => 'employee_performance_evaluations',
            'employee_performance_evaluation' => $employee_performance_evaluation,
            'status' => $status
        ));


    }

    public function hr_index(Request $request)
    {

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $search = isset($request->search) ? $request->search : "";
        $company = isset($request->company) ? $request->company : "";

        $status = $request->status ? $request->status : "";
        $employee_performance_evaluation = EmployeePerformanceEvaluation::with('user','employee')
                                                                                ->when(!empty($status),function($q) use($status){
                                                                                    $q->where('status',$status);
                                                                                })
                                                                                ->when(!empty($search),function($q) use($search){
                                                                                    $q->whereHas('employee',function($w) use($search){
                                                                                        $w->where('first_name', 'like' , '%' .  $search . '%')->orWhere('last_name', 'like' , '%' .  $search . '%')
                                                                                        ->orWhere('employee_number', 'like' , '%' .  $search . '%')
                                                                                        ->orWhereRaw("CONCAT(`first_name`, ' ', `last_name`) LIKE ?", ["%{$search}%"])
                                                                                        ->orWhereRaw("CONCAT(`last_name`, ' ', `first_name`) LIKE ?", ["%{$search}%"]);
                                                                                    });
                                                                                })
                                                                                ->when(!empty($company),function($q) use($company){
                                                                                    $q->whereHas('employee',function($w) use($company){
                                                                                        $w->where('company_id',$company);
                                                                                    });
                                                                                })
                                                                                ->whereHas('employee',function($q) use($allowed_companies){
                                                                                    $q->whereIn('company_id',$allowed_companies);
                                                                                })
                                                                                ->orderBy('review_date','DESC')
                                                                                ->get();
        
        $companies = Company::whereIn('id',$allowed_companies)
                                    ->orderBy('company_name','ASC')
                                    ->get();

        return view('employee_performance_evaluations.hr_index',array(
            'header' => 'employee_performance_evaluations_report',
            'employee_performance_evaluation' => $employee_performance_evaluation,
            'status' => $status,
            'companies' => $companies,
            'search' => $search,
            'company' => $company,
        ));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('employee_performance_evaluations.create',array(
            'header' => 'employee_performance_evaluations',
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate_ppr = EmployeePerformanceEvaluation::where('user_id',Auth::user()->id)
                                                        ->where('period',$request->period)
                                                        ->first();
        if(empty($validate_ppr)){

            $new_eval = new EmployeePerformanceEvaluation;
            $new_eval->user_id = Auth::user()->id;
            $new_eval->calendar_year = date('Y');
            $new_eval->review_date = date('Y-m-d h:i:s');
            $new_eval->period = $request->period;
            $new_eval->financial_perspective = $request->financial_perspective ? json_encode($request->financial_perspective,true) : "";
            $new_eval->customer_focus = $request->customer_focus ? json_encode($request->customer_focus,true) : "";
            $new_eval->operation_efficiency = $request->operation_efficiency ? json_encode($request->operation_efficiency,true) : "";
            $new_eval->people = $request->people ? json_encode($request->people,true) : "";
            $new_eval->integrity = $request->integrity ? json_encode($request->integrity,true) : "";
            $new_eval->commitment = $request->commitment ? json_encode($request->commitment,true) : "";
            $new_eval->humility = $request->humility ? json_encode($request->humility,true) : "";
            $new_eval->genuine_concern = $request->genuine_concern ? json_encode($request->genuine_concern,true) : "";
            $new_eval->premium_service = $request->premium_service ? json_encode($request->premium_service,true) : "";
            $new_eval->innovation = $request->innovation ? json_encode($request->innovation,true) : "";
            $new_eval->synergy = $request->synergy ? json_encode($request->synergy,true) : "";
            $new_eval->stewardship = $request->stewardship ? json_encode($request->stewardship,true) : "";

            $new_eval->bsc_weight = $request->bsc_weight;
            $new_eval->bsc_actual_score = $request->bsc_actual_score;
            $new_eval->bsc_description = $request->bsc_description;
            $new_eval->competency_weight = $request->competency_weight;
            $new_eval->competency_actual_score = $request->competency_actual_score;
            $new_eval->competency_description = $request->competency_description;
            $new_eval->competency_actual_score = $request->competency_actual_score;

            $new_eval->areas_of_strength = $request->areas_of_strength;
            $new_eval->developmental_needs = $request->developmental_needs;
            $new_eval->areas_for_enhancement = $request->areas_for_enhancement;
            $new_eval->training_and_development_plans = $request->training_and_development_plans;

            $new_eval->total_weight = $request->total_weight;
            $new_eval->total_actual_score = $request->total_actual_score;

            $new_eval->status = 'Draft';
            $new_eval->save();

            Alert::success('Successfully Store')->persistent('Dismiss');
            return redirect('/performance-plan-review');
        }else{
            Alert::warning('PPR Exists')->persistent('Dismiss');
            return back();
        }

    }

    public function submitForReview($id){

        $ppr = EmployeePerformanceEvaluation::where('user_id',Auth::user()->id)
                                                        ->where('id',$id)
                                                        ->first();
        if($ppr){
            $ppr->status = "For Review";
            $ppr->save();

            Alert::success('Successfully Change to for Review')->persistent('Dismiss');
            return redirect('/performance-plan-review');

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ppr = EmployeePerformanceEvaluation::where('id',$id)->first();

                                                  
        $employee_performance_evaluation = [];
        $employee_performance_evaluation['id'] = $ppr->id;
        $employee_performance_evaluation['calendar_year'] = $ppr->calendar_year;
        $employee_performance_evaluation['review_date'] = $ppr->review_date;
        $employee_performance_evaluation['period'] = $ppr->period;
        $employee_performance_evaluation['financial_perspective'] = json_decode($ppr->financial_perspective,true);
        $employee_performance_evaluation['customer_focus'] = json_decode($ppr->customer_focus,true);
        $employee_performance_evaluation['operation_efficiency'] = json_decode($ppr->operation_efficiency,true);
        $employee_performance_evaluation['people'] = json_decode($ppr->people,true);
        $employee_performance_evaluation['integrity'] = json_decode($ppr->integrity,true);
        $employee_performance_evaluation['commitment'] = json_decode($ppr->commitment,true);
        $employee_performance_evaluation['humility'] = json_decode($ppr->humility,true);
        $employee_performance_evaluation['genuine_concern'] = json_decode($ppr->genuine_concern,true);
        $employee_performance_evaluation['premium_service'] = json_decode($ppr->premium_service,true);
        $employee_performance_evaluation['innovation'] = json_decode($ppr->innovation,true);
        $employee_performance_evaluation['synergy'] = json_decode($ppr->synergy,true);
        $employee_performance_evaluation['stewardship'] = json_decode($ppr->stewardship,true);
        $employee_performance_evaluation['areas_of_strength'] = $ppr->areas_of_strength;
        $employee_performance_evaluation['developmental_needs'] = $ppr->developmental_needs;
        $employee_performance_evaluation['areas_for_enhancement'] = $ppr->areas_for_enhancement;
        $employee_performance_evaluation['training_and_development_plans'] = $ppr->training_and_development_plans;
        $employee_performance_evaluation['bsc_weight'] = $ppr->bsc_weight;
        $employee_performance_evaluation['bsc_actual_score'] = $ppr->bsc_actual_score;
        $employee_performance_evaluation['bsc_description'] = $ppr->bsc_description;
        $employee_performance_evaluation['competency_weight'] = $ppr->competency_weight;
        $employee_performance_evaluation['competency_actual_score'] = $ppr->competency_actual_score;
        $employee_performance_evaluation['competency_description'] = $ppr->competency_description;
        $employee_performance_evaluation['total_weight'] = $ppr->total_weight;
        $employee_performance_evaluation['total_actual_score'] = $ppr->total_actual_score;


        $employee_performance_evaluation['ratees_comments'] = $ppr->ratees_comments;
        $employee_performance_evaluation['summary_ratees_comments_recommendation'] = $ppr->summary_ratees_comments_recommendation;
        
        $employee_performance_evaluation['status'] = $ppr->status;

        return view('employee_performance_evaluations.view',array(
            'header' => 'employee_performance_evaluations',
            'ppr' => $employee_performance_evaluation,
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ppr = EmployeePerformanceEvaluation::where('user_id',Auth::user()->id)
                                                        ->where('id',$id)
                                                        ->first();
        if($ppr){                                             
            $employee_performance_evaluation = [];
            $employee_performance_evaluation['id'] = $ppr->id;
            $employee_performance_evaluation['calendar_year'] = $ppr->calendar_year;
            $employee_performance_evaluation['review_date'] = $ppr->review_date;
            $employee_performance_evaluation['period'] = $ppr->period;
            $employee_performance_evaluation['financial_perspective'] = json_decode($ppr->financial_perspective,true);
            $employee_performance_evaluation['customer_focus'] = json_decode($ppr->customer_focus,true);
            $employee_performance_evaluation['operation_efficiency'] = json_decode($ppr->operation_efficiency,true);
            $employee_performance_evaluation['people'] = json_decode($ppr->people,true);
            $employee_performance_evaluation['integrity'] = json_decode($ppr->integrity,true);
            $employee_performance_evaluation['commitment'] = json_decode($ppr->commitment,true);
            $employee_performance_evaluation['humility'] = json_decode($ppr->humility,true);
            $employee_performance_evaluation['genuine_concern'] = json_decode($ppr->genuine_concern,true);
            $employee_performance_evaluation['premium_service'] = json_decode($ppr->premium_service,true);
            $employee_performance_evaluation['innovation'] = json_decode($ppr->innovation,true);
            $employee_performance_evaluation['synergy'] = json_decode($ppr->synergy,true);
            $employee_performance_evaluation['stewardship'] = json_decode($ppr->stewardship,true);
            $employee_performance_evaluation['areas_of_strength'] = $ppr->areas_of_strength;
            $employee_performance_evaluation['developmental_needs'] = $ppr->developmental_needs;
            $employee_performance_evaluation['areas_for_enhancement'] = $ppr->areas_for_enhancement;
            $employee_performance_evaluation['training_and_development_plans'] = $ppr->training_and_development_plans;
            $employee_performance_evaluation['bsc_weight'] = $ppr->bsc_weight;
            $employee_performance_evaluation['bsc_actual_score'] = $ppr->bsc_actual_score;
            $employee_performance_evaluation['bsc_description'] = $ppr->bsc_description;
            $employee_performance_evaluation['competency_weight'] = $ppr->competency_weight;
            $employee_performance_evaluation['competency_actual_score'] = $ppr->competency_actual_score;
            $employee_performance_evaluation['competency_description'] = $ppr->competency_description;
            $employee_performance_evaluation['total_weight'] = $ppr->total_weight;
            $employee_performance_evaluation['total_actual_score'] = $ppr->total_actual_score;


            $employee_performance_evaluation['ratees_comments'] = $ppr->ratees_comments;
            $employee_performance_evaluation['summary_ratees_comments_recommendation'] = $ppr->summary_ratees_comments_recommendation;
            
            $employee_performance_evaluation['status'] = $ppr->status;
            
            return view('employee_performance_evaluations.edit',array(
                'header' => 'employee_performance_evaluations',
                'ppr' => $employee_performance_evaluation,
            ));
        }else{
            Alert::warning('Not Allowed')->persistent('Dismiss');
            return redirect('/performance-plan-review');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $ppr = EmployeePerformanceEvaluation::where('user_id',Auth::user()->id)
                                                        ->where('id',$id)
                                                        ->first();
        if($ppr){   
            $ppr->period = $request->period;
            $ppr->financial_perspective = $request->financial_perspective ? json_encode($request->financial_perspective,true) : "";
            $ppr->customer_focus = $request->customer_focus ? json_encode($request->customer_focus,true) : "";
            $ppr->operation_efficiency = $request->operation_efficiency ? json_encode($request->operation_efficiency,true) : "";
            $ppr->people = $request->people ? json_encode($request->people,true) : "";
            $ppr->integrity = $request->integrity ? json_encode($request->integrity,true) : "";
            $ppr->commitment = $request->commitment ? json_encode($request->commitment,true) : "";
            $ppr->genuine_concern = $request->genuine_concern ? json_encode($request->genuine_concern,true) : "";
            $ppr->premium_service = $request->premium_service ? json_encode($request->premium_service,true) : "";
            $ppr->innovation = $request->innovation ? json_encode($request->innovation,true) : "";
            $ppr->synergy = $request->synergy ? json_encode($request->synergy,true) : "";
            $ppr->stewardship = $request->stewardship ? json_encode($request->stewardship,true) : "";

            $ppr->bsc_weight = $request->bsc_weight;
            $ppr->bsc_actual_score = $request->bsc_actual_score;
            $ppr->bsc_description = $request->bsc_description;
            $ppr->competency_weight = $request->competency_weight;
            $ppr->competency_actual_score = $request->competency_actual_score;
            $ppr->competency_description = $request->competency_description;
            $ppr->competency_actual_score = $request->competency_actual_score;

            $ppr->areas_of_strength = $request->areas_of_strength;
            $ppr->developmental_needs = $request->developmental_needs;
            $ppr->areas_for_enhancement = $request->areas_for_enhancement;
            $ppr->training_and_development_plans = $request->training_and_development_plans;

            $ppr->total_weight = $request->total_weight;
            $ppr->total_actual_score = $request->total_actual_score;
            $ppr->save();

            Alert::success('Successfully updated')->persistent('Dismiss');
            return redirect('edit-performance-plan-review/' . $ppr->id);
        
        }else{
            Alert::warning('Not Allowed')->persistent('Dismiss');
            return redirect('/performance-plan-review');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
