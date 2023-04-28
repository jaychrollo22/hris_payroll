@extends('layouts.header')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Holidays</h4>
                <p class="card-description">
                    @if (checkUserPrivilege('settings_add',auth()->user()->id) == 'yes')
                    <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#newHoliday">
                      <i class="ti-plus btn-icon-prepend"></i>                                                    
                      New Holiday
                    </button>
                    @endif
                  </p>
             
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                        <tr>
                            
                            <th>Holiday Date</th>
                            <th>Holiday Name</th>
                            <th>Holiday Type</th>
                            <th>Location</th>
                            <th>Holiday Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($holidays as $holiday)
                        <tr>
                            
                            <td>{{date('Y.').date('m.d',strtotime($holiday->holiday_date))}}</td>
                            <td>{{$holiday->holiday_name}}</td>
                            <td>{{$holiday->holiday_type}}</td>
                            <td>{{$holiday->location}}</td>
                            <td>
                                @if (checkUserPrivilege('settings_edit',auth()->user()->id) == 'yes')
                                <button type="button" class="btn btn-info btn-rounded btn-icon" href="#edit_holiday{{$holiday->id}}" data-toggle="modal" title='EDIT'>
                                    <i class="ti-pencil-alt"></i>
                                </button>
                                @endif
                                @if (checkUserPrivilege('settings_delete',auth()->user()->id) == 'yes')
                                <a href="delete-holiday/{{$holiday->id}}">
                                    <button  title='DELETE' onclick="return confirm('Are you sure you want to delete this holiday?')" class="btn btn-rounded btn-danger btn-icon">
                                        <i class="ti-trash"></i>
                                    </button>
                                </a>
                                @endif
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
@include('holidays.new_holiday')
@foreach($holidays as $holiday)
@include('holidays.edit_holiday')
@endforeach
@endsection
