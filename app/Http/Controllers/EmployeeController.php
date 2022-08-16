<?php

namespace App\Http\Controllers;
use App\Classification;
use App\Employee;
use App\Department;
use App\Schedule;
use App\Level;
use App\Bank;
use App\User;
use App\MaritalStatus;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    //
    public function view ()
    {
        $classifications = Classification::get();

        $employees = Employee::with('department')->get();
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
}
