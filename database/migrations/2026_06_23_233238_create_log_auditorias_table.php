<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_auditorias', function (Blueprint $table) {
            $table->id();
            // Utilizador que gerou a ação (pode ser nulo se for um evento do sistema)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->string('operacao'); // Ex: "Criação de Vaga", "Match Encontrado", "Alerta SMS"
            $table->text('detalhes')->nullable(); // Descrição textual complementar
            $table->string('ip_address', 45)->nullable();
            $table->timestamps(); // O created_at serve como o Timestamp imutável da ação
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_auditorias');
    }
};