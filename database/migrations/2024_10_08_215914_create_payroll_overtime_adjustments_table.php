<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollOvertimeAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_overtime_adjustments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->integer('payroll_period_id');
            $table->decimal('amount', 15, 2); // Adjustment amount
            $table->enum('type', ['Deduction', 'Addition'])->nullable();// e.g., 'deduction','addition'
            $table->enum('payroll_cutoff', ['First Cut-Off', 'Second Cut-Off', 'Every Cut-Off']);
            $table->enum('status', ['Active', 'Inactive'])->nullable();
            $table->string('reason')->nullable(); // Reason for the adjustment
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payroll_overtime_adjustments');
    }
}
