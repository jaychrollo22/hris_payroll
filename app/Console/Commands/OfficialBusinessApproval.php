<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\EmployeeOb;

use App\Mail\OfficialBusinessNotification;
use Illuminate\Support\Facades\Mail;

class OfficialBusinessApproval extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:official_business_approval';

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
        $request = $this->getObApproval();

        return $this->info($request);
    }

    public function getObApproval(){

        $for_approval = EmployeeOb::with('user','approver.approver_info')
                                        ->where(function($q){
                                            $q->whereNull('mail_1')
                                                ->orWhereNull('mail_2');
                                        })
                                        ->where('status','Pending')
                                        ->get();
        $count = 0;                                
        if(count($for_approval) > 0){
            foreach($for_approval as $employee_ob){
                if(count($employee_ob->approver)){
                    foreach($employee_ob->approver as $approver){
                        
                        if($employee_ob->level == 0 && $approver->level == 1){ // If Level 0 Notify Level 1
                            $details = [
                                'approver_info' => $approver->approver_info,
                                'user_info' => $employee_ob->user,
                                'details' => $employee_ob,
                            ];
                            if(empty($employee_ob->mail_1)){
                                if($approver->approver_info->email != null)
                                {
                                $send_update = Mail::to($approver->approver_info->email)->send(new OfficialBusinessNotification($details));
                                EmployeeOb::where('id',$employee_ob->id)->update(['mail_1'=>1]);
                                $count++;
                                }
                            }
                        }
                        
                        if($employee_ob->level == 1 && $approver->level == 2){ // If Level 1 Notify Level 2
                            $details = [
                                'approver_info' => $approver->approver_info,
                                'user_info' => $employee_ob->user,
                                'details' => $employee_ob,
                            ];
                            if(empty($employee_ob->mail_2)){
                                if($approver->approver_info->email != null)
                                {
                                $send_update = Mail::to($approver->approver_info->email)->send(new OfficialBusinessNotification($details));
                                EmployeeOb::where('id',$employee_ob->id)->update(['mail_2'=>1]);
                                $count++;
                                }
                            }
                        }
                    }
                }
            }   
        }

        return $count;
    }
}
