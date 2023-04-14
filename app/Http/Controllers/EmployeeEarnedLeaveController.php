<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EmployeeEarnedLeave;

class EmployeeEarnedLeaveController extends Controller
{
    public function index(){
        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $earned_leaves = EmployeeEarnedLeave::with('employee.company','employee.classification_info','leave_type_info')
                                                ->whereHas('employee',function($q) use($allowed_companies){
                                                    $q->whereHas('company',function($w) use($allowed_companies){
                                                        return $w->whereIn('company_id',$allowed_companies);
                                                    });
                                                })
                                                ->orderBy('user_id','ASC')
                                                ->orderBy('leave_type','ASC')
                                                ->orderBy('created_at','DESC')
                                                ->get();

        return view('employee_earned_leaves.index', array(
            'header' => 'masterfiles',
            'earned_leaves' => $earned_leaves,
        ));
    }
}
