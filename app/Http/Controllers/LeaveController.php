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
        return view(
            'forms.leaves.leaves',
            array(
                'header' => 'forms',
                'leave_types' => $leave_types,
            )
        );
    }
    public function leaveDetails()
    {
        $leave_types = Leave::get();
        return view(
            'leaves.leave_types',
            array(
                'header' => 'Handbooks',
                'leave_types' => $leave_types,
            )
        );
    }
    public function leave_report()
    {
        return view('reports.leave_report', array(
            'header' => 'reports',
        ));
    }
}
