<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPayrollAttendanceFieldToUserPrivilegesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_privileges', function (Blueprint $table) {
            $table->string('payroll_attendance',20)->nullable()->after('payroll_payreg_unpost');
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
            $table->dropColumn('payroll_attendance');
        });
    }
}
