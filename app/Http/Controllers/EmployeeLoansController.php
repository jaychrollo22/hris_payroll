<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\EmployeeLoan;
use App\Employee;
use App\Company;
use App\Department;

use RealRashid\SweetAlert\Facades\Alert;

use App\Exports\EmployeeLoanExport;
use App\Imports\EmployeeLoanImport;

use Excel;

class EmployeeLoansController extends Controller
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
        $employee_loans = [];

        $employee_loans = EmployeeLoan::with('user','employee')
                                        ->whereHas('employee',function($q) use($allowed_companies){
                                            $q->whereIn('company_id',$allowed_companies);
                                        })
                                        ->where('status',$status);
        if($search){
            $employee_loans = $employee_loans
                                            ->whereHas('employee',function($q) use($search){
                                                $q->where('first_name', 'like' , '%' .  $search . '%')->orWhere('last_name', 'like' , '%' .  $search . '%')
                                                ->orWhere('employee_number', 'like' , '%' .  $search . '%')
                                                ->orWhere('user_id', 'like' , '%' .  $search . '%')
                                                ->orWhereRaw("CONCAT(`first_name`, ' ', `last_name`) LIKE ?", ["%{$search}%"])
                                                ->orWhereRaw("CONCAT(`last_name`, ' ', `first_name`) LIKE ?", ["%{$search}%"]);;
                                            });
        }
        if($company){
            $employee_loans = $employee_loans
                                            ->whereHas('employee',function($q) use($company){
                                                $q->where('company_id',$company);
                                            });
        }
        if($department){
            $employee_loans = $employee_loans
                                            ->whereHas('employee',function($q) use($department){
                                                $q->where('department_id',$department);
                                            });
        }
        if($search | $company | $department){
            $employee_loans = $employee_loans->get();
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

        return view('employee_loans.employee_loans',array(
            'header' => 'employee_loans',
            'company'=>$company,
            'department'=>$department,
            'employee_loans' => $employee_loans,
            'companies' => $companies,
            'departments' => $departments,
            'employees_selection' => $employees_selection,
            'search' => $search,
            'status' => $status,
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $new = new EmployeeLoan;
        $new->user_id = $request->user_id;
        $new->collection_date = $request->collection_date;
        $new->due_date = $request->due_date;
        $new->particular = $request->particular;
        $new->description = $request->description;
        $new->credit_schedule = $request->credit_schedule;
        $new->credit_company = $request->credit_company;
        $new->credit_branch = $request->credit_branch;
        $new->payable_amount = $request->payable_amount;
        $new->payable_adjustment = $request->payable_adjustment;
        $new->outright_deduction_bolean = $request->outright_deduction_bolean;
        $new->monthly_deduction = $request->monthly_deduction;
        $new->created_by = auth()->user()->id;
        $new->status = 'Active';
        $new->save();

        Alert::success('Successfully Store')->persistent('Dismiss');

        $employee = Employee::select('company_id')->where('user_id',$request->user_id)->first();
        return redirect('employee-loans?search=&company='.$employee->company_id.'&department=&status=');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        $employee_loan = EmployeeLoan::with('user','employee')->where('id',$id)->first();
        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);

        $companies = Company::whereHas('employee_has_company')
                                ->whereIn('id',$allowed_companies)
                                ->get();
        if($employee_loan){
            return view('employee_loans.edit_employee_loan',array(
                'header' => 'edit_employee_loan',
                'employee_loan'=>$employee_loan,
                'search'=>$request->search,
                'companies'=>$companies,
                'company'=>$request->company,
                'department'=>$request->department,
                'status'=>$request->status
            ));
        }else{
            return redirect('employee-loans');
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
        $update = EmployeeLoan::where('id',$id)->first();
        $update->collection_date = $request->collection_date;
        $update->due_date = $request->due_date;
        $update->particular = $request->particular;
        $update->description = $request->description;
        $update->credit_schedule = $request->credit_schedule;
        $update->credit_company = $request->credit_company;
        $update->credit_branch = $request->credit_branch;
        $update->payable_amount = $request->payable_amount;
        $update->payable_adjustment = $request->payable_adjustment;
        $update->outright_deduction_bolean = $request->outright_deduction_bolean;
        $update->monthly_deduction = $request->monthly_deduction;
        $update->updated_by = auth()->user()->id;
        $update->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return redirect('employee-loans?search='.$request->search.'&company='.$request->company.'&department='.$request->department.'&status='.$request->status);
    }

    public function export(Request $request){

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $allowed_companies = json_encode($allowed_companies);
        $company = isset($request->company) ? $request->company : "";
        $department = isset($request->department) ? $request->department : "";
        $status = isset($request->status) ? $request->status : "Active";

        $company_info = Company::where('id',$company)->first();
        $company_name = $company_info ? $company_info->company_code : "";

        return Excel::download(new EmployeeLoanExport($company,$department,$allowed_companies,$status), 'Employee Loan '. $company_name .' .xlsx');
    }

    public function import(Request $request){

        ini_set('memory_limit', '-1');
        
        $path = $request->file('file')->getRealPath();
        $data = Excel::toArray(new EmployeeLoanImport, $request->file('file'));

        $company = isset($request->company) ?  $request->company : null;

        if(count($data[0]) > 0)
        {
            $save_count = 0;
            $not_save = [];
            foreach($data[0] as $key => $value)
            {
                $loan = EmployeeLoan::where('user_id',$value['user_id'])
                                                                ->where('collection_date',$value['collection_date'])
                                                                ->where('particular',$value['particular'])
                                                                ->first();
                if($loan){
                    $loan->collection_date = $value['collection_date'];
                    $loan->due_date = $value['due_date'];
                    $loan->particular = $value['particular'];
                    $loan->description = $value['description'];
                    $loan->credit_schedule = $value['credit_schedule'];
                    $loan->credit_company = $value['credit_company'];
                    $loan->credit_branch = $value['credit_branch'];
                    $loan->payable_amount = $value['payable_amount'];
                    $loan->payable_adjustment = $value['payable_adjustment'];
                    $loan->outright_deduction_bolean = $value['outright_deduction_bolean'];
                    $loan->monthly_deduction = $value['monthly_deduction'];
                    $loan->updated_by = auth()->user()->id;
                    $loan->status = 'Active';
                    $loan->save();

                    $save_count+=1;
                }else{
                    $new_loan = new  EmployeeLoan;
                    $new_loan->user_id = $value['user_id'];
                    $new_loan->collection_date = $value['collection_date'];
                    $new_loan->due_date = $value['due_date'];
                    $new_loan->particular = $value['particular'];
                    $new_loan->description = $value['description'];
                    $new_loan->credit_schedule = $value['credit_schedule'];
                    $new_loan->credit_company = $value['credit_company'];
                    $new_loan->credit_branch = $value['credit_branch'];
                    $new_loan->payable_amount = $value['payable_amount'];
                    $new_loan->payable_adjustment = $value['payable_adjustment'];
                    $new_loan->outright_deduction_bolean = $value['outright_deduction_bolean'];
                    $new_loan->monthly_deduction = $value['monthly_deduction'];
                    $new_loan->status = 'Active';
                    $new_loan->updated_by = auth()->user()->id;
                    $new_loan->save();

                    $save_count+=1;
                }                                         
            }

            Alert::success('Successfully Import Employees (' . $save_count. ')')->persistent('Dismiss');

            return redirect('employee-loans?search=&company='.$company.'&department=&status=Active');

            
        }
    }

}
