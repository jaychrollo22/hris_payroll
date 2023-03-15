<?php

namespace App\Exports;

use App\EmployeeLeave;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class EmployeeLeaveExport implements FromView
{

    public function __construct($company,$from,$to,$status)
    {
        $this->company = $company;
        $this->from = $from;
        $this->to = $to;
        $this->status = $status;
    }

    public function view(): View
    {
        $company = $this->company;
        return view('exports.leave_export', [
            'leaves' => EmployeeLeave::with('user','leave','employee')
                            ->whereDate('date_from','>=',$this->from)
                            ->whereDate('date_from','<=',$this->to)
                            ->whereHas('employee',function($q) use($company){
                                $q->where('company_id',$company);
                            })
                            ->where('status','Approved')
                            ->orderBy('user_id','ASC')
                            ->get()
        ]);
    }

    // public function query()
    // {
    //     $company = $this->company;
    //     return EmployeeLeave::query()->with('user','leave','employee')
    //                             ->whereDate('approved_date','>=',$this->from)
    //                             ->whereDate('approved_date','<=',$this->to)
    //                             ->whereHas('employee',function($q) use($company){
    //                                 $q->where('company_id',$company);
    //                             })
    //                             ->where('status','Approved');
    // }



    // public function headings(): array
    // {
    //     return [
    //         'User ID',
    //         'Employee Name',
    //         'Form Type',
    //         'With Pay',
    //         'From',
    //         'To',
    //         'Approved Date',
    //         'Status',
    //         'Reason/Remarks',
    //     ];
    // }

    // public function map($employee_leave): array
    // {
    //     return [
    //         $employee_leave->user->id,
    //         $employee_leave->user->name,
    //         $employee_leave->leave->leave_type,
    //         $employee_leave->withpay == 1 ? 'With-Pay' : 'Without-Pay',
    //         date('d/m/Y',strtotime($employee_leave->date_to)),
    //         date('d/m/Y',strtotime($employee_leave->date_from)),
    //         date('d/m/Y',strtotime($employee_leave->approved_date)),
    //         $employee_leave->status,
    //         $employee_leave->reason
    //     ];
    // }

}
