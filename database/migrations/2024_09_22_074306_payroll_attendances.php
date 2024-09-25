<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PayrollAttendances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_attendances', function (Blueprint $table) {
            $table->increments('id');  // Auto-incrementing ID

            // Employee Information
            $table->integer('user_id')->nullable();
            $table->string('full_name')->nullable();
            $table->string('department')->nullable();
            $table->string('location')->nullable();

            // Payroll Information
            $table->decimal('basic_pay', 10, 2)->nullable();
            $table->decimal('daily_rate', 10, 2)->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->integer('no_of_days_worked')->nullable();
            $table->decimal('days_worked_amount', 10, 2)->nullable();

            // Leave Information
            $table->integer('sl_with_pay_days')->nullable();
            $table->decimal('sl_with_pay_amount', 10, 2)->nullable();
            $table->integer('vl_with_pay_days')->nullable();
            $table->decimal('vl_with_pay_amount', 10, 2)->nullable();
            $table->integer('total_days_worked_leave')->nullable();
            $table->decimal('total_basic_pay_worked_leave', 10, 2)->nullable();

            // Absences, Lates, and Undertime
            $table->integer('absences_days')->nullable();
            $table->decimal('absences_amount', 10, 2)->nullable();
            $table->decimal('lates_hours', 10, 2)->nullable();
            $table->decimal('lates_amount', 10, 2)->nullable();
            $table->decimal('undertime_hours', 10, 2)->nullable();
            $table->decimal('undertime_amount', 10, 2)->nullable();

            // Overtime Information
            $table->decimal('reg_ot_hours', 10, 2)->nullable();
            $table->decimal('reg_ot_amount', 10, 2)->nullable();
            $table->decimal('rest_day_hours', 10, 2)->nullable();
            $table->decimal('rest_day_amount', 10, 2)->nullable();
            $table->decimal('rdot_shot_hours', 10, 2)->nullable();
            $table->decimal('rdot_shot_amount', 10, 2)->nullable();
            $table->decimal('special_holiday_hours', 10, 2)->nullable();
            $table->decimal('special_holiday_amount', 10, 2)->nullable();
            $table->decimal('shrd_hours', 10, 2)->nullable();
            $table->decimal('shrd_amount', 10, 2)->nullable();
            $table->decimal('sh_rd_ot_hours', 10, 2)->nullable();
            $table->decimal('sh_rd_ot_amount', 10, 2)->nullable();
            $table->decimal('regular_holiday_hours', 10, 2)->nullable();
            $table->decimal('regular_holiday_amount', 10, 2)->nullable();
            $table->decimal('rh_rd_or_lh_ot_hours', 10, 2)->nullable();
            $table->decimal('rh_rd_or_lh_ot_amount', 10, 2)->nullable();
            $table->decimal('lhrd_ot_hours', 10, 2)->nullable();
            $table->decimal('lhrd_ot_amount', 10, 2)->nullable();
            $table->decimal('night_diff_hours', 10, 2)->nullable();
            $table->decimal('night_diff_amount', 10, 2)->nullable();
            $table->decimal('overtime_adjustment', 10, 2)->nullable();
            $table->decimal('total_overtime_pay', 10, 2)->nullable();

            // Other Information
            $table->string('status')->nullable();
            $table->string('remarks')->nullable();
            $table->string('company')->nullable();
            $table->string('timekeeper')->nullable();
            $table->string('overtime_approver')->nullable();
            $table->integer('number')->nullable();

            $table->timestamps();  // Adds created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payroll_attendances');
    }
}
