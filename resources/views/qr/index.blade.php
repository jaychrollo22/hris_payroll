@extends('layouts.app_qr')

@section('content')
<div class="calling-card">
    <div class="company-logo">
        <img src="/company_images/{{$employee->company->company_code}}.png" alt="{{$employee->company->company_code}}">
        
    </div>
   
    <div class="details">
       
        <div class="name">
            <span class="first-name">{{$employee->first_name}}</span>
            <span class="last-name">{{$employee->last_name}}</span>
        </div>
        <hr>
        <div class="company">{{$employee->company->company_name}}</div>
        <div class="position">{{$employee->position}}</div>
        {{-- <div class="contact-number">{{$employee->personal_number}}</div> --}}
        @if($employee->status == 'Active')
            <div class="status-active">{{$employee->status}}</div>
        @else
            <div class="status-inactive">{{$employee->status}}</div>
        @endif
        
    </div>
</div>
@endsection