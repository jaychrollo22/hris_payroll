<?php

namespace App\Http\Controllers;
use Excel;
use App\Imports\EmployeesImport;
use App\Classification;
use App\Employee;
use App\EmployeeContactPerson;
use App\EmployeeBeneficiary;
use App\EmployeeLeaveCredit;
use App\EmployeeApprover;
use App\EmployeeVessel;
use App\Department;
use App\Location;
use App\Project;
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
use App\HikAttLog;
use App\HikVisionAttendance;
use App\IclockTransation;
use App\MaritalStatus;
use App\IclockTerminal;
use App\AttPunch;
use App\UserAllowedOvertime;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\AttendanceLog;
use App\Exports\EmployeesExport;
use App\Exports\EmployeeHRExport;
use App\Exports\AttendancePerLocationExport;
use App\Exports\EmployeeAssociateExport;
use Barryvdh\DomPDF\Facade as PDF;


class EmployeeController extends Controller
{
    //
    public function view(Request $request)
    {

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $allowed_locations = getUserAllowedLocations(auth()->user()->id);
        $allowed_projects = getUserAllowedProjects(auth()->user()->id);

        $default_limit = 1000;
        $search = isset($request->search) ? $request->search : "";
        $company = isset($request->company) ? $request->company : "";
        $department = isset($request->department) ? $request->department : "";
        $status = isset($request->status) ? $request->status : "Active";
        $classification = isset($request->classification) ? $request->classification : "";
        $gender = isset($request->gender) ? $request->gender : "";

        $classifications = Classification::get();

        $employees_classification = Employee::select('classification', DB::raw('count(*) as total'))->with('classification_info')
                                                ->when($company,function($q) use($company){
                                                    $q->where('company_id',$company);
                                                })
                                                ->when($department,function($q) use($department){
                                                    $q->where('department_id',$department);
                                                })
                                                ->when($status,function($q) use($status){
                                                    $q->where('status',$status);
                                                })
                                                ->when($classification,function($q) use($classification){
                                                    if($classification == 'N/A'){
                                                        $q->whereNull('classification');
                                                    }else{
                                                        $q->where('classification',$classification);
                                                    }  
                                                })
                                                ->when($gender,function($q) use($gender){
                                                    if($gender == 'N/A'){
                                                        $q->whereNull('gender')->orWhere('gender','');
                                                    }else{
                                                        $q->where('gender',$gender);
                                                    }
                                                })
                                                ->whereIn('company_id',$allowed_companies)
                                                ->when($allowed_locations,function($q) use($allowed_locations){
                                                    $q->whereIn('location',$allowed_locations);
                                                })
                                                ->when($allowed_projects,function($q) use($allowed_projects){
                                                    $q->whereIn('project',$allowed_projects);
                                                })
                                                ->groupBy('classification')
                                                ->orderBy('classification','ASC')
                                                ->get();
        $employees_gender = Employee::select('gender', DB::raw('count(*) as total'))
                                                ->when($company,function($q) use($company){
                                                    $q->where('company_id',$company);
                                                })
                                                ->when($department,function($q) use($department){
                                                    $q->where('department_id',$department);
                                                })
                                                ->when($status,function($q) use($status){
                                                    $q->where('status',$status);
                                                })
                                                ->when($classification,function($q) use($classification){
                                                    if($classification == 'N/A'){
                                                        $q->whereNull('classification')->orWhere('classification','');
                                                    }else{
                                                        $q->where('classification',$classification);
                                                    }  
                                                })
                                                ->when($gender,function($q) use($gender){
                                                    if($gender == 'N/A'){
                                                        $q->whereNull('gender');
                                                    }else{
                                                        $q->where('gender',$gender);
                                                    }
                                                })
                                                ->whereIn('company_id',$allowed_companies)
                                                ->when($allowed_locations,function($q) use($allowed_locations){
                                                    $q->whereIn('location',$allowed_locations);
                                                })
                                                ->when($allowed_projects,function($q) use($allowed_projects){
                                                    $q->whereIn('project',$allowed_projects);
                                                })
                                                ->groupBy('gender')
                                                ->orderBy('gender','ASC')
                                                ->get();

        $employees = Employee::select('id','user_id','employee_number','first_name','last_name','department_id','company_id','immediate_sup','classification','status')
                                ->with('department', 'immediate_sup_data', 'user_info', 'company','classification_info')
                                ->when($search,function($q) use($search){
                                    $q->where(function($w) use($search){
                                        $w->where('first_name', 'like' , '%' .  $search . '%')->orWhere('last_name', 'like' , '%' .  $search . '%')
                                        ->orWhere('employee_number', 'like' , '%' .  $search . '%')
                                        ->orWhereRaw("CONCAT(`first_name`, ' ', `last_name`) LIKE ?", ["%{$search}%"])
                                        ->orWhereRaw("CONCAT(`last_name`, ' ', `first_name`) LIKE ?", ["%{$search}%"]);
                                    });
                                })
                                ->when(empty($company),function($q) use($default_limit){
                                    $q->limit($default_limit);
                                })
                                ->when($company,function($q) use($company){
                                    $q->where('company_id',$company);
                                })
                                ->when($department,function($q) use($department){
                                    $q->where('department_id',$department);
                                })
                                
                                ->when($classification,function($q) use($classification){
                                    if($classification == 'N/A'){
                                        $q->whereNull('classification');
                                    }else{
                                        $q->where('classification',$classification);
                                    }  
                                })
                                ->when($gender,function($q) use($gender){
                                    if($gender == 'N/A'){
                                        $q->whereNull('gender');
                                    }else{
                                        $q->where('gender',$gender);
                                    }
                                })     
                                ->when($allowed_locations,function($q) use($allowed_locations){
                                    $q->whereIn('location',$allowed_locations);
                                })
                                ->when($allowed_projects,function($q) use($allowed_projects){
                                    $q->whereIn('project',$allowed_projects);
                                })
                                ->whereIn('company_id',$allowed_companies)
                                ->where('status',$status)
                                ->get();

        $employees_active = Employee::select('id','user_id')     
                                ->when($allowed_locations,function($q) use($allowed_locations){
                                    $q->whereIn('location',$allowed_locations);
                                })
                                ->when($allowed_projects,function($q) use($allowed_projects){
                                    $q->whereIn('project',$allowed_projects);
                                })
                                ->whereIn('company_id',$allowed_companies)
                                ->where('status','Active')
                                ->count();
       
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
            $departments = Department::where('status','1')
                                        ->orderBy('name')
                                        ->get();
        }
        
