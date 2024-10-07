<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPayrollCutoffToPayrollSalaryAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_salary_adjustments', function (Blueprint $table) {
            // $table->date('effectivity_date')->nullable();
            $table->string('payroll_cutoff')->after('reason');
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
            // $table->dropColumn('effectivity_date');
            $table->dropColumn('payroll_cutoff');
        });
    }
}
