@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
                        <h4 class="card-title">Edit Early Cutoff : {{$early_cutoff->name}}</h4>
                        <div class="col-md-12">
                            <form method='POST' action='{{url('update-early-cutoff/'.$early_cutoff->id)}}' onsubmit='show()' enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class='col-md-12 form-group'>
                                        From
                                        <input type="date" name="from" value="{{$early_cutoff->from}}" class="form-control">
                                    </div>
                                    <div class='col-md-12 form-group'>
                                        To
                                        <input type="date" name="to" value="{{$early_cutoff->to}}" class="form-control">
                                    </div>
                                    <div class='col-md-12 form-group'>
                                        Status
                                        <select name="status" class="form-control">
                                            <option value="">Choose</option>
                                            <option value="Active" {{$early_cutoff->status == 'Active' ? 'selected' : ''}}>Active</option>
                                            <option value="Inactive" {{$early_cutoff->status == 'Inactive' ? 'selected' : ''}}>Inactive</option>
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
