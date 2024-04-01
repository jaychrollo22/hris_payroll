<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PerformancePlanPeriod;

use RealRashid\SweetAlert\Facades\Alert;

class PerformancePlanPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $performance_plan_periods = PerformancePlanPeriod::all();
        return view(
            'performance_plan_periods.index',
            array(
                'header' => 'performance_plan_periods',
                'performance_plan_periods' => $performance_plan_periods,
            )
        );
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
        $new = new PerformancePlanPeriod;
        $new->name = $request->name;
        $new->save();

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
        $performance_plan_period = PerformancePlanPeriod::where('id',$id)->first();
        return view('performance_plan_periods.edit',
                    array(
                        'header' => 'performance_plan_periods',
                        'performance_plan_period'=>$performance_plan_period
        ));
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
        $update = PerformancePlanPeriod::where('id',$id)->first();
        $update->name = $request->name;
        $update->save();

        Alert::success('Successfully Store')->persistent('Dismiss');
        return redirect('/performance-plan-periods');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
