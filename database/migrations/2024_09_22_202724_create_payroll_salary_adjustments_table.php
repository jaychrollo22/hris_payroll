<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollSalaryAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_salary_adjustments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->date('effectivity_date');
            $table->decimal('amount', 15, 2); // Adjustment amount
            $table->enum('type', ['Bonus', 'Deduction', 'Addition'])->nullable();// e.g., 'bonus', 'deduction','addition'
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
        Schema::dropIfExists('payroll_salary_adjustments');
    }
}
