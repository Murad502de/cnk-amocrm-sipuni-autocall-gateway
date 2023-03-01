<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('uuid')->nullable()->index();
            $table->string('name');
            $table->bigInteger('amo_pipeline_id');
            $table->string('operator_extension_number');
            $table->string('start_work_hours');
            $table->string('start_work_minutes');
            $table->string('end_work_hours');
            $table->string('end_work_minutes');
            $table->bigInteger('auto_redial_delay');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calls');
    }
}
