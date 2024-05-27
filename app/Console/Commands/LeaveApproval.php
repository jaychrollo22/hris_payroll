<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\EmployeeLeave;

use App\Mail\LeaveNotification;
use Illuminate\Support\Facades\Mail;

class LeaveApproval extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:leave_approval';

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
        $request = $this->getLeaveApproval();

        return $this->info($request);
    }

    public function getLeaveApproval(){

        $for_approval = EmployeeLeave::with('user','leave','approver.approver_info')
                                        ->where(function($q){
                                            $q->whereNull('mail_1')
                                                ->orWhereNull('mail_2');
                                        })
                                        ->where('status','Pending')
                                        ->get();
        $count = 0;                                
        if(count($for_approval) > 0){
            foreach($for_approval as $employee_leave){
                if(count($employee_leave->approver)){
                    foreach($employee_leave->approver as $approver){
                        
                        if($employee_leave->level == 0 && $approver->level == 1){ // If Level 0 Notify Level 1
                            $details = [
                                'approver_info' => $approver->approver_info,
                                'user_info' => $employee_leave->user,
                                'details' => $employee_leave,
                            ];

                            if(empty($employee_leave->mail_1)){
                                if($approver->approver_info->email != null)
                                {
                                    $send_update = Mail::to($approver->approver_info->email)->send(new LeaveNotification($details));
                                    EmployeeLeave::where('id',$employee_leave->id)->update(['mail_1'=>1]);
                                    $count++;
                                }
                            
                            }
                            
                        }
                        
                        if($employee_leave->level == 1 && $approver->level == 2){ // If Level 1 Notify Level 2
                            $details = [
                                'approver_info' => $approver->approver_info,
                                'user_info' => $employee_leave->user,
                                'details' => $employee_leave,
                            ];
                            if(empty($employee_leave->mail_2)){
                                if($approver->approver_info->email != null)
                                {
                                $send_update = Mail::to($approver->approver_info->email)->send(new LeaveNotification($details));
                                EmployeeLeave::where('id',$employee_leave->id)->update(['mail_2'=>1]);
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
