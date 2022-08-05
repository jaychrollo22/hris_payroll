<?php

namespace App\Http\Controllers;
use App\Handbook;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class HandbookController extends Controller
{
    //
    public function view()
    {
        return view('handbooks.handbooks',
        array(
            'header' => 'Handbooks',
        ));
    }

    public function newhandbook(Request $request)
    {
        // dd($request->all());

        $handbook = new Handbook;
        $handbook->remarks = $request->reason;
        $handbook->created_by = auth()->user()->id;

        $attachment = $request->file('attachment');
        $original_name = $attachment->getClientOriginalName();
        $name = time().'_'.$attachment->getClientOriginalName();
        $attachment->move(public_path().'/handbooks_attachment/', $name);
        $file_name = '/handbooks_attachment/'.$name;

        $handbook->attachment = $file_name;
        $handbook->save();

        Alert::success('Successfully save.')->persistent('Dismiss');  
        return back();
        
    }
}
