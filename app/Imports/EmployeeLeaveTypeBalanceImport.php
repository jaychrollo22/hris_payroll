<?php

namespace App\Imports;

use App\EmployeeLeaveTypeBalance;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeLeaveTypeBalanceImport implements ToModel,WithHeadingRow
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