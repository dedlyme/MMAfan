<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dreamfights', function (Blueprint $table) {
            $table->timestamp('round_end_time')->nullable()->after('current_round');
        });
    }

    public function down(): void
    {
        Schema::table('dreamfights', function (Blueprint $table) {
            $table->dropColumn('round_end_time');
        });
    }
};
