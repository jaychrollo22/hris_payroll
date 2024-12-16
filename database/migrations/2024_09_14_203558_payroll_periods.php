<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PayrollPeriods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_periods', function (Blueprint $table) {
            $table->increments('id'); // Primary key with auto-increment
            $table->string('payroll_name'); // Name or label of the payroll period
            $table->date('start_date'); // Start date of the payroll period
            $table->date('end_date'); // End date of the payroll period
            $table->enum('payroll_frequency', ['weekly', 'bi-weekly', 'semi-monthly', 'monthly']); // Payroll frequency
            $table->date('cut_off_date'); // Payroll cut-off date
            $table->date('payment_date'); // Payment date for employees
            $table->integer('total_days')->nullable(); // Total number of days in the payroll period
            $table->enum('status', ['open', 'closed', 'in_progress'])->default('open'); // Status of the payroll period
            $table->text('notes')->nullable(); // Additional remarks or details
            $table->timestamps(); // Created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payroll_periods');
    }
}
