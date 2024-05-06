<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\EmployeeLeaveTypeBalance;
use App\Employee;
use App\Company;
use App\Department;
use App\Leave;
use App\EmployeeLeave;
use App\EmployeeLeaveAdditional;

use RealRashid\SweetAlert\Facades\Alert;

use App\Exports\EmployeeLeaveTypeBalanceExport;
use App\Imports\EmployeeLeaveTypeBalanceImport;

use Excel;

class EmployeeLeaveTypeBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);

        $companies = Company::whereHas('employee_has_company')
                                ->whereIn('id',$allowed_companies)
                                ->get();

        $search = isset($request->search) ? $request->search : "";
        $status = isset($request->status) ? $request->status : "Active";
        $company = isset($request->company) ? $request->company : "";
        $department = isset($request->department) ? $request->department : "";
        $employee_leave_type_balances = [];

        
        $employee_leave_type_balances = EmployeeLeaveTypeBalance::with('user','employee','leave_type_info')
                                                                    ->whereHas('employee',function($q) use($allowed_companies){
                                                                        $q->whereIn('company_id',$allowed_companies);
                                                                    })
                                                                    ->where('status',$status);
        if($search){
            $employee_leave_type_balances = $employee_leave_type_balances
                                            ->whereHas('employee',function($q) use($search){
                                                $q->where('first_name', 'like' , '%' .  $search . '%')->orWhere('last_name', 'like' , '%' .  $search . '%')
                                                ->orWhere('employee_number', 'like' , '%' .  $search . '%')
                                                ->orWhere('user_id', 'like' , '%' .  $search . '%')
                                                ->orWhereRaw("CONCAT(`first_name`, ' ', `last_name`) LIKE ?", ["%{$search}%"])
                                                ->orWhereRaw("CONCAT(`last_name`, ' ', `first_name`) LIKE ?", ["%{$search}%"]);;
                                            });
        }
        if($company){
            $employee_leave_type_balances = $employee_leave_type_balances
                                            ->whereHas('employee',function($q) use($company){
                                                $q->where('company_id',$company);
                                            });
        }
        if($department){
            $employee_leave_type_balances = $employee_leave_type_balances
                                            ->whereHas('employee',function($q) use($department){
                                                $q->where('department_id',$department);
                                            });
        }
        if($search | $company | $department){
            $employee_leave_type_balances = $employee_leave_type_balances->get();
        }

        $departments = [];
        if($company){
            $department_companies = Employee::when($company,function($q) use($company){
                            $q->where('company_id',$company);
                        })
                        ->groupBy('department_id')
                        ->pluck('department_id')
                        ->toArray();

            $departments = Department::whereIn('id',$department_companies)->where('status','1')
                    ->orderBy('name')
                    ->get();
        }else{
            $departments = Department::where('status','1')->orderBy('name')->get();
        }
        
        $employees_selection = Employee::whereIn('company_id',$allowed_companies)->where('status','Active')->get();

        $leave_types = Leave::all();

        return view('employee_leave_type_balances.employee_leave_type_balances',array(
            'header' => 'employee_leave_type_balances',
            'company'=>$company,
            'department'=>$department,
            'employee_leave_type_balances' => $employee_leave_type_balances,
            'companies' => $companies,
            'departments' => $departments,
            'employees_selection' => $employees_selection,
            'leave_types' => $leave_types,
            'search' => $search,
            'status' => $status,
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $new = new EmployeeLeaveTypeBalance;
        $new->user_id = $request->user_id;
        $new->year = $request->year;
        $new->leave_type = $request->leave_type;
        $new->balance = $request->balance;
        $new->status = 'Active';
        $new->save();

        Alert::success('Successfully Store')->persistent('Dismiss');

        $employee = Employee::select('company_id')->where('user_id',$request->user_id)->first();
        return redirect('employee-leave-type-balances?search=&company='.$employee->company_id.'&department=&status=');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {

        $employee_leave_type_balance = EmployeeLeaveTypeBalance::with('user','employee','leave_type_info')->where('id',$id)->first();
        $leave_types = Leave::all();

        if($employee_leave_type_balance){
            return view('employee_leave_type_balances.edit_employee_leave_type_balance',array(
                'header' => 'employee_leave_type_balances',
                'employee_leave_type_balance'=>$employee_leave_type_balance,
                'leave_types'=>$leave_types,
                'search'=>$request->search,
                'company'=>$request->company,
                'department'=>$request->department,
                'status'=>$request->status
            ));
        }else{
            return redirect('employee-leave-type-balances');
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $update = EmployeeLeaveTypeBalance::where('id',$id)->first();
        $update->year = $request->year;
        $update->leave_type = $request->leave_type;
        $update->balance = $request->balance;
        $update->status = $request->status;
        $update->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return redirect('employee-leave-type-balances?search='.$request->search.'&company='.$request->company.'&department='.$request->department.'&status='.$request->status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = EmployeeLeaveTypeBalance::where('id',$id)->delete();
        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function export(Request $request){

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $allowed_companies = json_encode($allowed_companies);
        $company = isset($request->company) ? $request->company : "";
        $department = isset($request->department) ? $request->department : "";
        $status = isset($request->status) ? $request->status : "Active";

        $company_info = Company::where('id',$company)->first();
        $company_name = $company_info ? $company_info->company_code : "";

        return Excel::download(new EmployeeLeaveTypeBalanceExport($company,$department,$allowed_companies,$status), 'Employee Leave Management '. $company_name .' .xlsx');
    }

    public function import(Request $request){

        ini_set('memory_limit', '-1');
        
        $path = $request->file('file')->getRealPath();
        $data = Excel::toArray(new EmployeeLeaveTypeBalanceImport, $request->file('file'));

        $company = isset($request->company) ?  $request->company : null;

        if(count($data[0]) > 0)
        {
            $save_count = 0;
            $not_save = [];
            foreach($data[0] as $key => $value)
            {
                $leave_balance = EmployeeLeaveTypeBalance::where('user_id',$value['user_id'])
                                                                ->where('year',$value['year'])
                                                                ->where('leave_type',$value['leave_type'])
                                                                ->first();
                if($leave_balance){
                    $leave_balance->user_id = $value['user_id'];
                    $leave_balance->year = $value['year'];
                    $leave_balance->leave_type = $value['leave_type'];
                    $leave_balance->balance = $value['leave_balance'];
                    $leave_balance->save();

                    $save_count+=1;
                }else{
                    $new_leave_balance = new  EmployeeLeaveTypeBalance;
                    $new_leave_balance->user_id = $value['user_id'];
                    $new_leave_balance->year = $value['year'];
                    $new_leave_balance->leave_type = $value['leave_type'];
                    $new_leave_balance->balance = $value['leave_balance'];
                    $new_leave_balance->status = 'Active';
                    $new_leave_balance->save();

                    $save_count+=1;
                }                                         
            }

            Alert::success('Successfully Import Employees (' . $save_count. ')')->persistent('Dismiss');

            return redirect('employee-leave-type-balances?search=&company='.$company.'&department=&status=Active');

            
        }
    }

    public function employee_used_leaves(Request $request,$id)
    {

        $leaves = Leave::all();

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $year = isset($request->year) ? $request->year : date('Y');
        $leave_type = isset($request->leave_type) ? $request->leave_type : "";
        $withpay_status = isset($request->withpay_status) ? $request->withpay_status : "1";
        $status = isset($request->status) ? $request->status : "Approved";

        $employee = Employee::where('user_id',$id)->first();
        $employee_leaves = EmployeeLeave::where('user_id',$id)
                                    ->where('withpay',$withpay_status)
                                    ->where('status',$status)
                                    ->whereYear('date_from', '=', $year);
        if($leave_type){
            $employee_leaves = $employee_leaves->where('leave_type',$leave_type);
        }
        
        $employee_leaves = $employee_leaves->get();

        if($employee_leaves){
            return view('employee_leave_type_balances.employee_used_leaves',array(
                'header' => 'employee_leave_type_balances',
                'employee'=>$employee,
                'employee_leaves'=>$employee_leaves,
                'year'=>$year,
                'status'=>$status,
                'withpay_status'=>$withpay_status,
                'leave_type'=>$leave_type,
                'leaves'=>$leaves
            ));     
        }else{
            return "No Employee Found!";
        }
    }

    public function cancel_employee_used_leaves(Request $request, $id){

        $employee_leave = EmployeeLeave::where('id',$id)->first();

        if($employee_leave){
            $employee_leave->hr_cancel_remarks = $request->hr_cancel_remarks;
            $employee_leave->status = "Cancelled";
            $employee_leave->save();

            Alert::success('Employee Leave has been cancelled.')->persistent('Dismiss');
            return redirect('employee-used-leaves/' . $employee_leave->user_id);

        }

    }

    public function manual_additional_earned_leave(Request $request){ //Manual Additional Earned Leaves


        $d = isset($request->date) ? date('d',strtotime($request->date)) : date('d');
        $m = isset($request->date) ? date('m',strtotime($request->date)) : date('m');
        $year = isset($request->date) ? date('Y',strtotime($request->date)) : date('Y');
        $today = isset($request->date) ? date('Y-m-d',strtotime($request->date)) : date('Y-m-d');

        $employees = Employee::select('id','user_id','classification','original_date_hired')
                                ->whereNotNull('original_date_hired')
                                ->whereRaw("DATE_FORMAT(original_date_hired, '%d') = ?", [$d])
                                ->where('classification','1')
                                ->where('status','Active')
                                ->get();

        $count = 0;
        if(count($employees) > 0){
            foreach($employees as $employee){

                $vl_id = 1;
                $sl_id = 2;

                $validate_leave_type_balance_vl = EmployeeLeaveTypeBalance::where('user_id',$employee->user_id)
                                                                        ->where('year',$year)
                                                                        ->where('leave_type','VL')
                                                                        ->first();
                if(empty($validate_leave_type_balance_vl)){
                    $new_leave_type_balance_vl = new EmployeeLeaveTypeBalance;
                    $new_leave_type_balance_vl->user_id = $employee->user_id;
                    $new_leave_type_balance_vl->year = $year;
                    $new_leave_type_balance_vl->leave_type = 'VL';
                    $new_leave_type_balance_vl->balance = 0;
                    $new_leave_type_balance_vl->status = 'Active';
                    $new_leave_type_balance_vl->save();
                }

                $validate_leave_type_balance_sl = EmployeeLeaveTypeBalance::where('user_id',$employee->user_id)
                                                                        ->where('year',$year)
                                                                        ->where('leave_type','SL')
                                                                        ->first();

                if(empty($validate_leave_type_balance_sl)){
                    $new_leave_type_balance_sl = new EmployeeLeaveTypeBalance;
                    $new_leave_type_balance_sl->user_id = $employee->user_id;
                    $new_leave_type_balance_sl->year = $year;
                    $new_leave_type_balance_sl->leave_type = 'SL';
                    $new_leave_type_balance_sl->balance = 0;
                    $new_leave_type_balance_sl->status = 'Active';
                    $new_leave_type_balance_sl->save();
                }

                $validate_vl = EmployeeLeaveAdditional::where('leave_type',$vl_id)
                                                        ->where('user_id',$employee->user_id)
                                                        ->where('earned_date',$today)
                                                        ->first();

                $validate_sl = EmployeeLeaveAdditional::where('leave_type',$sl_id)
                                                        ->where('user_id',$employee->user_id)
                                                        ->where('earned_date',$today)
                                                        ->first();
                if(empty($validate_vl)){
                    $earned_leave = new EmployeeLeaveAdditional;
                    $earned_leave->leave_type = $vl_id;
                    $earned_leave->user_id = $employee->user_id;
                    $earned_leave->earned_date = $today;
                    $earned_leave->earned_year = $year;
                    $earned_leave->earned_leave = 0.833;
                    $earned_leave->save();

                    $count++;
                }

                if(empty($validate_sl)){
                    $earned_leave = new  EmployeeLeaveAdditional;
                    $earned_leave->leave_type = $sl_id;
                    $earned_leave->user_id = $employee->user_id;
                    $earned_leave->earned_date = $today;
                    $earned_leave->earned_year = $year;
                    $earned_leave->earned_leave = 0.833;
                    $earned_leave->save();
                    $count++;
                }
            }
        }

        return $count;
    }

}
