<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perfil_estudantes', function (Blueprint $table) {
            $table->id();
            // Chave estrangeira ligada à tabela de utilizadores
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Armazena as valências selecionadas (Ex: ["Laravel", "PHP", "Redes"])
            $table->json('valencias')->nullable();
            
            // Coordenadas geográficas para o cálculo do raio de transporte
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            $table->string('municipio')->nullable();
            $table->string('provincia')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perfil_estudantes');
    }
};