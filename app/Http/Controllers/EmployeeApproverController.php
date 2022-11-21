<?php

namespace App\Http\Controllers;
use App\EmployeeApprover;

use Illuminate\Http\Request;

class EmployeeApproverController extends Controller
{
    public function get_approvers($id)
    {

        $approvers = EmployeeApprover::with('user_info','approver_info')
        ->where('user_id',$id)
        ->get();

        return $approvers;
    }
}
