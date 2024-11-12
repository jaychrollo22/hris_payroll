<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Deduction;
use App\Company;
use App\EmployeeDeduction;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

use App\Exports\EmployeeDeductionExport;
use App\Imports\EmployeeDeductionImport;
use Excel;

class EmployeeDeductionController extends Controller
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

            $employeeDeduction = EmployeeDeduction::where('status',$status)
                                                        ->whereHas('employee',function($q) use($allowed_companies){
                                                            $q->whereIn('company_id',$allowed_companies);
                                                        })
                                                        ->with('employee.company');

            if($company){
                $employeeDeduction = $employeeDeduction->whereHas('employee',function($q) use($company){
                    $q->where('company_id',$company);
                });
            }

            $employeeDeduction = $employeeDeduction->get();


            $deductionTypes = Deduction::where('status','1')->get();

            return view('employee_deductions.index', array(
                'header' => 'masterfiles',
                'employee_deductions' => $employeeDeduction,
                'deductionTypes' => $deductionTypes,
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
            'deduction_id' => 'required',
            'user_id' => 'required',
            'amount' => 'required', 'min:1',
        ]);

        $employeeDeduction = new EmployeeDeduction;
        $employeeDeduction->deduction_id = $request->deduction_id;
        $employeeDeduction->user_id = $request->user_id;
        $employeeDeduction->amount = $request->amount;
        $employeeDeduction->no_of_years_deduction = $request->no_of_years_deduction;
        $employeeDeduction->amortization = $request->amortization;
        $employeeDeduction->type_of_deduction = $request->type_of_deduction;
        $employeeDeduction->status = 'Active';
        $employeeDeduction->save();

        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    }
    
    public function update(Request $request, $id)
    {
        // Validation
        $this->validate($request, [
            'amount' => 'required', 'min:1',
        ]);

        $employeeDeduction = EmployeeDeduction::findOrFail($id);
        $employeeDeduction->deduction_id = $request->deduction_id;
        $employeeDeduction->amount = $request->amount;
        $employeeDeduction->no_of_years_deduction = $request->no_of_years_deduction;
        $employeeDeduction->amortization = $request->amortization;
        $employeeDeduction->type_of_deduction = $request->type_of_deduction;
        $employeeDeduction->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return redirect('edit-employee-deduction/' . $id);
    }

    public function disable($id)
    {
        EmployeeDeduction::Where('id', $id)->update(['status' => 'Inactive']);
        Alert::success('Employee Deduction Inactive')->persistent('Dismiss');
        return back();
    }

    public function delete($id)
    {
        EmployeeDeduction::Where('id', $id)->delete();
        Alert::success('Employee Deduction has been deleted.')->persistent('Dismiss');
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmployeeDeduction  $employeeAllowance
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $deductionTypes = Deduction::where('status','1')->get();

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);

        $employee_deduction = EmployeeDeduction::with('employee')
                                                    ->whereHas('employee',function($q) use($allowed_companies){
                                                        $q->whereIn('company_id',$allowed_companies);
                                                    })
                                                    ->where('id',$id)
                                                    ->first();
        $employees = Employee::select('id','user_id','first_name','last_name','middle_name')
                                    ->whereIn('company_id',$allowed_companies)
                                    ->where('status','Active')
                                    ->get(); 

        if($employee_deduction){
            return view('employee_deductions.edit', array(
                'header' => 'masterfiles',
                'deductionTypes' => $deductionTypes,
                'employee_deduction' => $employee_deduction,
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

        return Excel::download(new EmployeeDeductionExport($company,$status), $company_code. ' Allowances Export.xlsx');
    }

    public function import(Request $request){

        ini_set('memory_limit', '-1');
        
        $path = $request->file('file')->getRealPath();
        $data = Excel::toArray(new EmployeeDeductionImport, $request->file('file'));

        $company = isset($request->company) ?  $request->company : null;

        if(count($data[0]) > 0)
        {
            
            $save_count = 0;
            $not_save = [];
            
            foreach($data[0] as $key => $value)
            {

                $employee_deduction = EmployeeDeduction::where('user_id',$value['user_id'])
                                                                ->where('deduction_id',$value['deduction_id'])
                                                                ->first();
                if($employee_deduction){
                    if(isset($value['deduction_id'])){
                        $employee_deduction->deduction_id = $value['deduction_id'];
                    }
                    if(isset($value['user_id'])){
                        $employee_deduction->deduction_id = $value['user_id'];
                    }
                    if(isset($value['amount'])){
                        $employee_deduction->amount = $value['amount'];
                    }
                    if(isset($value['no_of_years_deduction'])){
                        $employee_deduction->no_of_years_deduction = $value['no_of_years_deduction'];
                    }
                    if(isset($value['amortization'])){
                        $employee_deduction->no_of_years_deduction = $value['amortization'];
                    }
                    if(isset($value['type_of_deduction'])){
                        $employee_deduction->no_of_years_deduction = $value['type_of_deduction'];
                    }
                    
                    $employee_deduction->save();
                    $save_count+=1;
                }else{

                    $employeeDeduction = new EmployeeDeduction;
                    $employeeDeduction->deduction_id = $value['deduction_id'];
                    $employeeDeduction->user_id = $value['user_id'];
                    $employeeDeduction->amount = $value['amount'];
                    $employeeDeduction->no_of_years_deduction = $value['no_of_years_deduction'];
                    $employeeDeduction->amortization = $value['amortization'];
                    $employeeDeduction->type_of_deduction = $value['type_of_deduction'];
                    $employeeDeduction->status = 'Active';
                    $employeeDeduction->save();

                    $save_count+=1;
                }                                         
            }

            Alert::success('Successfully Import Employee Deduction (' . $save_count. ')')->persistent('Dismiss');

            return redirect('employee-deduction?search=&company='.$company);

            
        }
    }

}
