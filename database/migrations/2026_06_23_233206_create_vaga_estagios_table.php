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
        Schema::create('vaga_estagios', function (Blueprint $table) {
            $table->id();
            
            // Identifica a empresa dona da vaga (referencia a tabela users)
            $table->foreignId('empresa_id')->constrained('users')->onDelete('cascade');
            
            // Detalhes da Oportunidade
            $table->string('titulo');
            $table->text('descricao');
            
            // Valências exigidas armazenadas em formato JSON (Ex: ["PHP", "Tailwind", "MySQL"])
            $table->json('valencias_exigidas');
            
            // Localização textual da vaga para exibição rápida e filtros diretos
            $table->string('bairro')->nullable();
            $table->string('municipio'); // Ex: Viana, Cacuaco, Maianga
            $table->string('provincia')->default('Luanda');
            
            // Coordenadas geográficas precisas para o cálculo de distância do Motor Radar
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            
            // O Raio limite em KM escolhido pela empresa para o match com o estudante
            $table->integer('raio_atuacao_km')->default(15);
            
            // Estado operacional da vaga
            $table->enum('status', ['ativa', 'preenchida', 'cancelada'])->default('ativa');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaga_estagios');
    }
};