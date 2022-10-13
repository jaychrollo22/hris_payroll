<div class="modal fade" id="view_attendance{{$att->period_from}}" tabindex="-1" role="dialog" aria-labelledby="view_attendancedata" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="view_attendancedata">View Attendances {{date('M d, Y',strtotime($att->period_from))}} - {{date('M d, Y',strtotime($att->period_to))}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
       
          <div class="modal-body">
            <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Employee Code</th>
                            <th>Location</th>
                            <th>Total Days Absent</th>
                            <th>Total Days Work</th>
                            <th>Total Lates</th>
                            <th>Total Adjustment Hours</th>
                            <th>Total Overtime Regular</th>
                            <th>ND</th>
                            <th>ND OT</th>
                            <th>REG HOLIDAY</th>
                            <th>REG HOLIDAY ND</th>
                            <th>OVERTIME REG HOLIDAY ND</th>
                            <th>SH</th>
                            <th>SH OT</th>
                            <th>SH ND</th>
                            <th>SH ND OT</th>
                            <th>RD</th>
                            <th>RD OT</th>
                            <th>RD ND</th>
                            <th>RD ND OT</th>
                            <th>RD ND RH</th>
                            <th>RD ND OT RH</th>
                            <th>RD SH</th>
                            <th>RD SH OT</th>
                            <th>RD SH ND</th>
                            <th>RD SH ND OT</th>
                            <th>TOTAL UNDERTIME</th>
                            <th>SICK LEAVE</th>
                            <th>VACATION LEAVE</th>
                            <th>SICK LEAVE NO PAY</th>
                            <th>VACATION LEAVE NO PAY</th>
                            <th>WORK FROM HOME</th>
                            <th>OFFICIAL BUSINESS</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach($attendances->where('period_from',$att->period_from) as $attendance)
                            <tr>
                                <td>{{$attendance->employee}}</td>
                                <td>{{$attendance->company}}</td>
                                <td>{{$attendance->badge_no}}</td>
                                <td>{{$attendance->location}}</td>
                                <td>{{$attendance->tot_days_absent}}</td>
                                <td>{{$attendance->tot_days_work}}</td>
                                <td>{{$attendance->tot_lates}}</td>
                                <td>{{$attendance->total_adjstmenthrs}}</td>
                                <td>{{$attendance->tot_overtime_reg}}</td>
                                <td>{{$attendance->night_differential}}</td>
                                <td>{{$attendance->night_differential_ot}}</td>
                                <td>{{$attendance->tot_regholiday}}</td>
                                <td>{{$attendance->tot_overtime_regholiday}}</td>
                                <td>{{$attendance->tot_overtime_regholiday_nightdiff}}</td>
                                <td>{{$attendance->tot_spholiday}}</td>
                                <td>{{$attendance->tot_overtime_spholiday}}<</td>
                                <td>{{$attendance->tot_spholiday_nightdiff}}</td>
                                <td>{{$attendance->tot_overtime_spholiday_nightdiff}}</td>
                                <td>{{$attendance->tot_rest}}</td>
                                <td>{{$attendance->tot_overtime_rest}}</td>
                                <td>{{$attendance->night_differential_rest}}</td>
                                <td>{{$attendance->night_differential_ot_rest}}</td>
                                <td>{{$attendance->night_differential_rest_regholiday}}</td>
                                <td>{{$attendance->tot_overtime_night_diff_rest_regholiday}}</td>
                                <td>{{$attendance->tot_sprestholiday}}</td>
                                <td>{{$attendance->tot_overtime_sprestholiday}}</td>
                                <td>{{$attendance->tot_sprestholiday_nightdiff}}</td>
                                <td>{{$attendance->tot_overtime_sprestholiday_nightdiff}}</td>
                                <td>{{$attendance->total_undertime}}</td>
                                <td>{{$attendance->sick_leave}}</td>
                                <td>{{$attendance->vacation_leave}}</td>
                                <td>{{$attendance->sick_leave_nopay}}</td>
                                <td>{{$attendance->vacation_leave_nopay}}</td>
                                <td>{{$attendance->workfromhome}}</td>
                                <td>{{$attendance->offbusiness}}</td>
                            </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
      </div>
    </div>
  </div>