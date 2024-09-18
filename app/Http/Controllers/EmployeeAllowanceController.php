<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Allowance;
use App\Company;
use App\EmployeeAllowance;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

use App\Exports\EmployeeAllowanceExport;
use App\Imports\EmployeeAllowanceImport;
use Excel;

class EmployeeAllowanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(checkUserPrivilege('masterfiles_employee_allowances',auth()->user()->id) == 'yes'){

            $allowed_companies = getUserAllowedCompanies(auth()->user()->id);

            $companies = Company::whereHas('employee_has_company')
                                    ->whereIn('id',$allowed_companies)
                                    ->get();

            $company = isset($request->company) ? $request->company : "";
            $status = isset($request->status) ? $request->status : "Active";

            $employees = Employee::select('id','user_id','first_name','last_name','middle_name')
                                        ->whereIn('company_id',$allowed_companies)
                                        ->where('status','Active')
                                        ->get();

            $employeeAllowances = EmployeeAllowance::where('status',$status)
                                                        ->whereHas('employee',function($q) use($allowed_companies){
                                                            $q->whereIn('company_id',$allowed_companies);
                                                        })
                                                        ->with('employee.company');

            if($company){
                $employeeAllowances = $employeeAllowances->whereHas('employee',function($q) use($company){
                    $q->where('company_id',$company);
                });
            }

            $employeeAllowances = $employeeAllowances->get();


            $allowanceTypes = Allowance::where('status','Active')->get();

