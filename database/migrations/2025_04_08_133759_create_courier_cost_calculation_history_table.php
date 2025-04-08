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
        //Ideally it would be courier_cost_calculation_history but alas name too long, lmao
        Schema::create('courier_cost_calc_history', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer('user_id');
            $table->decimal('cost_per_mile','14','2');
            $table->integer('no_of_drop_off_locations');
            $table->integer('extra_person_count')->nullable();
            $table->decimal('extra_person_price_override','14','2')->nullable();
            $table->decimal('total_distance','14','2');
            $table->decimal('total_price','14','2');
            $table->decimal('extra_person_price','14','2');
            $table->dateTime('calculation_created_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courier_cost_calc_history');
    }
};
