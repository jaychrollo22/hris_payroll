<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\EmployeeLeaveTypeBalance;
use App\Employee;
use App\Company;
use App\Department;
use App\Leave;

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
        return back();
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
}
