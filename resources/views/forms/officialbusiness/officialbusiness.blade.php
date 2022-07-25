@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Official Business</h4>
                <p class="card-description">
                  <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#ob">
                    <i class="ti-plus btn-icon-prepend"></i>                                                    
                    Apply
                  </button>
                </p>
                <div class="table-responsive">
                  <table class="table table-hover table-bordered">
                    <thead>
                      <tr>
                        <th>Date Filed</th>
                        <th>Official Business</th>
                        <th>BO Date</th>
                        <th>Time In and Out</th>
                        <th>Destination</th>
                        <th>Person/Company to see</th>
                        <th>Purpose</th>
                        <th>Attachment </th>
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
            @include('forms.officialbusiness.apply_ob')
          </div>
        </div>
    </div>
</div>
@endsection
