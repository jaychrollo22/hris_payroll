<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class DepartmentController extends Controller
{
    //

    public function department_index()
    {
        $departments = Department::all();
        return view(
            'masterfiles.department_index',
            array(
                'header' => 'masterfiles',
                'departments' => $departments,
            )
        );
    }
    public function store_department(Request $request)
    {
        $new_department = new Department();
        $new_department->name = $request->department_name;
        $new_department->code = $request->department_code;
        $new_department->status = 1;
        $new_department->save();

        Alert::success('Successfully Stored')->persistent('Dismiss');
        return back();
    }
    public function disable_department($id)
    {

        Department::Where('id', $id)->update(['status' => 0]);
        return back();
    }
    public function enable_department($id)
    {
        Department::Where('id', $id)->update(['status' => 1]);
        return back();
    }

    public function edit_department($id){

        $department = Department::where('id',$id)->first();
        return view('masterfiles.edit_department',
                    array(
                        'header' => 'masterfiles',
                        'department'=>$department
        ));
        
    }
    public function update_department(Request $request,$id){

        $new_department = Department::where('id',$id)->first();
        $new_department->name = $request->name;
        $new_department->code = $request->code;
        $new_department->save();

        Alert::success('Successfully Stored')->persistent('Dismiss');
        return redirect('/department');
        
    }
}
