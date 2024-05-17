<?php

namespace App\Http\Controllers;
use App\Bank;
use App\User;
use App\UserPrivilege;
use App\UserAllowedCompany;
use App\UserAllowedProject;
use App\UserAllowedLocation;

use App\Level;
use App\Company;
use App\Employee;
use App\Schedule;
use App\Department;
use App\EmployeeApprover;
use App\EmployeeContactPerson;
use App\MaritalStatus;
use App\Classification;
use App\EmployeeCompany;

use App\Project;
use App\Location;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Psy\Command\ListCommand\FunctionEnumerator;

use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    //
    public function index(Request $request){

        if(auth()->user()->id == '471' || auth()->user()->id == '1'){
            $search = isset($request->search) ? $request->search : "";
            $limit = isset($request->limit) ? $request->limit : 1000;
            $companies = Company::whereHas('employee_has_company')->orderBy('company_name','ASC')->get();
            $users = User::select('id','name','email','status','role')
                            ->whereHas('employee',function($q){
                                $q->where('status','Active');
                            })
                            ->when($search,function($q) use($search){
                                $q->whereHas('employee',function($w) use($search){
                                    $w->where('first_name', 'like' , '%' .  $search . '%')->orWhere('last_name', 'like' , '%' .  $search . '%')->orWhere('employee_number', 'like' , '%' .  $search . '%');
                                    $w->orWhereRaw("CONCAT(`first_name`, ' ', `last_name`) LIKE ?", ["%{$search}%"]);
                                    $w->orWhereRaw("CONCAT(`last_name`, ' ', `first_name`) LIKE ?", ["%{$search}%"]);
                                });
                            })
                            ->limit($limit)
                            ->get();

            return view('users.index',
            array(
                'header' => 'users',
                'users' => $users,
                'companies' => $companies,
                'search' => $search,
            ));
        }else{
            return redirect('/');
        }
        
    }

    public function export() 
    {
        return Excel::download(new UsersExport, 'Users.xlsx');
    }

    public function editUserRole(User $user){

        $companies = Company::whereHas('employee_has_company')->orderBy('company_name','ASC')->get();
        $projects = Project::orderBy('project_id','ASC')->get();
        $locations = Location::orderBy('location','ASC')->get();
        $user = User::with('user_allowed_company','user_privilege')
                        ->where('id',$user->id)
                        ->first();

        return view('users.edit_user_role',
        array(
            'header' => 'edit_user_role',
            'user' => $user,
            'companies' => $companies,
            'projects' => $projects,
            'locations' => $locations,
        ));

    }
    public function changePassword(User $user){

        
        $user = User::with('user_allowed_company','user_privilege')
                        ->where('id',$user->id)
                        ->first();

        return view('users.change_password',
        array(
            'header' => 'users',
            'user' => $user
        ));

    }

    public function updateUserRole(Request $request, User $user){
        if($user){
            $user = User::findOrFail($user->id);
            $user->email = $request->email;
            $user->name = $request->name;
            $user->role = $request->role;
            $user->save();

            if($request->company){
                $user_allowed_company = UserAllowedCompany::where('user_id',$user->id)->first(); 
                if($user_allowed_company){
                    $user_allowed_company->company_ids = json_encode($request->company,true);
                    $user_allowed_company->save();
                }else{
                    $new_user_allowed_company = new UserAllowedCompany;
                    $new_user_allowed_company->user_id = $user->id;
                    $new_user_allowed_company->company_ids = json_encode($request->company,true);
                    $new_user_allowed_company->save();
                }
            }else{
                $user_allowed_company = UserAllowedCompany::where('user_id',$user->id)->delete();
            }
            if($request->location){
                $user_allowed_location = UserAllowedLocation::where('user_id',$user->id)->first(); 
                if($user_allowed_location){
                    $user_allowed_location->location_ids = json_encode($request->location,true);
                    $user_allowed_location->save();
                }else{
                    $new_user_allowed_location = new UserAllowedLocation;
                    $new_user_allowed_location->user_id = $user->id;
                    $new_user_allowed_location->location_ids = json_encode($request->location,true);
                    $new_user_allowed_location->save();
                }
            }else{
                $user_allowed_location = UserAllowedLocation::where('user_id',$user->id)->delete();
            }
            if($request->project){
                $user_allowed_project = UserAllowedProject::where('user_id',$user->id)->first(); 
                if($user_allowed_project){
                    $user_allowed_project->project_ids = json_encode($request->project,true);
                    $user_allowed_project->save();
                }else{
                    $new_user_allowed_project = new UserAllowedProject;
                    $new_user_allowed_project->user_id = $user->id;
                    $new_user_allowed_project->project_ids = json_encode($request->project,true);
                    $new_user_allowed_project->save();
                }
            }else{
                $user_allowed_project = UserAllowedProject::where('user_id',$user->id)->delete();
            }

            $user_privilege = UserPrivilege::where('user_id',$user->id)->first();
            
            if($user_privilege){
                $user_privilege->employees_view = $request->employees_view;
                $user_privilege->employees_edit = $request->employees_edit;
                $user_privilege->employees_add = $request->employees_add;
                $user_privilege->employees_export = $request->employees_export;
                $user_privilege->employees_export_hr = $request->employees_export_hr;
                $user_privilege->employees_rate = $request->employees_rate;

                $user_privilege->reports_leave = $request->reports_leave;
                $user_privilege->reports_overtime = $request->reports_overtime;
                $user_privilege->reports_wfh = $request->reports_wfh;
                $user_privilege->reports_ob = $request->reports_ob;
                $user_privilege->reports_dtr = $request->reports_dtr;

                $user_privilege->biometrics_per_employee = $request->biometrics_per_employee;
                $user_privilege->biometrics_per_location = $request->biometrics_per_location;
                $user_privilege->biometrics_per_location_hik = $request->biometrics_per_location_hik;
                $user_privilege->biometrics_per_company = $request->biometrics_per_company;
                $user_privilege->biometrics_per_seabased = $request->biometrics_per_seabased;
                $user_privilege->biometrics_per_hik_vision = $request->biometrics_per_hik_vision;
                $user_privilege->biometrics_sync = $request->biometrics_sync;
                $user_privilege->timekeeping_dashboard = $request->timekeeping_dashboard;

                $user_privilege->settings_view = $request->settings_view;
                $user_privilege->settings_add = $request->settings_add;
                $user_privilege->settings_edit = $request->settings_edit;
                $user_privilege->settings_delete = $request->settings_delete;
                
                $user_privilege->masterfiles_companies = $request->masterfiles_companies;
                $user_privilege->masterfiles_departments = $request->masterfiles_departments;
                $user_privilege->masterfiles_locations = $request->masterfiles_locations;
                $user_privilege->masterfiles_projects = $request->masterfiles_projects;
                $user_privilege->masterfiles_loan_types = $request->masterfiles_loan_types;
                $user_privilege->masterfiles_employee_leave_credits = $request->masterfiles_employee_leave_credits;
                $user_privilege->masterfiles_employee_leave_earned = $request->masterfiles_employee_leave_earned;
                $user_privilege->masterfiles_employee_allowances = $request->masterfiles_employee_allowances;

                $user_privilege->save();
                Alert::success('Successfully Updated')->persistent('Dismiss');
                return back();
            }else{
                $new_user_privilege = new UserPrivilege;
                $new_user_privilege->user_id = $user->id;
                $new_user_privilege->employees_view = $request->employees_view;
                $new_user_privilege->employees_edit = $request->employees_edit;
                $new_user_privilege->employees_add = $request->employees_add;
                $new_user_privilege->employees_export = $request->employees_export;
                $new_user_privilege->employees_export_hr = $request->employees_export_hr;
                $new_user_privilege->employees_rate = $request->employees_rate;

                $new_user_privilege->reports_leave = $request->reports_leave;
                $new_user_privilege->reports_overtime = $request->reports_overtime;
                $new_user_privilege->reports_wfh = $request->reports_wfh;
                $new_user_privilege->reports_ob = $request->reports_ob;
                $new_user_privilege->reports_dtr = $request->reports_dtr;

                $new_user_privilege->biometrics_per_employee = $request->biometrics_per_employee;
                $new_user_privilege->biometrics_per_location = $request->biometrics_per_location;
                $new_user_privilege->biometrics_per_location_hik = $request->biometrics_per_location_hik;
                $new_user_privilege->biometrics_per_company = $request->biometrics_per_company;
                $new_user_privilege->biometrics_per_seabased = $request->biometrics_per_seabased;
                $new_user_privilege->biometrics_per_hik_vision = $request->biometrics_per_hik_vision;
                $new_user_privilege->biometrics_sync = $request->biometrics_sync;
                $new_user_privilege->timekeeping_dashboard = $request->timekeeping_dashboard;

                $new_user_privilege->settings_view = $request->settings_view;
                $new_user_privilege->settings_add = $request->settings_add;
                $new_user_privilege->settings_edit = $request->settings_edit;
                $new_user_privilege->settings_delete = $request->settings_delete;

                $new_user_privilege->masterfiles_companies = $request->masterfiles_companies;
                $new_user_privilege->masterfiles_departments = $request->masterfiles_departments;
                $new_user_privilege->masterfiles_locations = $request->masterfiles_locations;
                $new_user_privilege->masterfiles_projects = $request->masterfiles_projects;
                $new_user_privilege->masterfiles_loan_types = $request->masterfiles_loan_types;
                $new_user_privilege->masterfiles_employee_leave_credits = $request->masterfiles_employee_leave_credits;
                $new_user_privilege->masterfiles_employee_leave_earned = $request->masterfiles_employee_leave_earned;
                $new_user_privilege->masterfiles_employee_allowances = $request->masterfiles_employee_allowances;
                
                $new_user_privilege->save();
                Alert::success('Successfully Updated')->persistent('Dismiss');
                return back();
            }

            
        }
    }

    public function accountSetting()
    {
        $classifications = Classification::get();

        $employees = Employee::with('department', 'payment_info', 'ScheduleData', 'immediate_sup_data', 'user_info', 'company')->get();
        $schedules = Schedule::get();
        $banks = Bank::get();
        $users = User::get();
        $levels = Level::get();
        $departments = Department::get();
        $marital_statuses = MaritalStatus::get();
        $companies = Company::get();
        $user = User::where('id',auth()->user()->id)->with('employee.department','employee.payment_info','employee.classification_info','employee.level_info','employee.ScheduleData','employee.immediate_sup_data','approvers.approver_data','subbordinates')->first();

       

        return view('users.user_settings',
        array(
            'header' => 'user1',
            'user' => $user,
            'header' => 'account-setting',
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
    
    public function updateInfo(Request $request, $id){

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

    public function updateEmpInfo(Request $request, $id){

        $employee = Employee::findOrFail($id);
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
        $employee->employee_number = $request->biometric_code;
        $employee->save();

        $approver = EmployeeApprover::where('user_id',$employee->user_id)->delete();
        $level = 1;
        foreach($request->approver as  $approver)
        {
            $new_approver = new EmployeeApprover;
            $new_approver->user_id = $employee->user_id;
            $new_approver->approver_id = $approver;
            $new_approver->level = $level;
            $new_approver->save();
            $level = $level+1;

        }
        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();

    }

    public function updateEmpContactInfo(Request $request, $id){
        $employee = Employee::where('user_id',$id)->first();

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

    public function uploadAvatar(Request $request)
    {
        $employee = Employee::where('user_id',auth()->user()->id)->first();
        if($request->hasFile('file'))
        {
            $attachment = $request->file('file');
            $original_name = $attachment->getClientOriginalName();
            $name = time().'_'.$attachment->getClientOriginalName();
            $attachment->move(public_path().'/avatar/', $name);
            $file_name = '/avatar/'.$name;
            $employee->avatar = $file_name;
            $employee->save();
            Alert::success('Successfully avatar uploaded.')->persistent('Dismiss');
            return back();
            
        }
    }
    public function uploadSignature(Request $request)
    {
        $employee = Employee::where('user_id',auth()->user()->id)->first();
        if($request->hasFile('signature'))
        {
            $attachment = $request->file('signature');
            $original_name = $attachment->getClientOriginalName();
            $name = time().'_'.$attachment->getClientOriginalName();
            $attachment->move(public_path().'/signature/', $name);
            $file_name = '/signature/'.$name;
            $employee->signature = $file_name;
            $employee->save();
            Alert::success('Successfully signature uploaded.')->persistent('Dismiss');
            return back();
            
        }
    }
    public function get_salary(Request $request)
    {
        // return $request;
        $user = User::where('id',auth()->user()->id)->with('employee.department','employee.payment_info')->first();
        $data = Hash::check($request->password_salary, $user->password);
        if($data == true)
        {
            return $user;
        }
        else
        {
            return $request;
        }
    }

    public function updateUserPassword(Request $request,User $user){

        $validator = $request->validate([
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);
    
        $user = User::findOrFail($user->id);
        $user->password = bcrypt($request->input('password'));
        $user->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return redirect('/users');

    }
}
