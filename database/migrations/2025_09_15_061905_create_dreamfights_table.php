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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('fighter_one_name');
            $table->string('fighter_two_name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dreamfights');
    }
};
