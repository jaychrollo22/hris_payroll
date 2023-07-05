<?php

namespace App\Exports;

use App\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;


class EmployeesExport implements FromQuery, WithHeadings, WithMapping
{

    public function __construct($company,$department,$allowed_companies,$access_rate)
    {
        $this->company = $company;
        $this->department = $department;
        $this->allowed_companies = $allowed_companies;
        $this->access_rate = $access_rate;
    }

    public function query()
    {
        $company = $this->company;
        $department = $this->department;
        $allowed_companies = json_decode($this->allowed_companies);
        return Employee::query()->select('user_id',
                                            'employee_number',
                                            'original_date_hired',
                                            'work_description',
                                            'rate',
                                            'location',
                                            'position',
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
                                            'tax_number',
                                            'company_id'
                                        )
                                        ->with('company')
                                        ->when($company,function($q) use($company){
                                            $q->where('company_id',$company);
                                        })
                                        ->when($department,function($q) use($department){
                                            $q->where('department_id',$department);
                                        })
                                        ->whereIn('company_id',$allowed_companies)
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
            'COMPANY',
            'BRANCH',
            'JOB TITLE',
            'WORK DESCRIPTION',
            'RATE',
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
        $company = $employee->company ? $employee->company->company_name : "";

        if($this->access_rate == 'Yes'){

            try{
                $rate = $employee->rate ? (float) Crypt::decryptString($employee->rate) : ""; 
            }
            catch(Exception $e) {
                $rate = "";
            }
            
        }else{
            $rate = "";
        }
        
        
        return [
            $employee->employee_number,
            $employee->user_id,
            $company,
            $employee->location,
            $employee->position,
            $employee->work_description,
            $rate,
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
