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

use App\EmployeeApprover;

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

            session([
                'pending_leave_count'=>$pending_leave_count + $request_to_cancel,
                'pending_overtime_count'=>$pending_overtime_count,
                'pending_wfh_count'=>$pending_wfh_count,
                'pending_dtr_count'=>$pending_dtr_count,
                'pending_ob_count'=>$pending_ob_count,
            ]);
        }
    }

}
