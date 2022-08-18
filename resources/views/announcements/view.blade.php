@extends('layouts.header')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Announcements</h4>
                <p class="card-description">
                    <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#newAnnouncement">
                      <i class="ti-plus btn-icon-prepend"></i>                                                    
                      New Announcement
                    </button>
                  </p>
             
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                        <tr>
                          <th>Title</th>
                          <th>Attachment</th> 
                          <th>Created By</th>
                          <th>Expiration</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                    <tbody>
                        @foreach($announcements as $announcement)
                            <tr>
                                <td>{{$announcement->announcement_title}}</td>
                                <td><a href="{{url($announcement->attachment)}}" target='_blank'>Attachment</a></td> 
                                <td>{{$announcement->user->name}}</td>
                                <td>@if($announcement->expired){{date('M d, Y',strtotime($announcement->expired))}}@endif</td>
                                <td>
                                    <a href="delete-announcement/{{$announcement->id}}">
                                        <button  title='DELETE' onclick="return confirm('Are you sure you want to delete this announcement?')" class="btn btn-rounded btn-danger btn-icon">
                                            <i class="ti-trash"></i>
                                        </button>
                                    </a>
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
@include('announcements.new_announcement')
@endsection
