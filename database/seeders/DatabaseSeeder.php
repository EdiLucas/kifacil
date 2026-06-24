<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PerfilEstudante;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. CRIAR O ADMINISTRADOR DO ECOSSISTEMA
        User::create([
            'name' => 'Administrador Kifacil',
            'email' => 'admin@kifacil.com',
            'password' => Hash::make('admin123'), // Senha padrão para testes
            'role' => 'admin',
        ]);

        // 2. CRIAR UMA EMPRESA DE TESTE
        $empresa = User::create([
            'name' => 'AngoTech Soluções',
            'email' => 'recrutamento@angotech.ao',
            'password' => Hash::make('empresa123'),
            'role' => 'empresa',
        ]);

        // 3. CRIAR UM ESTUDANTE DE TESTE (Exemplo: Residente na Maianga, Luanda)
        $estudante = User::create([
            'name' => 'Silvano Lucas',
            'email' => 'estudante@kifacil.com',
            'password' => Hash::make('user123'),
            'role' => 'estudante',
        ]);

        // Criar o perfil georreferenciado e valências técnicos do estudante
        PerfilEstudante::create([
            'user_id' => $estudante->id,
            // Valências salvas no formato JSON compatível com a nossa migration
            'valencias' => json_encode(['PHP', 'Laravel', 'C', 'Redes (CCNA)', 'Cybersecurity']),
            'latitude' => -8.838333,   // Coordenadas aproximadas da Maianga
            'longitude' => 13.234444,
            'municipio' => 'Luanda',
            'provincia' => 'Luanda',
        ]);

        // No final do método run(), logo abaixo do PerfilEstudante::create(...):
\Illuminate\Support\Facades\DB::table('activity_log')->insert([
    [
        'operacao' => 'Match Encontrado',
        'detalhes' => 'Estudante Silvano Lucas cruzou com AngoTech (Raio: 12km)',
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'operacao' => 'Alerta SMS Enviado',
        'detalhes' => 'Notificação de vaga geo-disparada para o terminal do candidato.',
        'created_at' => now()->subMinutes(10),
        'updated_at' => now()->subMinutes(10)
    ]
]);
    }
}
