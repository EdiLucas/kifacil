<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            
            // Define o perfil do utilizador: 'admin', 'empresa' ou 'estudante'
            $table->enum('role', ['admin', 'empresa', 'estudante'])->default('estudante');
            
            // Campo status integrado com o modificador de posição
            $table->string('status')->default('ativo'); 
            
            // CAMPOS DE GEOLOCALIZAÇÃO COMPLETOS E SKILLS
            $table->string('bairro')->nullable();
            $table->string('municipio')->nullable();
            $table->string('provincia')->nullable();
            
            // Coordenadas geográficas do utilizador (Estudante ou Empresa) para cálculo de raio no Radar
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            $table->text('skills')->nullable(); 
            
            $table->rememberToken();
            $table->timestamps();
        });
    } 

    public function down(): void
    {
        // Garante o rollback limpo de toda a estrutura de utilizadores
        Schema::dropIfExists('users');
    }
};