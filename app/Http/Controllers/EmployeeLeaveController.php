<?php

namespace App\Http\Controllers;
use App\Http\Controllers\LeaveBalanceController;
use App\Http\Controllers\EmployeeApproverController;
use App\Employee;
use App\ScheduleData;
use App\Leave;
use App\EmployeeLeave;
use App\EmployeeLeaveCredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

use DateTime;

class EmployeeLeaveController extends Controller
{

 
    public function leaveBalances(Request $request)
    {

        $today = date('Y-m-d');
        $from = isset($request->from) ? $request->from : date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to = isset($request->to) ? $request->to : date('Y-m-d');
        $status = isset($request->status) ? $request->status : 'Pending';

        $employee_status = Employee::select('original_date_hired','classification','gender')->where('user_id',auth()->user()->id)->first();

        $used_vl = checkUsedSLVLSILLeave(auth()->user()->id,1,$employee_status->original_date_hired);
        $used_sl = checkUsedSLVLSILLeave(auth()->user()->id,2,$employee_status->original_date_hired);
        $used_sil = checkUsedSLVLSILLeave(auth()->user()->id,10,$employee_status->original_date_hired);

        $used_ml = checkUsedLeave(auth()->user()->id,3);
        $used_pl = checkUsedLeave(auth()->user()->id,4);
        $used_spl = checkUsedLeave(auth()->user()->id,5);
        $used_splw = checkUsedLeave(auth()->user()->id,7);
        $used_splvv = checkUsedLeave(auth()->user()->id,9);
       
        $earned_vl = checkEarnedLeave(auth()->user()->id,1,$employee_status->original_date_hired);
        $earned_sl = checkEarnedLeave(auth()->user()->id,2,$employee_status->original_date_hired);
        $earned_sil = checkEarnedLeave(auth()->user()->id,10,$employee_status->original_date_hired);

        
        $leave_types = Leave::all(); //masterfile
        $employee_leaves = EmployeeLeave::with('user','leave','schedule')
                                            ->where('user_id',auth()->user()->id)
                                            ->where('status',$status)
                                            ->whereDate('created_at','>=',$from)
                                            ->whereDate('created_at','<=',$to)
                                            ->orderBy('created_at','DESC')
                                            ->get();

        $employee_leaves_all = EmployeeLeave::with('user','leave','schedule')
                                            ->where('user_id',auth()->user()->id)
                                            ->get();

        $get_leave_balances = new LeaveBalanceController;
        $get_approvers = new EmployeeApproverController;
        $leave_balances = EmployeeLeaveCredit::with('leave')->where('user_id',auth()->user()->id)->get();
        $all_approvers = $get_approvers->get_approvers(auth()->user()->id);
        
        $allowed_to_file = true;
        //Validate Project Based
        if($employee_status->classification == '3' || $employee_status->classification == '5'){
            $date_from = new DateTime($employee_status->original_date_hired);
            $date_diff = $date_from->diff(new DateTime(date('Y-m-d')));
            $total_months = (($date_diff->y) * 12) + ($date_diff->m);

            if($total_months > 5){
                $allowed_to_file = true;
            }else{
                $allowed_to_file = false;
            }
        }


        return view('forms.leaves.leaves',
        array(
            'header' => 'forms',
            'leave_balances' => $leave_balances,
            'all_approvers' => $all_approvers,
            'employee_leaves' => $employee_leaves,
            'employee_leaves_all' => $employee_leaves_all,
            'leave_types' => $leave_types,
            'employee_status' => $employee_status,
            'used_vl' => $used_vl,
            'used_sl' => $used_sl,
            'used_sil' => $used_sil,
            'used_ml' => $used_ml,
            'used_pl' => $used_pl,
            'used_spl' => $used_spl,
            'used_splw' => $used_splw,
            'used_splvv' => $used_splvv,
            'earned_vl' => $earned_vl,
            'earned_sl' => $earned_sl,
            'earned_sil' => $earned_sil,
            'allowed_to_file' => $allowed_to_file,
            'from' => $from,
            'to' => $to,
            'status' => $status,
        ));
    }  


