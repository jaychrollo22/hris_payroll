For approval leave application requested by {{$details['user_info']['name']}}
<br>
Start Date: {{$details['details']['date_from']}} <br>
End Date: {{$details['details']['date_to']}} <br>
@if($details['details']['half_day'] == 1)
Day Count: Halfday <br>
@else
Day Count: {{get_count_days_leave($details['details']['schedule'],$details['details']['date_from'],$details['details']['date_to'])}} <br>
@endif
Leave Type: {{$details['details']['leave']['leave_type']}} <br>
@if($details['details']['withpay'] == 1)   
With Pay: Yes<br>
@else
With Pay: No<br>
@endif  
Remarks: {{$details['details']['reason']}} <br>
Last Update: {{appFormatFullDate($details['details']['updated_at'])}} <br>
Link: <a href="https://hris.wsystem.online/for-leave">Click Here</a> <br>
