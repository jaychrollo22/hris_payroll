<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToPayrollRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_registers', function (Blueprint $table) {
            $table->dateTime('posting_date')->nullable()->after('number');
            $table->enum('posting_status', ['Posted', 'Unposted'])->default('unposted')->after('number');
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
            $table->dropColumn('posting_date');
            $table->dropColumn('posting_status');
        });
    }
}
