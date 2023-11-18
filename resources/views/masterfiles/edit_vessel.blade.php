@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
                        <h4 class="card-title">Edit Vessel : {{$vessel->name}}</h4>
                        <div class="col-md-12">
                            <form method='POST' action='{{url('update-vessel/'.$vessel->id)}}' onsubmit='show()' enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class='col-md-12 form-group'>
                                        Code:
                                        <input type="text" name='code' value="{{$vessel->code}}" class="form-control" required>
                                    </div>
                                    <div class='col-md-12 form-group'>
                                        Name:
                                        <input type="text" name='name' value="{{$vessel->name}}" class="form-control" required>
                                    </div>
                                    <div class='col-md-12 form-group'>
                                        Status:
                                        <select data-placeholder="Select Status" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='status'>
                                            <option value="">-- Select Status --</option>
                                            <option value="Active" @if ($vessel->status == 'Active') selected @endif>Active</option>
                                            <option value="Inactive" @if ($vessel->status == 'Inactive') selected @endif>Inactive</option>
                                        </select>
                                    </div>


                                </div>

                                <a href='/vessel' type="button" class="btn btn-secondary">Close</a>
                                <button type="submit" class="btn btn-primary">Save</button>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
