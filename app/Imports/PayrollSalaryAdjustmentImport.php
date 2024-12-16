<?php

namespace App\Imports;

use App\PayrollSalaryAdjustment;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PayrollSalaryAdjustmentImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new PayrollSalaryAdjustment([
            'id'  => $row['id'],
        ]);
    }
}