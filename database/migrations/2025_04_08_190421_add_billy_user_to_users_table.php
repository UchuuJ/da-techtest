<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('users')->insert(['name'=>'billy','email'=>'billy@da.test','password'=>Hash::make('billy')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
