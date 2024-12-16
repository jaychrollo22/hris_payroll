<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixedAttendances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixed_attendances', function (Blueprint $table) {
            $table->increments('id');
            $table->string('employee_code');
            $table->datetime('time_in');
            $table->datetime('time_out');
            $table->string('type');
            $table->string('remarks');
            $table->string('device_in');
            $table->integer('last_id');
            $table->string('is_upload_hik');
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
        //
    }
}
