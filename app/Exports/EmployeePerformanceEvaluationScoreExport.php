<?php

namespace App\Exports;

use App\EmployeePerformanceEvaluationScore;
use App\EmployeePerformanceEvaluation;
use App\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeePerformanceEvaluationScoreExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{

    use Exportable;

    public function __construct($company,$status,$period_ppr,$calendar_date, $allowed_companies)
    {
        $this->company = $company;
        $this->status = $status;
        $this->period_ppr = $period_ppr;
        $this->calendar_date = $calendar_date;
        $this->allowed_companies = $allowed_companies;
    }


    public function query()
    {

        $company = $this->company;
        $period_ppr = $this->period_ppr;
        $status = $this->status;
        $calendar_date = $this->calendar_date;
        $allowed_companies = json_decode($this->allowed_companies);

        $ppr_ratings = EmployeePerformanceEvaluation::with('employee.company','employee.department','approver','customized_ppr_approver','ppr_score')
                                                ->when($company,function($q) use($company){
                                                    $q->whereHas('employee',function($w) use($company){
                                                        $w->where('company_id',$company);
                                                    });
                                                })
                                                ->where(function($q) use($allowed_companies){
                                                    $q->whereHas('employee',function($w) use($allowed_companies){
                                                        $w->whereIn('company_id',$allowed_companies);
                                                    });
                                                })
                                                ->when($period_ppr,function($q) use($period_ppr){
                                                    $q->where('period',$period_ppr);
                                                })
                                                // ->when($status,function($q) use($status){
                                                //     $q->where('status',$status);
                                                // })
                                                ->when($calendar_date,function($q) use($calendar_date){
                                                    $q->where('calendar_year',$calendar_date);
                                                });

        if($status){
            if($status == 'Pending Self Ratings' || $status == 'Ongoing Self Ratings' || $status == 'For Approval' || $status == 'For Acceptance' || $status == 'Accepted' || $status == 'Summary of Ratings' || $status == 'Completed'){
                
                if($status == 'Pending Self Ratings'){
                    $ppr_ratings = $ppr_ratings->whereDoesntHave('ppr_score')->where('status','Approved');
                }elseif($status == 'Ongoing Self Ratings'){
                    $ppr_ratings = $ppr_ratings->whereHas('ppr_score',function($q){
                        $q->whereNull('self_assessment_is_posted');
                    });
                }
                elseif($status == 'Summary of Ratings'){
                    $ppr_ratings = $ppr_ratings->whereHas('ppr_score',function($q){
                        $q->where('status','Accepted')->whereNull('summary_of_ratings_is_posted');
                    });
                }
                elseif($status == 'Completed'){
                    $ppr_ratings = $ppr_ratings->whereHas('ppr_score',function($q){
                        $q->where('status','Accepted')->where('summary_of_ratings_is_posted','1');
                    });
                }
                else{
                    $ppr_ratings = $ppr_ratings->whereHas('ppr_score',function($q) use($status){
                        $q->where('status',$status);
                    });
                }
            }else{
                $ppr_ratings->where('status',$status);
            }

            return $ppr_ratings;
            
        }
    }

    public function headings(): array
    {
        return [
            [
                'Employee Information', '', '', '','','',
                'Review Details', '', '', 
                'Rating Details', '','','','',
                'Performance and Development Summary Report Narratives', '','','','','','',
                'Approver Details or Status', '','','',
            ],
            [

                'Group/ Business Unit', 'Department/ Unit', 'Employee Name', 'Position Title', 'Employee Number', 'Date Hired/Project Start and End Date',
                'Calendar Year', 'Review Date', 'Review Period',
                'BSC Actual (Ave) Rating (1-5)', 'BSC (Total) WTD Rating (>/<90)' , 'Competency Actual (Ave) Rating (1-4)' , 'Competency (Total) WTD Rating (>/<10)' , 'Rating Description',
                'Ratees Comments', 'Agree/ Acknowledge', 'Areas of Strength', 'Develpmental Needs', 'Training & Developmental Plans','Summary of Raters Comments',
                'Approver 1 Name', 'Approver 1 Action', 'Approver 2 (If, applicable) Name', 'Approver 2 Action'
            ]
        ];
    }

    public function map($ppr): array
    {
        $company = $ppr->employee ? $ppr->employee->company->company_name : "";
        $department = "";
        if($ppr->employee){
            if($ppr->employee->department){
                $department = $ppr->employee->department->name;
            }
        }

        $employee_name = $ppr->employee ? $ppr->employee->first_name . ' ' . $ppr->employee->last_name : "";
        $position = $ppr->employee ? $ppr->employee->position : "";
        $employee_number = $ppr->employee ? $ppr->employee->employee_number : "";
        $original_date_hired = $ppr->employee ? $ppr->employee->original_date_hired : "";
        
        $calendar_year = $ppr->calendar_year;
        $review_date = $ppr->review_date;
        $review_period = $ppr->period;


        $bsc_actual_avg_rating = '';
        $bsc_total_wtd_rating = '';
        $competency_actual_avg_rating = '';
        $competency_total_wtd_rating = '';
        $rating_description = '';
        $total_wtd_score = '';

        $ratees_comments = '';
        $acceptance_status = '';
        $agree_acknowledge = '';
        $areas_of_strength = '';
        $developmental_needs = '';
        $areas_for_enhancements = '';
        $training_developmental_plans = '';
        $summary_of_ratee_comments_recommendations = '';


        if($ppr->ppr_score){
            $bsc_actual_avg_rating = $ppr->ppr_score->manager_assessment_bsc_actual_score;
            $bsc_total_wtd_rating = $ppr->ppr_score->manager_assessment_bsc_wtd_rating;
            $competency_actual_avg_rating = $ppr->ppr_score->manager_assessment_competency_actual_score;
            $competency_total_wtd_rating = $ppr->ppr_score->manager_assessment_competency_wtd_rating;
            $total_wtd_score = $bsc_total_wtd_rating + $competency_total_wtd_rating;
            $rating_description = summarOfRatingScale($total_wtd_score);



            $ratees_comments = $ppr->ppr_score->ratees_comments;
            $acceptance_status = $ppr->ppr_score->user_acceptance_status;
            
            $areas_of_strength = $ppr->ppr_score->areas_of_strength;
            $developmental_needs = $ppr->ppr_score->developmental_needs;
            $areas_for_enhancements = $ppr->ppr_score->areas_for_enhancements;
            $training_developmental_plans = $ppr->ppr_score->training_developmental_plans;
            $summary_of_ratee_comments_recommendations = $ppr->ppr_score->summary_of_ratee_comments_recommendations;

        }

        $approver1_name = '';
        $approver1_status = '';

        $approver2_name = '';
        $approver2_status = '';

        if($ppr->customized_ppr_approver){
            if($ppr->customized_ppr_approver->first_approver_info){
                $approver1_name = $ppr->customized_ppr_approver->first_approver_info->name;
                if($ppr->level == 0){
                    if ($ppr->status == 'Declined'){
                        $approver1_status = 'Declined';
                    }else{
                        $approver1_status = 'For Review';
                    }
                }else{
                    $approver1_status = 'Approved';
                }
            }

            if($ppr->customized_ppr_approver->first_approver_info){
                $approver2_name = $ppr->customized_ppr_approver->first_approver_info->name;
                if($ppr->level <= 1){
                    if ($ppr->status == 'Declined'){
                        $approver2_status = 'Declined';
                    }else{
                        $approver2_status = 'For Review';
                    }
                }else{
                    $approver2_status = 'Approved';
                }
            }
        }else{
            $x = 1;
            foreach($ppr->approver as $approver){
                if($x == 1){
                    $approver1_name = $approver->approver_info ? $approver->approver_info->name : "";
                    if($ppr->level >= $approver->level){
                        if ($ppr->level == 0 && $ppr->status == 'Declined'){
                            $approver1_status = "Declined";
                        }else{
                            $approver1_status = "Approved";
                        }
                    }else{
                        if ($ppr->status == 'Declined'){
                            $approver1_status = "Declined";
                        }elseif ($ppr->status == 'Draft'){
                            $approver1_status = "Draft";
                        }else{
                            $approver1_status = "For Review";
                        }
                    }
                    $x++;
                }else{
                    $approver2_name = $approver->approver_info ? $approver->approver_info->name : "";
                    if($ppr->level >= $approver->level){
                        if ($ppr->level == 0 && $ppr->status == 'Declined'){
                            $approver2_status = "Declined";
                        }else{
                            $approver2_status = "Approved";
                        }
                    }else{
                        if ($ppr->status == 'Declined'){
                            $approver2_status = "Declined";
                        }elseif ($ppr->status == 'Draft'){
                            $approver2_status = "Draft";
                        }else{
                            $approver2_status = "For Review";
                        }
                    }
                    $x++;
                }
                
            }
        }


        return [
            $company,
            $department,
            $employee_name,
            $position,
            $employee_number,
            $original_date_hired,
            $calendar_year,
            $review_date,
            $review_period,
            $bsc_actual_avg_rating,
            $bsc_total_wtd_rating,
            $competency_actual_avg_rating,
            $competency_total_wtd_rating,
            $rating_description,
            $ratees_comments,
            $acceptance_status,
            $areas_of_strength,
            $developmental_needs,
            $training_developmental_plans,
            $summary_of_ratee_comments_recommendations,
            $approver1_name,
            $approver1_status,
            $approver2_name,
            $approver2_status
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('G1:I1');
        $sheet->mergeCells('J1:N1');
        $sheet->mergeCells('O1:U1');
        $sheet->mergeCells('V1:Y1');

        $sheet->getStyle('A1:Y1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A2:Y2')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);

        return [];
    }

}
