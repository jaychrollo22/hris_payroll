@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
                        <h4 class="card-title">Edit Cost Centr : {{$cost_center->name}}</h4>
                        <div class="col-md-12">
                            <form method='POST' action='{{url('update-cost-center/'.$cost_center->id)}}' onsubmit='show()' enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class='col-md-12 form-group'>
                                        Code
                                        <input type="text" name="code" value="{{$cost_center->code}}" class="form-control">
                                    </div>
                                    <div class='col-md-12 form-group'>
                                        Name
                                        <input type="text" name="name" value="{{$cost_center->name}}" class="form-control">
                                    </div>
                                    <div class='col-md-12 form-group'>
                                        Company
                                        <input type="text" name="company_code" value="{{$cost_center->company_code}}" class="form-control">
                                    </div>
                                    <div class='col-md-12 form-group'>
                                        Status
                                        <select name="status" class="form-control">
                                            <option value="">Choose</option>
                                            <option value="Active" {{$cost_center->status == 'Active' ? 'selected' : ''}}>Active</option>
                                            <option value="Inactive" {{$cost_center->status == 'Inactive' ? 'selected' : ''}}>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <a href='/early-cutoff' type="button" class="btn btn-secondary">Close</a>
                                <button type="submit" class="btn btn-primary">Save</button>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
