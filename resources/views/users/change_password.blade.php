@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Change Password for  : {{$user->name}}</h4>
                        <div class="col-md-12">
                            <form method='POST' action='{{url('update-user-password/' . $user->id)}}' onsubmit='show()' enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class='col-md-12 form-group'>
                                        Password
                                        <input type="password" name="password" class="form-control">
                                    </div>
                                    <div class='col-md-12 form-group'>
                                        Confirm Password
                                        <input type="password" name="password_confirmation" class="form-control">
                                    </div>
                                </div>
                                <a href='/users' type="button" class="btn btn-secondary">Close</a>
                                <button type="submit" class="btn btn-primary">Save</button>
                         
                            </form>
                        </div>

                    </div>
                </div>
            </div>
           
        </div>
    </div>
</div>
@endsection