@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Work from Home</h4>
                <p class="card-description">
                  <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#wfh">
                    <i class="ti-plus btn-icon-prepend"></i>                                                    
                    Work from Home
                  </button>
                </p>
                <div class="table-responsive">
                  <table class="table table-hover table-bordered">
                    <thead>
                      <tr>
                        <th>WFH Date</th>
                        <th>Date Filed</th>
                        <th>Task</th>
                        <th>Remarks</th>
                        <th>Reason</th>
                        <th>Attachments</th>
                        <th>Status </th>
                        <th>Action </th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            @include('forms.wfh.apply_wfh')
          </div>
        </div>
    </div>
</div>
@endsection
