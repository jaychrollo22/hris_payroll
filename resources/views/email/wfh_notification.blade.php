For approval work from home application requested by {{$details['user_info']['name']}}
<br>
Applied Date: {{ $details['details']['applied_date'] }} <br>
Start Time: {{ date('H:i', strtotime($details['details']['date_from'])) }} <br>
End Time: {{ date('H:i', strtotime($details['details']['date_to'])) }} <br>
Remarks: {{$details['details']['remarks']}} <br>
Last Update: {{appFormatFullDate($details['details']['updated_at'])}} <br>
Link: <a href="https://hris.wsystem.online/for-work-from-home">Click Here</a> <br>