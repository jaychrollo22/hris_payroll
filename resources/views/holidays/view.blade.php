@extends('layouts.header')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Holidays</h4>
                <p class="card-description">
                    <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#newHoliday">
                      <i class="ti-plus btn-icon-prepend"></i>                                                    
                      New Holiday
                    </button>
                  </p>
             
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                        <tr>
                            
                            <th>Holiday Date</th>
                            <th>Holiday Name</th>
                            <th>Holiday Type</th>
                            <th>Holiday Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($holidays as $holiday)
                        <tr>
                            
                            <td>{{date('Y.').date('m.d',strtotime($holiday->holiday_date))}}</td>
                            <td>{{$holiday->holiday_name}}</td>
                            <td>{{$holiday->holiday_type}}</td>
                            <td>
                                @if($holiday->status == "Permanent") 
                                    {{$holiday->status}}
                                @else
                                    <button type="button" class="btn btn-info btn-rounded btn-icon" href="#edit_holiday{{$holiday->id}}" data-toggle="modal" title='EDIT'>
                                        <i class="ti-pencil-alt"></i>
                                    </button>
                                    <a href="delete-holiday/{{$holiday->id}}">
                                        <button  title='DELETE' onclick="return confirm('Are you sure you want to delete this holiday?')" class="btn btn-rounded btn-danger btn-icon">
                                            <i class="ti-trash"></i>
                                        </button>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @include('holidays.edit_holiday')
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
@include('holidays.new_holiday')
@endsection
