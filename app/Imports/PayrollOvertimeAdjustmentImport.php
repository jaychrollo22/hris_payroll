<?php

namespace App\Imports;

use App\PayrollOvertimeAdjustment;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PayrollOvertimeAdjustmentImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new PayrollOvertimeAdjustment([
            'id'  => $row['id'],
        ]);
    }
}