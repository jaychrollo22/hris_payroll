<?php

namespace App\Imports;

use App\PagibigMatrixContribution;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PagibigMatrixContributionImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new PagibigMatrixContribution([
            'id'  => $row['id'],
        ]);
    }
}