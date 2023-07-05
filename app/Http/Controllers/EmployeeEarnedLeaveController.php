<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EmployeeEarnedLeave;
use App\Employee;

use Alert;

use DateTime;

class EmployeeEarnedLeaveController extends Controller
{
    public function index(){
        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $earned_leaves = EmployeeEarnedLeave::with('employee.company','employee.classification_info','leave_type_info')
                                                ->whereHas('employee',function($q) use($allowed_companies){
                                                    $q->whereHas('company',function($w) use($allowed_companies){
                                                        return $w->whereIn('company_id',$allowed_companies);
                                                    });
                                                })
                                                ->orderBy('user_id','ASC')
                                                ->orderBy('leave_type','ASC')
                                                ->orderBy('created_at','DESC')
                                                ->get();

        return view('employee_earned_leaves.index', array(
            'header' => 'masterfiles',
            'earned_leaves' => $earned_leaves,
        ));
    }

    public function manual(Request $request){
        
        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);

        $classifications = [1,2];
        $employees_selection = Employee::whereIn('company_id',$allowed_companies)
                                            ->whereIn('classification',$classifications)
                                            ->where('status','Active')
                                            ->get();

        $fromMonth = $request->date_from ? $request->date_from : ""; 
        $toMonth = $request->date_to ? $request->date_to : ""; 
        $leave_earned_leaves = [];
        if($request->user_id){
            
            $employee = Employee::where('user_id',$request->user_id)->first();

            $day = date('d',strtotime($employee->original_date_hired));
            $from_date = $request->date_from . '-' . $day;
            $to_date = $request->date_to . '-' . $day;
    
            $leave_earned_leaves = EmployeeEarnedLeave::where('user_id',$request->user_id)
                                                        ->whereBetween('earned_date',[$from_date,$to_date])
                                                        ->orderBy('earned_date','ASC')
                                                        ->get();
        }
       

        return view('employee_earned_leaves.manual_earned_leaves', array(
            'header' => 'masterfiles',
            'employees_selection' => $employees_selection,
            'user_id' => $request->user_id,
            'date_from' => $fromMonth,
            'date_to' => $toMonth,
            'earned_leaves' => $leave_earned_leaves,
        ));
    }

    public function manual_store(Request $request){

        $this->validate($request, [
            'user_id' => 'required',
            'from' => 'required',
            'to' => 'required',
        ]);

       
        if($request->from && $request->to && $request->user_id){
            
            $employee = Employee::where('user_id',$request->user_id)->first();

            $fromMonth = new DateTime($request->from); // Replace with your desired starting month and year
            $toMonth = new DateTime($request->to); // Replace with your desired ending month and year

            // Generate the range of month and year combinations
            $count = 0;
            $month_years = [];
            $currentMonth = clone $fromMonth;
            
            $dates = [];
            while ($currentMonth <= $toMonth) {
                $month = $currentMonth->format('m'); // Format the month and year as desired
                $year = $currentMonth->format('Y'); // Format the month and year as desired
                $day = date('d',strtotime($employee->original_date_hired)); // Format the month and year as desired
                $leave_date=$year . '-' . $month . '-' . $day;
                $dates[] = $leave_date;
                // Move to the next month
                $currentMonth->modify('+1 month');
            }
            // return $dates;
            if(count($dates) > 0){
                foreach($dates as $leave_date){

                    // if(date('Y-m-d',strtotime($employee->original_date_hired)) >= date('Y-m-d',strtotime($leave_date))){

                        $year = date('Y',strtotime($leave_date));
                        $month = date('m',strtotime($leave_date));
                        $day = date('d',strtotime($leave_date));
                        
                        $check_if_exist_vl = EmployeeEarnedLeave::where('user_id',$request->user_id)
                                                                    ->where('earned_date',$leave_date)
                                                                    ->where('leave_type',1)
                                                                    ->first();
        
                        
        
                        if(empty($check_if_exist_vl)){
                                $earned_leave = new EmployeeEarnedLeave;
                                $earned_leave->leave_type = 1; // Vacation Leave
                                $earned_leave->user_id = $employee->user_id;
                                $earned_leave->earned_day = $day;
                                $earned_leave->earned_month = $month;
                                $earned_leave->earned_year = $year;
                                $earned_leave->earned_date = $leave_date;
                                $earned_leave->earned_leave = 0.833;
                                $earned_leave->save();
                                $count++;
                        }
        
                        $check_if_exist_sl = EmployeeEarnedLeave::where('user_id',$request->user_id)
                                                                    ->where('earned_date',$leave_date)
                                                                    ->where('leave_type',2)
                                                                    ->first();
        
                        if(empty($check_if_exist_sl)){
                                $earned_leave = new EmployeeEarnedLeave;
                                $earned_leave->leave_type = 2; // Sick Leave
                                $earned_leave->user_id = $employee->user_id;
                                $earned_leave->earned_day = $day;
                                $earned_leave->earned_month = $month;
                                $earned_leave->earned_year = $year;
                                $earned_leave->earned_date = $leave_date;
                                $earned_leave->earned_leave = 0.833;
                                $earned_leave->save();
                                $count++;
                        }
                    // }
    
                }
            }
        }

        Alert::success('Successfully Store')->persistent('Dismiss');
        return redirect('/manual-employee-earned-leaves?user_id=' .$request->user_id . '&date_from=' . $request->from . '&date_to=' . $request->to);
    }

    public function manual_delete(Request $request){
        EmployeeEarnedLeave::where('id',$request->id)->delete();
        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }
}
