<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pound_fighters', function (Blueprint $table) {
            $table->id();
            $table->string('fighter_name');
            $table->integer('rank')->unique(); // top 1-10
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pound_fighters');
    }
};
