<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use App\Employee;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class EmployeeAssociateExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct($company,$department,$allowed_companies,$access_rate,$allowed_locations,$allowed_projects,$status)
    {
        $this->company = $company;
        $this->department = $department;
        $this->allowed_companies = $allowed_companies;
        $this->allowed_locations = $allowed_locations;
        $this->allowed_projects = $allowed_projects;
        $this->access_rate = $access_rate;
        $this->status = $status;
    }

    public function query()
    {
        $company = $this->company;
        $department = $this->department;
        $status = $this->status;
        $allowed_companies = json_decode($this->allowed_companies);
        $allowed_locations = json_decode($this->allowed_locations);
        $allowed_projects = json_decode($this->allowed_projects);
        return Employee::query()->with('company','department', 'payment_info', 'schedule_info' , 'ScheduleData', 'immediate_sup_data', 'user_info','classification_info')
                                ->when($company,function($q) use($company){
                                    $q->where('company_id',$company);
                                })
                                ->when($department,function($q) use($department){
                                    $q->where('department_id',$department);
                                })
                                ->whereIn('company_id',$allowed_companies)
                                ->when($allowed_locations,function($q) use($allowed_locations){
                                    $q->whereIn('location',$allowed_locations);
                                })
                                ->when($allowed_projects,function($q) use($allowed_projects){
                                    $q->whereIn('project',$allowed_projects);
                                })
                                ->when($status,function($q) use($status){
                                    $q->where('status',$status);
                                });
    }

    public function headings(): array
    {
        return [

            'USER ID',
            'EMPLOYEE ID NUMBER',
            'EMPLOYEE NAME',
            'PAYROLL STATUS',
            'COMPANY',
            'BRANCH',
            'JOB TITLE',
            'WORK DESCRIPTION',
            'RATE',
            'REST DAY 1',
            'REST DAY 2',
            
            'FIRST EXPECTED TIME IN MONDAY',
            'FIRST EXPECTED TIME IN TUESDAY',
            'FIRST EXPECTED TIME IN WEDNESDAY',
            'FIRST EXPECTED TIME IN THURSDAY',
            'FIRST EXPECTED TIME IN FRIDAY',
            'FIRST EXPECTED TIME IN SATURDAY',
            'FIRST EXPECTED TIME IN SUNDAY',
            
            'SHIFT DESCRIPTION MONDAY',
            'SHIFT DESCRIPTION TUESDAY',
            'SHIFT DESCRIPTION WEDNESDAY',
            'SHIFT DESCRIPTION THURSDAY',
            'SHIFT DESCRIPTION FRIDAY',
            'SHIFT DESCRIPTION SATURDAY',
            'SHIFT DESCRIPTION SUNDAY',
            
            'SHIFT COMPUTATION MONDAY',
            'SHIFT COMPUTATION TUESDAY',
            'SHIFT COMPUTATION WEDNESDAY',
            'SHIFT COMPUTATION THURSDAY',
            'SHIFT COMPUTATION FRIDAY',
            'SHIFT COMPUTATION SATURDAY',
            'SHIFT COMPUTATION SUNDAY',
           
            'COMPUTE SSS',
            'SCHEDULE COMPUTATION SSS',
            'OVERRIDE REFERENCE AMOUNT SSS',
            'USE FIXED REFERENCE AMOUNT SSS',
            'EE SHARE SSS OVERRIDE',
            'ER SHARE SSS OVERRIDE',
            'EE SHARE SSS ADVANCE',
            'ER SHARE SSS ADVANCE',
            'SHARE SSS ADVANCE FSC',

            'COMPUTE HDMF',
            'SCHEDULE COMPUTATION HDMF',
            'OVERRIDE REFERENCE AMOUNT HDMF',
            'USE FIXED REFERENCE AMOUNT HDMF',
            'EE SHARE HDMF OVERRIDE',
            'ER SHARE HDMF OVERRIDE',
            'EE SHARE HDMF ADVANCE',
            'ER SHARE HDMF ADVANCE',
            'SHARE HDMF ADVANCE FSC',

            'COMPUTE PHILHEALTH',
            'SCHEDULE COMPUTATION PHILHEALTH',
            'OVERRIDE REFERENCE AMOUNT PHILHEALTH',
            'USE FIXED REFERENCE AMOUNT PHILHEALTH',
            'EE SHARE PHILHEALTH OVERRIDE',
            'ER SHARE PHILHEALTH OVERRIDE',
            'EE SHARE PHILHEALTH ADVANCE',
            'ER SHARE PHILHEALTH ADVANCE',
            'SHARE PHILHEALTH ADVANCE FSC',

            'COMPUTE TAX',
            'TAX APPLICATION',
            'TAX DESCRIPTION',
            'OVERRIDE REFERENCE AMOUNT TAX',
            'USE FIXED REFERENCE AMOUNT TAX',
            'PERSONAL EXEMPTION DESCRIPTION',
            'ADDITIONAL EXEMPTION DESCRIPTION',
            'TAX ADVANCE',
            'TAX ADVANCE FSC',
        ];
    }

    public function map($employee): array
    {
        $employee_name = $employee->last_name .', '. $employee->first_name . ' ' . $employee->middle_name;  
        $company = $employee->company ? $employee->company->company_name : "";

        $branch = '';
        $rate = '';
        if($this->access_rate == 'yes'){

            try{
                $rate = $employee->rate ? Crypt::decryptString($employee->rate) : ""; 
            }
            catch(Exception $e) {
                $rate = "";
            }
            catch(DecryptException $e) {
                $rate = "";
            }
            
        }else{
            $rate = "";
        }

        $rest_day_1 = '';
        $rest_day_2 = '';

        $first_expected_time_in_monday = '';
        $first_expected_time_in_tuesday = '';
        $first_expected_time_in_wednesday = '';
        $first_expected_time_in_thursday = '';
        $first_expected_time_in_friday = '';
        $first_expected_time_in_saturday = '';
        $first_expected_time_in_sunday = '';

        $shift_description_monday = '';
        $shift_description_tuesday = '';
        $shift_description_wednesday = '';
        $shift_description_thursday = '';
        $shift_description_friday = '';
        $shift_description_saturday = '';
        $shift_description_sunday = '';

        $shift_computation_monday = '';
        $shift_computation_tuesday = '';
        $shift_computation_wednesday = '';
        $shift_computation_thursday = '';
        $shift_computation_friday = '';
        $shift_computation_saturday = '';
        $shift_computation_sunday = '';

        $compute_sss = '1';
        $schedule_computation_sss = 'Last Cut-Off';
        $override_referrence_amount_sss = '';
        $used_fix_referrence_amount_sss = '';
        $ee_share_sss_override = '';
        $er_share_sss_override = '';
        $ee_share_sss_advance = '';
        $er_share_sss_advance = '';
        $share_sss_advance_fsc = '';

        $compute_hdmf = '1';
        $schedule_computation_hdmf = 'Last Cut-Off';
        $override_referrence_amount_hdmf = '5000';
        $used_fix_referrence_amount_hdmf = '';
        $ee_share_hdmf_override = '';
        $er_share_hdmf_override = '';
        $ee_share_hdmf_advance = '';
        $er_share_hdmf_advance = '';
        $share_hdmf_advance_fsc = '';

        $compute_philhealth = '1';
        $schedule_computation_philhealth = 'Last Cut-Off';
        $override_referrence_amount_philhealth = '';

        if($employee->work_description == 'Monthly'){
            $override_referrence_amount_philhealth = $rate;
        }else if($employee->work_description == 'Non-Monthly'){
            if($rate){
                $override_referrence_amount_philhealth = $rate / 12;
            }   
        }

        $used_fix_referrence_amount_philhealth = '';
        $ee_share_philhealth_override = '';
        $er_share_philhealth_override = '';
        $ee_share_philhealth_advance = '';
        $er_share_philhealth_advance = '';
        $share_philhealth_advance_fsc = '';

        $compute_tax = '1';
        $tax_application = ''; // Tax Application in Employee Database
        $tax_description = 'Semi-Monthly';
        $override_referrence_amount_tax = '';
        $used_fix_referrence_amount_tax = '';
        $personal_exemption_description = 'Not Applicable';
        $additional_exemption_description = 'Not Applicable';
        $tax_advance = '';
        $tax_advance_fsc = '';
     

        if($employee->ScheduleData){
            foreach($employee->ScheduleData as $item){
                if($item->name == 'Monday'){
                    $first_expected_time_in_monday = $item->time_in_from;
                    // $shift_computation_monday = gmdate('H:i', floor($item->working_hours * 3600));
                }
                if($item->name == 'Tuesday'){
                    $first_expected_time_in_tuesday = $item->time_in_from;
                    // $shift_computation_tuesday = gmdate('H:i', floor($item->working_hours * 3600));
                }
                if($item->name == 'Wednesday'){
                    $first_expected_time_in_wednesday = $item->time_in_from;
                    // $shift_computation_wednesday = gmdate('H:i', floor($item->working_hours * 3600));
                }
                if($item->name == 'Thursday'){
                    $first_expected_time_in_thursday = $item->time_in_from;
                    // $shift_computation_thursday = gmdate('H:i', floor($item->working_hours * 3600));
                }
                if($item->name == 'Friday'){
                    $first_expected_time_in_friday = $item->time_in_from;
                    // $shift_computation_friday = gmdate('H:i', floor($item->working_hours * 3600));
                }
                if($item->name == 'Saturday'){
                    $first_expected_time_in_saturday = $item->time_in_from;
                    // $shift_computation_saturday = gmdate('H:i', floor($item->working_hours * 3600));
                }
                if($item->name == 'Sunday'){
                    $first_expected_time_in_sunday = $item->time_in_from;
                    // $shift_computation_sunday = gmdate('H:i', floor($item->working_hours * 3600));
                }
            }
        }
        

        if(empty($first_expected_time_in_monday)){
            if(empty($rest_day_1)){
                $rest_day_1 = 'Monday';
            }else{
                $rest_day_2 = 'Monday';
            }
        }
        if(empty($first_expected_time_in_tuesday)){
            if(empty($rest_day_1)){
                $rest_day_1 = 'Tuesday';
            }else{
                $rest_day_2 = 'Tuesday';
            }
        }
        if(empty($first_expected_time_in_wednesday)){
            if(empty($rest_day_1)){
                $rest_day_1 = 'Wednesday';
            }else{
                $rest_day_2 = 'Wednesday';
            }
        }
        if(empty($first_expected_time_in_thursday)){
            if(empty($rest_day_1)){
                $rest_day_1 = 'Thursday';
            }else{
                $rest_day_2 = 'Thursday';
            }
        }
        if(empty($first_expected_time_in_friday)){
            if(empty($rest_day_1)){
                $rest_day_1 = 'Friday';
            }else{
                $rest_day_2 = 'Friday';
            }
        }
        if(empty($first_expected_time_in_saturday)){
            if(empty($rest_day_1)){
                $rest_day_1 = 'Saturday';
            }else{
                $rest_day_2 = 'Saturday';
            }
        }
        if(empty($first_expected_time_in_sunday)){
            if(empty($rest_day_1)){
                $rest_day_1 = 'Sunday';
            }else{
                $rest_day_2 = 'Sunday';
            }
        }

        if(empty($rest_day_1)){
            $rest_day_1 = 'Not Applicable';
        }
        if(empty($rest_day_2)){
            $rest_day_2 = 'Not Applicable';
        }

        if($employee->schedule_info){
            $schedule_info = $employee->schedule_info->schedule_name;

            if (str_contains($schedule_info, 'Compressed')) {

                
                //RestDay 2
                if($rest_day_1 == 'Monday' || $rest_day_2 == 'Monday'){
                    $shift_computation_monday = 'Regular Shift';
                }else{
                    $shift_computation_monday = 'Comp-Work';
                }

                if($rest_day_1 == 'Tuesday' || $rest_day_2 == 'Tuesday'){
                    $shift_computation_tuesday = 'Regular Shift';
                }else{
                    $shift_computation_tuesday = 'Comp-Work';
                }

                if($rest_day_1 == 'Wednesay' || $rest_day_2 == 'Wednesay'){
                    $shift_computation_wednesday = 'Regular Shift';
                }else{
                    $shift_computation_wednesday = 'Comp-Work';
                }

                if($rest_day_1 == 'Thursday' || $rest_day_2 == 'Thursday'){
                    $shift_computation_thursday = 'Regular Shift';
                }else{
                    $shift_computation_thursday = 'Comp-Work';
                }

                if($rest_day_1 == 'Friday' || $rest_day_2 == 'Friday'){
                    $shift_computation_friday = 'Regular Shift';
                }else{
                    $shift_computation_friday = 'Comp-Work';
                }

                if($rest_day_1 == 'Saturday' || $rest_day_2 == 'Saturday'){
                    $shift_computation_saturday = 'Regular Shift';
                }else{
                    $shift_computation_saturday = 'Comp-Work';
                }

                if($rest_day_1 == 'Sunday' || $rest_day_2 == 'Sunday'){
                    $shift_computation_sunday = 'Regular Shift';
                }else{
                    $shift_computation_sunday = 'Comp-Work';
                }

            }else{
                if($employee->schedule_info->is_flexi == 1){
                    

                    //RestDay 1
                    if($rest_day_1 == 'Monday' || $rest_day_2 == 'Monday'){
                        $shift_computation_monday = 'Regular Shift';
                    }else{
                        $shift_computation_monday = 'Flexi-Time';
                    }

                    if($rest_day_1 == 'Tuesday' || $rest_day_2 == 'Tuesday'){
                        $shift_computation_tuesday = 'Regular Shift';
                    }else{
                        $shift_computation_tuesday = 'Flexi-Time';
                    }

                    if($rest_day_1 == 'Wednesay' || $rest_day_2 == 'Wednesay'){
                        $shift_computation_wednesday = 'Regular Shift';
                    }else{
                        $shift_computation_wednesday = 'Flexi-Time';
                    }

                    if($rest_day_1 == 'Thursday' || $rest_day_2 == 'Thursday'){
                        $shift_computation_thursday = 'Regular Shift';
                    }else{
                        $shift_computation_thursday = 'Flexi-Time';
                    }

                    if($rest_day_1 == 'Friday' ||  $rest_day_2 == 'Friday'){
                        $shift_computation_friday = 'Regular Shift';
                    }else{
                        $shift_computation_friday = 'Flexi-Time';
                    }

                    if($rest_day_1 == 'Saturday' || $rest_day_2 == 'Saturday'){
                        $shift_computation_saturday = 'Regular Shift';
                    }else{
                        $shift_computation_saturday = 'Flexi-Time';
                    }

                    if($rest_day_1 == 'Sunday' || $rest_day_2 == 'Sunday'){
                        $shift_computation_sunday = 'Regular Shift';
                    }else{
                        $shift_computation_sunday = 'Flexi-Time';
                    }
                    
                }else{

                    $shift_computation_monday = 'Regular Shift';
                    $shift_computation_tuesday = 'Regular Shift';
                    $shift_computation_wednesday = 'Regular Shift';
                    $shift_computation_thursday = 'Regular Shift';
                    $shift_computation_friday = 'Regular Shift';
                    $shift_computation_saturday = 'Regular Shift';
                    $shift_computation_sunday = 'Regular Shift'; 
                }
            }
        }

        if($employee->schedule_info){
            if($employee->schedule_info->is_flexi == 1){
                $branch = 'BRANCH 2';
            }
            else if($employee->schedule_info->id == 9){
                $branch = 'BRANCH 3';
            }else{
                $branch = 'BRANCH 1';
            }

            $shift_description_monday = 'Regular Shift';
            $shift_description_tuesday = 'Regular Shift';
            $shift_description_wednesday = 'Regular Shift';
            $shift_description_thursday = 'Regular Shift';
            $shift_description_friday = 'Regular Shift';
            $shift_description_saturday = 'Regular Shift';
            $shift_description_sunday = 'Regular Shift';
        }

        return [
            $employee->employee_number,
            $employee->user_id,
            $employee_name,
            $employee->status,
            $company,
            $branch,
            $employee->position,
            $employee->work_description,
            $rate,
            $rest_day_1,
            $rest_day_2,

            $first_expected_time_in_monday,
            $first_expected_time_in_tuesday,
            $first_expected_time_in_wednesday,
            $first_expected_time_in_thursday,
            $first_expected_time_in_friday,
            $first_expected_time_in_saturday,
            $first_expected_time_in_sunday,

            $shift_description_monday,
            $shift_description_tuesday,
            $shift_description_wednesday,
            $shift_description_thursday,
            $shift_description_friday,
            $shift_description_saturday,
            $shift_description_sunday,

            $shift_computation_monday,
            $shift_computation_tuesday,
            $shift_computation_wednesday,
            $shift_computation_thursday,
            $shift_computation_friday,
            $shift_computation_saturday,
            $shift_computation_sunday,

            $compute_sss,
            $schedule_computation_sss,
            $override_referrence_amount_sss,
            $used_fix_referrence_amount_sss,
            $ee_share_sss_override,
            $er_share_sss_override,
            $ee_share_sss_advance,
            $er_share_sss_advance,
            $share_sss_advance_fsc,

            $compute_hdmf,
            $schedule_computation_hdmf,
            $override_referrence_amount_hdmf,
            $used_fix_referrence_amount_hdmf,
            $ee_share_hdmf_override,
            $er_share_hdmf_override,
            $ee_share_hdmf_advance,
            $er_share_hdmf_advance,
            $share_hdmf_advance_fsc,

            $compute_philhealth,
            $schedule_computation_philhealth,
            $override_referrence_amount_philhealth,
            $used_fix_referrence_amount_philhealth,
            $ee_share_philhealth_override,
            $er_share_philhealth_override,
            $ee_share_philhealth_advance,
            $er_share_philhealth_advance,
            $share_philhealth_advance_fsc,

            $compute_tax,
            $employee->tax_application,
            $tax_description,
            $override_referrence_amount_tax,
            $used_fix_referrence_amount_tax,
            $personal_exemption_description,
            $additional_exemption_description,
            $tax_advance,
            $tax_advance_fsc,

        ];

    }
}
