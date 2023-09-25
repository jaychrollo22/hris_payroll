<?php

namespace App\Imports;

use App\HikAttLog;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HikAttLogAttendanceImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new HikAttLog([
            'id'  => $row['id'],
        ]);
    }
}
