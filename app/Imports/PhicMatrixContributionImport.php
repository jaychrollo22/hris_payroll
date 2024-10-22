<?php

namespace App\Imports;

use App\PhicMatrixContribution;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PhicMatrixContributionImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new PhicMatrixContribution([
            'id'  => $row['id'],
        ]);
    }
}