<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Exception;

class NoticiaController extends Controller
{
    public function index()
    {
        $noticias = [];

try {
    // Usamos o NOT para excluir futebol e política em vários idiomas
    $response = Http::timeout(5)->get('https://newsapi.org/v2/everything', [
        'q' => '(software OR "artificial intelligence" OR cibersegurança OR tecnologia OR "software engineering" OR developer) NOT (futebol OR football OR futeboll OR política OR politics OR eleição OR elecciones)',
        'sortBy' => 'publishedAt',
        'pageSize' => 6,
        'apiKey' => '307cae176cb54d39b3b50436cbc2013b'
    ]);

    if ($response->successful()) {
        $noticias = $response->json()['articles'] ?? [];
    }

} catch (ConnectionException | Exception $e) {
    logger()->error('Falha ao conectar com a NewsAPI: ' . $e->getMessage());
}

// Antes: return view('noticias', compact('noticias'));
return view('noticias.index', compact('noticias'));
    }
}