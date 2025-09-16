<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dreamfights', function (Blueprint $table) {
            $table->renameColumn('fighter_one', 'fighter_one_name');
            $table->renameColumn('fighter_two', 'fighter_two_name');
        });
    }

    public function down(): void
    {
        Schema::table('dreamfights', function (Blueprint $table) {
            $table->renameColumn('fighter_one_name', 'fighter_one');
            $table->renameColumn('fighter_two_name', 'fighter_two');
        });
    }
};
