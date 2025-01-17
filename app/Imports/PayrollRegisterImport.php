<?php

namespace App\Imports;

use App\Models\PayrollRegister;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PayrollRegisterImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new PayrollRegister([
            'id'  => $row['payroll_register_id'],
        ]);
    }
}
