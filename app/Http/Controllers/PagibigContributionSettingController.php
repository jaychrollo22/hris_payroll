<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\PagibigContributionSetting;
use RealRashid\SweetAlert\Facades\Alert;

class PagibigContributionSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pagibig_contributions = PagibigContributionSetting::with('employee')
            ->get();

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $employees = Employee::select('id','user_id','first_name','last_name','middle_name')
            ->doesntHave('pagibig_contribution')
            ->whereIn('company_id',$allowed_companies)
            ->where('level','!=',5)//Excludes consultant
            ->where('status','Active')
            ->get();


        return view('pagibig_contribution_settings.index', array(
            'header' => 'consultant_settings',
            'pagibig_contributions' => $pagibig_contributions,
            'employees' => $employees
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation
        $this->validate($request, [
            'user_id' => 'required',
            'amount' => 'required', 'min:1',
            'payroll_cutoff' => 'required',
        ]);

        $pagibigContributionSetting = new PagibigContributionSetting;
        $pagibigContributionSetting->user_id = $request->user_id;
        $pagibigContributionSetting->amount = $request->amount;
        $pagibigContributionSetting->payroll_cutoff = $request->payroll_cutoff;
        $pagibigContributionSetting->save();

        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pagibigContributionSetting = PagibigContributionSetting::findOrFail($id);
        $pagibigContributionSetting->amount = $request->amount;
        $pagibigContributionSetting->payroll_cutoff = $request->payroll_cutoff;
        $pagibigContributionSetting->save();
        
        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PagibigContributionSetting::where('id',$id)->delete();

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }
}
