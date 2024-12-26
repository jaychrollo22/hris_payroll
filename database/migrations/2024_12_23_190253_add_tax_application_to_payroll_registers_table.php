<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaxApplicationToPayrollRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_registers', function (Blueprint $table) {
            $table->char('tax_application',50)->nullable()->after('minimum_wage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll_registers', function (Blueprint $table) {
            $table->dropColumn('tax_application');
        });
    }
}
