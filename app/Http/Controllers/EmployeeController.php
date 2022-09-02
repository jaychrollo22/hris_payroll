<?php

namespace App\Http\Controllers;
use App\Classification;
use App\Employee;
use App\Department;
use App\Schedule;
use App\Level;
use App\Bank;
use App\User;
use App\PersonnelEmployee;
use App\MaritalStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    //
    public function view ()
    {
        $classifications = Classification::get();

        $employees = Employee::with('department','payment_info','ScheduleData','immediate_sup_data','user_info')->get();
        $schedules = Schedule::get();
        $banks = Bank::get();
        $users = User::get();
        $levels = Level::get();
        $departments = Department::where('status',null)->get();
        $marital_statuses = MaritalStatus::get();
        return view('employees.view_employees',
        array(
            'header' => 'employees',
            'classifications' => $classifications,
            'employees' => $employees,
            'marital_statuses' => $marital_statuses,
            'departments' => $departments,
            'levels' => $levels,
            'users' => $users,
            'banks' => $banks,
            'schedules' => $schedules,
        ));
    }

    public function new(Request $request)
    {
        dd($request->all());
        $user = new User;
        $user->email = $request->work_email;
        $user->name = $request->first_name." ".$request->last_name;
        $user->status = "Active";
        $user->save();

        $employee = new Employee;
        $employee->employee_number = $request->biometric_code;
        $employee->employee_code = $this->generate_emp_code('Employee',"OBN",2022);
        $employee->user_id = $user->id;
        $employee->first_name = $request->first_name;
        $employee->first_name = $request->middle_name;
        $employee->last_name = $request->last_name;
        $employee->classification = $request->classification;
        $employee->department_id = $request->department;
        $employee->position = $request->position;
        $employee->nick_name = $request->nickname;
        $employee->level = $request->level;
        $employee->obanana_date_hired = $request->date_hired;
        $employee->birth_date = $request->birthdate;
        $employee->birth_place = $request->birthplace;
        $employee->marital_status = $request->marital_status;
        $employee->status = "Active";
        $employee->present_address = $request->present_address;
        $employee->permanent_address = ($request->permanent_address == '') ? $request->present_address : $request->permanent_address;
        $employee->personal_number = $request->personal_number;
        $employee->phil_number = $request->philhealth;
        $employee->sss_number = $request->sss;
        $employee->tax_number = $request->tin;
        $employee->hdmf_number = $request->pagibig;
        $employee->original_date_hired = $request->date_hired;
        $employee->personal_email = $request->personal_email;
        $employee->immediate_sup = $request->immediate_supervisor;
        $employee->schedule_id = $request->schedule;
        $employee->middle_initial = $request->middile_initial;
        $employee->name_suffix = $request->suffix;
        $employee->religion = $request->religion;
        $employee->save();
        
        
        
    }
    

    public function generate_emp_code($table,$code,$year)
    {
        // dd($table);
        $data = Employee::whereYear('original_date_hired',"=",$year)->orderBy('id','desc')->first();
        if($data == null)
        {
            $emp_code = $code."-".$year."-00001";
        }
        else
        {
            $code_data = explode("-",$data->employee_code);
            // dd($code_data);
            $code_final = intval($code_data[2])+1;
            $emp_code = $code."-".$year."-".str_pad($code_final,5, '0', STR_PAD_LEFT);
        }

        return $emp_code;
    }

    public function employees_biotime()
    {
        $employees = PersonnelEmployee::get();
    }
}
