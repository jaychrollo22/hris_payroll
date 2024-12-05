<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPayrollPrivilegesToUserPrivilegesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_privileges', function (Blueprint $table) {
            $table->string('deduction_setting',20)->nullable()->after('masterfiles_performance_plan_periods');
            $table->string('allowance_setting',20)->nullable()->after('masterfiles_performance_plan_periods');
            $table->string('payroll_deduction',20)->nullable()->after('masterfiles_performance_plan_periods');
            $table->string('payroll_contribution',20)->nullable()->after('masterfiles_performance_plan_periods');
            $table->string('payslip_filter_per_company',20)->nullable()->after('masterfiles_performance_plan_periods');
            $table->string('payroll_payreg_unpost',20)->nullable()->after('masterfiles_performance_plan_periods');
            $table->string('payroll_payreg_post',20)->nullable()->after('masterfiles_performance_plan_periods');
            $table->string('payroll_payreg',20)->nullable()->after('masterfiles_performance_plan_periods');
            $table->string('payroll_period',20)->nullable()->after('masterfiles_performance_plan_periods');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_privileges', function (Blueprint $table) {
            $table->dropColumn('deduction_setting');
            $table->dropColumn('allowance_setting');
            $table->dropColumn('payroll_deduction');
            $table->dropColumn('payroll_contribution');
            $table->dropColumn('payslip_filter_per_company');
            $table->dropColumn('payroll_payreg_unpost');
            $table->dropColumn('payroll_payreg_post');
            $table->dropColumn('payroll_payreg');
            $table->dropColumn('payroll_period');
        });
    }
}
