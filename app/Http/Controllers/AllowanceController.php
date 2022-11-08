<?php

namespace App\Http\Controllers;

use App\Allowances;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AllowanceController extends Controller
{
    //
    public function viewAllowances()
    {
        $allowances = Allowances::with('user')->get();
        return view(
            'allowances.allowances',
            array(
                'header' => 'settings',
                'allowances' => $allowances,

            )
        );
    }
    public function new(Request $request)
    {
        $new_holiday = new Allowances;
        $new_holiday->name = $request->allowance_name;
        $new_holiday->add_by = Auth::user()->id;
        $new_holiday->status = 'Active';
        $new_holiday->save();

        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    }
    public function edit_allowance(Request $request, $id)
    {
        $allowance = Allowances::findOrFail($id);
        // dd($allowance)->all();
        $allowance->name = $request->allowance_name;
        $allowance->add_by = Auth::user()->id;
        $allowance->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }
    public function disable_allowance($id)
    {

        Allowances::Where('id', $id)->update(['status' => 'Inactive']);
        Alert::success('Allowance Inactive')->persistent('Dismiss');
        return back();
    }
    public function activate_allowance($id)
    {
        Allowances::Where('id', $id)->update(['status' => 'Active']);
        Alert::success('Allowance Activated')->persistent('Dismiss');
        return back();
    }
}
