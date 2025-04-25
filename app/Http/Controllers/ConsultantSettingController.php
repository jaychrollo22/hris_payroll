<?php

namespace App\Http\Controllers;

use App\ConsultantSetting;
use App\Employee;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ConsultantSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $consultants = Employee::doesntHave('non_taxable__consultant')
            ->where('classification',7)
            ->where('status','Active')
            ->get();
        
        $nontaxable_consultants = Employee::whereHas('non_taxable__consultant')
            ->where('classification',7)
            ->where('status','Active')
            ->get();

        return view('consultant_settings.index', array(
            'header' => 'consultant_settings',
            'consultants' => $consultants,
            'nontaxable_consultants' => $nontaxable_consultants
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
        $consultantSetting = new ConsultantSetting;
        $consultantSetting->user_id = $request->consultant;
        $consultantSetting->save();

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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ConsultantSetting::where('user_id',$id)->delete();

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }
}
