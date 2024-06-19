<?php


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\HikAttLog2;

Auth::routes();
Route::get('get-devices','AttendanceController@devices');
Route::group(['middleware' => 'auth'], function () {

    
    //Users
    Route::get('account-setting', 'UserController@accountSetting');
    Route::post('upload-avatar', 'UserController@uploadAvatar');
    Route::post('upload-signature', 'UserController@uploadSignature');
    Route::get('get-salary', 'UserController@get_salary');
    Route::post('updateInfo/{id}', 'UserController@updateInfo');
    Route::post('updateEmpInfo/{id}', 'UserController@updateEmpInfo');
    Route::post('updateEmpContactInfo/{id}', 'UserController@updateEmpContactInfo');
    

    //employees
    Route::get('/dashboard', 'HomeController@index')->name('home');
    Route::get('', 'HomeController@index');
    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index')->name('home');
    //approvers
    Route::get('/dashboard-manager', 'HomeController@managerDashboard');
    //admin

    Route::get('attendances', 'AttendanceController@index');


    Route::get('get-attendance-bio', 'AttendanceController@get_attendances');


    Route::get('attendance-per-company-export', 'AttendanceController@attendancePerCompanyExport');

    Route::get('seabased-attendances', 'AttendanceController@seabasedAttendances');
    Route::get('seabased-attendances-export', 'AttendanceController@attendanceSeabasedAttendnaceExport');
    Route::post('upload-seabased-attendance', 'AttendanceController@uploadSeabasedAttendance');

    Route::get('hik-attendances', 'AttendanceController@hikAttendances');
    Route::get('hik-attendances-export', 'AttendanceController@attendanceHikAttendnaceExport');
    Route::post('upload-hik-attendance', 'AttendanceController@uploadHikAttendance');

    //Leaves
    Route::get('file-leave', 'EmployeeLeaveController@leaveBalances');
    Route::post('new-leave','EmployeeLeaveController@new');
    Route::post('edit-leave/{id}', 'EmployeeLeaveController@edit_leave');
    Route::get('disable-leave/{id}', 'EmployeeLeaveController@disable_leave');
    Route::post('request-to-cancel-leave/{id}', 'EmployeeLeaveController@request_to_cancel');
    Route::get('void-to-cancel-leave/{id}', 'EmployeeLeaveController@void_request_to_cancel');
    Route::get('approve-request-to-cancel-leave/{id}', 'EmployeeLeaveController@approve_request_to_cancel');
    Route::get('decline-request-to-cancel-leave/{id}', 'EmployeeLeaveController@decline_request_to_cancel');

    Route::post('approve-leave-all','FormApprovalController@approveLeaveAll');
    Route::post('disapprove-leave-all','FormApprovalController@disapproveLeaveAll');

    //Overtime
    Route::get('overtime','EmployeeOvertimeController@overtime');
    Route::post('new-overtime','EmployeeOvertimeController@new');
    Route::post('edit-overtime/{id}', 'EmployeeOvertimeController@edit_overtime');
    Route::get('disable-overtime/{id}', 'EmployeeOvertimeController@disable_overtime');    
    Route::get('check-valid-overtime', 'EmployeeOvertimeController@checkValidOvertime');    

    //Work-from-home
    Route::get('work-from-home', 'EmployeeWfhController@wfh');
    Route::post('new-wfh','EmployeeWfhController@new');
    Route::post('edit-wfh/{id}','EmployeeWfhController@edit_wfh');
    Route::get('disable-wfh/{id}','EmployeeWfhController@disable_wfh');
    // Route::post('approve-wfh-all','FormApprovalController@approveWfhAll');
    // Route::post('disapprove-wfh-all','FormApprovalController@disapproveWfhAll');

    //official-business
    Route::get('official-business', 'EmployeeObController@ob');
    Route::post('new-ob','EmployeeObController@new');
    Route::post('edit-ob/{id}', 'EmployeeObController@edit_ob');
    Route::get('disable-ob/{id}', 'EmployeeObController@disable_ob');  
    
    Route::post('approve-ob-all','FormApprovalController@approveObAll');
    Route::post('disapprove-ob-all','FormApprovalController@disapproveObAll');

    //DTR Correction
    Route::get('dtr-correction', 'EmployeeDtrController@dtr');
    Route::post('new-dtr','EmployeeDtrController@new');
    Route::post('edit-dtr/{id}', 'EmployeeDtrController@edit_dtr');
    Route::get('disable-dtr/{id}', 'EmployeeDtrController@disable_dtr');     

    //FOR APPROVAL
    Route::get('for-leave','FormApprovalController@form_leave_approval');
    Route::post('approve-leave/{id}','FormApprovalController@approveLeave');
    Route::post('decline-leave/{id}','FormApprovalController@declineLeave');

    Route::get('for-overtime','FormApprovalController@form_overtime_approval');
    Route::post('approve-ot-hrs/{employee_overtime}','FormApprovalController@approveOvertime');
    Route::post('decline-overtime/{id}','FormApprovalController@declineOvertime');

    Route::get('for-work-from-home','FormApprovalController@form_wfh_approval');
    // Route::get('approve-wfh/{id}','FormApprovalController@approveWfh');
    Route::post('decline-wfh/{id}','FormApprovalController@declineWfh');
    Route::post('approve-wfh-percentage/{id}','FormApprovalController@approveWfh');
    
    Route::get('for-official-business','FormApprovalController@form_ob_approval');
    Route::post('approve-ob/{id}','FormApprovalController@approveOb');
    Route::post('decline-ob/{id}','FormApprovalController@declineOb');

    Route::get('for-dtr-correction','FormApprovalController@form_dtr_approval');
    Route::post('approve-dtr/{id}','FormApprovalController@approveDtr');
    Route::post('decline-dtr/{id}','FormApprovalController@declineDtr');
    Route::post('approve-dtr-all','FormApprovalController@approveDtrAll');
    Route::post('disapprove-dtr-all','FormApprovalController@disapproveDtrAll');

    //employees
    Route::get('employees', 'EmployeeController@view');
    Route::get('print-id/{id}','EmployeeController@print');
    Route::get('employees-export', 'EmployeeController@export');
    Route::get('employees-export-hr', 'EmployeeController@export_hr');
    Route::post('new-employee', 'EmployeeController@new');
    Route::get('account-setting-hr/{user}', 'EmployeeController@employeeSettingsHR');
    Route::post('account-setting-hr/updateInfoHR/{id}', 'EmployeeController@updateInfoHR');
    Route::post('account-setting-hr/updateEmpInfoHR/{id}', 'EmployeeController@updateEmpInfoHR');
    Route::post('account-setting-hr/updateContactInfoHR/{id}', 'EmployeeController@updateContactInfoHR');
    Route::post('account-setting-hr/updateBeneficiariesHR/{id}', 'EmployeeController@updateBeneficiariesHR');
    Route::get('account-setting-hr/getBeneficiariesHR/{id}', 'EmployeeController@getBeneficiariesHR');
    Route::post('account-setting-hr/uploadAvatarHr/{id}', 'EmployeeController@uploadAvatarHr');
    Route::post('account-setting-hr/uploadSignatureHr/{id}', 'EmployeeController@uploadSignatureHr');

    Route::get('associate-employees-export','EmployeeController@export_employee_associates');


    //Payslips
    Route::get('payslips', 'PayslipController@view');

    //handbooks
    Route::get('handbooks', 'HandbookController@view');
    Route::post('new-handbook', 'HandbookController@newhandbook');

    //Holidays
    Route::get('holidays', 'HolidayController@view');
    Route::post('new-holiday', 'HolidayController@new');
    Route::get('delete-holiday/{id}', 'HolidayController@delete_holiday');
    Route::post('edit-holiday/{id}', 'HolidayController@edit_holiday');

    //formsLeave
    Route::get('leavee-settings', 'LeaveController@leaveDetails');

    //Schedules
    Route::get('schedules', 'ScheduleController@schedules');
    Route::post('new-schedule', 'ScheduleController@newSchedule');


    //Announcement
    Route::get('announcements', 'AnnouncementController@view');
    Route::post('new-announcement', 'AnnouncementController@new');
    Route::get('delete-announcement/{id}', 'AnnouncementController@delete');

    //Logos
    Route::get('logos', 'SettingController@view');
    Route::post('upload-icon', 'SettingController@uploadIcon');
    Route::post('upload-logo', 'SettingController@uploadLogo');

    //Manager
    Route::get('subordinates', 'AttendanceController@subordinates');

    //Allowances
    Route::get('allowances', 'AllowanceController@viewAllowances');
    Route::post('new-allowance', 'AllowanceController@new');
    Route::get('disable-allowance/{id}', 'AllowanceController@disable_allowance');
    Route::get('activate-allowance/{id}', 'AllowanceController@activate_allowance');
    Route::post('edit-allowance/{id}', 'AllowanceController@edit_allowance');

    // Incentives
    Route::get('incentives', 'IncentiveController@index');
    Route::post('new-incentive', 'IncentiveController@store');
    Route::get('disable-incentive/{id}', 'IncentiveController@disable_incentive');
    Route::get('activate-incentive/{id}', 'IncentiveController@activate_incentive');
    Route::post('edit-incentive/{id}', 'IncentiveController@update');

    //Biometrics
    Route::get('get-biometrics', 'EmployeeController@employees_biotime');
    Route::post('new-biocode', 'EmployeeController@newBio');
    Route::post('update-biocode', 'EmployeeController@updatebiocode');
    Route::get('biologs-employee', 'EmployeeController@employee_attendance');
    Route::get('bio-per-location', 'EmployeeController@biologs_per_location');
    Route::get('bio-per-location-hik', 'EmployeeController@biologs_per_location_hik');
    Route::get('bio-per-location-export', 'EmployeeController@biologs_per_location_export');
    Route::get('pmi-local', 'EmployeeController@localbio');
    Route::get('biometrics-per-company', 'EmployeeController@perCompany');
    Route::get('sync-biometrics','EmployeeController@sync');
    Route::get('sync-biometric-per-employee','EmployeeController@sync_per_employee');
    // Route::get('sync-biometric-per-employee-hik','EmployeeController@sync_per_employee_hik');
    Route::get('sync-biometric-per-employee-hik','EmployeeController@sync_per_employee_hik_with_upload');

    Route::get('biologs-employee-attendance-report', 'EmployeeController@employee_attendance_report');

    // Route::get('sync-per-employee','EmployeeController@sync_per_employee');
    Route::get('sync-hik-att-logs','EmployeeController@sync_hik_with_upload');

    //Payroll
    Route::get('pay-reg', 'PayslipController@payroll_datas');
    Route::get('timekeeping', 'PayslipController@attendances');
    Route::get('generated-timekeeping', 'PayslipController@generatedAttendances');
    Route::post('pay-reg', 'PayslipController@import');
    Route::post('upload-attendance', 'PayslipController@upload_attendance');

    // Company
    Route::get('company', 'CompanyController@company_index');
    Route::post('newCompany', 'CompanyController@store_company');

    // Department
    Route::post('newDepartment', 'DepartmentController@store_department');
    Route::get('department', 'DepartmentController@department_index');
    Route::get('enable-department/{id}', 'DepartmentController@enable_department');
    Route::get('disable-department/{id}', 'DepartmentController@disable_department');
    Route::get('edit-deparment/{id}', 'DepartmentController@edit_department');
    Route::post('update-department/{id}', 'DepartmentController@update_department');

    // Location
    Route::post('store-location', 'LocationController@store');
    Route::get('location', 'LocationController@index');
    Route::get('edit-location/{id}', 'LocationController@edit');
    Route::post('update-location/{id}', 'LocationController@update');

    // Project
    Route::post('store-project', 'ProjectController@store');
    Route::get('project', 'ProjectController@index');
    Route::get('edit-project/{id}', 'ProjectController@edit');
    Route::post('update-project/{id}', 'ProjectController@update');

    // Loan Type
    Route::get('loan-type', 'LoanTypeController@loanTypes_index');
    Route::post('newLoanType', 'LoanTypeController@store_loanType');
    Route::get('enable-loanType/{id}', 'LoanTypeController@enable_loanType');
    Route::get('disable-loanType/{id}', 'LoanTypeController@disable_loanType');

    // Employee Allowance
    Route::get('employee-allowance', 'EmployeeAllowanceController@index');
    Route::post('new-employee-allowance', 'EmployeeAllowanceController@store');
    Route::post('update-employee-allowance/{id}', 'EmployeeAllowanceController@update');
    Route::get('edit-employee-allowance/{id}', 'EmployeeAllowanceController@edit');
    Route::get('delete-employee-allowance/{id}', 'EmployeeAllowanceController@delete');
    Route::get('disableEmp-allowance/{id}', 'EmployeeAllowanceController@disable');

    // Employee Incentive
    Route::get('employee-incentive', 'EmployeeIncentiveController@index');
    Route::post('new-employee-incentive', 'EmployeeIncentiveController@store');
    Route::get('disableEmp-incentive/{id}', 'EmployeeIncentiveController@disable');

    // Employee Groups
    Route::get('employee-companies', 'EmployeeCompanyController@index');
    Route::post('new-employee-group', 'EmployeeCompanyController@store');
    Route::get('disableEmp-incentive/{id}', 'EmployeeCompanyController@disable');

    // Adjustments
    Route::get('salary-management', 'AdjustmentController@index');
    Route::post('new-adjustment', 'AdjustmentController@store');
    Route::get('disable-adjustment/{id}', 'AdjustmentController@disable');

    // Loans
    Route::get('loans', 'LoanController@index');
    Route::get('loan-reg', 'LoanController@loan_reg');
    Route::post('new-loan', 'LoanController@store_loanReg');

    // Reports
    Route::get('employee-report', 'EmployeeController@employee_report');
    Route::get('leave-report', 'LeaveController@leave_report');
    Route::get('leave-report-export', 'LeaveController@export');
    Route::get('totalExpense-report', 'PayrollController@totalExpense_report');
    Route::get('loan-report', 'LoanController@loan_report');
    Route::get('incentive-report', 'IncentiveController@incentive_report');
    Route::get('payroll-report', 'PayrollController@payroll_report');
    Route::get('overtime-report', 'OvertimeController@overtime_report');
    Route::get('overtime-report-export', 'OvertimeController@export');
    Route::get('wfh-report', 'WorkfromhomeController@wfh_report');
    Route::get('wfh-report-export', 'WorkfromhomeController@export');
    Route::get('ob-report', 'OfficialbusinessController@ob_report');
    Route::get('ob-report-export', 'OfficialbusinessController@export');
    Route::get('dtr-report', 'DailytimerecordController@dtr_report');
    Route::get('dtr-report-export', 'DailytimerecordController@export');

    //13th month
    Route::get('month-benefit', 'PayslipController@monthly_benefit');

    // Employee Leave Credits
    Route::get('employee-leave-credits', 'LeaveCreditsController@index');
    Route::post('new-employee-leave-credit', 'LeaveCreditsController@store');

    //Employee Leave Balances
    Route::get('employee-leave-balances', 'LeaveBalancesController@index');

    // Employee Earned Leaves
    Route::get('employee-earned-leaves', 'EmployeeEarnedLeaveController@index');
    Route::get('manual-employee-earned-leaves', 'EmployeeEarnedLeaveController@manual');
    Route::post('manual-employee-earned-leaves-store', 'EmployeeEarnedLeaveController@manual_store');
    Route::get('manual-employee-earned-leaves-delete', 'EmployeeEarnedLeaveController@manual_delete');

    //User
    Route::get('/users','UserController@index');
    Route::get('/edit-user-role/{user}','UserController@editUserRole');
    Route::get('/change-password/{user}','UserController@changePassword');
    Route::post('/update-user-role/{user}','UserController@updateUserRole');
    Route::post('/update-user-password/{user}','UserController@updateUserPassword');


    Route::get('users-export', 'UserController@export');

    //HR Approver Setting
    Route::get('/hr-approver-setting','HrApproverSettingController@index');
    Route::post('/save-hr-approver-setting','HrApproverSettingController@store');
    Route::get('/remove-hr-approver/{id}','HrApproverSettingController@remove'); 

    //Timekeeping Dashboard
    

    Route::get('/timekeeping-dashboard','TimekeepingDashboardController@index');
    Route::get('/reset-leave/{id}','TimekeepingDashboardController@reset_leave');
    Route::get('/reset-ob/{id}','TimekeepingDashboardController@reset_ob');
    Route::get('/reset-wfh/{id}','TimekeepingDashboardController@reset_wfh');
    Route::get('/reset-ot/{id}','TimekeepingDashboardController@reset_ot');
    Route::get('/reset-dtr/{id}','TimekeepingDashboardController@reset_dtr');
});
Route::post('new-employee', 'EmployeeController@new');
Route::post('upload-employee', 'EmployeeController@upload');
Route::post('upload-employee-rate', 'EmployeeController@reverseRate');

Route::get('leave-credit-acc','EmployeeEarnedLeaveController@addLeave');

Route::get('hik-logs', function(){
    return HikAttLog2::orderBy('authDate')->get()->take(5);
});