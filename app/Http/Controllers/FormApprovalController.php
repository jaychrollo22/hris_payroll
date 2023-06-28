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

        $today = date('Y-m-d');
        $from_date = isset($request->from) ? $request->from : date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to_date = isset($request->to) ? $request->to : date('Y-m-d');

        $filter_status = isset($request->status) ? $request->status : 'Pending';
        $filter_request_to_cancel = isset($request->request_to_cancel) ? $request->request_to_cancel : '';
        $approver_id = auth()->user()->id;
        $leaves = EmployeeLeave::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->when($filter_status, function($q) use($filter_status){
                                    $q->where('status',$filter_status);
                                })
                                ->when($filter_request_to_cancel, function($q) use($filter_request_to_cancel){
                                    $q->where('request_to_cancel',$filter_request_to_cancel);
                                })
                                ->whereDate('date_from','>=',$from_date)
                                ->whereDate('date_from','<=',$to_date)
                                ->orderBy('created_at','DESC')
                                ->get();

        $for_approval = EmployeeLeave::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->whereDate('date_from','>=',$from_date)
                                ->whereDate('date_from','<=',$to_date)
                                ->where('status','Pending')
                                ->count();
        $approved = EmployeeLeave::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->whereDate('date_from','>=',$from_date)
                                ->whereDate('date_from','<=',$to_date)
                                ->where('status','Approved')
                                ->count();
        $declined = EmployeeLeave::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->whereDate('date_from','>=',$from_date)
                                ->whereDate('date_from','<=',$to_date)
                                ->where('status','Declined')
                                ->count();
        $request_to_cancel = EmployeeLeave::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->whereDate('date_from','>=',$from_date)
                                ->whereDate('date_from','<=',$to_date)
                                ->where('request_to_cancel','1')
                                ->count();
        
        return view('for-approval.leave-approval',
        array(
            'header' => 'for-approval',
            'leaves' => $leaves,
            'for_approval' => $for_approval,
            'approved' => $approved,
            'declined' => $declined,
            'request_to_cancel' => $request_to_cancel,
            'approver_id' => $approver_id,
            'from' => $from_date,
            'to' => $to_date,
            'status' => $filter_status,
        ));

    }

    public function approveLeave(Request $request, $id){

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
                        'approval_remarks' => $request->approval_remarks,
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
                    'approval_remarks' => $request->approval_remarks,
                    'level' => 2,
                ]);
            }
            Alert::success('Leave has been approved.')->persistent('Dismiss');
            return back();
        }
    }

    public function declineLeave(Request $request, $id){
        EmployeeLeave::Where('id', $id)->update([
                        'status' => 'Declined',
                        'approval_remarks' => $request->approval_remarks,
                    ]);
        Alert::success('Leave has been declined.')->persistent('Dismiss');
        return back();
    }

    public function form_overtime_approval(Request $request)
    { 
        $today = date('Y-m-d');
        $from_date = isset($request->from) ? $request->from : date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to_date = isset($request->to) ? $request->to : date('Y-m-d');

        $filter_status = isset($request->status) ? $request->status : 'Pending';
        $approver_id = auth()->user()->id;
        $overtimes = EmployeeOvertime::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status',$filter_status)
                                ->whereDate('ot_date','>=',$from_date)
                                ->whereDate('ot_date','<=',$to_date)
                                ->orderBy('created_at','DESC')
                                ->get();

        $for_approval = EmployeeOvertime::whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Pending')
                                ->whereDate('ot_date','>=',$from_date)
                                ->whereDate('ot_date','<=',$to_date)
                                ->count();
                                
        $approved = EmployeeOvertime::whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->whereDate('ot_date','>=',$from_date)
                                ->whereDate('ot_date','<=',$to_date)
                                ->where('status','Approved')
                                ->count();

        $declined = EmployeeOvertime::whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->whereDate('ot_date','>=',$from_date)
                                ->whereDate('ot_date','<=',$to_date)
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
            'from' => $from_date,
            'to' => $to_date,
            'status' => $filter_status,
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
                        'approval_remarks' => $request->approval_remarks,
                        'level' => 1,
                        'break_hrs' => $request->break_hrs,
                        'ot_approved_hrs' => $ot_approved_hrs
                    ]);
                }else{
                    EmployeeOvertime::Where('id', $employee_overtime->id)->update([
                        'approval_remarks' => $request->approval_remarks,
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
                    'approval_remarks' => $request->approval_remarks,
                    'level' => 2,
                    'break_hrs' => $request->break_hrs,
                    'ot_approved_hrs' => $ot_approved_hrs
                ]);
            }
            Alert::success('Overtime has been approved.')->persistent('Dismiss');
            return back();
        }
    }

    public function declineOvertime(Request $request,$id){
        EmployeeOvertime::Where('id', $id)->update([
                            'status' => 'Declined',
                            'approval_remarks' => $request->approval_remarks,
                        ]);
        Alert::success('Overtime has been declined.')->persistent('Dismiss');
        return back();
    }


    public function form_wfh_approval(Request $request)
    { 
        $today = date('Y-m-d');
        $from_date = isset($request->from) ? $request->from : date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to_date = isset($request->to) ? $request->to : date('Y-m-d');

        $filter_status = isset($request->status) ? $request->status : 'Pending';
        $approver_id = auth()->user()->id;
        $wfhs = EmployeeWfh::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status',$filter_status)
                                ->whereDate('applied_date','>=',$from_date)
                                ->whereDate('applied_date','<=',$to_date)
                                ->orderBy('created_at','DESC')
                                ->get();

        $for_approval = EmployeeWfh::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Pending')
                                ->whereDate('applied_date','>=',$from_date)
                                ->whereDate('applied_date','<=',$to_date)
                                ->count();
        $approved = EmployeeWfh::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Approved')
                                ->whereDate('applied_date','>=',$from_date)
                                ->whereDate('applied_date','<=',$to_date)
                                ->count();
        $declined = EmployeeWfh::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Declined')
                                ->whereDate('applied_date','>=',$from_date)
                                ->whereDate('applied_date','<=',$to_date)
                                ->count();
        
        return view('for-approval.wfh-approval',
        array(
            'header' => 'for-approval',
            'wfhs' => $wfhs,
            'for_approval' => $for_approval,
            'approved' => $approved,
            'declined' => $declined,
            'approver_id' => $approver_id,
            'from' => $from_date,
            'to' => $to_date,
            'status' => $filter_status,
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
                        'approval_remarks' => $request->approval_remarks,
                        'level' => 1,
                    ]);
                }else{
                    EmployeeWfh::Where('id', $id)->update([
                        'level' => 1,
                        'approve_percentage' => $request->approve_percentage,
                        'approval_remarks' => $request->approval_remarks,
                    ]);
                }
            }
            else if($employee_wfh->level == 1){
                EmployeeWfh::Where('id', $id)->update([
                    'approved_date' => date('Y-m-d'),
                    'status' => 'Approved',
                    'approve_percentage' => $request->approve_percentage,
                    'approval_remarks' => $request->approval_remarks,
                    'level' => 2,
                ]);
            }
            Alert::success('Wfh has been approved.')->persistent('Dismiss');
            return back();
        }
    }

    public function declineWfh(Request $request,$id){
        EmployeeWfh::Where('id', $id)->update([
                'status' => 'Declined',
                'approval_remarks' => $request->approval_remarks,
        ]);
        Alert::success('Wfh has been declined.')->persistent('Dismiss');
        return back();
    }

    public function form_ob_approval(Request $request)
    { 

        $today = date('Y-m-d');
        $from_date = isset($request->from) ? $request->from : date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to_date = isset($request->to) ? $request->to : date('Y-m-d');

        $filter_status = isset($request->status) ? $request->status : 'Pending';
        $approver_id = auth()->user()->id;
        $obs = EmployeeOb::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status',$filter_status)
                                ->whereDate('applied_date','>=',$from_date)
                                ->whereDate('applied_date','<=',$to_date)
                                ->orderBy('created_at','DESC')
                                ->get();

        $for_approval = EmployeeOb::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Pending')
                                ->whereDate('applied_date','>=',$from_date)
                                ->whereDate('applied_date','<=',$to_date)
                                ->count();
        $approved = EmployeeOb::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Approved')
                                ->whereDate('applied_date','>=',$from_date)
                                ->whereDate('applied_date','<=',$to_date)
                                ->count();
        $declined = EmployeeOb::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Declined')
                                ->whereDate('applied_date','>=',$from_date)
                                ->whereDate('applied_date','<=',$to_date)
                                ->count();
        
        return view('for-approval.ob-approval',
        array(
            'header' => 'for-approval',
            'obs' => $obs,
            'for_approval' => $for_approval,
            'approved' => $approved,
            'declined' => $declined,
            'approver_id' => $approver_id,
            'from' => $from_date,
            'to' => $to_date,
            'status' => $filter_status,
        ));

    }

    public function approveOb(Request $request,$id){

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
                        'approval_remarks' => $request->approval_remarks,
                        'level' => 1,
                    ]);

                }else{
                    EmployeeOb::Where('id', $id)->update([
                        'level' => 1,
                        'approval_remarks' => $request->approval_remarks,
                    ]);
                }
            }
            else if($employee_ob->level == 1){
                EmployeeOb::Where('id', $id)->update([
                    'approved_date' => date('Y-m-d'),
                    'status' => 'Approved',
                    'approval_remarks' => $request->approval_remarks,
                    'level' => 2,
                ]);
            }
            Alert::success('OB has been approved.')->persistent('Dismiss');
            return back();
        }
    }

    public function declineOb(Request $request,$id){
        EmployeeOb::Where('id', $id)->update([
                    'status' => 'Declined',
                    'approval_remarks' => $request->approval_remarks,
        ]);
        Alert::success('OB has been declined.')->persistent('Dismiss');
        return back();
    }

    public function form_dtr_approval(Request $request)
    { 
        $today = date('Y-m-d');
        $from_date = isset($request->from) ? $request->from : date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to_date = isset($request->to) ? $request->to : date('Y-m-d');

        $filter_status = isset($request->status) ? $request->status : 'Pending';
        $approver_id = auth()->user()->id;
        $dtrs = EmployeeDtr::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status',$filter_status)
                                ->whereDate('dtr_date','>=',$from_date)
                                ->whereDate('dtr_date','<=',$to_date)
                                ->orderBy('created_at','DESC')
                                ->get();

        $for_approval = EmployeeDtr::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Pending')
                                ->whereDate('dtr_date','>=',$from_date)
                                ->whereDate('dtr_date','<=',$to_date)
                                ->count();
        $approved = EmployeeDtr::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Approved')
                                ->whereDate('dtr_date','>=',$from_date)
                                ->whereDate('dtr_date','<=',$to_date)
                                ->count();
        $declined = EmployeeDtr::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status','Declined')
                                ->whereDate('dtr_date','>=',$from_date)
                                ->whereDate('dtr_date','<=',$to_date)
                                ->count();
        
        return view('for-approval.dtr-approval',
        array(
            'header' => 'for-approval',
            'dtrs' => $dtrs,
            'for_approval' => $for_approval,
            'approved' => $approved,
            'declined' => $declined,
            'approver_id' => $approver_id,
            'from' => $from_date,
            'to' => $to_date,
            'status' => $filter_status,
        ));

    }

    public function approveDtr(Request $request,$id){
        $employee_dtr = EmployeeDtr::where('id', $id)->first();
        if($employee_dtr){
            $level = '';
            if($employee_dtr->level == 0){
                $employee_approver = EmployeeApprover::where('user_id', $employee_dtr->user_id)->where('approver_id', auth()->user()->id)->first();
                if($employee_approver->as_final == 'on'){
                    EmployeeDtr::Where('id', $id)->update([
                        'approved_date' => date('Y-m-d'),
                        'status' => 'Approved',
                        'approval_remarks' => $request->approval_remarks,
                        'level' => 1,
                    ]);
                }else{
                    EmployeeDtr::Where('id', $id)->update([
                        'approval_remarks' => $request->approval_remarks,
                        'level' => 1
                    ]);
                }
            }
            else if($employee_dtr->level == 1){
                EmployeeDtr::Where('id', $id)->update([
                    'approved_date' => date('Y-m-d'),
                    'status' => 'Approved',
                    'approval_remarks' => $request->approval_remarks,
                    'level' => 2,
                ]);
            }
            Alert::success('DTR has been approved.')->persistent('Dismiss');
            return back();
        }
    }

    public function declineDtr(Request $request,$id){
        EmployeeDtr::Where('id', $id)->update([
                        'status' => 'Declined',
                        'approval_remarks' => $request->approval_remarks,
                    ]);
        Alert::success('DTR has been declined.')->persistent('Dismiss');
        return back();
    }
}
