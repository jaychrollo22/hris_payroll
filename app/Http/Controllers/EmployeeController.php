<?php

namespace App\Http\Controllers;
use Excel;
use App\Imports\EmployeesImport;
use App\Classification;
use App\Employee;
use App\EmployeeApprover;
use App\Department;
use App\Schedule;
use App\Attendance;
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


use App\Exports\EmployeesExport;

class EmployeeController extends Controller
{
    //
    public function view(Request $request)
    {
        $company = isset($request->company) ? $request->company : "";
        $department = isset($request->department) ? $request->department : "";
        $status = isset($request->status) ? $request->status : "Active";

        $classifications = Classification::get();

        $employees = Employee::with('department', 'payment_info', 'ScheduleData', 'immediate_sup_data', 'user_info', 'company')
                                ->when($company,function($q) use($company){
                                    $q->where('company_id',$company);
                                })
                                ->when($department,function($q) use($department){
                                    $q->where('department_id',$department);
                                })
                                ->when($status,function($q) use($status){
                                    $q->where('status',$status);
                                })
                                ->get();
       
        if($company){
            $department_companies = Employee::select('department_id')
                                                ->when($company,function($q) use($company){
                                                    $q->where('company_id',$company);
                                                })
                                            ->groupBy('department_id')
                                            ->get();
            $department_ids = [];
            if($department_companies){
                foreach($department_companies as $item){
                    array_push($department_ids,$item->department_id);
                }
            }
            $departments = Department::whereIn('id',$department_ids)->where('status','1')->orderBy('name')->get();
        }else{
            $departments = Department::where('status','1')->orderBy('name')->get();
        }
        
        $schedules = Schedule::get();
        $banks = Bank::get();
        $users = User::get();
        $levels = Level::get();
        $marital_statuses = MaritalStatus::get();
        $companies = Company::whereHas('employee_company')->orderBy('company_name','ASC')->get();

       
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
                'company' => $company,
                'department' => $department,
                'status' => $status,
            )
        );
    }

    public function export(Request $request) 
    {
        $company = isset($request->company) ? $request->company : "";
        $department = isset($request->department) ? $request->department : "";
        $company_info = Company::where('id',$company)->first();
        $company_name = $company_info ? $company_info->company_code : "";
        return Excel::download(new EmployeesExport($company,$department), 'Employees '. $company_name .' .xlsx');
    }

    public function new(Request $request)
    {

        $validate_employee = Employee::where('first_name',$request->first_name)
                                        ->where('last_name',$request->last_name)
                                        ->where('company_id',$request->company)
                                        ->first();

        if(empty($validate_employee)){

            $company = Company::findOrfail($request->company);
            // dd($company);
            $user = new User;
            $user->email = $request->work_email;
            $user->name = $request->first_name . " " . $request->last_name;
            $password = strtolower($request->first_name) . '.'. strtolower($request->last_name);
            $stripped_password = str_replace(' ', '', $password);
            $user->password = bcrypt($stripped_password);
            $user->status = "Active";
            $user->save();

            $employee_code = $this->generate_emp_code('Employee', $company->company_code, date('Y',strtotime($request->date_hired)), $company->id);
            $employee_number = $this->generate_biometric_code(date('Y',strtotime($request->date_hired)), $company->id ,$user->id);

            $employee = new Employee;
            $employee->employee_number = $employee_number;
            $employee->employee_code = $employee_code;
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

            if(isset($request->approver)){
                
                $level = 1;
                if(count($request->approver) > 0){
                    $approver = EmployeeApprover::where('user_id',$employee->user_id)->delete();
                    foreach($request->approver as  $approver)
                    {
                        $new_approver = new EmployeeApprover;
                        $new_approver->user_id = $employee->user_id;
                        $new_approver->approver_id = $approver['approver_id'];
                        $new_approver->level = $level;
                        $new_approver->as_final = isset($approver['as_final']) ? $approver['as_final'] : "";
                        $new_approver->save();
                        $level = $level+1;
                    }
                }
            }

            Alert::success('Successfully Registered')->persistent('Dismiss');
            return back();
        }else{
            Alert::warning('Warning : Employee Exist!')->persistent('Dismiss');
            return back();
        }
    }

    public function upload(Request $request){

        $path = $request->file('file')->getRealPath();
        $data = Excel::toArray(new EmployeesImport, $request->file('file'));
        // return $data = Excel::load($path)->get();

        if(count($data[0]) > 0)
        {
            $save_count = 0;
            $not_save = [];
            foreach($data[0] as $key => $value)
            {
                if($value['employee_number'] != null)
                {
                    $validate = Employee::where('employee_number',$value['employee_number'])->first();
                    if(empty($validate)){
                        $company = Company::findOrfail($value['company_id']);
                        $user_id = '';
                        if($value['company_email']){
                            $validate = User::where('email',$value['company_email'])->first();
                            if(empty($validate)){
                                $user = new User;
                                $user->email = $value['company_email'];
                                $user->name = $value['first_name'] . " " . $value['last_name'];
                                $password = strtolower($value['first_name']) . '.'. strtolower($value['last_name']);
                                $stripped_password = str_replace(' ', '', $password);
                                $user->password = bcrypt($stripped_password);
                                $user->status = "Active";
                                $user->save();

                                $user_id = $user->id;
                            }else{
                                $user_id = $validate->id;
                            }
                        }
                        $employee_code = $this->generate_emp_code('Employee', $company->company_code, date('Y',strtotime($value['original_date_hired'])), $company->id);
                        $employee = new Employee;
                        $employee->user_id = $user_id;
                        $employee->employee_number = $value['employee_number'];
                        $employee->employee_code =  $employee_code;
                        $employee->first_name = $value['first_name'];
                        $employee->last_name = $value['last_name'];
                        $employee->middle_name = $value['middle_name'];
                        $employee->name_suffix = isset($value['name_suffix']) ? $value['name_suffix'] : "";
                        $employee->classification = isset($value['classification']) ? $value['classification'] : "";
                        $employee->department_id = isset($value['department_id']) ? $value['department_id'] : "";
                        $employee->company_id = isset($value['company_id']) ? $value['company_id'] : "";
                        $employee->original_date_hired = isset($value['original_date_hired']) && !empty($value['original_date_hired']) ? date('Y-m-d',strtotime($value['original_date_hired'])) : null;

                        $employee->position = isset($value['position']) ? $value['position'] : "";
                        $employee->nick_name = isset($value['nick_name']) ? $value['nick_name'] : "";
                        $employee->level = $value['level'];
                        $employee->date_regularized = isset($value['date_regularized']) && !empty($value['date_regularized']) ? date('Y-m-d',strtotime($value['date_regularized'])) : null;
                        $employee->date_resigned = isset($value['date_resigned']) && !empty($value['date_resigned']) ? date('Y-m-d',strtotime($value['date_resigned'])) : null;
                        $employee->birth_date = isset($value['birth_date']) && !empty($value['birth_date']) ? date('Y-m-d',strtotime($value['birth_date'])) : null;
                        $employee->birth_place = isset($value['birth_place']) ? $value['birth_place'] : "";
                        $employee->gender = isset($value['gender']) ? $value['gender'] : "";
                        $employee->marital_status = isset($value['marital_status']) ? $value['marital_status'] : "";
                        $employee->permanent_address = isset($value['permanent_address']) ? $value['permanent_address'] : "";
                        $employee->present_address = isset($value['permanent_address']) ? $value['present_address'] : "";
                        $employee->personal_number = isset($value['personal_number']) ? $value['personal_number'] : "";
                        $employee->phil_number = isset($value['phil_number']) ? $value['phil_number'] : "";
                        $employee->sss_number = isset($value['phil_number']) ? $value['sss_number'] : "";
                        $employee->tax_number = isset($value['tax_number']) ? $value['tax_number'] : "";
                        $employee->hdmf_number = isset($value['hdmf_number']) ? $value['hdmf_number'] : "";
                        $employee->bank_name = isset($value['bank_name']) ? $value['bank_name'] : "";
                        $employee->bank_account_number = isset($value['bank_account_number']) ? $value['bank_account_number'] : "";
                        $employee->personal_email = isset($value['personal_email']) ? $value['personal_email'] : "";
                        $employee->area = isset($value['area']) ? $value['area'] : "";
                        $employee->religion = isset($value['religion']) ? $value['religion'] : "";
                        $employee->schedule_id = isset($value['schedule_id']) ? $value['schedule_id'] : "1";
                        $employee->status = "Active";
                        $employee->save();

                        $save_count++;

                    }else{
                        $check_if_exist = Employee::where('employee_number',$value['employee_number'])->first();

                        if($check_if_exist){
                            $check_if_exist->position = isset($value['position']) ? $value['position'] : "";
                            $check_if_exist->nick_name = isset($value['nick_name']) ? $value['nick_name'] : "";
                            $check_if_exist->level = $value['level'];
                            $check_if_exist->date_regularized = isset($value['date_regularized']) && !empty($value['date_regularized']) ? date('Y-m-d',strtotime($value['date_regularized'])) : null;
                            $check_if_exist->date_resigned = isset($value['date_resigned']) && !empty($value['date_resigned']) ? date('Y-m-d',strtotime($value['date_resigned'])) : null;
                            $check_if_exist->birth_date = isset($value['birth_date']) && !empty($value['birth_date']) ? date('Y-m-d',strtotime($value['birth_date'])) : null;
                            $check_if_exist->birth_place = isset($value['birth_place']) ? $value['birth_place'] : "";
                            $check_if_exist->gender = isset($value['gender']) ? $value['gender'] : "";
                            $check_if_exist->marital_status = isset($value['marital_status']) ? $value['marital_status'] : "";
                            $check_if_exist->permanent_address = isset($value['permanent_address']) ? $value['permanent_address'] : "";
                            $check_if_exist->present_address = isset($value['permanent_address']) ? $value['present_address'] : "";
                            $check_if_exist->personal_number = isset($value['personal_number']) ? $value['personal_number'] : "";
                            $check_if_exist->phil_number = isset($value['phil_number']) ? $value['phil_number'] : "";
                            $check_if_exist->sss_number = isset($value['phil_number']) ? $value['sss_number'] : "";
                            $check_if_exist->tax_number = isset($value['tax_number']) ? $value['tax_number'] : "";
                            $check_if_exist->hdmf_number = isset($value['hdmf_number']) ? $value['hdmf_number'] : "";
                            $check_if_exist->bank_name = isset($value['bank_name']) ? $value['bank_name'] : "";
                            $check_if_exist->bank_account_number = isset($value['bank_account_number']) ? $value['bank_account_number'] : "";
                            $check_if_exist->personal_email = isset($value['personal_email']) ? $value['personal_email'] : "";
                            $check_if_exist->area = isset($value['area']) ? $value['area'] : "";
                            $check_if_exist->religion = isset($value['religion']) ? $value['religion'] : "";
                            $check_if_exist->schedule_id = isset($value['schedule_id']) ? $value['schedule_id'] : "1";
                            $check_if_exist->status = "Active";
                            $check_if_exist->save();

                            $save_count++;
                        }
                    }
                    
                }else{

                    $validate_employee = Employee::where('first_name',$value['first_name'])
                                                    ->where('last_name',$value['last_name'])
                                                    ->where('company_id',$value['company_id'])
                                                    ->first();

                    if(empty($validate_employee)){
                        $company = Company::findOrfail($value['company_id']);
                        $user_id = '';
                        if($value['company_email']){
                            $validate = User::where('email',$value['company_email'])->first();
                            if(empty($validate)){
                                $user = new User;
                                $user->email = $value['company_email'];
                                $user->name = $value['first_name'] . " " . $value['last_name'];
                                $password = strtolower($value['first_name']) . '.'. strtolower($value['last_name']);
                                $stripped_password = str_replace(' ', '', $password);
                                $user->password = bcrypt($stripped_password);
                                $user->status = "Active";
                                $user->save();

                                $user_id = $user->id;
                            }else{
                                $user_id = $validate->id;
                            }
                        }
                        $employee_code = $this->generate_emp_code('Employee', $company->company_code, date('Y',strtotime($value['original_date_hired'])), $company->id);
                        $employee_number = $this->generate_biometric_code(date('Y',strtotime($value['original_date_hired'])), $company->id, $user_id);
                        $employee = new Employee;
                        $employee->user_id = $user_id;
                        $employee->employee_number = $employee_number;
                        $employee->employee_code =  $employee_code;
                        $employee->first_name = $value['first_name'];
                        $employee->last_name = $value['last_name'];
                        $employee->middle_name = $value['middle_name'];
                        $employee->name_suffix = isset($value['name_suffix']) ? $value['name_suffix'] : "";
                        $employee->classification = isset($value['classification']) ? $value['classification'] : "";
                        $employee->department_id = isset($value['department_id']) ? $value['department_id'] : "";
                        $employee->company_id = isset($value['company_id']) ? $value['company_id'] : "";
                        $employee->original_date_hired = isset($value['original_date_hired']) && $value['original_date_hired'] ? date('Y-m-d',strtotime($value['original_date_hired'])) : "";

                        $employee->position = isset($value['position']) ? $value['position'] : "";
                        $employee->nick_name = isset($value['nick_name']) ? $value['nick_name'] : "";
                        $employee->level = $value['level'];
                        $employee->date_regularized = isset($value['date_regularized']) && !empty($value['date_regularized']) ? date('Y-m-d',strtotime($value['date_regularized'])) : null;
                        $employee->date_resigned = isset($value['date_resigned']) && !empty($value['date_resigned']) ? date('Y-m-d',strtotime($value['date_resigned'])) : null;
                        $employee->birth_date = isset($value['birth_date']) && !empty($value['birth_date']) ? date('Y-m-d',strtotime($value['birth_date'])) : null;
                        $employee->birth_place = isset($value['birth_place']) ? $value['birth_place'] : "";
                        $employee->gender = isset($value['gender']) ? $value['gender'] : "";
                        $employee->marital_status = isset($value['marital_status']) ? $value['marital_status'] : "";
                        $employee->permanent_address = isset($value['permanent_address']) ? $value['permanent_address'] : "";
                        $employee->present_address = isset($value['permanent_address']) ? $value['present_address'] : "";
                        $employee->personal_number = isset($value['personal_number']) ? $value['personal_number'] : "";
                        $employee->phil_number = isset($value['phil_number']) ? $value['phil_number'] : "";
                        $employee->sss_number = isset($value['phil_number']) ? $value['sss_number'] : "";
                        $employee->tax_number = isset($value['tax_number']) ? $value['tax_number'] : "";
                        $employee->hdmf_number = isset($value['hdmf_number']) ? $value['hdmf_number'] : "";
                        $employee->bank_name = isset($value['bank_name']) ? $value['bank_name'] : "";
                        $employee->bank_account_number = isset($value['bank_account_number']) ? $value['bank_account_number'] : "";
                        $employee->personal_email = isset($value['personal_email']) ? $value['personal_email'] : "";
                        $employee->area = isset($value['area']) ? $value['area'] : "";
                        $employee->religion = isset($value['religion']) ? $value['religion'] : "";
                        $employee->schedule_id = isset($value['schedule_id']) ? $value['schedule_id'] : "1";
                        $employee->status = "Active";
                        $employee->save();

                        $save_count++;
                    }
                }
            }

            // return $not_save;

            Alert::success('Successfully Import Employees (' . $save_count. ')')->persistent('Dismiss');
            return back();
            
        }
    }

    public function employeeSettingsHR(User $user)
    {
        $classifications = Classification::get();

        $employees = Employee::with('department', 'payment_info', 'ScheduleData', 'immediate_sup_data', 'user_info', 'company')->get();
        $schedules = Schedule::get();
        $banks = Bank::get();
        $users = User::all();
        $levels = Level::get();
        $departments = Department::get();
        $marital_statuses = MaritalStatus::get();
        $companies = Company::get();
        $user = User::where('id',$user->id)->with('employee.department','employee.payment_info','employee.ScheduleData','employee.immediate_sup_data','approvers.approver_data','subbordinates')->first();

       

        return view('employees.employee_settings_hr',
        array(
            'header' => 'employees',
            'user' => $user,
            'classifications' => $classifications,
            'employees' => $employees,
            'marital_statuses' => $marital_statuses,
            'departments' => $departments,
            'levels' => $levels,
            'users' => $users,
            'banks' => $banks,
            'schedules' => $schedules,
            'companies' => $companies,
        ));
    
    }

    public function updateInfoHR(Request $request, $id){

        $employee = Employee::findOrFail($id);
        $employee->first_name = $request->first_name;
        $employee->middle_name = $request->middle_name;
        $employee->middle_initial = $request->middile_initial;
        $employee->last_name = $request->last_name;
        $employee->name_suffix = $request->suffix;
        $employee->nick_name = $request->nickname;
        $employee->marital_status = $request->marital_status;
        $employee->religion = $request->religion;
        $employee->gender = $request->gender;
        $employee->birth_date = $request->birthdate;
        $employee->birth_place = $request->birthplace;
        $employee->personal_email = $request->personal_email;
        $employee->personal_number = $request->personal_number;
        $employee->present_address = $request->present_address;
        $employee->permanent_address = ($request->permanent_address == '') ? $request->present_address : $request->permanent_address;
        $employee->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();

    }
    public function updateEmpInfoHR(Request $request, $id){

        // return  $request->all();
        $employee = Employee::findOrFail($id);
        $employee->employee_number = $request->employee_number;
        $employee->company_id = $request->company;
        $employee->position = $request->position;
        $employee->department_id = $request->department;
        $employee->classification = $request->classification;
        $employee->phil_number = $request->philhealth;
        $employee->level = $request->level;
        $employee->immediate_sup = $request->immediate_supervisor;
        $employee->sss_number = $request->sss;
        $employee->tax_number = $request->tin;
        $employee->hdmf_number = $request->pagibig;
        $employee->original_date_hired = $request->date_hired;
        $employee->personal_email = $request->personal_email;
        $employee->immediate_sup = $request->immediate_supervisor;
        $employee->schedule_id = $request->schedule;
        $employee->bank_name = $request->bank_name;
        $employee->bank_account_number = $request->bank_account_number;
        $employee->save();

        $approver = EmployeeApprover::where('user_id',$employee->user_id)->delete();
        if(isset($request->approver)){
            $level = 1;
            if(count($request->approver) > 0){
                foreach($request->approver as  $approver)
                {
                    $new_approver = new EmployeeApprover;
                    $new_approver->user_id = $employee->user_id;
                    $new_approver->approver_id = $approver['approver_id'];
                    $new_approver->level = $level;
                    $new_approver->as_final = isset($approver['as_final']) ? $approver['as_final'] : "";
                    $new_approver->save();
                    $level = $level+1;
                }
            }
        }
        
        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();

    }


    public function generate_emp_code($table, $code, $year, $compId)
    {
        // dd($table);
        $data = Employee::whereYear('original_date_hired', "=", $year)->where('company_id', $compId)->orderBy('id', 'desc')->first();
        //  dd($data);
        if (empty($data)) {
            $emp_code = $code . "-" . $year . "-00001";
        } else {
            $code_data = explode("-", $data->employee_code);
            //  dd($code_data);
            if(count($code_data) > 1){
                $code_final = intval($code_data[2]) + 1;
            }else{
                $code_final = "00001";
            }
            
            $emp_code = $code . "-" . $year . "-" . str_pad($code_final, 5, '0', STR_PAD_LEFT);
        }

        return $emp_code;
    }

    public function generate_biometric_code( $year, $compId, $user_id)
    {
       
        $comp_code = str_pad($compId, 2, '0', STR_PAD_LEFT);
        $user_id = str_pad($user_id, 5, '0', STR_PAD_LEFT);
        $emp_code = $comp_code . $year . $user_id;
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
            $emp_data = PersonnelEmployee::select('emp_code')->with(['employee','attendances' => function ($query) use ($from_date, $to_date) {
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
        $attendances = iclocktransactions_mysql::where('terminal_id','=',$request->terminal)->whereBetween('punch_time',[$request->from,$request->to])->orderBy('punch_time','asc')->get();
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
