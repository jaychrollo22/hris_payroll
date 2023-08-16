<?php

namespace App\Imports;


use App\SeabasedAttendance;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeSeabasedAttendanceImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new SeabasedAttendance([
            'id'  => $row['id'],
        ]);
    }
}

