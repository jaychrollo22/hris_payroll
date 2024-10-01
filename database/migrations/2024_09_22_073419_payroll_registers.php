<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PayrollRegisters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_registers', function (Blueprint $table) {

            $table->increments('id');  // Auto-incrementing ID
            
            // Employee and payroll details
            $table->integer('user_id')->nullable();
            $table->string('bank_account', 50)->nullable();
            $table->string('name')->nullable();
            $table->string('position')->nullable();
            $table->string('employment_status')->nullable();
            $table->string('company')->nullable();
            $table->string('department')->nullable();
            $table->string('project')->nullable();
            $table->date('date_hired')->nullable();
            $table->integer('payroll_period_id')->nullable();
            $table->date('cut_from')->nullable();
            $table->date('cut_to')->nullable();

            // Payroll details
            $table->decimal('monthly_basic_pay', 10, 2)->nullable();
            $table->decimal('daily_rate', 10, 2)->nullable();
            $table->decimal('basic_pay', 10, 2)->nullable();
            $table->decimal('absences_amount', 10, 2)->nullable();
            $table->decimal('lates_amount', 10, 2)->nullable();
            $table->decimal('undertime_amount', 10, 2)->nullable();
            $table->decimal('salary_adjustment', 10, 2)->nullable();
            $table->decimal('overtime_pay', 10, 2)->nullable();
            $table->decimal('meal_allowance', 10, 2)->nullable();
            $table->decimal('salary_allowance', 10, 2)->nullable();
            $table->decimal('out_of_town_allowance', 10, 2)->nullable();
            $table->decimal('incentives_allowance', 10, 2)->nullable();
            $table->decimal('relocation_allowance', 10, 2)->nullable();
            $table->decimal('discretionary_allowance', 10, 2)->nullable();
            $table->decimal('transport_allowance', 10, 2)->nullable();
            $table->decimal('load_allowance', 10, 2)->nullable();
            $table->decimal('grosspay', 10, 2)->nullable();

            // Taxable and Non-Taxable fields
            $table->decimal('total_taxable', 10, 2)->nullable();
            $table->char('minimum_wage',3)->nullable();
            $table->decimal('withholding_tax', 10, 2)->nullable();
            
            // SSS, PHIC, HDMF Contributions
            $table->decimal('sss_reg_ee_15', 10, 2)->nullable();
            $table->decimal('sss_mpf_ee_15', 10, 2)->nullable();
            $table->decimal('phic_ee_15', 10, 2)->nullable();
            $table->decimal('hmdf_ee_15', 10, 2)->nullable();
            
            // Loans and deductions
            $table->decimal('hdmf_salary_loan', 10, 2)->nullable();
            $table->decimal('hdmf_calamity_loan', 10, 2)->nullable();
            $table->decimal('sss_salary_loan', 10, 2)->nullable();
            $table->decimal('sss_calamity_loan', 10, 2)->nullable();
            $table->decimal('salary_deduction_taxable', 10, 2)->nullable();
            $table->decimal('salary_deduction_nontaxable', 10, 2)->nullable();
            $table->decimal('company_loan', 10, 2)->nullable();
            $table->decimal('omhas_loan', 10, 2)->nullable();
            $table->decimal('coop_cbu', 10, 2)->nullable();
            $table->decimal('coop_regular_loan', 10, 2)->nullable();
            $table->decimal('coop_mescco', 10, 2)->nullable();
            $table->decimal('petty_cash_mescco', 10, 2)->nullable();
            $table->decimal('others', 10, 2)->nullable();
            $table->decimal('total_deduction', 10, 2)->nullable();
            $table->decimal('netpay', 10, 2)->nullable();
            
            // Employer contributions
            $table->decimal('sss_reg_er_15', 10, 2)->nullable();
            $table->decimal('sss_mpf_er_15', 10, 2)->nullable();
            $table->decimal('sss_ec_15', 10, 2)->nullable();
            $table->decimal('phic_er_15', 10, 2)->nullable();
            $table->decimal('hdmf_er_15', 10, 2)->nullable();
            
            // Bank, status, and remarks
            $table->string('bank')->nullable();
            $table->string('status')->nullable();
            $table->string('remarks')->nullable();
            $table->string('status_last_payroll')->nullable();

            // Government IDs
            $table->string('sss_no', 20)->nullable();
            $table->string('philhealth_no', 20)->nullable();
            $table->string('pagibig_no', 20)->nullable();
            $table->string('tin_no', 20)->nullable();
            $table->string('bir_tagging')->nullable();
            
            // Specific payroll periods
            $table->decimal('month_15', 10, 2)->nullable();
            $table->decimal('month_30', 10, 2)->nullable();
            $table->decimal('accumulated', 10, 2)->nullable();
            $table->integer('number')->nullable();

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

            $table->timestamps();  // Adds created_at and updated_at
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
        Schema::dropIfExists('payroll_register');
    }
}
