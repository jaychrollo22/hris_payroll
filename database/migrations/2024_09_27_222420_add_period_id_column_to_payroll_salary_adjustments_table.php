<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPeriodIdColumnToPayrollSalaryAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_salary_adjustments', function (Blueprint $table) {
            $table->integer('payroll_period_id')->after('effectivity_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll_salary_adjustments', function (Blueprint $table) {
            $table->dropColumn('payroll_period_id');
        });
    }
}
