<?php

namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\PayrollPeriod;
use App\PayrollRegister;
use App\Employee;
use App\Company;
use App\Department;
use PDF;

class PayrollPayslipController extends Controller
{
    public function index(Request $request){
        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);

        $companies = Company::whereHas('employee_has_company')
                                ->whereIn('id',$allowed_companies)
                                ->get();

        $search = isset($request->search) ? $request->search : "";
        $status = isset($request->status) ? $request->status : "Active";
        $company = isset($request->company) ? $request->company : "";
        $department = isset($request->department) ? $request->department : "";
        $payroll_period = isset($request->payroll_period) ? $request->payroll_period : "";

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        
        $departments = [];
        
        $payroll_registers = PayrollRegister::where('payroll_period_id',$payroll_period);
        
        if($department){
            $payroll_registers->whereHas('employee',function($q) use($department){
                $q->where('department_id',$department);
            });
        }

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

            $payroll_registers->whereHas('employee',function($q) use($company){
                $q->where('company_id',$company);
            });

        }else{
            $departments = Department::where('status','1')->orderBy('name')->get();
        }

        $payroll_periods = PayrollPeriod::all();
        $payroll_registers = $payroll_registers->get();

        return view(
            'payroll_payslip.index',
            array(
                'header' => 'payslip',
                'payroll_periods' => $payroll_periods,
                'payroll_period' => $payroll_period,
                'companies' => $companies,
                'company' => $company,
                'departments' => $departments,
                'department' => $department,
                'search' => $search,
                'payroll_registers' => $payroll_registers
            )
        );
    }

    public function generate(PayrollRegister $payrollRegister)
    {
        $pdf = PDF::loadView('payroll_payslip.print', compact('payrollRegister'));
        return $pdf->stream('payroll_payslip.print');
        // return $pdf->download('payroll_payslip.print_payslip');
    }    
}
