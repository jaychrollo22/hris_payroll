<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSssMatrixContributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sss_matrix_contributions', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('min_salary', 10, 2);
            $table->decimal('max_salary', 10, 2)->nullable();
            $table->decimal('employee_share_ee', 10, 2);
            $table->decimal('employer_share_er', 10, 2);
            $table->decimal('total_contribution', 10, 2);
            $table->decimal('mpf_ee', 10, 2)->default(0);
            $table->decimal('mpf_er', 10, 2)->default(0);
            $table->decimal('total_mpf', 10, 2)->default(0);
            $table->boolean('is_no_limit')->default(false);
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
        Schema::dropIfExists('sss_matrix_contributions');
    }
}
