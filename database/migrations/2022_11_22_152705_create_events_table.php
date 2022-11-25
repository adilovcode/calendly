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
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->bigInteger('duration');
            $table->bigInteger('buffer_time')->default(0);
            $table->bigInteger('bookable_in_advance');
            $table->bigInteger('accept_per_slot')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('events');
    }
};
