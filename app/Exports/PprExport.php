<?php

namespace App\Exports;

use App\Employee;
use App\EmployeePerformanceEvaluation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PprExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct($company,$status,$calendar_date, $allowed_companies)
    {
        $this->company = $company;
        $this->status = $status;
        $this->calendar_date = $calendar_date;
        $this->allowed_companies = $allowed_companies;
    }

    public function query(){
        
        $company = $this->company;
        $status = $this->status;
        $calendar_date = $this->calendar_date;
        $allowed_companies = json_decode($this->allowed_companies);

        return Employee::with('employee_performance_evaluations.approver.approver_info')
                                ->with(['employee_performance_evaluations'=>function($q) use($calendar_date,$status){
                                    return $q->select('user_id','calendar_year','review_date','period','status','level','created_at')
                                                ->when($status,function($w) use($status){
                                                    $w->where('status',$status);
                                                })
                                                ->when($calendar_date,function($w) use($calendar_date){
                                                    $w->where('calendar_year',$calendar_date);
                                                });
                                },
                                'department',
                                'company',
                                'classification_info',
                                'level_info'
                            ])
                            ->whereIn('company_id',$allowed_companies)
                            ->where('status','Active');
    }

    public function headings(): array
    {
        return [
            'EMPLOYEE NUMBER',
            'EMPLOYEE',
            'POSITION',
            'COMPANY',
            'DEPARTMENT',
            'CLASSIFICATION',
            'LEVEL',
            'DATE FILED',
            'CALENDAR DATE',
            'PPR PERIOD',
            'APPROVER 1',
            'APPROVER 1 STATUS',
            'APPROVER 2',
            'APPROVER 2 STATUS',
            'REVIEW DATE',
            'STATUS',
        ];
    }

    public function map($employee): array
    {
        $employee_name = $employee->last_name .', '. $employee->first_name . ' ' . $employee->middle_name;
        $company = $employee->company ? $employee->company->company_name : "";
        $department = $employee->department ? $employee->department->name : "";
        $classification_info = $employee->classification_info ? $employee->classification_info->name : "";
        $level_info = $employee->level_info ? $employee->level_info->name : "";

        $ppr = count($employee->employee_performance_evaluations) > 0 ? $employee->employee_performance_evaluations[0] : '';

        
        $date_filed = $ppr ? $ppr['created_at'] : "Not yet filed";
        $calendar_year = $ppr ? $ppr['calendar_year'] : "";
        $period = $ppr ? $ppr['period'] : "";
        $review_date = $ppr ? $ppr['review_date'] : "";
        $status = $ppr ? $ppr['status'] : "";
        $level = $ppr ?  $ppr['level'] : "";
        
        $approver1 = '';
        $approver1_status = '';
        $approver2 = '';
        $approver2_status = '';
        if($ppr){
            if(count($ppr['approver']) > 0){
                $counter = 1;

                // $approver1 = json_encode($ppr['approver'][0]['approver_info']);

                foreach($ppr['approver'] as $approver){
                    
                    if($counter == 1){
                        $approver1 = $approver['approver_info'] ? $approver['approver_info']['name'] : "";
                        // $approver1 = $approver['approver_info'] ? $approver['approver_info']['name'] : "";

                        if($level >= $approver['level']){ // Level 1
                            if ($level == 0 && $status == 'Declined'){
                                $approver1_status = 'Declined';
                            }elseif ($level == 1 && $status == 'Declined'){
                                $approver1_status = 'Approved';
                            }else{
                                $approver1_status = 'Approved';
                            }
                        }else{
                            if ($status == 'Declined'){
                                $approver1_status = 'Declined';
                            }elseif ($status == 'Approved'){
                                $approver1_status = 'Approved';
                            }else{
                                $approver1_status = 'Pending';
                            }
                        }
                    }else{ //Level 0
                        $approver2 = $approver['approver_info'] ? $approver['approver_info']['name'] : "";
                        if($level >= $approver['level']){
                            if ($level == 0 && $status == 'Declined'){
                                $approver2_status = 'Declined';
                            }elseif ($level == 1 && $status == 'Declined'){
                                $approver2_status = 'Approved';
                            }else{
                                $approver2_status = 'Approved';
                            }
                        }else{
                            if ($status == 'Declined'){
                                $approver2_status = 'Declined';
                            }elseif ($status == 'Approved'){
                                $approver2_status = 'Approved';
                            }else{
                                $approver2_status = 'Pending';
                            }
                        }
                    }
                    

                    $counter++;
                }
            }
        }


        return [
            $employee->employee_number,
            $employee_name,
            $employee->position,
            $company,
            $department,
            $classification_info,
            $level_info,
            $date_filed,
            $calendar_year,
            $period,
            $approver1,
            $approver1_status,
            $approver2,
            $approver2_status,
            $review_date,
            $status,
        ];

    }


    
}
