@extends('layouts.header')

@section('content')

<div class="main-panel">
    <div class="content-wrapper">
            @if (count($errors))
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissible fade show " role="alert">
                        <span class="alert-inner--icon"><i class="ni ni-fat-remove"></i></span>
                        <span class="alert-inner--text"><strong>Error!</strong> {{ $error }}</span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endforeach
            @endif
        @include('links')
        <div id="payroll" class="tab-pane  active">
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">13 Month 
                 
                </h4>
               
                <div class="table-responsive">
                  <table class="table table-hover table-bordered ">
                    <thead>
                        <tr>
                            <th>Employee Code</th>
                            <th>Name</th>
                            <th>13th Month</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                            <tr>
                                <td>{{$employee->emp_code}}</td>
                                <td>{{$employee->name}}</td>
                                <td>0</td>
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
@endsection

