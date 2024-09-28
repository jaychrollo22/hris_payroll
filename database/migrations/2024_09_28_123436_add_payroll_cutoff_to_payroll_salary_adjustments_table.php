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
            $table->date('effectivity_date')->nullable()->change();
            $table->enum('payroll_cutoff', ['First Cut-Off', 'Second Cut-Off', 'Every Cut-Off'])->after('reason');
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
            $table->date('effectivity_date')->nullable(false)->change(); // Revert if needed
            $table->dropColumn('payroll_cutoff');
        });
    }
}
