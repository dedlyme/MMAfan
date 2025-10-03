<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rankings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->constrained()->cascadeOnDelete();
            $table->string('fighter_name');
            $table->integer('rank')->default(1);
            $table->boolean('is_champion')->default(false);
            $table->timestamps();

            // ✅ unikāls cīnītājs vienā divīzijā
            $table->unique(['division_id', 'fighter_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rankings');
    }
};