    public function new(Request $request)
    {
        $employee = Employee::where('user_id',Auth::user()->id)->first();
        $count_days = get_count_days_leave($employee->ScheduleData,$request->date_from,$request->date_to);
        if($request->withpay == 'on'){
            if($count_days == 1){
                if($request->halfday == '1'){
                    $count_days = 0.5;
                }
            }
            if($request->leave_balances >= $count_days){
                $new_leave = new EmployeeLeave;
                $new_leave->user_id = Auth::user()->id;
                $emp = Employee::where('user_id',auth()->user()->id)->first();
                $new_leave->schedule_id = $emp->schedule_id;
                $new_leave->leave_type = $request->leave_type;
                $new_leave->date_from = $request->date_from;
                $new_leave->date_to = $request->date_to;
                $new_leave->reason = $request->reason;
                $new_leave->withpay = $request->withpay == 'on' ? 1 : 0 ;
                $new_leave->halfday = (isset($request->halfday)) ? $request->halfday : 0 ; 
                $new_leave->halfday_status = $request->halfday == '1' && (isset($request->halfday_status)) ? $request->halfday_status : "" ; 

                if($request->file('attachment')){
                    $logo = $request->file('attachment');
                    $original_name = $logo->getClientOriginalName();
                    $name = time() . '_' . $logo->getClientOriginalName();
                    $logo->move(public_path() . '/images/', $name);
                    $file_name = '/images/' . $name;
                    $new_leave->attachment = $file_name;
                }

                $new_leave->status = 'Pending';
                $new_leave->level = 0;
                $new_leave->created_by = Auth::user()->id;
                $new_leave->save();

                Alert::success('Successfully Stored')->persistent('Dismiss');
                return back();
            }else{
                Alert::warning('Insufficient Balance. Please try again.')->persistent('Dismiss');
                return back();
            }
        }else{
            $new_leave = new EmployeeLeave;
            $new_leave->user_id = Auth::user()->id;
            $emp = Employee::where('user_id',auth()->user()->id)->first();
            $new_leave->schedule_id = $emp->schedule_id;
            $new_leave->leave_type = $request->leave_type;
            $new_leave->date_from = $request->date_from;
            $new_leave->date_to = $request->date_to;
            $new_leave->reason = $request->reason;
            $new_leave->withpay = $request->withpay == 'on' ? 1 : 0 ;
            $new_leave->halfday = (isset($request->halfday)) ? $request->halfday : 0 ; 
            $new_leave->halfday_status = $request->halfday == '1' && (isset($request->halfday_status)) ? $request->halfday_status : "" ; 

            if($request->file('attachment')){
                $logo = $request->file('attachment');
                $original_name = $logo->getClientOriginalName();
                $name = time() . '_' . $logo->getClientOriginalName();
                $logo->move(public_path() . '/images/', $name);
                $file_name = '/images/' . $name;
                $new_leave->attachment = $file_name;
            }

            $new_leave->status = 'Pending';
            $new_leave->level = 0;
            $new_leave->created_by = Auth::user()->id;
            $new_leave->save();

            Alert::success('Successfully Stored')->persistent('Dismiss');
            return back();
        }

        
    }


