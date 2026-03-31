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
        Schema::create('telephone_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compte_id')->constrained('comptes')->onDelete('cascade');
            $table->foreignId('telephone_id')->constrained('telephones')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telephone_user');
    }
};
