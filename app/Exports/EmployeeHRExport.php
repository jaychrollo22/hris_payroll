<?php

namespace App\Exports;

use App\Employee;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeeHRExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct($company,$department,$allowed_companies)
    {
        $this->company = $company;
        $this->department = $department;
        $this->allowed_companies = $allowed_companies;
    }

    public function query()
    {
        $company = $this->company;
        $department = $this->department;
        $allowed_companies = json_decode($this->allowed_companies);
        return Employee::query()->with('company','department', 'payment_info', 'ScheduleData', 'immediate_sup_data', 'user_info','classification_info')
                                ->when($company,function($q) use($company){
                                    $q->where('company_id',$company);
                                })
                                ->when($department,function($q) use($department){
                                    $q->where('department_id',$department);
                                })
                                ->whereIn('company_id',$allowed_companies)
                                ->where('status','Active');
    }

    public function headings(): array
    {
        return [
            'Employee Number',
            'User ID',
            'First Name',
            'Middle Name',
            'Last Name',
            'Name Suffix',
            'Classification ID',
            'Classification',
            'Department ID',
            'Department',
            'Position',
            'Level',
            'Birth Date',
            'Birth Place',
            'Gender',
            'Marital Status',
            'Permanent Address',
            'Present Address',
            'Personal Number',
            'Philhealth',
            'SSS',
            'TIN',
            'HDMF',
            'Bank Name',
            'Bank Account Number',
            'Date Hired',
            'Personal Email',
            'Immediate Superior',
            'Schedule ID',
            'Location',
            'Project',
            'Religion',
            'Company ID',
            'Company',
            
        ];
    }

    public function map($employee): array
    {
        $company = $employee->company ? $employee->company->company_name : "";
        $department = $employee->department ? $employee->department->name : "";
        $classification_info = $employee->classification_info ? $employee->classification_info->name : "";
    
        return [
            $employee->employee_number,
            $employee->user_id,
            $employee->first_name,
            $employee->middle_name,
            $employee->last_name,
            $employee->name_suffix,
            $employee->classification,
            $classification_info,
            $employee->department_id,
            $department,
            $employee->position,
            $employee->level,
            date('d/m/Y',strtotime($employee->birth_date)),
            $employee->birth_place,
            $employee->gender,
            $employee->marital_status,
            $employee->permanent_address,
            $employee->present_address,
            $employee->personal_number,
            $employee->phil_number,
            $employee->sss_number,
            $employee->tax_number,
            $employee->hdmf_number,
            $employee->bank_name,
            $employee->bank_account_number,
            date('d/m/Y',strtotime($employee->original_date_hired)),
            $employee->personal_email,
            $employee->immediate_sup,
            $employee->schedule_id,
            $employee->location,
            $employee->project,
            $employee->religion,
            $employee->company_id,
            $company
        ];
        
    }

}
