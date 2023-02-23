<?php

namespace App\Exports;

use App\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class EmployeesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Employee::select('user_id','original_date_hired','first_name','last_name','middle_name','name_suffix','gender','personal_number','personal_email','permanent_address','bank_name','bank_account_number','sss_number','hdmf_number','phil_number','tax_number')
                        ->where('status','Active')
                        ->get();
    }

    public function headings(): array
    {
        return [
            'User ID',
            'DATE HIRED',
            'FIRST NAME',
            'LAST NAME',
            'MIDDLE NAME',
            'EXTENSION NAME',
            'GENDER',
            'MOBILE PHONE',
            'EMAIL ADDRESS',
            'REGISTERED ADDRESS',
            'BANK NAME',
            'BANK ACCOUNT NUMBER',
            'ID NUMBER SSS',
            'ID NUMBER HDMF',
            'ID NUMBER PHILHEALTH',
            'ID NUMBER TIN',
        ];
    }
}
