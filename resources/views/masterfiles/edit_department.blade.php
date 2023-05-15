@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
                        <h4 class="card-title">Edit Department : {{$department->name}}</h4>
                        <div class="col-md-12">
                            <form method='POST' action='{{url('update-department/'.$department->id)}}' onsubmit='show()' enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class='col-md-12 form-group'>
                                        Name
                                        <input type="text" name="name" value="{{$department->name}}" class="form-control">
                                    </div>
                                    <div class='col-md-12 form-group'>
                                        Code
                                        <input type="text" name="code" value="{{$department->code}}" class="form-control">
                                    </div>
                                </div>

                                <a href='/department' type="button" class="btn btn-secondary">Close</a>
                                <button type="submit" class="btn btn-primary">Save</button>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
