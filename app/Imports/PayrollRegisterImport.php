<?php

namespace App\Imports;

use App\Models\PayrollRegister;
use Maatwebsite\Excel\Files\ExcelFile;
use Illuminate\Support\Facades\DB;

class PayrollRegisterImport extends ExcelFile
{
    protected $payroll_period_id;

    public function setPayrollPeriodId($payroll_period_id)
    {
        $this->payroll_period_id = $payroll_period_id;
    }

    public function import()
    {
        $data = $this->get()->toArray(); // Fetch rows from Excel file

        // Process each row and insert into the database
        foreach ($data as $row) {
            PayrollRegister::create([
                'user_id' => $row['user_id'],
                'bank_account' => $row['bank_account'],
                'name' => $row['name'],
                'position' => $row['position'],
                'employment_status' => $row['employment_status'],
                'company' => $row['company'],
                'department' => $row['department'],
                'project' => $row['project'],
                'date_hired' => $row['date_hired'],
                'cut_from' => $row['cut_from'],
                'cut_to' => $row['cut_to'],
                'monthly_basic_pay' => $row['monthly_basic_pay'],
                'daily_rate' => $row['daily_rate'],
                'basic_pay' => $row['basic_pay'],
                'absences_amount' => $row['absences_amount'],
                'lates_amount' => $row['lates_amount'],
                'undertime_amount' => $row['undertime_amount'],
                'salary_adjustment' => $row['salary_adjustment'],
                'overtime_pay' => $row['overtime_pay'],
                'meal_allowance' => $row['meal_allowance'],
                'salary_allowance' => $row['salary_allowance'],
                'out_of_town_allowance' => $row['out_of_town_allowance'],
                'incentives_allowance' => $row['incentives_allowance'],
                'relocation_allowance' => $row['relocation_allowance'],
                'discretionary_allowance' => $row['discretionary_allowance'],
                'transport_allowance' => $row['transport_allowance'],
                'load_allowance' => $row['load_allowance'],
                'grosspay' => $row['grosspay'],
                'payroll_period_id' => $this->payroll_period_id, // Set payroll period ID here
            ]);
        }
    }
}
