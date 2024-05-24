For approval DTR Correction application requested by {{$details['user_info']['name']}}
<br>
Applied Date: {{ $details['details']['dtr_date'] }} <br>
Start Time: {{ date('H:i', strtotime($details['details']['time_in'])) }} <br>
End Time: {{ date('H:i', strtotime($details['details']['time_out'])) }} <br>
Correction: {{$details['details']['correction']}} <br>
Remarks: {{$details['details']['remarks']}} <br>
Last Update: {{appFormatFullDate($details['details']['updated_at'])}} <br>
Link: <a href="https://hris.wsystem.online/for-dtr-correction">Click Here</a> <br>