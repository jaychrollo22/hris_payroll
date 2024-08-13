<?php

namespace App\Exports;

use App\User;
use App\Company;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return User::query()->with('employee_info','user_allowed_company','user_allowed_location','user_allowed_project')
                            ->where('role','Admin')
                            ->whereHas('employee_info',function($q){
                                $q->where('status','Active');
                            });
    }

    public function headings(): array
    {
        return [
            'User ID',
            'Employee Name',
            'Email',
            'Role',
            'Companies',
            'Locations',
            'Projects',
            'employees_view',
            'employees_edit',
            'employees_add',
            'employees_export',
            'employees_export_hr',
            'employees_rate',
            'reports_leave',
            'reports_overtime',
            'reports_wfh',
            'reports_ob',
            'reports_dtr',
            'reports_ppr',
            'biometrics_per_employee',
            'biometrics_per_location',
            'biometrics_per_location_hik',
            'biometrics_per_company',
            'biometrics_per_seabased',
            'biometrics_per_hik_vision',
            'biometrics_sync',
            'settings_view',
            'settings_add',
            'settings_edit',
            'settings_delete',
            'masterfiles_companies',
            'masterfiles_departments',
            'masterfiles_loan_types',
            'masterfiles_employee_leave_credits',
            'masterfiles_employee_leave_earned',
            'masterfiles_employee_allowances',
            'masterfiles_employee_loans',
            'timekeeping_dashboard',
            'masterfiles_projects',
            'masterfiles_vessels',
            'masterfiles_locations',
            'masterfiles_early_cutoffs',
            'masterfiles_cost_centers',
            'masterfiles_performance_plan_periods'
        ];
    }

    public function map($user): array
    {
        $employee_name = $user->employee_info ? $user->employee_info->first_name . ' ' . $user->employee_info->last_name : "";
        $user_allowed_company = '';
        $user_allowed_location = '';
        $user_allowed_project = '';

        if($user->user_allowed_company){
            if($user->user_allowed_company->company_ids){
                $company_ids = json_decode($user->user_allowed_company->company_ids);
                $company_names = [];
                foreach($company_ids as $company){
                    $company_info = Company::where('id',$company)->first();
                    if($company_info){
                        $company_names[] = $company_info->company_code;
                    }
                }
                $user_allowed_company = implode (", ", $company_names);

            }
        }
        if($user->user_allowed_location){
            if($user->user_allowed_location->location_ids){
                $location_ids = json_decode($user->user_allowed_location->location_ids);
                $user_allowed_location = implode (", ", $location_ids);

            }
        }
        if($user->user_allowed_project){
            if($user->user_allowed_project->project_ids){
                $project_ids = json_decode($user->user_allowed_project->project_ids);
                $user_allowed_project = implode (", ", $project_ids);

            }
        }


        return [
            $user->id,
            $employee_name,
            $user->email,
            $user->role,
            $user_allowed_company,
            $user_allowed_location,
            $user_allowed_project,
            $user->user_privilege->employees_view,
            $user->user_privilege->employees_edit,
            $user->user_privilege->employees_add,
            $user->user_privilege->employees_export,
            $user->user_privilege->employees_export_hr,
            $user->user_privilege->employees_rate,
            $user->user_privilege->reports_leave,
            $user->user_privilege->reports_overtime,
            $user->user_privilege->reports_wfh,
            $user->user_privilege->reports_ob,
            $user->user_privilege->reports_dtr,
            $user->user_privilege->reports_ppr,
            $user->user_privilege->biometrics_per_employee,
            $user->user_privilege->biometrics_per_location,
            $user->user_privilege->biometrics_per_location_hik,
            $user->user_privilege->biometrics_per_company,
            $user->user_privilege->biometrics_per_seabased,
            $user->user_privilege->biometrics_per_hik_vision,
            $user->user_privilege->biometrics_sync,
            $user->user_privilege->settings_view,
            $user->user_privilege->settings_add,
            $user->user_privilege->settings_edit,
            $user->user_privilege->settings_delete,
            $user->user_privilege->masterfiles_companies,
            $user->user_privilege->masterfiles_departments,
            $user->user_privilege->masterfiles_loan_types,
            $user->user_privilege->masterfiles_employee_leave_credits,
            $user->user_privilege->masterfiles_employee_leave_earned,
            $user->user_privilege->masterfiles_employee_allowances,
            $user->user_privilege->masterfiles_employee_loans,
            $user->user_privilege->timekeeping_dashboard,
            $user->user_privilege->masterfiles_projects,
            $user->user_privilege->masterfiles_vessels,
            $user->user_privilege->masterfiles_locations,
            $user->user_privilege->masterfiles_early_cutoffs,
            $user->user_privilege->masterfiles_cost_centers,
            $user->user_privilege->masterfiles_performance_plan_periods,
        ];
    }


}
