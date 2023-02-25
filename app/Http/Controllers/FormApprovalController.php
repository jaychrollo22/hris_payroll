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
    public function form_approval (Request $request)
    { 
        $filter_status = isset($request->status) ? $request->status : 'Pending';
        $approver_id = auth()->user()->id;
        $leaves = EmployeeLeave::with('approver.approver_info','user')
                                ->whereHas('approver',function($q) use($approver_id) {
                                    $q->where('approver_id',$approver_id);
                                })
                                ->where('status',$filter_status)
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
                EmployeeLeave::Where('id', $id)->update([
                    'level' => 1,
                ]);
            }
            else if($employee_leave->level == 1){
                EmployeeLeave::Where('id', $id)->update([
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
}
