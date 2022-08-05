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
Auth::routes();
Route::group( ['middleware' => 'auth'], function()
{


    //employees
    Route::get('/dashboard', 'HomeController@index')->name('home');
    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index')->name('home');
    //approvers
    Route::get('/dashboard-manager', 'HomeController@managerDashboard');
    //admin

    Route::get('attendances','AttendanceController@index');


    Route::get('get-attendance-bio','AttendanceController@get_attendances');


    //Leaves
    Route::get('leave','LeaveController@leaves');
    Route::get('post','LeaveController@leaves');


    //Overtime
    Route::get('overtime','OvertimeController@overtime');


    //Work-from-home
    Route::get('work-from-home','WorkfromhomeController@workfromhome');


    //official-business
    Route::get('official-business','OfficialbusinessController@officialBusiness');


    //DTR Correction
    Route::get('dtr-correction','DailytimerecordController@dtr');


    //employees
    Route::get('employees','EmployeeController@view');

    //handbooks
    Route::get('handbooks','HandbookController@view');
    Route::post('new-handbook','HandbookController@newhandbook');

    //Holidays
    Route::get('holidays','HolidayController@view');
    


});