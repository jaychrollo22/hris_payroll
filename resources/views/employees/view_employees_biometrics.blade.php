@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
       
        <div class='row'>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Employees <button type="button" class="btn btn-outline-success btn-icon-text btn-sm text-center" data-toggle="modal" data-target="#newEmployee"><i class="ti-plus btn-icon-prepend"></i></button></h4>
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                      <tr>
                        <th>Employee Code</th>
                        <th>First Name</th> 
                        <th>Last Name</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                            <tr>
                                <td>{{$employee->emp_code}}</td>
                                <td>{{$employee->first_name}}</td> 
                                <td>{{$employee->last_name}}</td>
                                <td>
                                    <button data-toggle="modal" data-target="#editEmployee{{$employee->id}}" type="button" class="btn btn-outline-info btn-icon-text btn-sm">
                                        Edit
                                        <i class="ti-file btn-icon-append"></i>                          
                                    </button>
                                </td>
                            </tr>
                          
                        @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>
@include('employees.new_employee')
@foreach($employees as $employee)
@include('employees.edit_employee')
@endforeach
@endsection
