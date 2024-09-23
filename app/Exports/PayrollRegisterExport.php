<?php

namespace App\Exports;

use App\Models\PayrollRegister;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PayrollRegisterExport implements FromCollection, WithHeadings
{
    /**
     * Export the data as a collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return PayrollRegister::all();
    }

    /**
     * Define the headings for the export.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID', 'USER ID', 'BANK ACCOUNT', 'NAME', 'POSITION', 'EMPLOYMENT STATUS', 'COMPANY', 'DEPARTMENT', 
            'PROJECT', 'DATE HIRED', 'CUT FROM', 'CUT TO', 'MONTHLY BASIC PAY', 'DAILY RATE', 'BASIC PAY',
            'ABSENCES AMOUNT', 'LATES AMOUNT', 'UNDERTIME AMOUNT', 'SALARY ADJUSTMENT', 'OVERTIME PAY', 
            'MEAL ALLOWANCE', 'SALARY ALLOWANCE', 'OUT OF TOWN ALLOWANCE', 'INCENTIVES ALLOWANCE', 
            'RELOCATION ALLOWANCE', 'DISCRETIONARY ALLOWANCE', 'TRANSPORT ALLOWANCE', 'LOAD ALLOWANCE', 
            'GROSSPAY', 'TOTAL TAXABLE', 'MINIMUM WAGE', 'WITHOLDING TAX', 'SSS REG EE', 'SSS MPF EE', 
            'PHIC EE', 'HMDF EE', 'HDMF SALARY LOAN', 'HDMF CALAMITY LOAN', 'SSS SALARY LOAN', 
            'SSS CALAMITY LOAN', 'SALARY DEDUCTION TAXABLE', 'SALARY DEDUCTION NON-TAXABLE', 'COMPANY LOAN', 
            'OMHAS', 'COOP CBU', 'COOP REGULAR LOAN', 'COOP MESCCO', 'PETTY CASH MESCCO', 'OTHERS', 
            'TOTAL DEDUCTION', 'NETPAY', 'SSS REG ER', 'SSS MPF ER', 'SSS EC', 'PHIC ER', 'HDMF ER', 
            'BANK', 'STATUS', 'REMARKS', 'STATUS LAST PAYROLL', 'SSS NO.', 'PHILHEALTH NO.', 'PAG-IBIG NO.', 
            'TIN NO.', 'BIR TAGGING', 'SEPT 15', 'SEPT 30', 'ACCUMULATED', 'NUMBER', 'CREATED AT', 'UPDATED AT'
        ];
    }
}
