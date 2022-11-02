<ul class="nav  nav-pills nav-pills-custom" id="pills-tab-custom" >
    <li class="nav-item ">
        <a class="nav-link @if($header == 'Payroll')active @endif"   href="{{url('pay-reg')}}" >
        Payroll
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if($header == 'Timekeeping')active @endif"   href="{{url('timekeeping')}}"  >
        Timekeeping
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if($header == 'Month-Benefit')active @endif"   href="{{url('month-benefit')}}"  >
        13th Month Pay
        </a>
    </li>
</ul>