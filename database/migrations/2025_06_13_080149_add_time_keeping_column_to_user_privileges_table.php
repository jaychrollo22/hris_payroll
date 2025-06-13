<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimeKeepingColumnToUserPrivilegesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_privileges', function (Blueprint $table) {
            $table->string('time_keeping_hr_payroll_attendance',20)->nullable()->after('pagibig_contribution_setting');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_privileges', function (Blueprint $table) {
             $table->dropColumn('time_keeping_hr_payroll_attendance');
        });
    }
}
