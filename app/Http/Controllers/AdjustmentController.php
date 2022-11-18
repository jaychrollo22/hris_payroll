<?php

namespace App\Http\Controllers;

use App\Adjustment;
use App\Allowance;
use App\Employee;
use Illuminate\Http\Request;

class AdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::get();
        $adjustments = Adjustment::get();
        $allowances = Allowance::get();
        return view('salary_managements.index', array(
            'header' => 'salaryAdjustments',
            'employees' => $employees,
            'adjustments' => $adjustments,
            'allowances' => $allowances,
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Adjustment  $adjustment
     * @return \Illuminate\Http\Response
     */
    public function show(Adjustment $adjustment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Adjustment  $adjustment
     * @return \Illuminate\Http\Response
     */
    public function edit(Adjustment $adjustment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Adjustment  $adjustment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Adjustment $adjustment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Adjustment  $adjustment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Adjustment $adjustment)
    {
        //
    }
}
