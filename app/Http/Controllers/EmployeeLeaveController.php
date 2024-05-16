<?php

namespace App\Http\Controllers;
use App\Http\Controllers\LeaveBalanceController;
use App\Http\Controllers\EmployeeApproverController;
use App\Employee;
use App\ScheduleData;
use App\Leave;
use App\EmployeeLeave;
use App\EmployeeLeaveCredit;

use App\EmployeeLeaveTypeBalance;
use App\EmployeeEarnedAdditionalLeave;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

use DateTime;
use DB;

class EmployeeLeaveController extends Controller
{

 
    public function leaveBalances(Request $request)
    {

        $today = date('Y-m-d');
        
        $year = date('Y');

        $from = isset($request->from) ? $request->from : date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to = isset($request->to) ? $request->to : date('Y-m-d');
        $status = isset($request->status) ? $request->status : 'Pending';

        $employee_status = Employee::select('original_date_hired','classification','gender')
                                        ->where('user_id',auth()->user()->id)
                                        ->first();

        $employee_leave_type_balance = EmployeeLeaveTypeBalance::select('leave_type','year', DB::raw('SUM(balance) as total_balance'))
                                                                    ->groupBy('leave_type','year')
                                                                    ->where('user_id',auth()->user()->id)
                                                                    ->where('year',$year)
                                                                    ->with('leave_type_info')
                                                                    ->where('status','Active')
                                                                    ->get();
        
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

        // $get_leave_balances = new LeaveBalanceController;
        $get_approvers = new EmployeeApproverController;
        $leave_balances = EmployeeLeaveCredit::with('leave')->where('user_id',auth()->user()->id)->get();
        $all_approvers = $get_approvers->get_approvers(auth()->user()->id);
        
        $allowed_to_file = true;
        
        return view('forms.leaves.leaves',
        array(
            'header' => 'forms',
            'leave_balances' => $leave_balances,
            'all_approvers' => $all_approvers,
            'employee_leaves' => $employee_leaves,
            'employee_leaves_all' => $employee_leaves_all,
            'leave_types' => $leave_types,
            'employee_status' => $employee_status,

            'allowed_to_file' => $allowed_to_file,

            'from' => $from,
            'to' => $to,
            'status' => $status,

            'employee_leave_type_balance' => $employee_leave_type_balance
        ));
    }  


    public function new(Request $request)
    {
        $employee = Employee::where('user_id',Auth::user()->id)->first();

        $date_from = $request->date_from;
        $date_to = $request->date_to;

        if($request->halfday == '1'){
            $date_to = $request->date_from;
        }

        $count_days = get_count_days_leave($employee->ScheduleData,$date_from,$date_to);

        $pending_leave = checkPendingLeave(Auth::user()->id,$request->leave_type,date('Y'),'');
        
        $available_balance = $request->leave_balances - $pending_leave;

        if($count_days == 0){
            Alert::warning('Wrong Filing of Leave. Please check your application and then try again.')->persistent('Dismiss');
            return back();
        }

        if($request->withpay == 'on'){
            if($count_days == 1){
                if($request->halfday == '1'){
                    $count_days = 0.5;
                    $date_to = $request->date_from;
                }
            }
           
            if($available_balance >= $count_days){
                $new_leave = new EmployeeLeave;
                $new_leave->user_id = Auth::user()->id;
                $emp = Employee::where('user_id',auth()->user()->id)->first();
                $new_leave->schedule_id = $emp->schedule_id;
                $new_leave->leave_type = $request->leave_type;
                $new_leave->date_from = $date_from;
                $new_leave->date_to = $date_to;
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

                Alert::success('Successfully Store')->persistent('Dismiss');
                return back();
            }else{
                if($pending_leave > 0){
                    Alert::warning('Insufficient Balance to proceed due to pending leave request. Please try again.')->persistent('Dismiss');
                    return back();
                }else{
                    Alert::warning('Insufficient Balance. Please try again.')->persistent('Dismiss');
                    return back();
                }
                
            }
        }else{
            $new_leave = new EmployeeLeave;
            $new_leave->user_id = Auth::user()->id;
            $emp = Employee::where('user_id',auth()->user()->id)->first();
            $new_leave->schedule_id = $emp->schedule_id;
            $new_leave->leave_type = $request->leave_type;
            $new_leave->date_from = $date_from;
            $new_leave->date_to = $date_to;
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

            Alert::success('Successfully Store')->persistent('Dismiss');
            return back();
        }

        
    }

