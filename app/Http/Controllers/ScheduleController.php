<?php

namespace App\Http\Controllers;
use App\Schedule;
use App\ScheduleData;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ScheduleController extends Controller
{
    //

    public function schedules()
    {
        $schedules = Schedule::with('ScheduleData')->get();
        return view('schedules.schedule',
        array(
            'header' => 'Handbooks',
            'schedules' => $schedules,
            
        ));
    }
    public function newSchedule(Request $request)
    {
        // dd($request->working_hours);


        $new_schedule = new Schedule;
        $new_schedule->schedule_name = $request->schedule_name;
        $new_schedule->created_by = auth()->user()->id;
        $new_schedule->save();

        foreach($request->time_in_from as $key => $time_in)
        {
            if($time_in != null)
            {
                $schedule_data = new ScheduleData;
                $schedule_data->schedule_id = $new_schedule->id;
                $schedule_data->time_in_from = $time_in;
                $schedule_data->name = $key;
                $schedule_data->time_in_to = $request->time_in_to[$key];
                $schedule_data->time_out_from = $request->time_out_from[$key];
                $schedule_data->time_out_to = $request->time_out_to[$key];
                $schedule_data->working_hours = $request->working_hours[$key];
                $schedule_data->save();
            }
        }
      


        Alert::success('Successfully Store Schedule')->persistent('Dismiss');
        return back();

    }
}
