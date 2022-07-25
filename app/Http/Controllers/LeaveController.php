<?php

namespace App\Http\Controllers;
use App\Leave;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    //
    public function leaves()
    {
        $leave_types = Leave::get();
        return view('forms.leaves.leaves',
        array(
            'header' => 'forms',
            'leave_types' => $leave_types,
        ));
    }
}
