<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use App\EmployeeLeave;
use App\EmployeeOvertime;
use App\EmployeeWfh;
use App\EmployeeOb;
use App\EmployeeDtr;
use App\EmployeePerformanceEvaluation;
use App\EmployeePerformanceEvaluationScore;

use App\EmployeeApprover;

use App\Employee;

use App\UserLog;

use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticated(Request $request, $user) {

        $employee = Employee::select('user_id','status')
                            ->where('user_id',$user->id)
                            ->where('status','Active')
                            ->first();

        if($employee || $user->id == 1){

            $new_user_log = new UserLog;
            $new_user_log->user_id = $user->id;
            $new_user_log->log_date = date('Y-m-d h:i:s');
            $new_user_log->save();

            if(auth()->user()->employee_under->count() != 0){

                $user_ids = EmployeeApprover::select('user_id')->where('approver_id',$user->id)->pluck('user_id')->toArray();

                $today = date('Y-m-d');
                $from_date = date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
                $to_date = date('Y-m-d');

                $pending_leave_count = EmployeeLeave::select('user_id')
                                            ->whereIn('user_id',$user_ids)
                                            ->where('status','Pending')
                                            ->whereDate('created_at','>=',$from_date)
                                            ->whereDate('created_at','<=',$to_date)
                                            ->count();
                $request_to_cancel = EmployeeLeave::select('user_id')
                                            ->whereIn('user_id',$user_ids)
                                            ->whereDate('created_at','>=',$from_date)
                                            ->whereDate('created_at','<=',$to_date)
                                            ->where('request_to_cancel','1')
                                            ->count();

                $pending_overtime_count = EmployeeOvertime::select('user_id')
                                            ->whereIn('user_id',$user_ids)
                                            ->where('status','Pending')
                                            ->whereDate('created_at','>=',$from_date)
                                            ->whereDate('created_at','<=',$to_date)
                                            ->count();
                $pending_wfh_count = EmployeeWfh::select('user_id')
                                            ->whereIn('user_id',$user_ids)
                                            ->where('status','Pending')
                                            ->whereDate('created_at','>=',$from_date)
                                            ->whereDate('created_at','<=',$to_date)
                                            ->count();
                $pending_dtr_count = EmployeeDtr::select('user_id')
                                            ->whereIn('user_id',$user_ids)
                                            ->where('status','Pending')
                                            ->whereDate('created_at','>=',$from_date)
                                            ->whereDate('created_at','<=',$to_date)
                                            ->count();
                $pending_ob_count = EmployeeOb::select('user_id')
                                            ->whereIn('user_id',$user_ids)
                                            ->where('status','Pending')
                                            ->whereDate('created_at','>=',$from_date)
                                            ->whereDate('created_at','<=',$to_date)
                                            ->count();

                $pending_ppr_count = EmployeePerformanceEvaluation::whereIn('user_id',$user_ids)
                                            ->where('status','For Review')
                                            ->whereDate('created_at','>=',$from_date)
                                            ->whereDate('created_at','<=',$to_date)
                                            ->count();

                $pending_for_manager_ratings = EmployeePerformanceEvaluationScore::whereIn('user_id',$user_ids)->where('status','For Approval')->count();

                session([
                    'pending_leave_count'=>$pending_leave_count + $request_to_cancel,
                    'pending_overtime_count'=>$pending_overtime_count,
                    'pending_wfh_count'=>$pending_wfh_count,
                    'pending_dtr_count'=>$pending_dtr_count,
                    'pending_ob_count'=>$pending_ob_count,
                    'pending_performance_eval_count'=>$pending_ppr_count,
                    'pending_for_manager_ratings_count'=>$pending_for_manager_ratings,
                ]);
            }
        }else{
            Auth::logout();
            return redirect()->route('login')->with('message', 'Please contact Administrator.');
        }
    }

}
