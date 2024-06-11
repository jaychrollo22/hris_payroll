<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\Company;

use RealRashid\SweetAlert\Facades\Alert;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::all();
        $companies = Company::all();
        return view(
            'masterfiles.project_index',
            array(
                'header' => 'masterfiles',
                'projects' => $projects,
                'companies' => $companies,
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
        $project = new Project();
        $project->project_id = $request->project_id;
        $project->project_title = $request->project_title;
        $project->company_id = $request->company_id;
        $project->save();

        Alert::success('Successfully Stored')->persistent('Dismiss');
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
        $project = Project::where('id',$id)->first();
        $companies = Company::all();
        return view('masterfiles.edit_project',
                    array(
                        'header' => 'masterfiles',
                        'project'=>$project,
                        'companies'=>$companies,
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
        $project = Project::where('id',$id)->first();
        $project->project_id = $request->project_id;
        $project->project_title = $request->project_title;
        $project->company_id = $request->company_id;
        $project->save();

        Alert::success('Successfully Stored')->persistent('Dismiss');
        return redirect('/project');
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
