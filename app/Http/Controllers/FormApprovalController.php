<?php

namespace App\Http\Controllers;
use App\EmployeeLeave;
use App\EmployeeWfh;
use App\EmployeeOvertime;
use App\EmployeeOb;
use App\EmployeeDtr;
use App\EmployeeApprover;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class FormApprovalController extends Controller
{

    public function form_leave_approval (Request $request)
    { 
        $filter_status = isset($request->status) ? $request->status : 'Pending';
        $approver_id = auth()->user()->id;
        $leaves = EmployeeLeave::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status',$filter_status)
                                ->orderBy('created_at','DESC')
                                ->get();

        $for_approval = EmployeeLeave::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Pending')
                                ->count();
        $approved = EmployeeLeave::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Approved')
                                ->count();
        $declined = EmployeeLeave::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Declined')
                                ->count();
        
        return view('for-approval.leave-approval',
        array(
            'header' => 'for-approval',
            'leaves' => $leaves,
            'for_approval' => $for_approval,
            'approved' => $approved,
            'declined' => $declined,
            'approver_id' => $approver_id,
        ));

    }

    public function approveLeave($id){

        $employee_leave  = EmployeeLeave::where('id', $id)
                                            ->first();

        if($employee_leave){
            $level = '';
            if($employee_leave->level == 0){
                $employee_approver = EmployeeApprover::where('user_id', $employee_leave->user_id)->where('approver_id', auth()->user()->id)->first();
                if($employee_approver->as_final == 'on'){
                    EmployeeLeave::Where('id', $id)->update([
                        'approved_date' => date('Y-m-d'),
                        'status' => 'Approved',
                        'level' => 1,
                    ]);
                }else{
                    EmployeeLeave::Where('id', $id)->update([
                        'level' => 1,
                    ]);
                }
            }
            else if($employee_leave->level == 1){
                EmployeeLeave::Where('id', $id)->update([
                    'approved_date' => date('Y-m-d'),
                    'status' => 'Approved',
                    'level' => 2,
                ]);
            }
            Alert::success('Leave has been approved.')->persistent('Dismiss');
            return back();
        }
    }

    public function declineLeave($id){
        EmployeeLeave::Where('id', $id)->update(['status' => 'Declined',]);
        Alert::success('Leave has been declined.')->persistent('Dismiss');
        return back();
    }

    public function form_overtime_approval(Request $request)
    { 
        $filter_status = isset($request->status) ? $request->status : 'Pending';
        $approver_id = auth()->user()->id;
        $overtimes = EmployeeOvertime::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status',$filter_status)
                                ->orderBy('created_at','DESC')
                                ->get();

        $for_approval = EmployeeOvertime::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Pending')
                                ->count();
        $approved = EmployeeOvertime::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Approved')
                                ->count();
        $declined = EmployeeOvertime::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Declined')
                                ->count();
        
        return view('for-approval.overtime-approval',
        array(
            'header' => 'for-approval',
            'overtimes' => $overtimes,
            'for_approval' => $for_approval,
            'approved' => $approved,
            'declined' => $declined,
            'approver_id' => $approver_id,
        ));

    }

    public function approveOvertime(Request $request, EmployeeOvertime $employee_overtime){

    
        if($employee_overtime){
            $level = '';
            if($employee_overtime->level == 0){

                $employee_approver = EmployeeApprover::where('user_id', $employee_overtime->user_id)->where('approver_id', auth()->user()->id)->first();
                if($employee_approver->as_final == 'on'){
                    $ot_approved_hrs = $request->ot_approved_hrs;
                    EmployeeOvertime::Where('id', $employee_overtime->id)->update([
                        'approved_date' => date('Y-m-d'),
                        'status' => 'Approved',
                        'level' => 1,
                        'break_hrs' => $request->break_hrs,
                        'ot_approved_hrs' => $ot_approved_hrs
                    ]);
                }else{
                    EmployeeOvertime::Where('id', $employee_overtime->id)->update([
                        'level' => 1,
                        'break_hrs' => $request->break_hrs,
                        'ot_approved_hrs' => $request->ot_approved_hrs
                    ]);
                }
            }
            else if($employee_overtime->level == 1){
                $ot_approved_hrs = $request->ot_approved_hrs;
                EmployeeOvertime::Where('id', $employee_overtime->id)->update([
                    'approved_date' => date('Y-m-d'),
                    'status' => 'Approved',
                    'level' => 2,
                    'break_hrs' => $request->break_hrs,
                    'ot_approved_hrs' => $ot_approved_hrs
                ]);
            }
            Alert::success('Overtime has been approved.')->persistent('Dismiss');
            return back();
        }
    }

    public function declineOvertime($id){
        EmployeeOvertime::Where('id', $id)->update(['status' => 'Declined',]);
        Alert::success('Leave has been declined.')->persistent('Dismiss');
        return back();
    }


    public function form_wfh_approval(Request $request)
    { 
        $filter_status = isset($request->status) ? $request->status : 'Pending';
        $approver_id = auth()->user()->id;
        $wfhs = EmployeeWfh::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status',$filter_status)
                                ->orderBy('created_at','DESC')
                                ->get();

        $for_approval = EmployeeWfh::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Pending')
                                ->count();
        $approved = EmployeeWfh::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Approved')
                                ->count();
        $declined = EmployeeWfh::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Declined')
                                ->count();
        
        return view('for-approval.wfh-approval',
        array(
            'header' => 'for-approval',
            'wfhs' => $wfhs,
            'for_approval' => $for_approval,
            'approved' => $approved,
            'declined' => $declined,
            'approver_id' => $approver_id,
        ));

    }

    public function approveWfh(Request $request,$id){

        $employee_wfh = EmployeeWfh::where('id', $id)
                                            ->first();

        if($employee_wfh){
            $level = '';
            if($employee_wfh->level == 0){
                $employee_approver = EmployeeApprover::where('user_id', $employee_wfh->user_id)->where('approver_id', auth()->user()->id)->first();
                if($employee_approver->as_final == 'on'){
                    EmployeeWfh::Where('id', $id)->update([
                        'approved_date' => date('Y-m-d'),
                        'status' => 'Approved',
                        'approve_percentage' => $request->approve_percentage,
                        'level' => 1,
                    ]);
                }else{
                    EmployeeWfh::Where('id', $id)->update([
                        'level' => 1,
                        'approve_percentage' => $request->approve_percentage,
                    ]);
                }
            }
            else if($employee_wfh->level == 1){
                EmployeeWfh::Where('id', $id)->update([
                    'approved_date' => date('Y-m-d'),
                    'status' => 'Approved',
                    'approve_percentage' => $request->approve_percentage,
                    'level' => 2,
                ]);
            }
            Alert::success('Wfh has been approved.')->persistent('Dismiss');
            return back();
        }
    }

    public function declineWfh($id){
        EmployeeWfh::Where('id', $id)->update(['status' => 'Declined',]);
        Alert::success('Wfh has been declined.')->persistent('Dismiss');
        return back();
    }

    public function form_ob_approval(Request $request)
    { 
        $filter_status = isset($request->status) ? $request->status : 'Pending';
        $approver_id = auth()->user()->id;
        $obs = EmployeeOb::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status',$filter_status)
                                ->orderBy('created_at','DESC')
                                ->get();

        $for_approval = EmployeeOb::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Pending')
                                ->count();
        $approved = EmployeeOb::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Approved')
                                ->count();
        $declined = EmployeeOb::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Declined')
                                ->count();
        
        return view('for-approval.ob-approval',
        array(
            'header' => 'for-approval',
            'obs' => $obs,
            'for_approval' => $for_approval,
            'approved' => $approved,
            'declined' => $declined,
            'approver_id' => $approver_id,
        ));

    }

    public function approveOb($id){

        $employee_ob = EmployeeOb::where('id', $id)
                                            ->first();

        if($employee_ob){
            $level = '';
            if($employee_ob->level == 0){
                $employee_approver = EmployeeApprover::where('user_id', $employee_ob->user_id)->where('approver_id', auth()->user()->id)->first();
                if($employee_approver->as_final == 'on'){
                    EmployeeOb::Where('id', $id)->update([
                        'approved_date' => date('Y-m-d'),
                        'status' => 'Approved',
                        'level' => 1,
                    ]);

                }else{
                    EmployeeOb::Where('id', $id)->update([
                        'level' => 1,
                    ]);
                }
            }
            else if($employee_ob->level == 1){
                EmployeeOb::Where('id', $id)->update([
                    'approved_date' => date('Y-m-d'),
                    'status' => 'Approved',
                    'level' => 2,
                ]);
            }
            Alert::success('OB has been approved.')->persistent('Dismiss');
            return back();
        }
    }

    public function declineOb($id){
        EmployeeOb::Where('id', $id)->update(['status' => 'Declined',]);
        Alert::success('OB has been declined.')->persistent('Dismiss');
        return back();
    }

    public function form_dtr_approval(Request $request)
    { 
        $filter_status = isset($request->status) ? $request->status : 'Pending';
        $approver_id = auth()->user()->id;
        $dtrs = EmployeeDtr::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status',$filter_status)
                                ->orderBy('created_at','DESC')
                                ->get();

        $for_approval = EmployeeDtr::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Pending')
                                ->count();
        $approved = EmployeeDtr::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Approved')
                                ->count();
        $declined = EmployeeDtr::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Declined')
                                ->count();
        
        return view('for-approval.dtr-approval',
        array(
            'header' => 'for-approval',
            'dtrs' => $dtrs,
            'for_approval' => $for_approval,
            'approved' => $approved,
            'declined' => $declined,
            'approver_id' => $approver_id,
        ));

    }

    public function approveDtr($id){
        $employee_dtr = EmployeeDtr::where('id', $id)->first();
        if($employee_dtr){
            $level = '';
            if($employee_dtr->level == 0){
                $employee_approver = EmployeeApprover::where('user_id', $employee_dtr->user_id)->where('approver_id', auth()->user()->id)->first();
                if($employee_approver->as_final == 'on'){
                    EmployeeDtr::Where('id', $id)->update([
                        'approved_date' => date('Y-m-d'),
                        'status' => 'Approved',
                        'level' => 1,
                    ]);
                }else{
                    EmployeeDtr::Where('id', $id)->update([
                        'level' => 1,
                    ]);
                }
            }
            else if($employee_dtr->level == 1){
                EmployeeDtr::Where('id', $id)->update([
                    'approved_date' => date('Y-m-d'),
                    'status' => 'Approved',
                    'level' => 2,
                ]);
            }
            Alert::success('DTR has been approved.')->persistent('Dismiss');
            return back();
        }
    }

    public function declineDtr($id){
        EmployeeDtr::Where('id', $id)->update(['status' => 'Declined',]);
        Alert::success('DTR has been declined.')->persistent('Dismiss');
        return back();
    }
}
