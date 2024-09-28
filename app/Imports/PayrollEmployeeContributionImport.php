<?php

namespace App\Imports;

use App\PayrollEmployeeContribution;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PayrollEmployeeContributionImport implements ToModel,WithHeadingRow
{/**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new PayrollEmployeeContribution([
            'id'  => $row['id'],
        ]);
    }
}
