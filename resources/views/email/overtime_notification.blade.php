For approval overtime application requested by {{$details['user_info']['name']}}
<br>
Date: {{$details['details']['ot_date']}} <br>
Start Time: {{$details['details']['start_time']}} <br>
End Time: {{$details['details']['end_time']}} <br>
OT Requested (Hrs): {{intval((strtotime($details['details']['end_time'])-strtotime($details['details']['start_time']))/60/60)}} <br>
Remarks: {{$details['details']['remarks']}} <br>
Last Update: {{appFormatFullDate($details['details']['updated_at'])}} <br>
Link: <a href="https://hris.wsystem.online/for-overtime">Click Here</a> <br>