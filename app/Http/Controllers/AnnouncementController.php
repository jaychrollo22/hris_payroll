<?php

namespace App\Http\Controllers;
use App\Announcement;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AnnouncementController extends Controller
{
    //

    public function view()
    {
        $announcements = Announcement::with('user')->get();

        return view('announcements.view',
        array(
            'header' => 'Handbooks',
            'announcements' => $announcements,
            
        ));
    }
    public function new(Request $request)
    {
        $announcement = new Announcement;
        $announcement->created_by = auth()->user()->id;
        $announcement->announcement_title = $request->title;

        $attachment = $request->file('attachment');
        $original_name = $attachment->getClientOriginalName();
        $name = time().'_'.$attachment->getClientOriginalName();
        $attachment->move(public_path().'/announcement_attachments/', $name);
        $file_name = '/announcement_attachments/'.$name;

        $announcement->attachment = $file_name;
        $announcement->expired = $request->expiration;
        $announcement->save();

        Alert::success('Successfully save.')->persistent('Dismiss');  
        return back();
    }

    public function delete($id)
    {
        $holiday = Announcement::find($id)->delete();
        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }
}
