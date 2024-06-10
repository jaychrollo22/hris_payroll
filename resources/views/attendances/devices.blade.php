<!DOCTYPE html>
<html>
<head>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
</head>
<body>

<h2>Biometrics</h2>

<table>
  <tr>
    <th>Biometrics</th>
    <th>Last Attendance</th>
    <th></th>
  </tr>
  @foreach($devices as $device)
    @if($device->attendance)
        <tr>
            <td>{{$device->ip_address}}</td>
            <td>{{$device->attendance->datetime}}</td>
            <td>@if(date('Y-m-d',strtotime($device->attendance->datetime)) != date('Y-m-d')) <span style='background-color:red;color:white;'>Need to re-sync</span>@else <span style='background-color:green;color:white;'>No Error</span> @endif</td>
        </tr>
    @endif
  @endforeach
</table>

</body>
</html>