        $schedules = Schedule::get();
        $banks = Bank::get();
        $users = User::get();
        $levels = Level::get();
        $marital_statuses = MaritalStatus::get();
        $locations = Location::orderBy('location','ASC')->get();
        $projects = Project::get();

        
        $companies = Company::whereIn('id',$allowed_companies)
                                    ->orderBy('company_name','ASC')
                                    ->get();

       
        return view(
            'employees.view_employees',
            array(
                'header' => 'employees',
                'classifications' => $classifications,
                'employees_classification' => $employees_classification,
                'employees_gender' => $employees_gender,
                'employees' => $employees,
                'marital_statuses' => $marital_statuses,
                'departments' => $departments,
                'locations' => $locations,
                'projects' => $projects,
                'levels' => $levels,
                'users' => $users,
                'banks' => $banks,
                'schedules' => $schedules,
                'companies' => $companies,
                'search' => $search,
                'company' => $company,
                'department' => $department,
                'status' => $status,
                'employees_active' => $employees_active,
            )
        );
    }

    public function export(Request $request) 
    {

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $allowed_companies = json_encode($allowed_companies);

        $allowed_locations = getUserAllowedLocations(auth()->user()->id);
        $allowed_locations = json_encode($allowed_locations);

        $allowed_projects = getUserAllowedProjects(auth()->user()->id);
        $allowed_projects = json_encode($allowed_projects);

        $company = isset($request->company) ? $request->company : "";
        $department = isset($request->department) ? $request->department : "";
        $status = isset($request->status) ? $request->status : "Active";
        $company_info = Company::where('id',$company)->first();
        $company_name = $company_info ? $company_info->company_code : "";

        $access_rate = checkUserPrivilege('employees_rate',auth()->user()->id);

        

        return Excel::download(new EmployeesExport($company,$department,$allowed_companies,$access_rate,$allowed_locations,$allowed_projects,$status), 'Master List '. $company_name .' .xlsx');
    }

    public function export_hr(Request $request) 
    {

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $allowed_companies = json_encode($allowed_companies);

        $allowed_locations = getUserAllowedLocations(auth()->user()->id);
        $allowed_locations = json_encode($allowed_locations);

        $allowed_projects = getUserAllowedProjects(auth()->user()->id);
        $allowed_projects = json_encode($allowed_projects);

        $company = isset($request->company) ? $request->company : "";
        $department = isset($request->department) ? $request->department : "";
        $status = isset($request->status) ? $request->status : "Active";
        $company_info = Company::where('id',$company)->first();
        $company_name = $company_info ? $company_info->company_code : "";

        return Excel::download(new EmployeeHRExport($company,$department,$allowed_companies,$allowed_locations,$allowed_projects,$status), 'Master List '. $company_name .' .xlsx');
    }

    public function export_employee_associates(Request $request) 
    {

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $allowed_companies = json_encode($allowed_companies);

        $allowed_locations = getUserAllowedLocations(auth()->user()->id);
        $allowed_locations = json_encode($allowed_locations);

        $allowed_projects = getUserAllowedProjects(auth()->user()->id);
        $allowed_projects = json_encode($allowed_projects);

        $company = isset($request->company) ? $request->company : "";
        $department = isset($request->department) ? $request->department : "";
        $status = isset($request->status) ? $request->status : "Active";
        $company_info = Company::where('id',$company)->first();
        $company_name = $company_info ? $company_info->company_code : "";

        $access_rate = checkUserPrivilege('employees_rate',auth()->user()->id);

        return Excel::download(new EmployeeAssociateExport($company,$department,$allowed_companies,$access_rate,$allowed_locations,$allowed_projects,$status), 'Employee Associates '. $company_name .' .xlsx');
    }

    public function new(Request $request)
    {

        $validate_employee = Employee::where('first_name',$request->first_name)
                                        ->where('last_name',$request->last_name)
                                        ->where('company_id',$request->company)
                                        ->first();

        $validate_user = User::where('email',$request->work_email)->first();

        if(empty($validate_employee) && empty($validate_user)){

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
            $employee_number = $request->biometric_code;

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
            $employee->location = $request->location;
            $employee->project = $request->project;
            $employee->position = $request->position;
            $employee->nick_name = $request->nickname;
            $employee->level = $request->level;
            $employee->gender = $request->gender;
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
            
            $employee->bank_name = $request->bank_name;
            $employee->bank_account_number = $request->bank_account_number;

            $employee->location = $request->location;
            $employee->work_description = $request->work_description;
            $employee->rate = isset($request->rate) ? Crypt::encryptString($request->rate) : "";
            $employee->tax_application = $request->tax_application;

            $employee->is_new_employee = 1;

            if($request->hasFile('file'))
            {
                $attachment = $request->file('file');
                $original_name = $attachment->getClientOriginalName();
                $name = time().'_'.$attachment->getClientOriginalName();
                $attachment->move(public_path().'/avatar/', $name);
                $file_name = '/avatar/'.$name;
                $employee->avatar = $file_name;
            }

            if($request->hasFile('signature'))
            {
                $attachment = $request->file('signature');
                $original_name = $attachment->getClientOriginalName();
                $name = time().'_'.$attachment->getClientOriginalName();
                $attachment->move(public_path().'/signature/', $name);
                $file_name = '/signature/'.$name;
                $employee->signature = $file_name;
            }

            $employee->save();

            $employeeCompany = new EmployeeCompany;
            $employeeCompany->emp_code = $request->biometric_code;
            $employeeCompany->schedule_id = 1;
            $employeeCompany->company_id = $request->company;
            
            $employeeCompany->save();

            if(isset($request->approver)){

                $count_approver = count($request->approver);
                $level = 1;
                if(count($request->approver) > 0){
                    foreach($request->approver as $k =>  $approver_item)
                    {
                        $new_approver = new EmployeeApprover;
                        
                        if($count_approver == 1 && $k == 0){
                            $new_approver->as_final = "on";
                        }
    
                        if($count_approver == 2 && $k == 1){
                            $new_approver->as_final = "on";
                        }
    
                        $new_approver->user_id = $employee->user_id;
                        $new_approver->approver_id = $approver_item['approver_id'];
                        $new_approver->level = $level;
                        
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

    public function reverseRate(Request $request){
        $path = $request->file('file')->getRealPath();
        $data = Excel::toArray(new EmployeesImport, $request->file('file'));

        if(count($data[0]) > 0)
        {
            $save_count = 0;
            $not_save = [];
            foreach($data[0] as $key => $value)
            {
                
                $validate = Employee::where('id',$value['id'])->first();

                if($validate){
                    $old_values = json_decode($value['old_values'],true);
                    if(isset($old_values['rate'])){
                        if(empty($validate->rate) && $old_values['rate']){
                            $employee = Employee::findOrFail($value['id']);
                            $employee->rate =  $old_values['rate'];
                            $employee->work_description =  isset($old_values['work_description']) ? $old_values['work_description'] : "";
                            $employee->save();
                            $save_count++;
                        }
                        
                    }
                   
                }
            }
            Alert::success('Successfully Import Employees (' . $save_count. ')')->persistent('Dismiss');
            return redirect('/employees');
        }
    }

    public function upload(Request $request){

        ini_set('memory_limit', '-1');
        
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
                                $employee->original_date_hired = isset($value['date_hired']) && !empty($value['date_hired']) ? date('Y-m-d',strtotime($value['date_hired'])) : null;

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
                                $employee->present_address = isset($value['present_address']) ? $value['present_address'] : "";
                                $employee->personal_number = isset($value['personal_number']) ? $value['personal_number'] : "";
                                $employee->phil_number = isset($value['philhealth_number']) ? $value['philhealth_number'] : "";
                                $employee->sss_number = isset($value['sss_number']) ? $value['sss_number'] : "";
                                $employee->tax_number = isset($value['tax_number']) ? $value['tax_number'] : "";
                                $employee->hdmf_number = isset($value['hdmf_number']) ? $value['hdmf_number'] : "";
                                $employee->bank_name = isset($value['bank_name']) ? $value['bank_name'] : "";
                                $employee->bank_account_number = isset($value['bank_account_number']) ? $value['bank_account_number'] : "";
                                $employee->personal_email = isset($value['personal_email']) ? $value['personal_email'] : "";
                                $employee->area = isset($value['area']) ? $value['area'] : "";
                                $employee->religion = isset($value['religion']) ? $value['religion'] : "";
                                $employee->schedule_id = isset($value['schedule_id']) ? $value['schedule_id'] : "1";

                                $employee->location = isset($value['location']) ? $value['location'] : "";
                                $employee->work_description = isset($value['work_description']) ? $value['work_description'] : "";
                                $employee->rate = isset($value['rate']) ? Crypt::encryptString($value['rate']) : "";
                                
                                $employee->status = "Active";
                                $employee->save();

                                //Leave Beginning Balance
                                if(isset($value['vl_balance'])){
                                    if($value['vl_balance']){
                                        $vl_leave_credit = EmployeeLeaveCredit::where('user_id',$user_id)
                                                                                ->where('leave_type','1') // VL
                                                                                ->first();
                                        if($vl_leave_credit){
                                            $vl_leave_credit->count = $value['vl_balance'];
                                            $vl_leave_credit->save();
                                        }else{
                                            $vl_leave_credit = new EmployeeLeaveCredit;
                                            $vl_leave_credit->leave_type = '1';
                                            $vl_leave_credit->user_id = $user_id;
                                            $vl_leave_credit->count = $value['vl_balance'];
                                            $vl_leave_credit->save();
                                        }
                                    }
                                }
                                if(isset($value['sl_balance'])){
                                    if($value['sl_balance']){
                                        $sl_leave_credit = EmployeeLeaveCredit::where('user_id',$user_id)
                                                                                ->where('leave_type','2') // SL
                                                                                ->first();
                                        if($sl_leave_credit){
                                            $sl_leave_credit->count = $value['sl_balance'];
                                            $sl_leave_credit->save();
                                        }else{
                                            $sl_leave_credit = new EmployeeLeaveCredit;
                                            $sl_leave_credit->leave_type = '2';
                                            $sl_leave_credit->user_id = $user_id;
                                            $sl_leave_credit->count = $value['sl_balance'];
                                            $sl_leave_credit->save();
                                        }
                                    }
                                }

                                if(isset($value['el_balance'])){
                                    if($value['el_balance']){
                                        $el_leave_credit = EmployeeLeaveCredit::where('user_id',$user_id)
                                                                                ->where('leave_type','6') // EL
                                                                                ->first();
                                        if($el_leave_credit){
                                            $el_leave_credit->count = $value['el_balance'];
                                            $el_leave_credit->save();
                                        }else{
                                            $el_leave_credit = new EmployeeLeaveCredit;
                                            $el_leave_credit->leave_type = '6';
                                            $el_leave_credit->user_id = $user_id;
                                            $el_leave_credit->count = $value['el_balance'];
                                            $el_leave_credit->save();
                                        }
                                    }
                                }
                                
                                if(isset($value['sil_balance'])){
                                    if($value['sil_balance']){
                                        $sil_leave_credit = EmployeeLeaveCredit::where('user_id',$user_id)
                                                                                ->where('leave_type','10') // SIL
                                                                                ->first();
                                        if($sil_leave_credit){
                                            $sil_leave_credit->count = $value['sil_balance'];
                                            $sil_leave_credit->save();
                                        }else{
                                            $sil_leave_credit = new EmployeeLeaveCredit;
                                            $sil_leave_credit->leave_type = '10';
                                            $sil_leave_credit->user_id = $user_id;
                                            $sil_leave_credit->count = $value['sil_balance'];
                                            $sil_leave_credit->save();
                                        }
                                    }
                                }

                                $save_count+=1;

                            }
                        }

                    }else{
                        $check_if_exist = Employee::where('employee_number',$value['employee_number'])->first();

                        if($check_if_exist){

                            if(isset($value['classification'])){
                                if($value['classification']){
                                    $check_if_exist->classification =  $value['classification'];
                                }
                            }
                            if(isset($value['department_id'])){
                                if($value['department_id']){
                                    $check_if_exist->department_id =  $value['department_id'];
                                }
                            }
                            if(isset($value['company_id'])){
                                if($value['company_id']){
                                    $check_if_exist->company_id =  $value['company_id'];
                                }
                            }
                            if(isset($value['original_date_hired'])){
                                if($value['original_date_hired']){
                                    $check_if_exist->original_date_hired =  date('Y-m-d',strtotime($value['date_hired']));
                                }
                            }
                            if(isset($value['position'])){
                                if($value['position']){
                                    $check_if_exist->position =  $value['position'];
                                }
                            }
                            if(isset($value['nick_name'])){
                                if($value['nick_name']){
                                    $check_if_exist->nick_name =  $value['nick_name'];
                                }
                            }
                            if(isset($value['level'])){
                                if($value['level']){
                                    $check_if_exist->level =  $value['level'];
                                }
                            }
                            if(isset($value['date_regularized'])){
                                if($value['date_regularized']){
                                    $check_if_exist->date_regularized =  date('Y-m-d',strtotime($value['date_regularized']));
                                }
                            }
                            if(isset($value['date_resigned'])){
                                if($value['date_resigned']){
                                    $check_if_exist->date_resigned =  date('Y-m-d',strtotime($value['date_resigned']));
                                }
                            }
                            if(isset($value['birth_date'])){
                                if($value['birth_date']){
                                    $check_if_exist->birth_date =  date('Y-m-d',strtotime($value['birth_date']));
                                }
                            }
                            if(isset($value['birth_place'])){
                                if($value['birth_place']){
                                    $check_if_exist->birth_place =  $value['birth_place'];
                                }
                            }
                            if(isset($value['gender'])){
                                if($value['gender']){
                                    $check_if_exist->gender =  $value['gender'];
                                }
                            }
                            if(isset($value['marital_status'])){
                                if($value['marital_status']){
                                    $check_if_exist->marital_status =  $value['marital_status'];
                                }
                            }
                            if(isset($value['permanent_address'])){
                                if($value['permanent_address']){
                                    $check_if_exist->permanent_address =  $value['permanent_address'];
                                }
                            }
                            if(isset($value['present_address'])){
                                if($value['present_address']){
                                    $check_if_exist->present_address =  $value['present_address'];
                                }
                            }
                            if(isset($value['personal_number'])){
                                if($value['personal_number']){
                                    $check_if_exist->personal_number =  $value['personal_number'];
                                }
                            }
                            if(isset($value['philhealth_number'])){
                                if($value['philhealth_number']){
                                    $check_if_exist->phil_number =  $value['philhealth_number'];
                                }
                            }
                            if(isset($value['sss_number'])){
                                if($value['sss_number']){
                                    $check_if_exist->sss_number =  $value['sss_number'];
                                }
                            }
                            if(isset($value['tax_number'])){
                                if($value['tax_number']){
                                    $check_if_exist->tax_number =  $value['tax_number'];
                                }
                            }
                            if(isset($value['hdmf_number'])){
                                if($value['hdmf_number']){
                                    $check_if_exist->hdmf_number =  $value['hdmf_number'];
                                }
                            }
                            if(isset($value['bank_name'])){
                                if($value['bank_name']){
                                    $check_if_exist->bank_name =  $value['bank_name'];
                                }
                            }
                            if(isset($value['bank_account_number'])){
                                if($value['bank_account_number']){
                                    $check_if_exist->bank_account_number =  $value['bank_account_number'];
                                }
                            }
                            if(isset($value['personal_email'])){
                                if($value['personal_email']){
                                    $check_if_exist->personal_email =  $value['personal_email'];
                                }
                            }
                            if(isset($value['area'])){
                                if($value['area']){
                                    $check_if_exist->area =  $value['area'];
                                }
                            }
                            if(isset($value['religion'])){
                                if($value['religion']){
                                    $check_if_exist->religion =  $value['religion'];
                                }
                            }
                            if(isset($value['schedule_id'])){
                                if($value['schedule_id']){
                                    $check_if_exist->schedule_id =  $value['schedule_id'];
                                }
                            }
                     
                            if(isset($value['location'])){
                                if($value['location']){
                                    $check_if_exist->location =  $value['location'];
                                }
                            }
                            if(isset($value['work_description'])){
                                if($value['work_description']){
                                    $check_if_exist->work_description =  $value['work_description'];
                                }
                            }
                            if(isset($value['rate'])){
                                if($value['rate']){
                                    $check_if_exist->rate =  Crypt::encryptString($value['rate']);
                                }
                            }
                    
                            $check_if_exist->status = "Active";
                            $check_if_exist->save();

                            //Leave Beginning Balance
                            if(isset($value['vl_balance'])){
                                if($value['vl_balance']){
                                    $vl_leave_credit = EmployeeLeaveCredit::where('user_id',$check_if_exist->user_id)
                                                                            ->where('leave_type','1') // VL
                                                                            ->first();
                                    if($vl_leave_credit){
                                        $vl_leave_credit->count = $value['vl_balance'];
                                        $vl_leave_credit->save();
                                    }else{
                                        $vl_leave_credit = new EmployeeLeaveCredit;
                                        $vl_leave_credit->leave_type = '1';
                                        $vl_leave_credit->user_id = $check_if_exist->user_id;
                                        $vl_leave_credit->count = $value['vl_balance'];
                                        $vl_leave_credit->save();
                                    }
                                }
                            }
                            if(isset($value['sl_balance'])){
                                if($value['sl_balance']){
                                    $sl_leave_credit = EmployeeLeaveCredit::where('user_id',$check_if_exist->user_id)
                                                                            ->where('leave_type','2') // SL
                                                                            ->first();
                                    if($sl_leave_credit){
                                        $sl_leave_credit->count = $value['sl_balance'];
                                        $sl_leave_credit->save();
                                    }else{
                                        $sl_leave_credit = new EmployeeLeaveCredit;
                                        $sl_leave_credit->leave_type = '2';
                                        $sl_leave_credit->user_id = $check_if_exist->user_id;
                                        $sl_leave_credit->count = $value['sl_balance'];
                                        $sl_leave_credit->save();
                                    }
                                }
                            }

                            if(isset($value['el_balance'])){
                                if($value['el_balance']){
                                    $el_leave_credit = EmployeeLeaveCredit::where('user_id',$check_if_exist->user_id)
                                                                            ->where('leave_type','6') // EL
                                                                            ->first();
                                    if($el_leave_credit){
                                        $el_leave_credit->count = $value['el_balance'];
                                        $el_leave_credit->save();
                                    }else{
                                        $el_leave_credit = new EmployeeLeaveCredit;
                                        $el_leave_credit->leave_type = '6';
                                        $el_leave_credit->user_id = $check_if_exist->user_id;
                                        $el_leave_credit->count = $value['el_balance'];
                                        $el_leave_credit->save();
                                    }
                                }
                            }
                            
                            if(isset($value['sil_balance'])){
                                if($value['sil_balance']){
                                    $sil_leave_credit = EmployeeLeaveCredit::where('user_id',$check_if_exist->user_id)
                                                                            ->where('leave_type','10') // SIL
                                                                            ->first();
                                    if($sil_leave_credit){
                                        $sil_leave_credit->count = $value['sil_balance'];
                                        $sil_leave_credit->save();
                                    }else{
                                        $sil_leave_credit = new EmployeeLeaveCredit;
                                        $sil_leave_credit->leave_type = '10';
                                        $sil_leave_credit->user_id = $check_if_exist->user_id;
                                        $sil_leave_credit->count = $value['sil_balance'];
                                        $sil_leave_credit->save();
                                    }
                                }
                            }

                            $save_count+=1;
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
                                $employee->phil_number = isset($value['philhealth_number']) ? $value['philhealth_number'] : "";
                                $employee->sss_number = isset($value['sss_number']) ? $value['sss_number'] : "";
                                $employee->tax_number = isset($value['tax_number']) ? $value['tax_number'] : "";
                                $employee->hdmf_number = isset($value['hdmf_number']) ? $value['hdmf_number'] : "";
                                $employee->bank_name = isset($value['bank_name']) ? $value['bank_name'] : "";
                                $employee->bank_account_number = isset($value['bank_account_number']) ? $value['bank_account_number'] : "";
                                $employee->personal_email = isset($value['personal_email']) ? $value['personal_email'] : "";
                                $employee->area = isset($value['area']) ? $value['area'] : "";
                                $employee->religion = isset($value['religion']) ? $value['religion'] : "";
                                $employee->schedule_id = isset($value['schedule_id']) ? $value['schedule_id'] : "1";

                                $employee->location = isset($value['location']) ? $value['location'] : "";
                                $employee->work_description = isset($value['work_description']) ? $value['work_description'] : "";
                                $employee->rate = isset($value['rate']) ? Crypt::encryptString($value['rate']) : "";

                                $employee->status = "Active";
                                $employee->save();

                                //Leave Beginning Balance
                                if(isset($value['vl_balance'])){
                                    if($value['vl_balance']){
                                        $vl_leave_credit = EmployeeLeaveCredit::where('user_id',$user_id)
                                                                                ->where('leave_type','1') // VL
                                                                                ->first();
                                        if($vl_leave_credit){
                                            $vl_leave_credit->count = $value['vl_balance'];
                                            $vl_leave_credit->save();
                                        }else{
                                            $vl_leave_credit = new EmployeeLeaveCredit;
                                            $vl_leave_credit->leave_type = '1';
                                            $vl_leave_credit->user_id = $user_id;
                                            $vl_leave_credit->count = $value['vl_balance'];
                                            $vl_leave_credit->save();
                                        }
                                    }
                                }
                                if(isset($value['sl_balance'])){
                                    if($value['sl_balance']){
                                        $sl_leave_credit = EmployeeLeaveCredit::where('user_id',$user_id)
                                                                                ->where('leave_type','2') // SL
                                                                                ->first();
                                        if($sl_leave_credit){
                                            $sl_leave_credit->count = $value['sl_balance'];
                                            $sl_leave_credit->save();
                                        }else{
                                            $sl_leave_credit = new EmployeeLeaveCredit;
                                            $sl_leave_credit->leave_type = '2';
                                            $sl_leave_credit->user_id = $user_id;
                                            $sl_leave_credit->count = $value['sl_balance'];
                                            $sl_leave_credit->save();
                                        }
                                    }
                                }

                                if(isset($value['el_balance'])){
                                    if($value['el_balance']){
                                        $el_leave_credit = EmployeeLeaveCredit::where('user_id',$user_id)
                                                                                ->where('leave_type','6') // EL
                                                                                ->first();
                                        if($el_leave_credit){
                                            $el_leave_credit->count = $value['el_balance'];
                                            $el_leave_credit->save();
                                        }else{
                                            $el_leave_credit = new EmployeeLeaveCredit;
                                            $el_leave_credit->leave_type = '6';
                                            $el_leave_credit->user_id = $check_if_exist->user_id;
                                            $el_leave_credit->count = $value['el_balance'];
                                            $el_leave_credit->save();
                                        }
                                    }
                                }
                                
                                if(isset($value['sil_balance'])){
                                    if($value['sil_balance']){
                                        $sil_leave_credit = EmployeeLeaveCredit::where('user_id',$user_id)
                                                                                ->where('leave_type','10') // SIL
                                                                                ->first();
                                        if($sil_leave_credit){
                                            $sil_leave_credit->count = $value['sil_balance'];
                                            $sil_leave_credit->save();
                                        }else{
                                            $sil_leave_credit = new EmployeeLeaveCredit;
                                            $sil_leave_credit->leave_type = '10';
                                            $sil_leave_credit->user_id = $check_if_exist->user_id;
                                            $sil_leave_credit->count = $value['sil_balance'];
                                            $sil_leave_credit->save();
                                        }
                                    }
                                }

                                $save_count+=1;

                            }
                        }
                    }
                }
            }

            Alert::success('Successfully Import Employees (' . $save_count. ')')->persistent('Dismiss');
            return redirect('/employees');
            
        }
    }

    public function employeeSettingsHR(User $user)
    {

        $user = User::where('id',$user->id)->with('allowed_overtime','employee.department','employee.payment_info','employee.contact_person','employee.beneficiaries','employee.employee_vessel','employee.classification_info','employee.level_info','employee.ScheduleData','employee.immediate_sup_data','approvers.approver_data','subbordinates')->first();

        $classifications = Classification::get();

        $employees = Employee::with('department', 'payment_info', 'ScheduleData', 'immediate_sup_data', 'user_info', 'company','classification_info','level_info')->get();
        
        
        $employee_approvers = Employee::where('status','Active')
                                        ->pluck('user_id')
                                        ->toArray();
        // $employee_approvers = Employee::whereHas('company',function($q) use($user){
        //                                         if($user->employee->company_id){
        //                                             $q->where('company_id',$user->employee->company_id);
        //                                         }
        //                                 })
        //                                 ->where('status','Active')
        //                                 ->pluck('user_id')
        //                                 ->toArray();
        if($user->employee->level >= 2){
            $users = User::all();
        }else{
            $users = User::whereIn('id',$employee_approvers)->get();
        }
        
        $schedules = Schedule::get();
        $banks = Bank::get();
       
        $levels = Level::get();
        $departments = Department::get();
        $locations = Location::orderBy('location','ASC')->get();
        $projects = Project::get();
        $marital_statuses = MaritalStatus::get();
        $companies = Company::get();

        $level_id = Level::where('id',$user->employee->level)
                            ->orWhere('name',$user->employee->level)
                            ->first();
        
        return view('employees.employee_settings_hr',
        array(
            'header' => 'employees',
            'user' => $user,
            'classifications' => $classifications,
            'employees' => $employees,
            'marital_statuses' => $marital_statuses,
            'departments' => $departments,
            'locations' => $locations,
            'projects' => $projects,
            'levels' => $levels,
            'users' => $users,
            'banks' => $banks,
            'schedules' => $schedules,
            'companies' => $companies,
            'level_id' => $level_id
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
        
        $employee = Employee::findOrFail($id);
        $employee->employee_number = $request->employee_number;
        $employee->company_id = $request->company;
        $employee->position = $request->position;
        $employee->department_id = $request->department;
        $employee->location = $request->location;
        $employee->project = $request->project;
        $employee->classification = $request->classification;
        $employee->phil_number = $request->philhealth;
        $employee->level = $request->level;
        $employee->immediate_sup = $request->immediate_supervisor;
        $employee->sss_number = $request->sss;
        $employee->tax_number = $request->tin;
        $employee->hdmf_number = $request->pagibig;
        $employee->original_date_hired = $request->date_hired;
        // $employee->personal_email = $request->personal_email;
        $employee->immediate_sup = $request->immediate_supervisor;
        $employee->schedule_id = $request->schedule;
        $employee->bank_name = $request->bank_name;
        $employee->bank_account_number = $request->bank_account_number;

        if(checkUserPrivilege('employees_rate',auth()->user()->id) == 'yes'){
            $employee->work_description = $request->work_description;
            $employee->rate = $request->rate ? Crypt::encryptString($request->rate) : "";
            $employee->tax_application = $request->tax_application;
        }
       
        $employee->status = $request->status;

        $employee->date_resigned = $request->status == 'Inactive' ? $request->date_resigned : null;

        $employee->save();

        $approver = EmployeeApprover::where('user_id',$employee->user_id)->delete();

        if(isset($request->approver)){

            $count_approver = count($request->approver);
            $level = 1;
            if(count($request->approver) > 0){
                foreach($request->approver as $k =>  $approver_item)
                {
                    $new_approver = new EmployeeApprover;
                    
                    if($count_approver == 1 && $k == 0){
                        $new_approver->as_final = "on";
                    }

                    if($count_approver == 2 && $k == 1){
                        $new_approver->as_final = "on";
                    }

                    $new_approver->user_id = $employee->user_id;
                    $new_approver->approver_id = $approver_item['approver_id'];
                    $new_approver->level = $level;
                    
                    $new_approver->save();
                    $level = $level+1;
                }
            }
        }

        //Employee Vessel
        if($request->classification == 4 && $request->vessel_name){
            $employee_vessel = EmployeeVessel::where('user_id', $employee->user_id)->first();

            if($employee_vessel){
                $employee_vessel->vessel_name = $request->vessel_name;
                $employee_vessel->save();
            }else{
                $new_employee_vessel = new EmployeeVessel;
                $new_employee_vessel->user_id = $employee->user_id;
                $new_employee_vessel->vessel_name = $request->vessel_name;
                $new_employee_vessel->save();
            }
        }

        if($request->level != 1){ // Validate if User not Rank and File
            $check_user_allowed_overtime = UserAllowedOvertime::where('user_id',$employee->user_id)->first();
            if(empty($check_user_allowed_overtime)){
                $new_user_allowed_overtime = new UserAllowedOvertime;
                $new_user_allowed_overtime->user_id = $employee->user_id;
                $new_user_allowed_overtime->allowed_overtime = $request->allowed_overtime;
                $new_user_allowed_overtime->save();
            }else{
                $check_user_allowed_overtime->allowed_overtime = $request->allowed_overtime;
                $check_user_allowed_overtime->save();
            }
        }else{
            $check_user_allowed_overtime = UserAllowedOvertime::where('user_id',$employee->user_id)->first();
            if($check_user_allowed_overtime){
                $check_user_allowed_overtime->allowed_overtime = null;
                $check_user_allowed_overtime->save();
            }
        }
        
        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();

    }

    public function updateContactInfoHR(Request $request, $id){

        $employee = Employee::findOrFail($id);

        if($employee){
            $employee_contact_person = EmployeeContactPerson::where('user_id',$employee->user_id)->first();

            if(empty($employee_contact_person)){
                $new_contact_person = new EmployeeContactPerson;
                $new_contact_person->user_id = $employee->user_id;
                $new_contact_person->name = $request->name;
                $new_contact_person->contact_number = $request->contact_number;
                $new_contact_person->relation = $request->relation;
                $new_contact_person->save();
            }else{
                $employee_contact_person->name = $request->name;
                $employee_contact_person->contact_number = $request->contact_number;
                $employee_contact_person->relation = $request->relation;
                $employee_contact_person->save();
            }
        }

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function updateBeneficiariesHR(Request $request, $id){


        $beneficiaries = $request->beneficiaries ? json_decode($request->beneficiaries) : "";
        $deleted_beneficiaries = $request->deleted_beneficiaries ? json_decode($request->deleted_beneficiaries) : "";

        if($deleted_beneficiaries){
            foreach($deleted_beneficiaries as $item){
                EmployeeBeneficiary::where('id',$item->id)->delete(); //Delete Beneficiary
            }
        }

        if($beneficiaries){

            $employee = Employee::findOrFail($id);
            
            if($employee){
                foreach($beneficiaries as $item){
                    if($item->id){
                        $employee_beneficiary = EmployeeBeneficiary::where('id',$item->id)->first();

                        if($employee_beneficiary){
                            $employee_beneficiary->first_name = $item->first_name;
                            $employee_beneficiary->middle_name = $item->first_name;
                            $employee_beneficiary->last_name = $item->last_name;
                            $employee_beneficiary->gender = $item->gender;
                            $employee_beneficiary->bdate = $item->bdate;
                            $employee_beneficiary->relation = $item->relation;
                            $employee_beneficiary->save();
                        }
                    }else{
                        $new = new EmployeeBeneficiary;
                        $new->user_id = $employee->user_id;
                        $new->first_name = $item->first_name;
                        $new->middle_name = $item->first_name;
                        $new->last_name = $item->last_name;
                        $new->gender = $item->gender;
                        $new->bdate = $item->bdate;
                        $new->relation = $item->relation;
                        $new->save();
                    }
                }
            }
        }
    }

    public function uploadAvatarHr(Request $request,$id)
    {
        $employee = Employee::where('user_id',$id)->first();
        if($request->hasFile('file'))
        {
            $attachment = $request->file('file');
            $name = $employee->user_id . '.png';
            $attachment->move(public_path().'/avatar/', $name);
            $file_name = '/avatar/'.$name;
            $employee->avatar = $file_name;
            $employee->save();
            Alert::success('Successfully avatar uploaded.')->persistent('Dismiss');
            return back();
            
        }
    }

    public function uploadSignatureHr(Request $request,$id)
    {
        $employee = Employee::where('user_id',$id)->first();
        if($request->hasFile('signature'))
        {
            $attachment = $request->file('signature');
            $name = $employee->user_id . '.png';
            $attachment->move(public_path().'/signature/', $name);
            $file_name = '/signature/'.$name;
            $employee->signature = $file_name;
            $employee->save();
            Alert::success('Successfully signature uploaded.')->persistent('Dismiss');
            return back();
            
        }
    }

    public function getBeneficiariesHR($id){
        return $employee_beneficiary = EmployeeBeneficiary::where('user_id',$id)->get();
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
        $user_id = str_pad($user_id, 4, '0', STR_PAD_LEFT);
        $emp_code = $comp_code . substr($year, -2) . $user_id;
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
        ini_set('memory_limit', '-1');
    
        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $allowed_locations = getUserAllowedLocations(auth()->user()->id);
        $allowed_projects = getUserAllowedProjects(auth()->user()->id);

        $attendance_controller = new AttendanceController;
        $employees = Employee::select('id','user_id','employee_number','first_name','last_name')->where('status','Active')
                                ->whereIn('company_id', $allowed_companies)
                                ->when($allowed_locations,function($q) use($allowed_locations){
                                    $q->whereIn('location',$allowed_locations);
                                })
                                ->when($allowed_projects,function($q) use($allowed_projects){
                                    $q->whereIn('project',$allowed_projects);
                                })
                                ->get();
        $from_date = $request->from;
        $to_date = $request->to;
        $date_range =  [];
        $attendances = [];
        $schedules = [];
        $emp_code = $request->employee;
        $schedule_id = null;
        $emp_data = [];

        $company = isset($request->company) ? $request->company : "";

        if ($from_date != null) {
            
            

            $emp_data = Employee::select('id','user_id','employee_number','first_name','last_name','schedule_id')
                                    ->with(['schedule_info','attendances' => function ($query) use ($from_date, $to_date) {
                                            $query->whereBetween('time_in', [$from_date." 00:00:01", $to_date." 23:59:59"])
                                                    ->orWhereBetween('time_out', [$from_date." 00:00:01", $to_date." 23:59:59"])
                                                    ->orderBy('time_in','asc')
                                                    ->orderby('time_out','desc')
                                                    ->orderBy('id','asc');
                                    }])
                                    ->whereIn('company_id', $allowed_companies)
                                    ->when($emp_code,function($q) use($emp_code){
                                        $q->whereIn('employee_number', $emp_code);
                                    })
                                    ->when($company,function($q) use($company){
                                        $q->where('company_id', $company);
                                    })
                                    ->when($allowed_locations,function($q) use($allowed_locations){
                                        $q->whereIn('location',$allowed_locations);
                                    })
                                    ->when($allowed_projects,function($q) use($allowed_projects){
                                        $q->whereIn('project',$allowed_projects);
                                    })
                                    ->where('status','Active')
                                    ->get();

            $date_range =  $attendance_controller->dateRange($from_date, $to_date);
           
        }
        $schedules = ScheduleData::all();
        
        $companies = Company::whereHas('employee_has_company')
                                ->whereIn('id',$allowed_companies)
                                ->get();

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
                'companies' => $companies,
                'company' => $company,
            )
        );
    }

    public function employee_attendance_report(Request $request)
    {
        ini_set('memory_limit', '-1');
    
        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $allowed_locations = getUserAllowedLocations(auth()->user()->id);
        $allowed_projects = getUserAllowedProjects(auth()->user()->id);

        $attendance_controller = new AttendanceController;
        $employees = Employee::select('id','user_id','employee_number','first_name','last_name')->where('status','Active')
                                ->whereIn('company_id', $allowed_companies)
                                ->when($allowed_locations,function($q) use($allowed_locations){
                                    $q->whereIn('location',$allowed_locations);
                                })
                                ->when($allowed_projects,function($q) use($allowed_projects){
                                    $q->whereIn('project',$allowed_projects);
                                })
                                ->get();
        $from_date = $request->from;
        $to_date = $request->to;
        $date_range =  [];
        $attendances = [];
        $schedules = [];
        $emp_code = $request->employee;
        $schedule_id = null;
        $emp_data = [];

        $company = isset($request->company) ? $request->company : "";

        if ($from_date != null) {
            
            

            $emp_data = Employee::select('id','user_id','employee_number','first_name','last_name','schedule_id')
                                    ->with(['schedule_info','attendances' => function ($query) use ($from_date, $to_date) {
                                            $query->whereBetween('time_in', [$from_date." 00:00:01", $to_date." 23:59:59"])
                                                    ->orWhereBetween('time_out', [$from_date." 00:00:01", $to_date." 23:59:59"])
                                                    ->orderBy('time_in','asc')
                                                    ->orderby('time_out','desc')
                                                    ->orderBy('id','asc');
                                    }])
                                    ->whereIn('company_id', $allowed_companies)
                                    ->when($emp_code,function($q) use($emp_code){
                                        $q->whereIn('employee_number', $emp_code);
                                    })
                                    ->when($company,function($q) use($company){
                                        $q->where('company_id', $company);
                                    })
                                    ->when($allowed_locations,function($q) use($allowed_locations){
                                        $q->whereIn('location',$allowed_locations);
                                    })
                                    ->when($allowed_projects,function($q) use($allowed_projects){
                                        $q->whereIn('project',$allowed_projects);
                                    })
                                    ->where('status','Active')
                                    ->get();

            $date_range =  $attendance_controller->dateRange($from_date, $to_date);
           
        }
        $schedules = ScheduleData::all();
        
        $companies = Company::whereHas('employee_has_company')
                                ->whereIn('id',$allowed_companies)
                                ->get();

        return view(
            'attendances.employee_attendance_report',
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
                'companies' => $companies,
                'company' => $company,
            )
        );
    }


    public function perCompany(Request $request)
    {
        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $allowed_locations = getUserAllowedLocations(auth()->user()->id);
        $allowed_projects = getUserAllowedProjects(auth()->user()->id);

        $companies = Company::whereHas('employee_has_company')
                                ->whereIn('id',$allowed_companies)
                                ->get();


        $department_companies = Employee::where('company_id',$allowed_companies)
                                        ->groupBy('department_id')
                                        ->pluck('department_id')
                                        ->toArray();

        $departments = Department::whereIn('id',$department_companies)->where('status','1')
                                        ->orderBy('name')
                                        ->get();

        $location_companies = Employee::where('company_id',$allowed_companies)
                                        ->groupBy('location')
                                        ->pluck('location')
                                        ->toArray();

        $locations = Location::whereIn('location',$location_companies)
                                        ->orderBy('location')
                                        ->get();


        $attendance_controller = new AttendanceController;
        
        $company = isset($request->company) ? $request->company : "";
        $department = isset($request->department) ? $request->department : "";
        $location = isset($request->location) ? $request->location : "";

        $from_date = $request->from;
        $to_date = $request->to;
        $date_range =  [];
        $schedules = [];
        $emp_data = [];
        $attendances = [];
        $employees = [];
        
        if ($from_date != null) {
            $emp_data = Employee::select('employee_number','user_id','first_name','last_name','location','schedule_id','employee_code')
                                ->with(['attendances' => function ($query) use ($from_date, $to_date) {
                                    $query->whereBetween('time_in', [$from_date." 00:00:01", $to_date." 23:59:59"])
                                    ->orWhereBetween('time_out', [$from_date." 00:00:01", $to_date." 23:59:59"])
                                    ->orderBy('time_in','asc')
                                    ->orderby('time_out','desc')
                                    ->orderBy('id','asc');
                                }])
                                ->with(['approved_leaves' => function ($query) use ($from_date, $to_date) {
                                    $query->whereBetween('date_from', [$from_date, $to_date])
                                    ->where('status','Approved')
                                    ->orderBy('id','asc');
                                },'approved_leaves.leave'])
                                ->with(['approved_wfhs' => function ($query) use ($from_date, $to_date) {
                                    $query->whereBetween('applied_date', [$from_date, $to_date])
                                    ->where('status','Approved')
                                    ->orderBy('id','asc');
                                }])
                                ->with(['approved_obs' => function ($query) use ($from_date, $to_date) {
                                    $query->whereBetween('applied_date', [$from_date, $to_date])
                                    ->where('status','Approved')
                                    ->orderBy('id','asc');
                                }])
                                ->with(['approved_dtrs' => function ($query) use ($from_date, $to_date) {
                                    $query->whereBetween('dtr_date', [$from_date, $to_date])
                                    ->where('status','Approved')
                                    ->orderBy('id','asc');
                                }])
                                ->where('company_id', $company)
                                ->when($allowed_locations,function($q) use($allowed_locations){
                                    $q->whereIn('location',$allowed_locations);
                                })
                                ->when($allowed_projects,function($q) use($allowed_projects){
                                    $q->whereIn('project',$allowed_projects);
                                });
            if($department){
                $emp_data = $emp_data->where('department_id', $department);
            }
            if($location){
                $emp_data = $emp_data->where('location', $location);
            }

            $emp_data =  $emp_data->where('status','Active')->get();
            
            $date_range =  $attendance_controller->dateRange($from_date, $to_date);
        }
        $schedules = ScheduleData::all();

        return view(
            'attendances.employee_company',
            array(
                'header' => 'biometrics',
                'company' => $company,
                'department' => $department,
                'location' => $location,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'date_range' => $date_range,
                'attendances' => $attendances,
                'schedules' => $schedules,
                'emp_data' => $emp_data,
                'companies' => $companies,
                'departments' => $departments,
                'locations' => $locations,
            )
        );
    }
    public function biologs_per_location(Request $request)
    {
        $from_date = $request->from;
        $to_date = $request->to;
        
        $locations = AttendanceLog::groupBy('location')->get(['location']);
        $attendances = AttendanceLog::whereBetween('date',[$from_date,$to_date])->where('location',$request->location)->get();
        return view(
            'attendances.employee_attendance_location',
            array(
                'header' => 'biometrics',
                'locations' => $locations,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'attendances' => $attendances,
                'loc' => $request->location,
            )
        );
        
    }

    public function biologs_per_location_export(Request $request){

        $location = isset($request->location) ? $request->location : "";
        $from = isset($request->from) ? $request->from : "";
        $to =  isset($request->to) ? $request->to : "";
        $terminal = IclockTerminal::where('id',$location)->first();
        return Excel::download(new AttendancePerLocationExport($location,$from,$to), $terminal->alias . ' ' . $from . ' to ' . $to . ' Attendance Per Location Export.xlsx');
    }

    public function biologs_per_location_hik(Request $request)
    {

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $allowed_locations = getUserAllowedLocations(auth()->user()->id);
        $allowed_projects = getUserAllowedProjects(auth()->user()->id);

        $employee_names = Employee::select('id','employee_number','first_name','last_name')
                                                ->whereIn('company_id', $allowed_companies)
                                                ->when($allowed_locations,function($q) use($allowed_locations){
                                                    $q->whereIn('location',$allowed_locations);
                                                })
                                                ->when($allowed_projects,function($q) use($allowed_projects){
                                                    $q->whereIn('project',$allowed_projects);
                                                })
                                                ->where('status','Active')
                                                ->get();

        $terminals = HikAttLog::select('deviceName')->groupBy('deviceName')
                                    ->orderBy('deviceName' , 'ASC')
                                    ->get();

        $location = $request->location;
        $from_date = $request->from;
        $to_date = $request->to;
        $attendances = array();
        if ($from_date != null) {
            $attendances = HikAttLog::whereBetween('authDateTime', [$from_date." 00:00:01", $to_date." 23:59:59"])
                                ->where('deviceName', $request->location)
                                ->orderBy('employeeID', 'desc')
                                ->orderBy('authDateTime', 'asc')
                                ->get();
        }

        return view(
            'attendances.employee_attendance_location_hik',
            array(
                'header' => 'biometrics',
                'location' => $location,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'terminals' => $terminals,
                'attendances' => $attendances,
                'employee_names' => $employee_names,
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

        Alert::success('Successfully Stored')->persistent('Dismiss');
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
        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $allowed_locations = getUserAllowedLocations(auth()->user()->id);
        $allowed_projects = getUserAllowedProjects(auth()->user()->id);

        $terminals = iclockterminal_mysql::get();
        // $terminals_hik = HikVisionAttendance::select('deviceName')->groupBy('deviceName')
        //                             ->orderBy('deviceName' , 'ASC')
        //                             ->get();
        $terminals_hik = HikVisionAttendance::select('device')->groupBy('device')
                                    ->orderBy('device' , 'ASC')
                                    ->get();

        if($request->terminal)
        {

            $from = $request->from;
            $to = $request->to;

            $employee_numbers = Employee::whereIn('company_id', $allowed_companies)
                                                ->when($allowed_locations,function($q) use($allowed_locations){
                                                    $q->whereIn('location',$allowed_locations);
                                                })
                                                ->when($allowed_projects,function($q) use($allowed_projects){
                                                    $q->whereIn('project',$allowed_projects);
                                                })
                                                ->where('status','Active')
                                                ->pluck('employee_number')
                                                ->toArray();

            $attendances = iclocktransactions_mysql::whereIn('emp_code',$employee_numbers)
                                                    ->where('terminal_id','=',$request->terminal)
                                                    ->whereBetween('punch_time',[$from." 00:00:01", $to." 23:59:59"])
                                                    ->orderBy('punch_time','asc')
                                                    ->get();
            foreach($attendances as $att)
            {
                if($att->punch_state == 0) // Timein
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
                else if($att->punch_state == 1 || $att->punch_state == 5) // Timeout
                {
                    
                    $time_in_after = date('Y-m-d H:i:s',strtotime($att->punch_time));

                    $time_in_before = date('Y-m-d H:i:s', strtotime ( '-23 hour' , strtotime ( $time_in_after ) ));
                    
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
        
        $employees = Employee::where('status','Active')
                                ->whereIn('company_id', $allowed_companies)
                                ->get();

        return view('employees.sync', array(
            'header' => 'biometrics',
            'terminals' => $terminals,
            'terminals_hik' => $terminals_hik,
            'employees' => $employees,
        ));
    }

    public function sync_per_employee(Request $request)
    {
        $from = $request->from_biotime;
        $to = $request->to_biotime;
        $employee_code = $request->employee;

        $attendances = iclocktransactions_mysql::whereIn('emp_code',$employee_code)->whereBetween('punch_time',[$from." 00:00:01", $to." 23:59:59"])->orderBy('punch_time','asc')->get();
        
        $count = 0;
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
                        $count++;
                    }
                
            }
            else if($att->punch_state == 1 || $att->punch_state == 5)
            {
                $time_in_after = date('Y-m-d H:i:s',strtotime($att->punch_time));
                $time_in_before = date('Y-m-d H:i:s', strtotime ( '-22 hour' , strtotime ( $time_in_after ) )) ;
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

                $count++;
            }
        }
        // Alert::warning('Sync count : ' . $count)->persistent('Success');
        return back();
    }

    public function sync_hik(Request $request)
    {

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $allowed_locations = getUserAllowedLocations(auth()->user()->id);
        $allowed_projects = getUserAllowedProjects(auth()->user()->id);

        $from = $request->from_hik;
        $to = $request->to_hik;
        $terminal = $request->terminal_hik;

        $attendances = HikAttLog::where('deviceName',$terminal)
                                ->whereBetween('authDate',[$from,$to])
                                ->orderBy('authDate','asc')
                                ->orderBy('direction','asc')
                                ->get();
        
        $count = 0;
        if(count($attendances) > 0){
            foreach($attendances as $att)
            {
                $direction = str_replace(' ', '', $att->direction);

                if($direction == 'In' || $direction == 'IN')
                {
                    $attend = Attendance::where('employee_code',$att->emp_code)->whereDate('time_in',date('Y-m-d', strtotime($att->authDate)))->first();
                    if($attend == null)
                    {
                        $attendance = new Attendance;
                        $attendance->employee_code  = $att->employeeID;   
                        $attendance->time_in = date('Y-m-d H:i:s',strtotime($att->authDateTime));
                        $attendance->device_in = $att->deviceName;
                        $attendance->save();
                        $count++; 
                    }
                }
                else if($direction == 'Out' || $direction == 'OUT' )
                {
                    $time_in_after = date('Y-m-d H:i:s',strtotime($att->authDateTime));
                    $time_in_before = date('Y-m-d H:i:s', strtotime ( '-22 hour' , strtotime ( $time_in_after ) )) ;
                    $update = [
                        'time_out' =>  date('Y-m-d H:i:s', strtotime($att->authDateTime)),
                        'device_out' => $att->deviceName,
                        // 'last_id' =>$att->id,
                    ];
                    // return $time_in_after . ' - ' .$time_in_before;
                    $attendance_in = Attendance::where('employee_code',$att->employeeID)
                    ->whereBetween('time_in',[$time_in_before,$time_in_after])->first();

                    Attendance::where('employee_code',$att->employeeID)
                    ->whereBetween('time_in',[$time_in_before,$time_in_after])
                    ->update($update);

                    if($attendance_in ==  null)
                    {
                        $attendance = new Attendance;
                        $attendance->employee_code  = $att->employeeID;   
                        $attendance->time_out = date('Y-m-d H:i:s', strtotime($att->authDateTime));
                        $attendance->device_out = $att->deviceName;
                        $attendance->save(); 
                    }

                    $count++;
                }
            }
        }

        return back();
    }

    public function sync_hik_with_upload(Request $request)
    {

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $allowed_locations = getUserAllowedLocations(auth()->user()->id);
        $allowed_projects = getUserAllowedProjects(auth()->user()->id);

        $from = $request->from_hik;
        $to = $request->to_hik;
        $terminal = $request->terminal_hik;

        $attendances = HikVisionAttendance::where('device',$terminal)
                                ->whereBetween('attendance_date',[$from,$to])
                                ->orderBy('attendance_date','asc')
                                ->orderBy('direction','asc')
                                ->get();
        
        $count = 0;
        if(count($attendances) > 0){
            foreach($attendances as $att)
            {
                $direction = str_replace(' ', '', $att->direction);

                if($direction == 'In' || $direction == 'IN')
                {
                    $attend = Attendance::where('employee_code',$att->employee_code)->whereDate('time_in',date('Y-m-d', strtotime($att->attendance_date)))->first();
                    if($attend == null)
                    {
                        $attendance = new Attendance;
                        $attendance->employee_code  = $att->employee_code;   
                        $attendance->time_in = date('Y-m-d H:i:s',strtotime($att->attendance_date));
                        $attendance->device_in = $att->device;
                        $attendance->save();
                        $count++; 
                    }
                }
                else if($direction == 'Out' || $direction == 'OUT' )
                {
                    $time_in_after = date('Y-m-d H:i:s',strtotime($att->attendance_date));
                    $time_in_before = date('Y-m-d H:i:s', strtotime ( '-22 hour' , strtotime ( $time_in_after ) )) ;
                    $update = [
                        'time_out' =>  date('Y-m-d H:i:s', strtotime($att->attendance_date)),
                        'device_out' => $att->device,
                        // 'last_id' =>$att->id,
                    ];
                    // return $time_in_after . ' - ' .$time_in_before;
                    $attendance_in = Attendance::where('employee_code',$att->employee_code)
                    ->whereBetween('time_in',[$time_in_before,$time_in_after])->first();

                    Attendance::where('employee_code',$att->employee_code)
                    ->whereBetween('time_in',[$time_in_before,$time_in_after])
                    ->update($update);

                    if($attendance_in ==  null)
                    {
                        $attendance = new Attendance;
                        $attendance->employee_code  = $att->employee_code;   
                        $attendance->time_out = date('Y-m-d H:i:s', strtotime($att->attendance_date));
                        $attendance->device_out = $att->device;
                        $attendance->save(); 
                    }

                    $count++;
                }
            }
        }

        return back();
    }

    public function sync_per_employee_hik_with_upload(Request $request)
    {
        $from = $request->from_hik;
        $to = $request->to_hik;
        $employee_code = $request->employee;


        $employee_numbers = Employee::whereIn('employee_number',$employee_code)->where('status','Active')->pluck('employee_number')->toArray();

        $attendances = HikVisionAttendance::whereIn('employee_code',$employee_numbers)
                                ->whereBetween('attendance_date',[$from,$to])
                                ->orderBy('attendance_date','asc')
                                ->orderBy('direction','asc')
                                ->get();
        
        $count = 0;
        if(count($attendances) > 0){
            foreach($attendances as $att)
            {
                $direction = str_replace(' ', '', $att->direction);

                if($direction == 'In' || $direction == 'IN')
                {
                    $attend = Attendance::where('employee_code',$att->employee_code)->whereDate('time_in',date('Y-m-d', strtotime($att->attendance_date)))->first();
                    if($attend == null)
                    {
                        $attendance = new Attendance;
                        $attendance->employee_code  = $att->employee_code;   
                        $attendance->time_in = date('Y-m-d H:i:s',strtotime($att->attendance_date));
                        $attendance->device_in = $att->deviceName;
                        $attendance->save();
                        $count++; 
                    }
                }
                else if($direction == 'Out' || $direction == 'OUT' )
                {
                    $time_in_after = date('Y-m-d H:i:s',strtotime($att->attendance_date));
                    $time_in_before = date('Y-m-d H:i:s', strtotime ( '-22 hour' , strtotime ( $time_in_after ) )) ;
                    $update = [
                        'time_out' =>  date('Y-m-d H:i:s', strtotime($att->attendance_date)),
                        'device_out' => $att->deviceName,
                        // 'last_id' =>$att->id,
                    ];
                    // return $time_in_after . ' - ' .$time_in_before;
                    $attendance_in = Attendance::where('employee_code',$att->employee_code)
                    ->whereBetween('time_in',[$time_in_before,$time_in_after])->first();

                    Attendance::where('employee_code',$att->employee_code)
                    ->whereBetween('time_in',[$time_in_before,$time_in_after])
                    ->update($update);

                    if($attendance_in ==  null)
                    {
                        $attendance = new Attendance;
                        $attendance->employee_code  = $att->employee_code;   
                        $attendance->time_out = date('Y-m-d H:i:s', strtotime($att->authDateTime));
                        $attendance->device_out = $att->deviceName;
                        $attendance->save(); 
                    }

                    $count++;
                }
            }
        }

        return back();
    }

    public function sync_per_employee_hik(Request $request)
    {
        $from = $request->from_hik;
        $to = $request->to_hik;
        $employee_code = $request->employee;


        $employee_numbers = Employee::whereIn('employee_number',$employee_code)->where('status','Active')->pluck('employee_number')->toArray();

        $attendances = HikAttLog::whereIn('employeeID',$employee_numbers)
                                ->whereBetween('authDate',[$from,$to])
                                ->orderBy('authDate','asc')
                                ->orderBy('direction','asc')
                                ->get();
        
        $count = 0;
        if(count($attendances) > 0){
            foreach($attendances as $att)
            {
                $direction = str_replace(' ', '', $att->direction);

                if($direction == 'In' || $direction == 'IN')
                {
                    $attend = Attendance::where('employee_code',$att->emp_code)->whereDate('time_in',date('Y-m-d', strtotime($att->authDate)))->first();
                    if($attend == null)
                    {
                        $attendance = new Attendance;
                        $attendance->employee_code  = $att->employeeID;   
                        $attendance->time_in = date('Y-m-d H:i:s',strtotime($att->authDateTime));
                        $attendance->device_in = $att->deviceName;
                        $attendance->save();
                        $count++; 
                    }
                }
                else if($direction == 'Out' || $direction == 'OUT' )
                {
                    $time_in_after = date('Y-m-d H:i:s',strtotime($att->authDateTime));
                    $time_in_before = date('Y-m-d H:i:s', strtotime ( '-22 hour' , strtotime ( $time_in_after ) )) ;
                    $update = [
                        'time_out' =>  date('Y-m-d H:i:s', strtotime($att->authDateTime)),
                        'device_out' => $att->deviceName,
                        // 'last_id' =>$att->id,
                    ];
                    // return $time_in_after . ' - ' .$time_in_before;
                    $attendance_in = Attendance::where('employee_code',$att->employeeID)
                    ->whereBetween('time_in',[$time_in_before,$time_in_after])->first();

                    Attendance::where('employee_code',$att->employeeID)
                    ->whereBetween('time_in',[$time_in_before,$time_in_after])
                    ->update($update);

                    if($attendance_in ==  null)
                    {
                        $attendance = new Attendance;
                        $attendance->employee_code  = $att->employeeID;   
                        $attendance->time_out = date('Y-m-d H:i:s', strtotime($att->authDateTime));
                        $attendance->device_out = $att->deviceName;
                        $attendance->save(); 
                    }

                    $count++;
                }
            }
        }

        return back();
    }

    public function print(Request $request,$id)
    {
        $employee = Employee::findOrfail($id);
        $customPaper = array(0,0,638,1011);
        $pdf = Pdf::loadView('employees.'.$employee->company_id,
            array(
                'employee' => $employee
            )
        )->setPaper($customPaper);
        
        return $pdf->stream($employee->employee_code.'.pdf');
    }

}
