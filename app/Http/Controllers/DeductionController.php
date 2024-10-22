<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Deduction;

use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class DeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status = isset($request->status) ? $request->status : "Active";

        $deductions = Deduction::where('status',$status)
            ->get();
            
        return view(
            'deductions.index',
            array(
                'header' => 'settings',
                'deductions' => $deductions,
                'status' => 'Active'
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
        $new_deduction = new Deduction;
        $new_deduction->name = $request->deduction_name;
        $new_deduction->add_by = Auth::user()->id;
        $new_deduction->status = 1;
        $new_deduction->save();

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
        $deduction = Deduction::findOrFail($id);
        $deduction->name = $request->deduction_name;
        $deduction->save();
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
        Deduction::Where('id', $id)->update(['status' => '2']);
        Alert::success('Deduction Inactive')->persistent('Dismiss');
        return back();
    }
}
