@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Handbooks</h4>
                <p class="card-description">
                  <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#newHandbook">
                    <i class="ti-plus btn-icon-prepend"></i>                                                    
                    Add new version
                  </button>
                </p>
                <div class="table-responsive">
                  <table class="table table-hover table-bordered">
                    <thead>
                      <tr>
                        <th>Date Created</th>
                        <th>Attachment</th> 
                        <th>Created By</th>
                        <th>Remarks</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
      
          @include('handbooks.new_handbook')
        </div>
    </div>
</div>
@endsection
