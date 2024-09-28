<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPayrollCutoffToPayrollPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_periods', function (Blueprint $table) {
            $table->enum('payroll_cutoff', ['First Cut-Off', 'Second Cut-Off', 'Every Cut-Off'])->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll_periods', function (Blueprint $table) {
            $table->dropColumn('payroll_cutoff');
        });
    }
}
