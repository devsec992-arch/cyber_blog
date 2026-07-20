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
        Schema::create('files', function (Blueprint $table) {
             $table->id();
            $table->string('uid')->unique(); // Crea la colonna UID e la imposta come unica
            $table->string('name')->nullable(); // Opzionale: nome del file
            $table->string('path')->nullable(); // Opzionale: percorso di archiviazione
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Opzionale: collega il file all'utente
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
