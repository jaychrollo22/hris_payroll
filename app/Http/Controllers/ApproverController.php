<?php

namespace App\Http\Controllers;
use App\Approver;
use Illuminate\Http\Request;

class ApproverController extends Controller
{
    public function get_approvers($id)
    {

        $approvers = Approver::with('userinfo','approverinfo')
        ->where('user_id',$id)
        ->get();

        return $approvers;
    }
}
