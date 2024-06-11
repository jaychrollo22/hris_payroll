<?php

namespace App\Http\Controllers;
use App\Holiday;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

use App\Location;

class HolidayController extends Controller
{
    //

    
    public function view ()
    {
        $holidays = Holiday::where('status','Permanent')
        ->orWhere(function ($query)
        {
            $query->where('status',null)->whereYear('holiday_date', '=', date('Y'));
        })
        ->orderBy('holiday_date','asc')->get();

        $locations = Location::orderBy('location','ASC')->get();

        return view('holidays.view',
        array(
            'header' => 'settings',
            'holidays' => $holidays,
            'locations' => $locations,
            
        ));
    }
    
    public function new(Request $request)
    {
        $new_holiday = new Holiday;
        $new_holiday->holiday_name = $request->holiday_name;
        $new_holiday->holiday_type = $request->holiday_type;
        $new_holiday->holiday_date = $request->date;
        $new_holiday->location = $request->location;
        $new_holiday->save();

        Alert::success('Successfully Stored')->persistent('Dismiss');
        return back();
    }
    
    public function delete_holiday($id)
    {
        $holiday = Holiday::find($id)->delete();
        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }
    public function edit_holiday(Request $request,$id)
    {
        $new_holiday = Holiday::findOrfail($id);
        $new_holiday->holiday_name = $request->holiday_name;
        $new_holiday->holiday_type = $request->holiday_type;
        $new_holiday->holiday_date = $request->holiday_date;

        if($request->location == 'N/A' || empty($request->location)){
            $new_holiday->location = "";
        }else{
            $new_holiday->location = $request->location;
        }
        $new_holiday->save();

        Alert::success('Successfully updated')->persistent('Dismiss');
        return back();
    }
    
}
