<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('working_days', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->uuid('event_id');
            $table->foreign('event_id')->references('id')->on('events');
            $table->tinyInteger('day');
            $table->string('start_time');
            $table->string('end_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('working_hours');
    }
};
