<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollContributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_employee_contributions', function (Blueprint $table) {
            $table->increments('id');  // Auto-incrementing ID
            $table->unsignedBigInteger('user_id'); // Foreign key for user ID
            $table->decimal('sss_reg_ee', 10, 2)->default(0); // SSS REG EE amount
            $table->decimal('sss_mpf_ee', 10, 2)->default(0); // SSS MPF EE amount
            $table->decimal('phic_ee', 10, 2)->default(0); // PHIC EE amount
            $table->decimal('hdmf_ee', 10, 2)->default(0); // HDMF EE amount
            $table->decimal('sss_reg_er', 10, 2)->default(0); // SSS REG ER amount
            $table->decimal('sss_mpf_er', 10, 2)->default(0); // SSS MPF ER amount
            $table->decimal('sss_ec', 10, 2)->default(0); // SSS EC amount
            $table->decimal('phic_er', 10, 2)->default(0); // PHIC ER amount
            $table->decimal('hdmf_er', 10, 2)->default(0); // HDMF ER amount
            $table->enum('payment_schedule', ['First Cut-Off', 'Second Cut-Off']); // Payment schedule
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payroll_employee_contributions');
    }
}
