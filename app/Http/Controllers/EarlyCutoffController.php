<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\EarlyCutoff;

use RealRashid\SweetAlert\Facades\Alert;

class EarlyCutoffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $early_cutoffs = EarlyCutoff::all();
        return view(
            'masterfiles.early_cutoffs.early_cutoff_index',
            array(
                'header' => 'masterfiles',
                'early_cutoffs' => $early_cutoffs,
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
        $early_cutoff = new EarlyCutoff();
        $early_cutoff->from = $request->from;
        $early_cutoff->to = $request->to;
        $early_cutoff->status = 'Active';
        $early_cutoff->save();

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
        $early_cutoff = EarlyCutoff::where('id',$id)->first();
        return view('masterfiles.early_cutoffs.edit_early_cutoff',
                    array(
                        'header' => 'masterfiles',
                        'early_cutoff'=>$early_cutoff
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
        $early_cutoff = EarlyCutoff::where('id',$id)->first();
        $early_cutoff->from = $request->from;
        $early_cutoff->to = $request->to;
        $early_cutoff->status = $request->status;
        $early_cutoff->save();

        Alert::success('Successfully Store')->persistent('Dismiss');
        return redirect('/early-cutoff');
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
