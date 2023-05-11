<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\EmployeeLeave;
use App\EmployeeOb;
use App\EmployeeWfh;
use App\EmployeeOvertime;
use App\EmployeeDtr;
use RealRashid\SweetAlert\Facades\Alert;

class TimekeepingDashboardController extends Controller
{
    public function index(Request $request){

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);

        $from = isset($request->from) ? $request->from : "";
        $to =  isset($request->to) ? $request->to : "";

        $leaves = EmployeeLeave::with('approver.approver_info','user')
                                ->whereHas('employee',function($q) use($allowed_companies){
                                    $q->whereIn('company_id',$allowed_companies);
                                })
                                ->where('status','Pending')
                                ->whereDate('date_from','>=',$from)
                                ->whereDate('date_from','<=',$to)
                                ->orderBy('created_at','DESC')
                                ->get();
        $obs = EmployeeOb::with('approver.approver_info','user')
                                ->whereHas('employee',function($q) use($allowed_companies){
                                    $q->whereIn('company_id',$allowed_companies);
                                })
                                ->where('status','Pending')
                                ->whereDate('applied_date','>=',$from)
                                ->whereDate('applied_date','<=',$to)
                                ->orderBy('created_at','DESC')
                                ->get();
        
        $wfhs = EmployeeWfh::with('approver.approver_info','user')
                                ->whereHas('employee',function($q) use($allowed_companies){
                                    $q->whereIn('company_id',$allowed_companies);
                                })
                                ->where('status','Pending')
                                ->whereDate('applied_date','>=',$from)
                                ->whereDate('applied_date','<=',$to)
                                ->orderBy('created_at','DESC')
                                ->get();
        
        $overtimes = EmployeeOvertime::with('approver.approver_info','user')
                                ->whereHas('employee',function($q) use($allowed_companies){
                                    $q->whereIn('company_id',$allowed_companies);
                                })
                                ->where('status','Pending')
                                ->whereDate('ot_date','>=',$from)
                                ->whereDate('ot_date','<=',$to)
                                ->orderBy('created_at','DESC')
                                ->get();
        
        $dtrs = EmployeeDtr::with('approver.approver_info','user')
                                ->whereHas('employee',function($q) use($allowed_companies){
                                    $q->whereIn('company_id',$allowed_companies);
                                })
                                ->where('status','Pending')
                                ->whereDate('dtr_date','>=',$from)
                                ->whereDate('dtr_date','<=',$to)
                                ->orderBy('created_at','DESC')
                                ->get();
        
        $emp_data = Employee::with(['attendances' => function ($query) use ($from, $to) {
                                    $query->whereBetween('time_in', [$from." 00:00:01", $to." 23:59:59"])
                                    ->orWhereBetween('time_out', [$from." 00:00:01", $to." 23:59:59"])
                                    ->orderBy('time_in','asc')
                                    ->orderby('time_out','desc')
                                    ->orderBy('id','asc');
                            }])
                            ->whereIn('company_id',$allowed_companies)
                            ->get();

        return view('dashboards.timekeeping_dashboard', 
                    array(
                        'header' => 'Timekeeping',
                        'from' => $from,
                        'to' => $to,
                        'leaves' => $leaves,
                        'obs' => $obs,
                        'wfhs' => $wfhs,
                        'overtimes' => $overtimes,
                        'dtrs' => $dtrs,
                    )
        );
    }

    public function reset_leave($id){
        $request = EmployeeLeave::where('id',$id)->first();
        $request->level = 0;
        $request->mail_1 = null;
        $request->mail_2 = null;
        $request->save();
        Alert::success('Successfully reset')->persistent('Dismiss');
        return back();
    }
    public function reset_ob($id){
        $request = EmployeeOb::where('id',$id)->first();
        $request->level = 0;
        $request->mail_1 = null;
        $request->mail_2 = null;
        $request->save();
        Alert::success('Successfully reset')->persistent('Dismiss');
        return back();
    }
    public function reset_wfh($id){
        $request = EmployeeWfh::where('id',$id)->first();
        $request->level = 0;
        $request->mail_1 = null;
        $request->mail_2 = null;
        $request->save();
        Alert::success('Successfully reset')->persistent('Dismiss');
        return back();
    }
    public function reset_ot($id){
        $request = EmployeeOvertime::where('id',$id)->first();
        $request->level = 0;
        $request->mail_1 = null;
        $request->mail_2 = null;
        $request->save();
        Alert::success('Successfully reset')->persistent('Dismiss');
        return back();
    }
    public function reset_dtr($id){
        $request = EmployeeDtr::where('id',$id)->first();
        $request->level = 0;
        $request->mail_1 = null;
        $request->mail_2 = null;
        $request->save();
        Alert::success('Successfully reset')->persistent('Dismiss');
        return back();
    }
}
