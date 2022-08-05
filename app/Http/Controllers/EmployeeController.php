<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    //
    public function view ()
    {
        return view('employees.view_employees',
        array(
            'header' => 'employees',
        ));
    }
}
