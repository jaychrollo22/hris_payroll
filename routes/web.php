<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    //Users
    Route::get('account-setting', 'UserController@accountSetting');
    Route::post('upload-avatar', 'UserController@uploadAvatar');
    Route::post('upload-signature', 'UserController@uploadSignature');
    Route::get('get-salary', 'UserController@get_salary');

    //employees
    Route::get('/dashboard', 'HomeController@index')->name('home');
    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index')->name('home');
    //approvers
    Route::get('/dashboard-manager', 'HomeController@managerDashboard');
    //admin

    Route::get('attendances', 'AttendanceController@index');


    Route::get('get-attendance-bio', 'AttendanceController@get_attendances');


    //Leaves
    Route::get('leave', 'LeaveController@leaves');
    Route::get('post', 'LeaveController@leaves');


    //Overtime
    Route::get('overtime', 'OvertimeController@overtime');


    //Work-from-home
    Route::get('work-from-home', 'WorkfromhomeController@workfromhome');


    //official-business
    Route::get('official-business', 'OfficialbusinessController@officialBusiness');


    //DTR Correction
    Route::get('dtr-correction', 'DailytimerecordController@dtr');


    //employees
    Route::get('employees', 'EmployeeController@view');
    Route::post('new-employee', 'EmployeeController@new');


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

    //Biometrics
    Route::get('get-biometrics', 'EmployeeController@employees_biotime');
    Route::post('new-biocode', 'EmployeeController@newBio');
    Route::post('update-biocode', 'EmployeeController@updatebiocode');
    Route::get('biologs-employee', 'EmployeeController@employee_attendance');
    Route::get('bio-per-location', 'EmployeeController@biologs_per_location');
    Route::get('pmi-local', 'EmployeeController@localbio');

    //Payroll
    Route::get('pay-reg', 'PayslipController@payroll_datas');
    Route::get('timekeeping', 'PayslipController@attendances');
    Route::post('pay-reg', 'PayslipController@import');
    Route::post('upload-attendance', 'PayslipController@upload_attendance');

    // Masterfiles
    Route::get('company', 'MasterfileController@company_index');
    Route::post('newCompany', 'MasterfileController@store_company');
    Route::post('newDepartment', 'MasterfileController@store_department');
    Route::get('department', 'MasterfileController@department_index');
    Route::get('enable-department/{id}', 'MasterfileController@enable_department');
    Route::get('disable-department/{id}', 'MasterfileController@disable_department');
    Route::get('loan-type', 'MasterfileController@loanTypes_index');
    Route::post('newLoanType', 'MasterfileController@store_loanType');
    Route::get('enable-loanType/{id}', 'MasterfileController@enable_loanType');
    Route::get('disable-loanType/{id}', 'MasterfileController@disable_loanType');
    // Loans
    Route::get('loans', 'LoanController@index');
    Route::get('loan-reg', 'LoanController@loan_reg');
    Route::post('new-loan', 'LoanController@store_loanReg');

    //13th month
    Route::get('month-benefit', 'PayslipController@monthly_benefit');
});
