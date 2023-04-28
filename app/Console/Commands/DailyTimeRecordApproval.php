<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\EmployeeDtr;
use App\Mail\DtrNotification;
use Illuminate\Support\Facades\Mail;

class DailyTimeRecordApproval extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:dtr_approval';

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $request = $this->getDtrApproval();

        return $this->info($request);
    }

    public function getDtrApproval(){

        $for_approval = EmployeeDtr::with('user','approver.approver_info')
                                        ->where(function($q){
                                            $q->whereNull('mail_1')
                                                ->orWhereNull('mail_2');
                                        })
                                        ->where('status','Pending')
                                        ->get();
        $count = 0;                                
        if(count($for_approval) > 0){
            foreach($for_approval as $employee_dtr){
                if(count($employee_dtr->approver)){
                    foreach($employee_dtr->approver as $approver){
                        
                        if($employee_dtr->level == 0 && $approver->level == 1){ // If Level 0 Notify Level 1
                            $details = [
                                'approver_info' => $approver->approver_info,
                                'user_info' => $employee_dtr->user,
                                'details' => $employee_dtr,
                            ];
                            if(empty($employee_dtr->mail_1)){
                                $send_update = Mail::to($approver->approver_info->email)->send(new DtrNotification($details));
                                EmployeeDtr::where('id',$employee_dtr->id)->update(['mail_1'=>1]);
                                $count++;
                            }
                        }
                        
                        if($employee_dtr->level == 1 && $approver->level == 2){ // If Level 1 Notify Level 2
                            $details = [
                                'approver_info' => $approver->approver_info,
                                'user_info' => $employee_dtr->user,
                                'details' => $employee_dtr,
                            ];
                            if(empty($employee_dtr->mail_2)){
                                $send_update = Mail::to($approver->approver_info->email)->send(new DtrNotification($details));
                                EmployeeDtr::where('id',$employee_dtr->id)->update(['mail_2'=>1]);
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
