<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_days', function (Blueprint $table) {
            $table->id();
            $table->integer('startTime');
            $table->integer('endTime');

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('day_id')->constrained('days');
            $table->unique(['user_id', 'day_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_days');
    }
};
