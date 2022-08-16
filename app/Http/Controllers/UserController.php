<?php

namespace App\Http\Controllers;
use App\User;
use App\Employee;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    //

    public function accountSetting()
    {
        $user = User::where('id',auth()->user()->id)->with('employee.department')->first();
        
        return view('users.user_settings',
        array(
            'header' => 'user1',
            'user' => $user,
        ));

    }

    public function uploadAvatar(Request $request)
    {
        $employee = Employee::where('id',auth()->user()->id)->first();
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
        $employee = Employee::where('id',auth()->user()->id)->first();
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
}
