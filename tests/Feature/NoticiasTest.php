<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class NoticiasTest extends TestCase
{
    public function test_mural_exibe_noticias_quando_api_responde_com_sucesso()
    {
        // Simula uma resposta falsa e controlada da NewsAPI
        Http::fake([
            'newsapi.org/*' => Http::response([
                'articles' => [
                    [
                        'title' => 'Inovação Tecnológica em Luanda',
                        'description' => 'Estudantes desenvolvem novo sistema...',
                        'url' => 'https://exemplo.com',
                        'publishedAt' => now()->toIso8601String(),
                        'source' => ['name' => 'Tech Angola']
                    ]
                ]
            ], 200)
        ]);

        $response = $this->get(route('noticias.index'));

        $response->assertStatus(200);
        $response->assertSee('Inovação Tecnológica em Luanda');
    }

    public function test_mural_nao_rebeta_se_api_estiver_offline()
    {
        // Simula uma falha crítica de conexão (erro 500 ou queda de servidor)
        Http::fake([
            'newsapi.org/*' => Http::response([], 500)
        ]);

        $response = $this->get(route('noticias.index'));

        // Garante que a aplicação continua online e mostra a nossa mensagem amigável de erro
        $response->assertStatus(200);
        $response->assertSee('Nenhuma publicação de tecnologia encontrada');
    }
}