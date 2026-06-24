<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidaturas', function (Blueprint $table) {
            $table->id();
            // Estudante que concorre (aponta para users)
            $table->foreignId('estudante_id')->constrained('users')->onDelete('cascade');
            
            // Vaga de estágio (aponta para vaga_estagios que enviaste)
            $table->foreignId('vaga_estagio_id')->constrained('vaga_estagios')->onDelete('cascade');
            
            // Distância calculada pelo motor geográfico no momento do Match
            $table->decimal('distancia_km', 5, 2); 
            
            // Estado da candidatura
            $table->enum('status', ['pendente', 'aceite', 'rejeitada'])->default('pendente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidaturas');
    }
};