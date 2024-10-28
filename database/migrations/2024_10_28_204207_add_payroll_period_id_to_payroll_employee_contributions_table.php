<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPayrollPeriodIdToPayrollEmployeeContributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_employee_contributions', function (Blueprint $table) {
            $table->string('company')->after('user_id');
            $table->integer('payroll_period_id')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll_employee_contributions', function (Blueprint $table) {
            $table->dropColumn('company');
            $table->dropColumn('payroll_period_id');
        });
    }
}
