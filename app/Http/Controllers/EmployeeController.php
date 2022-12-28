<?php

namespace App\Http\Controllers;

use App\Classification;
use App\Employee;
use App\Department;
use App\Schedule;
use App\iclockterminal_mysql;
use App\iclocktransactions_mysql;
use App\Level;
use App\EmployeeCompany;
use App\Bank;
use App\User;
use App\Company;
use App\ScheduleData;
use App\PersonnelEmployee;
use App\IclockTransation;
use App\MaritalStatus;
use App\IclockTerminal;
use App\AttPunch;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeController extends Controller
{
    //
    public function view()
    {
        $classifications = Classification::get();

        $employees = Employee::with('department', 'payment_info', 'ScheduleData', 'immediate_sup_data', 'user_info', 'company')->get();
        $schedules = Schedule::get();
        $banks = Bank::get();
        $users = User::get();
        $levels = Level::get();
        $departments = Department::where('status', null)->get();
        $marital_statuses = MaritalStatus::get();
        $companies = Company::get();
        return view(
            'employees.view_employees',
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
                'companies' => $companies,
            )
        );
    }

    public function new(Request $request)
    {
        // dd($request->all());
        $company = Company::findOrfail($request->company);
        // dd($company);
        $user = new User;
        $user->email = $request->work_email;
        $user->name = $request->first_name . " " . $request->last_name;
        $user->status = "Active";
        $user->save();

        $employee = new Employee;
        $employee->employee_number = $request->biometric_code;
        $employee->employee_code = $this->generate_emp_code('Employee', $company->company_code, date('Y',strtotime($request->date_hired)), $company->id);
        $employee->user_id = $user->id;
        $employee->first_name = $request->first_name;
        $employee->middle_name = $request->middle_name;
        $employee->last_name = $request->last_name;
        $employee->classification = $request->classification;
        $employee->department_id = $request->department;
        $employee->company_id = $request->company;
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

        $employeeCompany = new EmployeeCompany;
        $employeeCompany->emp_code = $request->biometric_code;
        $employeeCompany->schedule_id = 1;
        $employeeCompany->company_id = $request->company;
        $employeeCompany->save();


        Alert::success('Successfully Registered')->persistent('Dismiss');
        return back();
    }


    public function generate_emp_code($table, $code, $year, $compId)
    {
        // dd($table);
        $data = Employee::whereYear('original_date_hired', "=", $year)->where('company_id', $compId)->orderBy('id', 'desc')->first();
        //  dd($data);
        if ($data == null) {
            $emp_code = $code . "-" . $year . "-00001";
        } else {
            $code_data = explode("-", $data->employee_code);
            //  dd($code_data);
            $code_final = intval($code_data[2]) + 1;
            $emp_code = $code . "-" . $year . "-" . str_pad($code_final, 5, '0', STR_PAD_LEFT);
        }

        return $emp_code;
    }

    public function employees_biotime()
    {
        $employees = PersonnelEmployee::get();

        return view(
            'employees.view_employees_biometrics',
            array(
                'header' => 'biometrics',
                'employees' => $employees,

            )
        );
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
        $emp_code = $request->employee;
        $schedule_id = null;
        $emp_data = [];
        if ($from_date != null) {
            $emp_data = PersonnelEmployee::with(['attendances' => function ($query) use ($from_date, $to_date) {
                $query->whereBetween('time_in', [$from_date." 00:00:01", $to_date." 23:59:59"])->orWhereBetween('time_out', [$from_date." 00:00:01", $to_date." 23:59:59"])
                ->orderBy('time_in','asc')->orderby('time_out','desc')->orderBy('id','asc');
            }])->whereIn('emp_code', $request->employee)->get();

            $date_range =  $attendance_controller->dateRange($from_date, $to_date);
            $schedules = ScheduleData::where('schedule_id', 1)->get();
        }
        
        return view(
            'attendances.employee_attendance',
            array(
                'header' => 'biometrics',
                'employees' => $employees,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'date_range' => $date_range,
                'attendances' => $attendances,
                'schedules' => $schedules,
                'emp_code' => $emp_code,
                'emp_data' => $emp_data,
            )
        );
    }
    public function perCompany(Request $request)
    {
        $companies = Company::whereHas('employee_company')->get();
        $attendance_controller = new AttendanceController;
        $from_date = $request->from;
        $to_date = $request->to;
        $date_range =  [];
        $schedules = [];
        $emp_data = [];
        $attendances = [];
        $employees = [];
        $coompany = $request->company_id;
        if ($from_date != null) {
            
            $company_employees = EmployeeCompany::where('company_id',$request->company)->pluck('emp_code')->toArray();
            $emp_data = PersonnelEmployee::with(['attendances' => function ($query) use ($from_date, $to_date) {
                $query->whereBetween('time_in', [$from_date." 00:00:01", $to_date." 23:59:59"])->orWhereBetween('time_out', [$from_date." 00:00:01", $to_date." 23:59:59"])
                ->orderBy('time_in','asc')->orderby('time_out','desc')->orderBy('id','asc');
            }])->whereIn('emp_code', $company_employees)->get();
            // dd($company_employees);
            $schedules = ScheduleData::where('schedule_id', 1)->get();
            $date_range =  $attendance_controller->dateRange($from_date, $to_date);
        }

        return view(
            'attendances.employee_company',
            array(
                'header' => 'biometrics',
                'from_date' => $from_date,
                'to_date' => $to_date,
                'date_range' => $date_range,
                'attendances' => $attendances,
                'schedules' => $schedules,
                'emp_data' => $emp_data,
                'companies' => $companies,
            )
        );
    }
    public function biologs_per_location(Request $request)
    {
        $terminals = IclockTerminal::get();
        $from_date = $request->from;
        $to_date = $request->to;
        $attendances = array();
        if ($from_date != null) {
            $attendances = IclockTransation::whereBetween('punch_time', [$from_date, $to_date])
                ->where('terminal_id', $request->location)
                ->whereIn('punch_state', array(0, 1))
                ->with('emp_data', 'location')
                ->orderBy('emp_code', 'desc')
                ->orderBy('punch_time', 'asc')
                ->get();
            // dd($attendances);
        }

        return view(
            'attendances.employee_attendance_location',
            array(
                'header' => 'biometrics',
                'from_date' => $from_date,
                'to_date' => $to_date,
                'terminals' => $terminals,
                'attendances' => $attendances,
            )
        );
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

        $emp = PersonnelEmployee::where('emp_code', $request->emp_code)->first();
        $emp->first_name = $request->first_name;
        $emp->last_name = $request->last_name;
        $emp->save();



        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function localbio(Request $request)
    {
        // dd($request->all());
        $from_date = $request->from;
        $to_date = $request->to;
        $attendances = array();
        if ($from_date != null) {
            $attendances = AttPunch::whereBetween('punch_time', [$from_date, date('Y-m-d', strtotime("+1 day", strtotime($to_date)))])
                ->where('terminal_id', 1004)
                ->with('personal_data')
                ->orderBy('employee_id', 'desc')
                ->orderBy('punch_time', 'asc')
                ->get();
        }

        return view(
            'attendances.pmi_local',
            array(
                'header' => 'biometrics',
                'from_date' => $from_date,
                'to_date' => $to_date,
                'attendances' => $attendances,
            )
        );
    }
    // Reports
    public function employee_report()
    {

        return view('reports.employee_report', array(
            'header' => 'reports',
        ));
    }

    public function sync(Request $request)
    {
        
        $terminals = iclockterminal_mysql::get();
        if($request->terminal)
        {
            // dd($request->all());
        $attendances = iclocktransactions_mysql::where('terminal_id','=',$request->terminal)->whereBetween('punch_time',[$request->from,$request->to])->get();
        foreach($attendances as $att)
            {
              if($att->punch_state == 0)
                {
                        $attend = Attendance::where('employee_code',$att->emp_code)->whereDate('time_in',date('Y-m-d', strtotime($att->punch_time)))->first();
                        if($attend == null)
                        {
                            $attendance = new Attendance;
                            $attendance->employee_code  = $att->emp_code;   
                            $attendance->time_in = date('Y-m-d H:i:s',strtotime($att->punch_time));
                            $attendance->device_in = $att->terminal_alias;
                            $attendance->save(); 
                        }
                    
                }
                else if($att->punch_state == 1)
                {
                    $time_in_after = date('Y-m-d H:i:s',strtotime($att->punch_time));
                    $time_in_before = date('Y-m-d H:i:s', strtotime ( '-20 hour' , strtotime ( $time_in_after ) )) ;
                    $update = [
                        'time_out' =>  date('Y-m-d H:i:s', strtotime($att->punch_time)),
                        'device_out' => $att->terminal_alias,
                        'last_id' =>$att->id,
                    ];

                    $attendance_in = Attendance::where('employee_code',$att->emp_code)
                    ->whereBetween('time_in',[$time_in_before,$time_in_after])->first();
                    Attendance::where('employee_code',$att->emp_code)
                    ->whereBetween('time_in',[$time_in_before,$time_in_after])
                    ->update($update);

                    if($attendance_in ==  null)
                    {
                        $attendance = new Attendance;
                        $attendance->employee_code  = $att->emp_code;   
                        $attendance->time_out = date('Y-m-d H:i:s', strtotime($att->punch_time));
                        $attendance->device_out = $att->terminal_alias;
                        $attendance->save(); 
                    }
                }
            }
        }
        return view('employees.sync', array(
            'header' => 'biometrics',
            'terminals' => $terminals,
        ));
    }
}
