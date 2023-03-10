<?php

namespace App\Exports;

use App\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


class EmployeesExport implements FromQuery, WithHeadings, WithMapping
{

    public function __construct($company,$department)
    {
        $this->company = $company;
        $this->department = $department;
    }

    public function query()
    {
        $company = $this->company;
        $department = $this->department;
        return Employee::query()->select('user_id',
                                            'employee_number',
                                            'original_date_hired',
                                            'first_name',
                                            'last_name',
                                            'middle_name',
                                            'name_suffix',
                                            'gender',
                                            'personal_number',
                                            'personal_email',
                                            'permanent_address',
                                            'bank_name',
                                            'bank_account_number',
                                            'sss_number',
                                            'hdmf_number',
                                            'phil_number',
                                            'tax_number'
                                        )
                                        ->with('company')
                                        ->when($company,function($q) use($company){
                                            $q->where('company_id',$company);
                                        })
                                        ->when($department,function($q) use($department){
                                            $q->where('department_id',$department);
                                        })
                                        ->where('status','Active');
    }


    // /**
    // * @return \Illuminate\Support\Collection
    // */
    // public function collection()
    // {
    //     return Employee::select('user_id','original_date_hired','first_name','last_name','middle_name','name_suffix','gender','personal_number','personal_email','permanent_address','bank_name','bank_account_number','sss_number','hdmf_number','phil_number','tax_number')
    //                     ->where('status','Active')
    //                     ->get();
    // }

    public function headings(): array
    {
        return [
            'USER ID',
            'EMPLOYEE ID NUMBER',
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

    public function map($employee): array
    {
        return [
            $employee->employee_number,
            $employee->user_id,
            date('d/m/Y',strtotime($employee->original_date_hired)),
            $employee->first_name,
            $employee->last_name,
            $employee->middle_name,
            $employee->name_suffix,
            $employee->gender,
            $employee->personal_number,
            $employee->personal_email,
            $employee->permanent_address,
            $employee->bank_name,
            $employee->bank_account_number,
            $employee->sss_number,
            $employee->hdmf_number,
            $employee->phil_number,
            $employee->tax_number,
        ];
        
    }
}
