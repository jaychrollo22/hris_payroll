<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vessel;

use RealRashid\SweetAlert\Facades\Alert;

class VesselController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vessels = Vessel::all();
        return view(
            'masterfiles.vessel_index',
            array(
                'header' => 'masterfiles',
                'vessels' => $vessels
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
        $vessel = new Vessel();
        $vessel->code = $request->code;
        $vessel->name = $request->name;
        $vessel->status = 'Active';
        $vessel->save();
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
        $vessel = Vessel::where('id',$id)->first();
        return view('masterfiles.edit_vessel',
                    array(
                        'header' => 'masterfiles',
                        'vessel'=>$vessel
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
        $vessel = Vessel::where('id',$id)->first();
        $vessel->code = $request->code;
        $vessel->name = $request->name;
        $vessel->status = $request->status;
        $vessel->save();

        Alert::success('Successfully Store')->persistent('Dismiss');
        return redirect('/vessel');
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
