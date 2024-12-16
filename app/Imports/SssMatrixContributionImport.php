<?php

namespace App\Imports;

use App\SssMatrixContribution;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SssMatrixContributionImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new SssMatrixContribution([
            'id'  => $row['id'],
        ]);
    }
}