            return view('employee_allowances.employee_allowance', array(
                'header' => 'masterfiles',
                'employeeAllowances' => $employeeAllowances,
                'allowanceTypes' => $allowanceTypes,
                'employees' => $employees,
                'companies' => $companies,
                'company' => $company,
                'status' => $status,

            ));
               
        }else{
            return 'Not Allowed. Please contact administrator. Thank you';
        }   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation
        $this->validate($request, [
            'allowance_type' => 'required',
            'user_id' => 'required',
            'amount' => 'required', 'min:1',
            'effective_date' => 'required',
            'is_taxable' => 'required'
        ]);

        $employeeAllowances = new EmployeeAllowance;
        $employeeAllowances->allowance_id = $request->allowance_type;
        $employeeAllowances->user_id = $request->user_id;
        $employeeAllowances->description = $request->description;
        $employeeAllowances->application = $request->application;
        $employeeAllowances->type = $request->type;
        $employeeAllowances->schedule = $request->schedule;
        $employeeAllowances->allowance_amount = $request->amount;
        $employeeAllowances->end_date = $request->end_date;
        $employeeAllowances->status = 'Active';
        $employeeAllowances->percentage = $request->percentage;
        $employeeAllowances->effective_date = $request->effective_date;
        $employeeAllowances->frequency = $request->frequency;
        $employeeAllowances->is_taxable = $request->is_taxable;
        $employeeAllowances->save();

        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    }
    
    public function update(Request $request, $id)
    {
        // Validation
        $this->validate($request, [
            'amount' => 'required', 'min:1',
        ]);

        $employeeAllowances = EmployeeAllowance::findOrFail($id);
        $employeeAllowances->allowance_id = $request->allowance_type;
        $employeeAllowances->allowance_amount = $request->amount;
        $employeeAllowances->description = $request->description;
        $employeeAllowances->application = $request->application;
        $employeeAllowances->type = $request->type;
        $employeeAllowances->schedule = $request->schedule;
        $employeeAllowances->allowance_amount = $request->amount;
        $employeeAllowances->end_date = $request->end_date;
        $employeeAllowances->status = 'Active';
        $employeeAllowances->percentage = $request->percentage;
        $employeeAllowances->effective_date = $request->effective_date;
        $employeeAllowances->frequency = $request->frequency;
        $employeeAllowances->is_taxable = $request->is_taxable;
        $employeeAllowances->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return redirect('edit-employee-allowance/' . $id);
    }

    public function disable($id)
    {
        EmployeeAllowance::Where('id', $id)->update(['status' => 'Inactive']);
        Alert::success('Employee Allowance Inactive')->persistent('Dismiss');
        return back();
    }

    public function delete($id)
    {
        EmployeeAllowance::Where('id', $id)->delete();
        Alert::success('Employee Allowance has been deleted.')->persistent('Dismiss');
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmployeeAllowance  $employeeAllowance
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $allowanceTypes = Allowance::where('status','Active')->get();

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);

        $employee_allowance = EmployeeAllowance::with('employee')
                                                    ->whereHas('employee',function($q) use($allowed_companies){
                                                        $q->whereIn('company_id',$allowed_companies);
                                                    })
                                                    ->where('id',$id)
                                                    ->first();
        $employees = Employee::select('id','user_id','first_name','last_name','middle_name')
                                    ->whereIn('company_id',$allowed_companies)
                                    ->where('status','Active')
                                    ->get(); 

        if($employee_allowance){
            return view('employee_allowances.edit_emp_allowance', array(
                'header' => 'masterfiles',
                'allowanceTypes' => $allowanceTypes,
                'employee_allowance' => $employee_allowance,
                'employees' => $employees
            ));
        }else{
            return 'You are not allowed to proceed. Thank you.';
        }
        
    }


    public function export(Request $request){
        $company = isset($request->company) ? $request->company : "";
        $status = isset($request->status) ? $request->status : "";
        $company_detail = Company::where('id',$company)->first();

        $company_code = $company_detail ? $company_detail->company_code : "";

        return Excel::download(new EmployeeAllowanceExport($company,$status), $company_code. ' Allowances Export.xlsx');
    }

    public function import(Request $request){

        ini_set('memory_limit', '-1');
        
        $path = $request->file('file')->getRealPath();
        $data = Excel::toArray(new EmployeeAllowanceImport, $request->file('file'));

        $company = isset($request->company) ?  $request->company : null;

        if(count($data[0]) > 0)
        {
            // return $data[0];
            $save_count = 0;
            $not_save = [];
            foreach($data[0] as $key => $value)
            {
                $employee_allowance = EmployeeAllowance::where('user_id',$value['user_id'])
                                                                ->where('allowance_id',$value['particular'])
                                                                ->first();
                if($employee_allowance){
                    if(isset($value['particular'])){
                        $employee_allowance->allowance_id = $value['particular'];
                    }
                    
                    if(isset($value['user_id'])){
                        $employee_allowance->user_id = $value['user_id'];
                    }
                    if(isset($value['description'])){
                        $employee_allowance->description = $value['description'];
                    }
                    if(isset($value['application'])){
                        $employee_allowance->application = $value['application'];
                    }
                    if(isset($value['type'])){
                        $employee_allowance->type = $value['type'];
                    }
                    if(isset($value['credit_schedule'])){
                        $employee_allowance->schedule = $value['credit_schedule'];
                    }
                    if(isset($value['amount'])){
                        $employee_allowance->allowance_amount = $value['amount'];
                    }
                    if(isset($value['end_date'])){
                        $end_date = $value['end_date'];
                        if($end_date > 0){
                            $convert_date = ($end_date - 25569) * 86400;
                            $employee_allowance->end_date = date('Y-m-d', $convert_date);
                        }
                    }
                    $employee_allowance->save();
                    $save_count+=1;
                }else{
                    $newEmployeeAllowance = new EmployeeAllowance;
                    $newEmployeeAllowance->allowance_id = $value['particular'];
                    $newEmployeeAllowance->user_id = $value['user_id'];
                    $newEmployeeAllowance->description = $value['description'];
                    $newEmployeeAllowance->application = $value['application'];
                    $newEmployeeAllowance->type = $value['type'];
                    $newEmployeeAllowance->schedule =$value['credit_schedule'];
                    $newEmployeeAllowance->allowance_amount = $value['amount'];
                
                    if(isset($value['end_date'])){
                        $end_date = $value['end_date'];
                        if($end_date > 0){
                            $convert_date = ($end_date - 25569) * 86400;
                            $newEmployeeAllowance->end_date =date('Y-m-d', $convert_date);
                        }
                    }

                    $newEmployeeAllowance->status = 'Active';
                    $newEmployeeAllowance->save();

                    $save_count+=1;
                }                                         
            }

            Alert::success('Successfully Import Employee Allowances (' . $save_count. ')')->persistent('Dismiss');

            return redirect('employee-allowance?search=&company='.$company);

            
        }
    }

}
