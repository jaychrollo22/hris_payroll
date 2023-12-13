<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


use App\EmployeePerformanceEvaluation;
use App\Mail\PerformancePlanNotification;
use Illuminate\Support\Facades\Mail;

class PerformancePlanApproval extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:performance_plan_approval';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $request = $this->getPerformancePlanApproval();

        return $this->info($request);
    }

    public function getPerformancePlanApproval(){

        $for_approval = EmployeePerformanceEvaluation::with('user','approver.approver_info')
                                        ->where(function($q){
                                            $q->whereNull('mail_1')
                                                ->orWhereNull('mail_2');
                                        })
                                        ->where('status','For Review')
                                        ->get();
        $count = 0;                                
        if(count($for_approval) > 0){
            foreach($for_approval as $employee_ppr){
                if(count($employee_ppr->approver)){
                    foreach($employee_ppr->approver as $approver){
                        
                        if($employee_ppr->level == 0 && $approver->level == 1){ // If Level 0 Notify Level 1
                            $details = [
                                'approver_info' => $approver->approver_info,
                                'user_info' => $employee_ppr->user,
                                'details' => $employee_ppr,
                            ];
                            if(empty($employee_ppr->mail_1)){
                                $send_update = Mail::to($approver->approver_info->email)->send(new PerformancePlanNotification($details));
                                EmployeePerformanceEvaluation::where('id',$employee_ppr->id)->update(['mail_1'=>1]);
                                $count++;
                            }
                        }
                        
                        if($employee_ppr->level == 1 && $approver->level == 2){ // If Level 1 Notify Level 2
                            $details = [
                                'approver_info' => $approver->approver_info,
                                'user_info' => $employee_ppr->user,
                                'details' => $employee_ppr,
                            ];
                            if(empty($employee_ppr->mail_2)){
                                $send_update = Mail::to($approver->approver_info->email)->send(new PerformancePlanNotification($details));
                                EmployeePerformanceEvaluation::where('id',$employee_ppr->id)->update(['mail_2'=>1]);
                                $count++;
                            }
                        }
                    }
                }
            }   
        }

        return $count;
    }
}
