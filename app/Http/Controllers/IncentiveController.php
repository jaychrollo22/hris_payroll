<?php

namespace App\Http\Controllers;

use App\Incentive;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class IncentiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $incentives = Incentive::all();
        return view('incentives.incentives', array(
            'header' => 'reports',
            'incentives' => $incentives,
        ));
    }
    public function store(Request $request)
    {
        $new_Incentive = new Incentive;
        $new_Incentive->name = $request->incentive_name;
        $new_Incentive->status = 'Active';
        $new_Incentive->save();

        Alert::success('Successfully Stored')->persistent('Dismiss');
        return back();
    }
    public function update(Request $request, $id)
    {
        $Incentive = Incentive::findOrFail($id);
        $Incentive->name = $request->incentive_name;
        $Incentive->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }
    public function disable_incentive($id)
    {

        Incentive::Where('id', $id)->update(['status' => 'Inactive']);
        Alert::success('Incentive Inactive')->persistent('Dismiss');
        return back();
    }
    public function activate_incentive($id)
    {
        Incentive::Where('id', $id)->update(['status' => 'Active']);
        Alert::success('Incentive Activated')->persistent('Dismiss');
        return back();
    }

    public function incentive_report()
    {
        return view('reports.incentive_report', array(
            'header' => 'reports',
        ));
    }
}
