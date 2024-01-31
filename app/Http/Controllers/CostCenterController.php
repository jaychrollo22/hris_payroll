<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\CostCenter;

use RealRashid\SweetAlert\Facades\Alert;

class CostCenterController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cost_centers = CostCenter::all();
        return view(
            'masterfiles.cost_centers.cost_center_index',
            array(
                'header' => 'masterfiles',
                'cost_centers' => $cost_centers,
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
        $cost_center = new CostCenter();
        $cost_center->code = $request->code;
        $cost_center->name = $request->name;
        $cost_center->company_code = $request->company_code;
        $cost_center->status = 'Active';
        $cost_center->save();

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
        $cost_center = CostCenter::where('id',$id)->first();
        return view('masterfiles.cost_centers.edit_cost_center',
                    array(
                        'header' => 'masterfiles',
                        'cost_center'=>$cost_center
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
        $cost_center = CostCenter::where('id',$id)->first();
        $cost_center->code = $request->code;
        $cost_center->name = $request->name;
        $cost_center->company_code = $request->company_code;
        $cost_center->status = $request->status;
        $cost_center->save();

        Alert::success('Successfully Store')->persistent('Dismiss');
        return redirect('/cost-center');
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
