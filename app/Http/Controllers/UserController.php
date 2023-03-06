<?php

namespace App\Http\Controllers;
use App\Bank;
use App\User;
use App\Level;
use App\Company;
use App\Employee;
use App\Schedule;
use App\Department;
use App\EmployeeApprover;
use App\MaritalStatus;
use App\Classification;
use App\EmployeeCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Psy\Command\ListCommand\FunctionEnumerator;

use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    //
    public function index(){

        $user = User::where('id',auth()->user()->id)->with('employee.department','employee.payment_info','employee.ScheduleData','employee.immediate_sup_data','approvers.approver_data','subbordinates')->first();


        $users = User::all();

        return view('users.index',
        array(
            'header' => 'users',
            'user' => $user,
            'header' => 'users',
                'users' => $users
        ));
    }

    public function export() 
    {
        return Excel::download(new UsersExport, 'Users.xlsx');
    }

    public function updateUserRole(Request $request, User $user){
        if($user){
            $user = User::findOrFail($user->id);
            $user->role = $request->role;
            $user->save();

            Alert::success('Successfully Updated')->persistent('Dismiss');
            return back();
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
        $user = User::where('id',auth()->user()->id)->with('employee.department','employee.payment_info','employee.ScheduleData','employee.immediate_sup_data','approvers.approver_data','subbordinates')->first();

       

        return view('users.user_settings',
        array(
            'header' => 'user1',
            'user' => $user,
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
        return back();

    }
}
