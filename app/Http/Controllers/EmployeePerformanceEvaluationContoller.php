<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Employee;
use App\EmployeePerformanceEvaluation;
use App\Company;
use App\EmployeeApprover;
use App\PerformancePlanPeriod;

use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

use Excel;

use App\Exports\PprExport;

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
                                                                                ->orderBy('created_at','DESC')
                                                                                ->get();

        return view('employee_performance_evaluations.index',array(
            'header' => 'employee_performance_evaluations',
            'employee_performance_evaluation' => $employee_performance_evaluation,
            'status' => $status
        ));


    }

    public function hr_index(Request $request)
    {
        
        if(auth()->user()->id == '3873'){
            $allowed_companies = Company::select('id')->pluck('id')->toArray();
        }else{
            $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        }
        
        $search = isset($request->search) ? $request->search : "";
        $company = isset($request->company) ? $request->company : "";
        $performance_plan_period = isset($request->performance_plan_period) ? $request->performance_plan_period : "";
        $period_ppr = isset($request->period_ppr) ? $request->period_ppr : "";

        $status = $request->status ? $request->status : "";
        $employee_performance_evaluation = EmployeePerformanceEvaluation::select('id','user_id','calendar_year','review_date','created_at','approved_by_date','period','status')
                                                                                ->with('user','employee')
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
                                                                                ->when(!empty($performance_plan_period),function($q) use($performance_plan_period){
                                                                                    $q->where('calendar_year',$performance_plan_period);
                                                                                })
                                                                                ->when(!empty($period_ppr),function($q) use($period_ppr){
                                                                                    $q->where('period',$period_ppr);
                                                                                })
                                                                                ->whereHas('employee',function($q) use($allowed_companies){
                                                                                    $q->whereIn('company_id',$allowed_companies)
                                                                                        ->where('status','Active');
                                                                                })
                                                                                ->orderBy('review_date','DESC')
                                                                                ->get();
                                                                                
        $draft = EmployeePerformanceEvaluation::select('id','user_id')
                                ->whereHas('employee',function($q) use($allowed_companies){
                                    $q->whereIn('company_id',$allowed_companies)
                                        ->where('status','Active');
                                })
                                ->when(!empty($company), function ($query) use ($company) {
                                    $query->whereHas('employee',function($q) use($company){
                                        $q->where('company_id',$company);
                                    });
                                })
                                ->when(!empty($performance_plan_period),function($q) use($performance_plan_period){
                                    $q->where('calendar_year',$performance_plan_period);
                                })
                                ->where('status','Draft')
                                ->count();

        $for_approval = EmployeePerformanceEvaluation::select('id','user_id')
                                ->whereHas('employee',function($q) use($allowed_companies){
                                    $q->whereIn('company_id',$allowed_companies)
                                    ->where('status','Active');
                                })
                                ->when(!empty($company), function ($query) use ($company) {
                                    $query->whereHas('employee',function($q) use($company){
                                        $q->where('company_id',$company);
                                    });
                                })
                                ->when(!empty($performance_plan_period),function($q) use($performance_plan_period){
                                    $q->where('calendar_year',$performance_plan_period);
                                })
                                ->where('status','For Review')
                                ->count();
                                
        $approved = EmployeePerformanceEvaluation::select('id','user_id')
                                ->whereHas('employee',function($q) use($allowed_companies){
                                    $q->whereIn('company_id',$allowed_companies)
                                    ->where('status','Active');
                                })
                                ->when(!empty($company), function ($query) use ($company) {
                                    $query->whereHas('employee',function($q) use($company){
                                        $q->where('company_id',$company);
                                    });
                                })
                                ->when(!empty($performance_plan_period),function($q) use($performance_plan_period){
                                    $q->where('calendar_year',$performance_plan_period);
                                })
                                ->where('status','Approved')
                                ->count();

        $declined = EmployeePerformanceEvaluation::select('id','user_id')
                                ->whereHas('employee',function($q) use($allowed_companies){
                                    $q->whereIn('company_id',$allowed_companies)
                                    ->where('status','Active');
                                })
                                ->when(!empty($company), function ($query) use ($company) {
                                    $query->whereHas('employee',function($q) use($company){
                                        $q->where('company_id',$company);
                                    });
                                })
                                ->when(!empty($performance_plan_period),function($q) use($performance_plan_period){
                                    $q->where('calendar_year',$performance_plan_period);
                                })
                                ->where('status','Declined')
                                ->count();
        
        $companies = Company::whereIn('id',$allowed_companies)
                                ->orderBy('company_name','ASC')
                                ->get();

        $performance_plan_periods = PerformancePlanPeriod::orderBy('created_at','DESC')->get();

        return view('employee_performance_evaluations.hr_index',array(
            'header' => 'employee_performance_evaluations_report',
            'employee_performance_evaluation' => $employee_performance_evaluation,
            'status' => $status,
            'companies' => $companies,
            'performance_plan_periods' => $performance_plan_periods,
            'search' => $search,
            'performance_plan_period' => $performance_plan_period,
            'period_ppr' => $period_ppr,
            'company' => $company,
            'draft' => $draft,
            'for_approval' => $for_approval,
            'approved' => $approved,
            'declined' => $declined
        ));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        // return redirect('/performance-plan-review');
        
        $performance_plan_period = PerformancePlanPeriod::where('status','Active')->orderBy('created_at','DESC')->get();
        //  $performance_plan_period = [];
        
        return view('employee_performance_evaluations.create',array(
            'header' => 'employee_performance_evaluations',
            'performance_plan_period' => $performance_plan_period
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
                                                        ->where('calendar_year',$request->calendar_year)
                                                        ->first();
        if(empty($validate_ppr)){

            $new_eval = new EmployeePerformanceEvaluation;
            $new_eval->user_id = Auth::user()->id;
            $new_eval->calendar_year = $request->calendar_year ? $request->calendar_year : date('Y');
            $new_eval->review_date = $request->review_date ? date('Y-m-d h:i:s',strtotime($request->review_date)) :  date('Y-m-d h:i:s');
            $new_eval->period = $request->period;
            $new_eval->financial_perspective = $request->financial_perspective ? json_encode($request->financial_perspective,true) : null;
            $new_eval->customer_focus = $request->customer_focus ? json_encode($request->customer_focus,true) : null;
            $new_eval->operation_efficiency = $request->operation_efficiency ? json_encode($request->operation_efficiency,true) : null;
            $new_eval->people = $request->people ? json_encode($request->people,true) : null;
            $new_eval->integrity = $request->integrity ? json_encode($request->integrity,true) : null;
            $new_eval->commitment = $request->commitment ? json_encode($request->commitment,true) : null;
            $new_eval->humility = $request->humility ? json_encode($request->humility,true) : null;
            $new_eval->genuine_concern = $request->genuine_concern ? json_encode($request->genuine_concern,true) : null;
            $new_eval->premium_service = $request->premium_service ? json_encode($request->premium_service,true) : null;
            $new_eval->innovation = $request->innovation ? json_encode($request->innovation,true) : null;
            $new_eval->synergy = $request->synergy ? json_encode($request->synergy,true) : null;
            $new_eval->stewardship = $request->stewardship ? json_encode($request->stewardship,true) : null;

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

            $new_eval->level = 0;
            $new_eval->status = 'Draft';
            $new_eval->save();

            Alert::success('Successfully Store')->persistent('Dismiss');
            return redirect('edit-performance-plan-review/' . $new_eval->id);
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
        $ppr = EmployeePerformanceEvaluation::with('approver.approver_info','user','employee.company','employee.department')->where('id',$id)->first();

                                                  
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
        $employee_performance_evaluation['level'] = $ppr->level;

        return view('employee_performance_evaluations.view',array(
            'header' => 'employee_performance_evaluations',
            'ppr' => $employee_performance_evaluation,
            'ppr_details' => $ppr,
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
        // return redirect('/performance-plan-review');

        $performance_plan_period = PerformancePlanPeriod::where('status','Active')->orderBy('created_at','DESC')->get();

        if(Auth::user()->id == '1'){
            $ppr = EmployeePerformanceEvaluation::with('approver.approver_info','user','employee')
                                                        ->where('id',$id)
                                                        ->first();
        }else{
            $ppr = EmployeePerformanceEvaluation::with('approver.approver_info','user','employee')
                                                        ->where('user_id',Auth::user()->id)
                                                        ->where('id',$id)
                                                        ->first();
        }
        
        if($ppr){                                             
            $employee_performance_evaluation = [];
            $employee_performance_evaluation['id'] = $ppr->id;
            $employee_performance_evaluation['calendar_year'] = $ppr->calendar_year;
            $employee_performance_evaluation['review_date'] = $ppr->review_date;
            $employee_performance_evaluation['period'] = $ppr->period;
            $employee_performance_evaluation['financial_perspective'] = $ppr->financial_perspective ? json_decode($ppr->financial_perspective,true) : "";
            $employee_performance_evaluation['customer_focus'] = $ppr->customer_focus ? json_decode($ppr->customer_focus,true) : "";
            $employee_performance_evaluation['operation_efficiency'] = $ppr->operation_efficiency ? json_decode($ppr->operation_efficiency,true) : "";
            $employee_performance_evaluation['people'] = $ppr->people ? json_decode($ppr->people,true) : "";
            $employee_performance_evaluation['integrity'] = $ppr->integrity ? json_decode($ppr->integrity,true) : "";
            $employee_performance_evaluation['commitment'] = $ppr->commitment ? json_decode($ppr->commitment,true) : "";
            $employee_performance_evaluation['humility'] = $ppr->humility ? json_decode($ppr->humility,true) : "";
            $employee_performance_evaluation['genuine_concern'] = $ppr->genuine_concern ? json_decode($ppr->genuine_concern,true) : "";
            $employee_performance_evaluation['premium_service'] = $ppr->premium_service ? json_decode($ppr->premium_service,true) : "";
            $employee_performance_evaluation['innovation'] = $ppr->innovation ? json_decode($ppr->innovation,true) : "";
            $employee_performance_evaluation['synergy'] = $ppr->synergy ? json_decode($ppr->synergy,true) : "";
            $employee_performance_evaluation['stewardship'] = $ppr->stewardship ? json_decode($ppr->stewardship,true) : "";
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
            $employee_performance_evaluation['is_version'] = $ppr->is_version;

            // return $employee_performance_evaluation;
            
            return view('employee_performance_evaluations.edit',array(
                'header' => 'employee_performance_evaluations',
                'ppr' => $employee_performance_evaluation,
                'performance_plan_period' => $performance_plan_period
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

            // if($ppr->status == 'Decline' && $ppr->is_version == ''){



            //     $new_eval = new EmployeePerformanceEvaluation;
            //     $new_eval->user_id = Auth::user()->id;
            //     $new_eval->calendar_year = $request->calendar_year ? $request->calendar_year : date('Y');
            //     $new_eval->review_date = $request->review_date ? date('Y-m-d h:i:s',strtotime($request->review_date)) :  date('Y-m-d h:i:s');
            //     $new_eval->period = $request->period;
            //     $new_eval->financial_perspective = $request->financial_perspective ? json_encode($request->financial_perspective,true) : null;
            //     $new_eval->customer_focus = $request->customer_focus ? json_encode($request->customer_focus,true) : null;
            //     $new_eval->operation_efficiency = $request->operation_efficiency ? json_encode($request->operation_efficiency,true) : null;
            //     $new_eval->people = $request->people ? json_encode($request->people,true) : null;
            //     $new_eval->integrity = $request->integrity ? json_encode($request->integrity,true) : null;
            //     $new_eval->commitment = $request->commitment ? json_encode($request->commitment,true) : null;
            //     $new_eval->humility = $request->humility ? json_encode($request->humility,true) : null;
            //     $new_eval->genuine_concern = $request->genuine_concern ? json_encode($request->genuine_concern,true) : null;
            //     $new_eval->premium_service = $request->premium_service ? json_encode($request->premium_service,true) : null;
            //     $new_eval->innovation = $request->innovation ? json_encode($request->innovation,true) : null;
            //     $new_eval->synergy = $request->synergy ? json_encode($request->synergy,true) : null;
            //     $new_eval->stewardship = $request->stewardship ? json_encode($request->stewardship,true) : null;
    
            //     $new_eval->bsc_weight = $request->bsc_weight;
            //     $new_eval->bsc_actual_score = $request->bsc_actual_score;
            //     $new_eval->bsc_description = $request->bsc_description;
            //     $new_eval->competency_weight = $request->competency_weight;
            //     $new_eval->competency_actual_score = $request->competency_actual_score;
            //     $new_eval->competency_description = $request->competency_description;
            //     $new_eval->competency_actual_score = $request->competency_actual_score;
    
            //     $new_eval->areas_of_strength = $request->areas_of_strength;
            //     $new_eval->developmental_needs = $request->developmental_needs;
            //     $new_eval->areas_for_enhancement = $request->areas_for_enhancement;
            //     $new_eval->training_and_development_plans = $request->training_and_development_plans;
    
            //     $new_eval->total_weight = $request->total_weight;
            //     $new_eval->total_actual_score = $request->total_actual_score;
    
            //     $new_eval->level = 0;
            //     $new_eval->status = 'Draft';
            //     $new_eval->save();

            //     $ppr->is_version = 1;
            //     $ppr->save();
    
            //     Alert::success('Successfully Store')->persistent('Dismiss');
            //     return redirect('edit-performance-plan-review/' . $new_eval->id);   


            // }else{

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

            // }

            
        
        }else{
            Alert::warning('Not Allowed')->persistent('Dismiss');
            return redirect('/performance-plan-review');
        }
    }

    public function performance_plan_approval(Request $request){

        $today = date('Y-m-d');
        $from_date = isset($request->from) ? $request->from : "";
        $to_date = isset($request->to) ? $request->to : "";
        
        $filter_status = isset($request->status) ? $request->status : 'For Review';

        $approver_id = auth()->user()->id;

        $performance_plans = EmployeePerformanceEvaluation::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status',$filter_status)
                                ->orderBy('created_at','DESC');

        if(isset($request->from)){
            $performance_plans = $performance_plans->whereDate('created_at','>=',$from_date);
        }
        if(isset($request->to)){
            $performance_plans = $performance_plans->whereDate('created_at','<=',$to_date);
        }

        $performance_plans = $performance_plans->get();
        

        $user_ids = EmployeeApprover::select('user_id')->where('approver_id',$approver_id)->pluck('user_id')->toArray();

        $for_approval = EmployeePerformanceEvaluation::whereIn('user_id',$user_ids)
                                ->where('status','For Review');
        
        if(isset($request->from)){
            $for_approval = $for_approval->whereDate('created_at','>=',$from_date);
        }
        if(isset($request->to)){
            $for_approval = $for_approval->whereDate('created_at','<=',$to_date);
        }

        $for_approval = $for_approval->count();


        $approved = EmployeePerformanceEvaluation::whereIn('user_id',$user_ids)
                                ->where('status','Approved');

        if(isset($request->from)){
            $approved = $approved->whereDate('created_at','>=',$from_date);
        }
        if(isset($request->to)){
            $approved = $approved->whereDate('created_at','<=',$to_date);
        }

        $approved = $approved->count();

        $declined = EmployeePerformanceEvaluation::whereIn('user_id',$user_ids)
                                ->where('status','Declined');
        
        if(isset($request->from)){
            $declined = $declined->whereDate('created_at','>=',$from_date);
        }
        if(isset($request->to)){
            $declined = $declined->whereDate('created_at','<=',$to_date);
        }

        $declined = $declined->count();
        
        session(['pending_performance_eval_count'=>$for_approval]);
                                
        return view('employee_performance_evaluations.for_approval',array(
            'header' => 'ppr_approval',
            'performance_plans' => $performance_plans,
            'for_approval' => $for_approval,
            'approved' => $approved,
            'declined' => $declined,
            'approver_id' => $approver_id,
            'from' => $from_date,
            'to' => $to_date,
            'status' => $filter_status,
        ));
    }

    public function approvePpr(Request $request,$id){
        $employee_ppr = EmployeePerformanceEvaluation::where('id', $id)->first();
        if($employee_ppr){
            $level = '';
            if($employee_ppr->level == 0){
                $employee_approver = EmployeeApprover::where('user_id', $employee_ppr->user_id)->where('approver_id', auth()->user()->id)->first();
                if($employee_approver->as_final == 'on'){
                    EmployeePerformanceEvaluation::Where('id', $id)->update([
                        'approved_by' =>auth()->user()->id,
                        'approved_by_date' => date('Y-m-d'),
                        'status' => 'Approved',
                        'approval_remarks' => $request->approval_remarks,
                        'level' => 1,
                    ]);
                }else{
                    EmployeePerformanceEvaluation::Where('id', $id)->update([
                        'approval_remarks' => $request->approval_remarks,
                        'level' => 1
                    ]);
                }
            }
            else if($employee_ppr->level == 1){
                EmployeePerformanceEvaluation::Where('id', $id)->update([
                    'approved_by' =>auth()->user()->id,
                    'approved_by_date' => date('Y-m-d'),
                    'status' => 'Approved',
                    'approval_remarks' => $request->approval_remarks,
                    'level' => 2,
                ]);
            }
            Alert::success('Performance Plan has been approved.')->persistent('Dismiss');
            return back();
        }
    }

    public function declinePpr(Request $request,$id){
        EmployeePerformanceEvaluation::Where('id', $id)->update([
                        'approved_by_date' => date('Y-m-d'),
                        'status' => 'Declined',
                        'approval_remarks' => $request->approval_remarks,
                    ]);
        Alert::success('Performance Plan has been declined.')->persistent('Dismiss');
        return back();
    }

    public function approvePprAll(Request $request){
        
        $ids = json_decode($request->ids,true);

        $count = 0;
        if(count($ids) > 0){
            
            foreach($ids as $id){
                $employee_ppr = EmployeePerformanceEvaluation::where('id', $id)->first();
                if($employee_ppr){
                    $level = '';
                    $employee_approver = EmployeeApprover::where('user_id', $employee_ppr->user_id)->where('approver_id', auth()->user()->id)->first();
                    if($employee_ppr->level == 0){
                        if($employee_approver->as_final == 'on'){
                            EmployeePerformanceEvaluation::Where('id', $id)->update([
                                'approved_by' =>auth()->user()->id,
                                'approved_by_date' => date('Y-m-d'),
                                'status' => 'Approved',
                                'approval_remarks' => 'Approved',
                                'level' => 1,
                            ]);
                            $count++;
                        }else{
                            EmployeePerformanceEvaluation::Where('id', $id)->update([
                                'approval_remarks' => 'Approved',
                                'level' => 1
                            ]);
                            $count++;
                        }
                    }
                    else if($employee_dtr->level == 1){
                        if($employee_approver->as_final == 'on'){
                            EmployeePerformanceEvaluation::Where('id', $id)->update([
                                'approved_by' =>auth()->user()->id,
                                'approved_by_date' => date('Y-m-d'),
                                'status' => 'Approved',
                                'approval_remarks' => 'Approved',
                                'level' => 2,
                            ]);
                            $count++;
                        }
                    }
                }
            }

            return $count;

        }else{
            return 'error';
        }
    }

    public function disapprovePprAll(Request $request){
        
        $ids = json_decode($request->ids,true);

        $count = 0;
        if(count($ids) > 0){
            
            foreach($ids as $id){
                EmployeePerformanceEvaluation::Where('id', $id)->update([
                    'status' => 'Declined',
                    'approval_remarks' => 'Declined',
                ]);

                $count++;
            }

            return $count;

        }else{
            return 'error';
        }
    }

    public function returnToDraft(Request $request,$id){

        $employee_ppr = EmployeePerformanceEvaluation::where('id', $id)->first();
        if($employee_ppr){

            $employee_ppr->mail_1 = null;
            $employee_ppr->mail_2 = null;
            $employee_ppr->level = 0;
            $employee_ppr->status = 'Draft';
            $employee_ppr->save();

            Alert::success('Performance Plan has been returned to Draft.')->persistent('Dismiss');
            return back();
        }
    }

    public function export(Request $request){
    
        $company = isset($request->company) ? $request->company : "";
        $status = isset($request->status) ? $request->status : "";
        $calendar_date = isset($request->calendar_date) ? $request->calendar_date : "";
        $period_ppr = isset($request->period_ppr) ? $request->period_ppr : "";
        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $allowed_companies = json_encode($allowed_companies);

        $company_detail = Company::where('id',$company)->first();
        $company_code =  $company_detail ?  $company_detail->company_code : "";
        return Excel::download(new PprExport($company,$status,$period_ppr,$calendar_date,$allowed_companies), $company_code . ' ' . $status . ' ' . $calendar_date . ' PPR Export.xlsx');
    }

    public function deletePPR(Request $request,$id){

        $employee_ppr = EmployeePerformanceEvaluation::where('id', $id)->first();
        if($employee_ppr){
            $employee_ppr->delete();
            return "Deleted";
        }
    }

    public function resetApprover(Request $request,$id){

        $employee_ppr = EmployeePerformanceEvaluation::where('id', $id)->first();
        if($employee_ppr){
            $employee_ppr->level = 0;
            $employee_ppr->status = 'For Review';
            $employee_ppr->save();
            return "Reset";
        }
    }

    public function returnToDraftAll(Request $request){
        
        if(auth()->user()->id == '3873'){
            $allowed_companies = Company::select('id')->pluck('id')->toArray();
        }else{
            $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        }
        
        $search = isset($request->search) ? $request->search : "";
        $company = isset($request->company) ? $request->company : "";
        $performance_plan_period = isset($request->performance_plan_period) ? $request->performance_plan_period : "";
        $period_ppr = isset($request->period_ppr) ? $request->period_ppr : "";

        $status = $request->status ? $request->status : "";

        if($company){
            $employee_performance_evaluation = EmployeePerformanceEvaluation::select('id','user_id','calendar_year','review_date','created_at','approved_by_date','period','status')
                                                    ->with('user','employee')
                                                    ->where('status','Approved')
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
                                                    ->when(!empty($performance_plan_period),function($q) use($performance_plan_period){
                                                        $q->where('calendar_year',$performance_plan_period);
                                                    })
                                                    ->when(!empty($period_ppr),function($q) use($period_ppr){
                                                        $q->where('period',$period_ppr);
                                                    })
                                                    ->whereHas('employee',function($q) use($allowed_companies){
                                                        $q->whereIn('company_id',$allowed_companies)
                                                            ->where('status','Active');
                                                    })
                                                    ->orderBy('review_date','DESC')
                                                    ->get();
            
            $count = 0;
            if($employee_performance_evaluation){
                foreach($employee_performance_evaluation as $item){
                    $employee_ppr = EmployeePerformanceEvaluation::where('id', $item->id)->first();
                    if($employee_ppr){
                        $employee_ppr->mail_1 = null;
                        $employee_ppr->mail_2 = null;
                        $employee_ppr->level = 0;
                        $employee_ppr->status = 'Draft';
                        $employee_ppr->save();
                        $count++;
                    }
                }
            }

            return $count;


        }
        
    }
}