    public function edit_leave(Request $request, $id)
    {
        $employee = Employee::where('user_id',Auth::user()->id)->first();
        $count_days = get_count_days_leave($employee->ScheduleData,$request->date_from,$request->date_to);
        if($request->withpay == 'on'){

            if($count_days == 1){
                if($request->halfday == '1'){
                    $count_days = 0.5;
                }
            }

            if($request->leave_balances >= $count_days){
                $new_leave = EmployeeLeave::findOrFail($id);
                $new_leave->user_id = Auth::user()->id;
                $new_leave->leave_type = $request->leave_type;
                $new_leave->date_from = $request->date_from;
                $new_leave->date_to = $request->date_to;
                $new_leave->reason = $request->reason;
                $new_leave->withpay = $request->withpay == 'on' ? 1 : 0 ;
                $new_leave->halfday = (isset($request->halfday)) ? $request->halfday : 0 ; 
                $new_leave->halfday_status = $request->halfday == '1' && (isset($request->halfday_status)) ? $request->halfday_status : ""; 

                $logo = $request->file('attachment');
                if(isset($logo)){
                    $original_name = $logo->getClientOriginalName();
                    $name = time() . '_' . $logo->getClientOriginalName();
                    $logo->move(public_path() . '/images/', $name);
                    $file_name = '/images/' . $name;
                    $new_leave->attachment = $file_name;
                }
                $new_leave->status = 'Pending';
                $new_leave->level = 0;
                $new_leave->created_by = Auth::user()->id;
                $new_leave->save();

                Alert::success('Successfully Updated')->persistent('Dismiss');
                return back();
            }else{

                Alert::warning('Insufficient Balance. Please try again.')->persistent('Dismiss');
                return back();
            }
        }else{
            $new_leave = EmployeeLeave::findOrFail($id);
            $new_leave->user_id = Auth::user()->id;
            $new_leave->leave_type = $request->leave_type;
            $new_leave->date_from = $request->date_from;
            $new_leave->date_to = $request->date_to;
            $new_leave->reason = $request->reason;
            $new_leave->withpay = $request->withpay == 'on' ? 1 : 0 ;
            $new_leave->halfday = (isset($request->halfday)) ? $request->halfday : 0 ; 
            $new_leave->halfday_status = $request->halfday == '1' && (isset($request->halfday_status)) ? $request->halfday_status : ""; 

            $logo = $request->file('attachment');
            if(isset($logo)){
                $original_name = $logo->getClientOriginalName();
                $name = time() . '_' . $logo->getClientOriginalName();
                $logo->move(public_path() . '/images/', $name);
                $file_name = '/images/' . $name;
                $new_leave->attachment = $file_name;
            }
            $new_leave->status = 'Pending';
            $new_leave->level = 0;
            $new_leave->created_by = Auth::user()->id;
            $new_leave->save();

            Alert::success('Successfully Updated')->persistent('Dismiss');
            return back();
        }
    }

    public function disable_leave($id)
    {
        EmployeeLeave::Where('id', $id)->update(['status' => 'Cancelled']);
        Alert::success('Leave has been cancelled.')->persistent('Dismiss');
        return back();
        
    }
    public function request_to_cancel(Request $request,$id)
    {
        EmployeeLeave::Where('id', $id)->update([
                        'request_to_cancel' => '1',
                        'request_to_cancel_remarks' => $request->request_to_cancel_remarks,
                    ]);
        Alert::success('Leave Request to Cancel has been saved.')->persistent('Dismiss');
        return back();
        
    }
    public function void_request_to_cancel($id)
    {
        EmployeeLeave::Where('id', $id)->update([
                        'request_to_cancel' => null,
                        'request_to_cancel_remarks' => null,
                    ]);
        Alert::success('Request to Cancel Leave has been Void.')->persistent('Dismiss');
        return back();
        
    }
    public function approve_request_to_cancel(Request $request,$id)
    {
        EmployeeLeave::Where('id', $id)->update([
                            'status' => 'Declined',
                            'approval_remarks' => 'Request to Cancel',
                            'request_to_cancel' => '2',
                        ]);

        Alert::success('Request to Cancel Leave has been Approved.')->persistent('Dismiss');
        return back();
        
    }
    public function decline_request_to_cancel(Request $request,$id)
    {
        EmployeeLeave::Where('id', $id)->update([
                            'request_to_cancel' => 0
                        ]);

        Alert::success('Request to Cancel Leave has been Declined.')->persistent('Dismiss');
        return back();
        
    }
    

        
}
