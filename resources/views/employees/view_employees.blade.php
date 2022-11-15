@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
          <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Employee Classification(Active)</th>
                        <th>Male</th>
                        <th>Female</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($classifications as $class)
                      <tr>
                        <td>{{$class->name}}</td>
                        <td>{{$employees->where('classification',$class->name)->where('gender','Male')->where('status','Active')->count()}}</td>
                        <td>{{$employees->where('classification',$class->name)->where('gender','Female')->where('status','Active')->count()}}</td>
                        <td>{{$employees->where('classification',$class->name)->where('status','Active')->count()}}</td>
                      </tr>
                      @endforeach
                     
                      <tr>
                        <td></td>
                        <td>{{$employees->where('gender','Male')->where('status','Active')->count()}}</td>
                        <td>{{$employees->where('gender','Female')->where('status','Active')->count()}}</td>
                        <td>{{$employees->where('status','Active')->count()}}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class='col-lg-2 grid-margin'>
            <div class="card card-tale">
              <div class="card-body">
                <div class="media">
                
                  <div class="media-body">
                    <h4 class="mb-4">For Clearance</h4>
                    <h2 class="card-text">0</h2>
                  </div>
                </div>
              </div>
            </div>
          </div> 
          <div class='col-lg-2 grid-margin'>
            <div class="card card-light-danger">
              <div class="card-body">
                <div class="media">
                
                  <div class="media-body">
                    <h4 class="mb-4">Cleared</h4>
                    <h2 class="card-text">0</h2>
                  </div>
                </div>
              </div>
            </div>
          </div> 
          <div class='col-lg-2'>
            <div class="card text-success">
              <div class="card-body">
                <div class="media">
                
                  <div class="media-body">
                    <h4 class="mb-4">Active</h4>
                    <h2 class="card-text">{{$employees->where('status','Active')->count()}}</h2>
                  </div>
                </div>
              </div>
            </div>
          </div> 
        </div>
        <div class='row'>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Employees <button type="button" class="btn btn-outline-success btn-icon-text btn-sm text-center" data-toggle="modal" data-target="#newEmployee"><i class="ti-plus btn-icon-prepend"></i></button></h4>
                <div class="table-responsive">
                  <table class="table table-hover table-bordered employees-table">
                    <thead>
                      <tr>
                        <th>Action</th>
                        <th>Image</th> 
                        <th>Company</th> 
                        <th>Department</th>
                        <th>Classification</th>
                        <th>Immediate Supervisor</th>
                        <th>Status </th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                          <tr>
                            <td></td>
                            <td>
                              <small><img class="rounded-circle" style='width:34px;height:34px;' src='{{URL::asset($employee->avatar)}}' onerror="this.src='{{URL::asset('/images/no_image.png')}}';"></small>
                              {{$employee->first_name}} {{$employee->last_name}} </small>
                            </td> 
                            <td>
                              @if($employee->company){{$employee->company->company_name}}@endif
                            </td> 
                            <td>@if($employee->department){{$employee->department->name}}@endif</td>
                            <td>{{$employee->classification}}</td>
                            <td>@if($employee->immediate_sup_data)
                              <small><img class="rounded-circle" style='width:34px;height:34px;' src='{{URL::asset($employee->immediate_sup_data->employee->avatar)}}' onerror="this.src='{{URL::asset('/images/no_image.png')}}';"></small>
                              {{$employee->immediate_sup_data->name}}@endif</td>
                            <td>{{$employee->status}} </td>
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
@include('employees.newEmployee')
{{-- @include('employees.capture_image') --}}


@endsection
@section('footer')
<script src="{{ asset('body_css/vendors/inputmask/jquery.inputmask.bundle.js') }}"></script>
<script src="{{ asset('body_css/vendors/inputmask/jquery.inputmask.bundle.js') }}"></script>
<script src="{{ asset('body_css/js/inputmask.js') }}"></script>
@endsection