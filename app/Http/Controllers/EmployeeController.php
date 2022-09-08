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
use App\IclockTransation;
use App\MaritalStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

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
        
        return view('employees.view_employees_biometrics',
        array(
            'header' => 'biometrics',
            'employees' => $employees,
        ));
    }

    public function employee_attendance(Request $request)
    {
        $attendance_controller = new AttendanceController;
        $employees = PersonnelEmployee::get();
        $from_date = $request->from;
        $to_date = $request->to;
        $date_range =  [];
        $attendances = [];
        $schedules = [];
        if($from_date != null)
        {
        $date_range =  $attendance_controller->dateRange( $from_date, $to_date);
        $attendances =  $attendance_controller->get_attendances($from_date,$to_date,$request->employee);
        $schedule_id = Employee::where('employee_number',$request->employee)->first();
        // dd($schedule_id);
        $schedules = ScheduleData::where('schedule_id',$schedule_id->schedule_id)->get();
        }
        if($schedule_id == null)
        {

        }
        else
        {

        }
        return view('employees.view_employees_biometrics',
        array(
            'header' => 'biometrics-attendances',
            'employees' => $employees,
        ));
    }

    public function newBio(Request $request)
    {
        // dd($request->all());

        $new_emp = new PersonnelEmployee;
        $new_emp->emp_code = $request->emp_code;
        $new_emp->first_name = $request->first_name;
        $new_emp->last_name = $request->last_name;
        $new_emp->status = 0;
        $new_emp->dev_privilege = 0;
        $new_emp->self_password = 'pbkdf2_sha256$36000$Gj09deAAUqW7$ih9vyun8PPwxvbG1+bhxB0TIQF+2IUfdhiDUf0AS0o4=';
        $new_emp->hire_date = date('Y-m-d');
        $new_emp->verify_mode = 0;
        $new_emp->is_admin = 0;
        $new_emp->app_role = 1;
        $new_emp->is_active = 1;
        $new_emp->department_id = 1;
        $new_emp->enable_payroll = 0;
        $new_emp->deleted = 0;
        $new_emp->save();

        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    }

    public function updatebiocode(Request $request)
    {
        // dd($request->all());

        $emp = PersonnelEmployee::where('emp_code',$request->emp_code)->first();
        $emp->first_name = $request->first_name;
        $emp->last_name = $request->last_name;
        $emp->save();

      

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    
}
