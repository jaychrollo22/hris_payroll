For approval Performance Plan requested by {{$details['user_info']['name']}}
<br>
Calendar Year: {{ $details['details']['calendar_year'] }} <br>
Review Date: {{ date('Y-m-d', strtotime($details['details']['review_date'])) }} <br>
Period: {{ $details['details']['period'] }} <br>
Last Update: {{appFormatFullDate($details['details']['updated_at'])}} <br>
Link: <a href="https://hris.pivi.com.ph/for-performance-plan">Click Here</a> <br>