@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                    <h5>Enter Password to Access Payslip</h5>
                    @if ($errors->any())
                        <div style="color: red;">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                
                    <form action="{{ route('payslip.password.validate') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <label for="password">Password:</label>
                                <input type="hidden" id="password" name="payslip_id" required value="{{ $payrollRegister->id }}" class="form-control">
                                <input type="password" id="password" name="password" required class="form-control">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="form-control form-control-sm btn btn-primary mb-2 btn-sm mt-4">Submit</button>
                            </div>
                        </div>
                    </form>
              </div>
            </div>
          </div>
        
        </div>
    </div>
</div>

@endsection