    public function edit($id){
        $year = date('Y');

        $leave = EmployeeLeave::with('leave')->where('id',$id)->first();
        $get_approvers = new EmployeeApproverController;
        $all_approvers = $get_approvers->get_approvers(auth()->user()->id);
        $employee_leave_type_balance = EmployeeLeaveTypeBalance::select('leave_type','year', DB::raw('SUM(balance) as total_balance'))
                                                                    ->groupBy('leave_type','year')
                                                                    ->where('user_id',auth()->user()->id)
                                                                    ->where('year',$year)
                                                                    ->with('leave_type_info')
                                                                    ->get();

        $leave_balance = EmployeeLeaveTypeBalance::select('leave_type','year', DB::raw('SUM(balance) as total_balance'))
                                                                ->groupBy('leave_type','year')
                                                                ->where('user_id',auth()->user()->id)
                                                                ->where('year',$year)
                                                                ->where('leave_type',$leave->leave->code)
                                                                ->first();

        if($leave->leave && $leave_balance){

            $additional_leave = checkEmployeeEarnedLeaveAdditional(auth()->user()->id,$leave->leave->id,$leave_balance->year);
            $used_leave = checkUsedLeave(auth()->user()->id,$leave->leave->id,$leave_balance->year);
            $total_balance = $leave_balance->total_balance + round($additional_leave);
            $remaining = $total_balance - $used_leave;
        }else{
            $used_leave = 0;
            $remaining = 0;
        }
       
        

        return view('forms.leaves.edit_leave',array(
            'header' => 'forms',
            'leave'=>$leave,
            'all_approvers'=>$all_approvers,
            'get_approvers'=>$get_approvers,
            'employee_leave_type_balance' => $employee_leave_type_balance,
            'remaining' => $remaining
        ));
    }

    public function edit_leave(Request $request, $id)
    {
        $employee = Employee::where('user_id',Auth::user()->id)->first();
        $date_from = $request->date_from;
        $date_to = $request->date_to;

        if(isset($request->halfday)){
            if($request->halfday == '1'){
                $date_to = date('Y-m-d',strtotime($request->date_from));
            } 
        }

        $count_days = get_count_days_leave($employee->ScheduleData,$date_from,$date_to);

        $pending_leave = checkPendingLeave(Auth::user()->id,$request->leave_type,date('Y'),$id);
        
        $available_balance = $request->leave_balances - $pending_leave;

        if($count_days == 0){
            Alert::warning('Wrong Filing of Leave. Please check your application and then try again.')->persistent('Dismiss');
            return back();
        }

        if($request->withpay == 'on'){
            if($count_days == 1){
                if($request->halfday == '1'){
                    $count_days = 0.5;
                }
            }

            if($available_balance >= $count_days){
                $new_leave = EmployeeLeave::findOrFail($id);
                $new_leave->user_id = Auth::user()->id;
                $new_leave->leave_type = $request->leave_type;
                $new_leave->date_from = $date_from;
                $new_leave->date_to = $date_to;
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

                if($pending_leave > 0){
                    Alert::warning('Insufficient Balance to proceed due to pending leave request. Please try again.')->persistent('Dismiss');
                    return back();
                }else{
                    Alert::warning('Insufficient Balance. Please try again.')->persistent('Dismiss');
                    return back();
                }
            }
        }else{
            $new_leave = EmployeeLeave::findOrFail($id);
            $new_leave->user_id = Auth::user()->id;
            $new_leave->leave_type = $request->leave_type;
            $new_leave->date_from = $date_from;
            $new_leave->date_to = $date_to;
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
