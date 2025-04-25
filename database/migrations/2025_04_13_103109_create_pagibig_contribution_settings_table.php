<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagibigContributionSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagibig_contribution_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id');
            $table->decimal('amount', 15, 2); // Adjustment amount
            $table->enum('payroll_cutoff', ['First Cut-Off', 'Second Cut-Off', 'Every Cut-Off']);
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
        Schema::dropIfExists('pagibig_contribution_settings');
    }
}
