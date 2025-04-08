<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('distance_between_locations', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->decimal('distance','8','2');
            $table->foreignId('courier_cost_calc_history_id');
//            $table->timestamps();
            $table->foreign('courier_cost_calc_history_id')->references('id')->on('courier_cost_calc_history')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distance_between_locations');
    }
};
