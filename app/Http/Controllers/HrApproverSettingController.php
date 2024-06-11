<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HrApproverSetting;
use App\User;
use App\Company;

use RealRashid\SweetAlert\Facades\Alert;

class HrApproverSettingController extends Controller
{
   public function index(){
        $hr_approvers = HrApproverSetting::with('user','company')
                                            ->where('status','Active')
                                            ->get();
        $companies = Company::whereHas('employee_has_company')->orderBy('company_name','ASC')->get();
        $users = User::whereHas('employee',function($q){
                                    $q->where('status','Active');
                                })
                                ->get();
        return view('hr_approver_settings.view_hr_approver_settings', array(
            'header' => 'hr_approvers',
            'hr_approvers' => $hr_approvers,
            'users' => $users,
            'companies' => $companies,
        ));
   }

   public function store(Request $request){

        if($request->companies){
            foreach($request->companies as $company){
                $hr_appprover = HrApproverSetting::where('user_id',$request->user_id)
                                                    ->where('company_id',$company)
                                                    ->first();
                if(empty($hr_approver)){
                    $new_hr_approver = new HrApproverSetting;
                    $new_hr_approver->user_id = $request->user_id;
                    $new_hr_approver->company_id = $company;
                    $new_hr_approver->save();
                }
            }
        }
        Alert::success('Successfully Stored')->persistent('Dismiss');
        return back();
   }

   public function remove($id){
        HrApproverSetting::Where('id', $id)->update(['status' => 'Inactive']);
        Alert::success('HR Approver has been removed.')->persistent('Dismiss');
        return back();
   }
}
