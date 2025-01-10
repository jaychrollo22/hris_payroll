<?php

namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\PayrollPeriod;
use App\PayrollRegister;
use App\Employee;
use App\Company;
use App\Department;
use App\User;
use PDF;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Hash;

class PayrollPayslipController extends Controller
{
    public function index(Request $request){
        $allowed_companies = getUserAllowedPayrollCompanies(auth()->user()->id);

        $companies = Company::whereHas('employee_has_company')
                                ->whereIn('id',$allowed_companies)
                                ->get();

        $search = isset($request->search) ? $request->search : "";
        $status = isset($request->status) ? $request->status : "Active";
        $company = isset($request->company) ? $request->company : "";
        $department = isset($request->department) ? $request->department : "";
        $payroll_period = isset($request->payroll_period) ? $request->payroll_period : "";
        
        $departments = [];
        
        $payroll_registers = PayrollRegister::where('payroll_period_id',$payroll_period)
            ->where('posting_status','Posted');

        if(checkUserPrivilege('payslip_filter_per_company',auth()->user()->id) == 'yes'){
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
        }else{
            $payroll_registers->where('user_id',auth()->user()->id);
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

    public function showPasswordForm(PayrollRegister $payrollRegister){
        return view('payroll_payslip.password',array(
            'header' => 'payslip',
            'payrollRegister' => $payrollRegister
        ));
    }


    // Validate the password and allow access to the PDF
    public function validatePassword(Request $request)
    {
        $request->validate([
            'payslip_id' => 'required',
            'password' => 'required',
        ]);

        $payrollRegister = PayrollRegister::find($request->payslip_id);
        $user = User::find($payrollRegister->user_id);
     
        $access_granted = false;

        if(Hash::check($request->password, $user->password)) $access_granted = true;
        if(!$access_granted && checkUserPrivilege('payslip_filter_per_company',auth()->user()->id) == 'yes'){
            $allowed_companies = getUserAllowedPayrollCompanies(auth()->user()->id);
            $company_names = Company::whereIn('id',$allowed_companies)
                ->get()
                ->pluck('company_name')
                ->toArray();

            if(in_array($payrollRegister->company,$company_names) && Hash::check($request->password, auth()->user()->password)) $access_granted = true;
        }

        if ($access_granted) {
            // Store a session variable to track access
            session(['payslip_access_granted' => true]);
            // Password matches
            return redirect()->route('payslip.view',['payrollRegister' => $request->payslip_id])->with('success', 'Password is correct!');
        } else {
            // Password does not match
            return back()->withErrors(['password' => 'Incorrect password.']);
        }
    }


    public function view(PayrollRegister $payrollRegister)
    {
        $authorize = true;
        if(checkUserPrivilege('payslip_filter_per_company',auth()->user()->id) != 'yes'){
            $authorize = auth()->user()->id == $payrollRegister->user_id ? true : false;
        }

        if(!$authorize){
            Alert::warning('Warning : Permission Denied!')->persistent('Dismiss');
            return redirect()->route('payslip.password.form',['payrollRegister' => $payrollRegister->id]);
        }

        if (!session('payslip_access_granted')) {
            Alert::warning('Warning : You must enter the correct password first!')->persistent('Dismiss');
            return redirect()->route('payslip.password.form',['payrollRegister' => $payrollRegister->id]);
        }

        $pdf = PDF::loadView('payroll_payslip.print', compact('payrollRegister'));
        // Removes a session variable to track access
        session()->forget('payslip_access_granted');
        return $pdf->stream('payroll_payslip.print');
    }    
}
