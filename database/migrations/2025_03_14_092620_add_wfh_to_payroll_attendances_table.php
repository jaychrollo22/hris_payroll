<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWfhToPayrollAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_attendances', function (Blueprint $table) {
            $table->decimal('total_wfh_60',10,2)->nullable();
            $table->decimal('total_wfh_70',10,2)->nullable();
            $table->decimal('total_wfh_deduction',10,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll_attendances', function (Blueprint $table) {
            $table->dropColumn('total_wfh_60');
            $table->dropColumn('total_wfh_70');
            $table->dropColumn('total_wfh_deduction');
        });
    }
}
