<?php

namespace App\Exports;

use App\PhicMatrixContribution;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


class PhicMatrixContributionExport implements FromQuery, WithHeadings, WithMapping
{
   
    public function query()
    {
        // Fetch SSS Matrix Contributions
        $contributions = PhicMatrixContribution::whereNull('deleted_at');

        return $contributions;
    }

    public function headings(): array
    {
        return [
            'MINIMUM SALARY',
            'MAXIMUM SALARY',
            'EMPLOYEE SHARE(EE)',
            'EMPLOYER SHARE(ER)',
            'TOTAL CONTRIBUTION',
            'NO LIMIT'
        ];
    }

    public function map($contribution): array
    {
        return [
            $contribution->min_salary,
            $contribution->max_salary,
            $contribution->employee_share_ee,
            $contribution->employer_share_er,
            $contribution->total_contribution,
            $contribution->is_no_limit
        ];
    }
}
