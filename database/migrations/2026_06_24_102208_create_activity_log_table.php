<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_log', function (Blueprint $table) {
            $table->id();
            $table->string('operacao'); // Ex: 'Match Encontrado', 'Alerta SMS'
            $table->text('detalhes');   // Ex: 'Estudante X cruzou com Empresa Y'
            $table->timestamps();       // Cria o created_at que usamos para ordenar
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_log');
    }
};