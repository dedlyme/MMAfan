// database/migrations/xxxx_xx_xx_create_dreamfights_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dreamfights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_one_id')->constrained('users');
            $table->foreignId('player_two_id')->nullable()->constrained('users');
            $table->string('player_one_fighter_id');
            $table->string('player_two_fighter_id')->nullable();
            $table->enum('player_one_choice', ['wrestling','kickbox','jiu-jitsu'])->nullable();
            $table->enum('player_two_choice', ['wrestling','kickbox','jiu-jitsu'])->nullable();
            $table->integer('player_one_score')->default(0);
            $table->integer('player_two_score')->default(0);
            $table->string('winner')->nullable();
            $table->enum('status', ['waiting','in_progress','finished'])->default('waiting');
            $table->timestamps();
        });
    }
    

    public function down(): void
    {
        Schema::dropIfExists('dreamfights');
    }
};
