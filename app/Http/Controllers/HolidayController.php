<?php

namespace App\Http\Controllers;
use App\Holiday;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;


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

        return view('holidays.view',
        array(
            'header' => 'Handbooks',
            'holidays' => $holidays,
            
        ));
    }
    
    public function new(Request $request)
    {
        $new_holiday = new Holiday;
        $new_holiday->holiday_name = $request->holiday_name;
        $new_holiday->holiday_type = $request->holiday_type;
        $new_holiday->holiday_date = $request->date;
        $new_holiday->save();

        Alert::success('', 'New Holiday Store');
        return back();
    }
    
    public function delete_holiday($id)
    {
        $holiday = Holiday::find($id)->delete();
        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }
    
}
