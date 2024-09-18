<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToEmployeeAllowancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_allowances', function (Blueprint $table) {
            $table->decimal('percentage', 5, 2)->nullable();  // percentage of base salary (optional)
            $table->date('effective_date')->nullable();  // date the allowance starts
            $table->enum('frequency', ['monthly', 'quarterly', 'annually'])->nullable();  // how often it's paid
            $table->boolean('is_taxable')->default(true);  // whether the allowance is taxable
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_allowances', function (Blueprint $table) {
            $table->dropColumn('percentage');
            $table->dropColumn('effective_date');
            $table->dropColumn('frequency');
            $table->dropColumn('is_taxable');
        });
    }
}
