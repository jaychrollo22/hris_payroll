@extends('layouts.header')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
          <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Total Days Work (Days)</h4>
                <div class="media">
                  <i class="ti-layout-media-overlay icon-md text-info d-flex align-self-center mr-3"></i>
                  <div class="media-body">
                    <h2 class="card-text">0</h2>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">No In/Out (Days)</h4>
                <div class="media">
                  <i class="ti-pencil icon-md text-info d-flex align-self-center mr-3"></i>
                  <div class="media-body">
                    <h2 class="card-text">0</h2>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Total Absent (Days)</h4>
                <div class="media">
                  <i class="ti-close icon-md text-info d-flex align-self-center mr-3"></i>
                  <div class="media-body">
                    <h2 class="card-text">0</h2>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Approved Overtime (Hrs)</h4>
                <div class="media">
                  <i class="ti-check-box icon-md text-info d-flex align-self-center mr-3"></i>
                  <div class="media-body">
                    <h2 class="card-text">0</h2>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Attendances</h4>
                <p class="card-description">
                  <div class=row>
                    <div class='col-md-3'>
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label text-right">From</label>
                        <div class="col-sm-8">
                          <input type="date" class="form-control" />
                        </div>
                      </div>
                    </div>
                    <div class='col-md-3'>
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label text-right">To</label>
                        <div class="col-sm-8">
                          <input type="date" class="form-control" />
                        </div>
                      </div>
                    </div>
                    <div class='col-md-3'>
                      <button type="submit" class="btn btn-primary mb-2">Submit</button>
                    </div>
                  </div>
                </p>
                <div class="table-responsive">
                  <table class="table table-hover table-bordered">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Work </th>
                        <th>Lates </th>
                        <th>Undertime</th>
                        <th>Overtime</th>
                        <th>Approved Overtime</th>
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
        
        </div>
    </div>
</div>
@endsection
