<?php

namespace App\Exports;

use App\IclockTransation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendancePerLocationExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct($location,$from,$to)
    {
        $this->location = $location;
        $this->from = $from;
        $this->to = $to;
    }

    public function query()
    {
        return IclockTransation::whereBetween('punch_time', [$this->from . " 00:00:01", $this->to." 23:59:59"])
                                    ->where('terminal_id', $this->location)
                                    ->whereIn('punch_state', array(0, 1))
                                    ->with('emp_data', 'location')
                                    ->orderBy('emp_code', 'desc')
                                    ->orderBy('punch_time', 'asc');
    }

    public function headings(): array
    {
        return [
            'Full Name',
            'Emp Code',
            'Date',
            'Time',
            'Type',
            'Location',
        ];
    }

    public function map($att_per_location): array
    {
        $type = $att_per_location->punch_state == 0 ? "Time In" : "Time Out";
        $full_name = $att_per_location->emp_data ? $att_per_location->emp_data->first_name . ' ' . $att_per_location->emp_data->last_name : "";
        return [
            $full_name,
            $att_per_location->emp_code,
            date('Y-m-d',strtotime($att_per_location->punch_time)),
            date('h:i A',strtotime($att_per_location->punch_time)),
            $type,
            $att_per_location->location->alias,
        ];
    }

}
