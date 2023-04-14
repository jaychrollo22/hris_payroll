<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\EmployeeWfh;
use App\Mail\WorkFromHomeNotification;
use Illuminate\Support\Facades\Mail;

class WorkFromHomeApproval extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:work_from_home_approval';

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
    public function handle()
    {
        $request = $this->getWfhApproval();

        return $this->info($request);
    }

    public function getWfhApproval(){

        $for_approval = EmployeeWfh::with('user','approver.approver_info')
                                        ->where(function($q){
                                            $q->whereNull('mail_1')
                                                ->orWhereNull('mail_2');
                                        })
                                        ->where('status','Pending')
                                        ->get();
        $count = 0;                                
        if(count($for_approval) > 0){
            foreach($for_approval as $employee_wfh){
                if(count($employee_wfh->approver)){
                    foreach($employee_wfh->approver as $approver){
                        
                        if($employee_wfh->level == 0 && $approver->level == 1){ // If Level 0 Notify Level 1
                            $details = [
                                'approver_info' => $approver->approver_info,
                                'user_info' => $employee_wfh->user,
                                'details' => $employee_wfh,
                            ];
                            if(empty($employee_wfh->mail_1)){
                                $send_update = Mail::to($approver->approver_info->email)->send(new WorkFromHomeNotification($details));
                                EmployeeWfh::where('id',$employee_wfh->id)->update(['mail_1'=>1]);
                                $count++;
                            }
                        }
                        
                        if($employee_wfh->level == 1 && $approver->level == 2){ // If Level 1 Notify Level 2
                            $details = [
                                'approver_info' => $approver->approver_info,
                                'user_info' => $employee_wfh->user,
                                'details' => $employee_wfh,
                            ];
                            if(empty($employee_wfh->mail_2)){
                                $send_update = Mail::to($approver->approver_info->email)->send(new WorkFromHomeNotification($details));
                                EmployeeWfh::where('id',$employee_wfh->id)->update(['mail_2'=>1]);
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
