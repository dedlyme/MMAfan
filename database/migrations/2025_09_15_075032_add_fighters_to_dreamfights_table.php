<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dreamfights', function (Blueprint $table) {
            $table->string('fighter_one_name');
            $table->string('fighter_two_name');
        });
    }

    public function down(): void
    {
        Schema::table('dreamfights', function (Blueprint $table) {
            $table->dropColumn(['fighter_one_name', 'fighter_two_name']);
        });
    }
};
