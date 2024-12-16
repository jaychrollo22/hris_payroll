@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <p class="card-description">
                    <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#newPagibigMatrixContribution">
                      <i class="ti-plus btn-icon-prepend"></i>                                                    
                      Add new Pagibig Matrix Contributions
                    </button>

                    <button type="button" class="btn btn-outline-primary btn-icon-text" data-toggle="modal" data-target="#importPayrollPagibigMatrixContribution">
                      <i class="ti-plus btn-icon-prepend"></i>                                                    
                      Import Pagibig Matrix Contributions
                    </button>
                </p>

                <h4 class="card-title">Pagibig Matrix Contribution <a href="/pagibig-matrix-contributions-export" title="Export" class="btn btn-outline-primary btn-icon-text btn-sm text-center"><i class="ti-arrow-down btn-icon-prepend"></i></a></h4>

                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                      <tr>
                        <th>Range of Compensation(PHP)</th>
                        <th>Employee Share(EE)</th> 
                        <th>Employer Share(ER)</th>
                        <th>Total Contribution</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($contributions as $contribution)
                          <tr class='cursor-pointer'>
                            <td>
                              @if ($contribution->is_no_limit)
                                {{ $contribution->min_salary .' + ' }}
                              @else
                                {{ $contribution->min_salary . ' - '. $contribution->max_salary }} 
                              @endif
                            </td>
                            <td>{{ $contribution->employee_share_ee }}</td>
                            <td>{{ $contribution->employer_share_er }}</td>
                            <td>{{ $contribution->total_contribution }}</td>
                            <td>
                              @if (checkUserPrivilege('settings_edit',auth()->user()->id) == 'yes')
                              <button type="button" class="btn btn-info btn-rounded btn-icon" href="#editPagibigMatrixContribution{{$contribution->id}}" data-toggle="modal" title='EDIT'>
                                  <i class="ti-pencil-alt"></i>
                              </button>
                              @endif
                              @if (checkUserPrivilege('settings_delete',auth()->user()->id) == 'yes')
                              <a href="delete-pagibig-matrix-contribution/{{$contribution->id}}">
                                  <button  title='DELETE' onclick="return confirm('Are you sure you want to delete this Pagibig Matrix Contributions?')" class="btn btn-rounded btn-danger btn-icon">
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
          @include('pagibig_matrix_contributions.form_add')
          @foreach($contributions as $contribution)
          @include('pagibig_matrix_contributions.form_edit')
          @endforeach
          @include('pagibig_matrix_contributions.import')
        </div>
    </div>
</div>
@endsection
