@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
                        <h4 class="card-title">Edit Project : {{$project->project_id}}</h4>
                        <div class="col-md-12">
                            <form method='POST' action='{{url('update-project/'.$project->id)}}' onsubmit='show()' enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class='col-md-12 form-group'>
                                        Project ID:
                                        <input type="text" name='project_id' value="{{$project->project_id}}" class="form-control" required>
                                    </div>
                                    <div class='col-md-12 form-group'>
                                        Project Title:
                                        <input type="text" name='project_title' value="{{$project->project_title}}" class="form-control" required>
                                    </div>
                                    <div class='col-md-12 form-group'>
                                        Company:
                                        <select data-placeholder="Select Company" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='company_id'>
                                            <option value="">-- Select Company --</option>
                                            @foreach($companies as $comp)
                                            <option value="{{$comp->id}}" @if ($comp->id == $project->company_id) selected @endif>{{$comp->company_name}} - {{$comp->company_code}}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                </div>

                                <a href='/project' type="button" class="btn btn-secondary">Close</a>
                                <button type="submit" class="btn btn-primary">Save</button>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
