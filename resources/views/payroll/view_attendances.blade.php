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
                                <td>{{number_format($attendance->tot_days_absent,2)}}</td>
                                <td>{{number_format($attendance->tot_days_work,2)}}</td>
                                <td>{{number_format($attendance->tot_lates,2)}}</td>
                                <td>{{number_format($attendance->total_adjstmenthrs,2)}}</td>
                                <td>{{number_format($attendance->tot_overtime_reg,2)}}</td>
                                <td>{{number_format($attendance->night_differential,2)}}</td>
                                <td>{{number_format($attendance->night_differential_ot,2)}}</td>
                                <td>{{number_format($attendance->tot_regholiday,2)}}</td>
                                <td>{{number_format($attendance->tot_overtime_regholiday,2)}}</td>
                                <td>{{number_format($attendance->tot_overtime_regholiday_nightdiff,2)}}</td>
                                <td>{{number_format($attendance->tot_spholiday,2)}}</td>
                                <td>{{number_format($attendance->tot_overtime_spholiday,2)}}</td>
                                <td>{{number_format($attendance->tot_spholiday_nightdiff,2)}}</td>
                                <td>{{number_format($attendance->tot_overtime_spholiday_nightdiff,2)}}</td>
                                <td>{{number_format($attendance->tot_rest,2)}}</td>
                                <td>{{number_format($attendance->tot_overtime_rest,2)}}</td>
                                <td>{{number_format($attendance->night_differential_rest,2)}}</td>
                                <td>{{number_format($attendance->night_differential_ot_rest,2)}}</td>
                                <td>{{number_format($attendance->night_differential_rest_regholiday,2)}}</td>
                                <td>{{number_format($attendance->tot_overtime_night_diff_rest_regholiday,2)}}</td>
                                <td>{{number_format($attendance->tot_sprestholiday,2)}}</td>
                                <td>{{number_format($attendance->tot_overtime_sprestholiday,2)}}</td>
                                <td>{{number_format($attendance->tot_sprestholiday_nightdiff,2)}}</td>
                                <td>{{number_format($attendance->tot_overtime_sprestholiday_nightdiff,2)}}</td>
                                <td>{{number_format($attendance->total_undertime,2)}}</td>
                                <td>{{number_format($attendance->sick_leave,2)}}</td>
                                <td>{{number_format($attendance->vacation_leave,2)}}</td>
                                <td>{{number_format($attendance->sick_leave_nopay,2)}}</td>
                                <td>{{number_format($attendance->vacation_leave_nopay,2)}}</td>
                                <td>{{number_format($attendance->workfromhome,2)}}</td>
                                <td>{{number_format($attendance->offbusiness,2)}}</td>
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