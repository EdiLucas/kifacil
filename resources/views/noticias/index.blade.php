<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mural de Notícias Tecnológicas — Kifacil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 font-sans text-slate-800">

    <!-- Header Padrão -->
    <header class="bg-green-800 text-white shadow-md px-6 py-4 flex justify-between items-center">
        <div>
            <a href="{{ url('/') }}" class="hover:opacity-90 transition block">
                <h1 class="text-xl font-black tracking-tight text-amber-400">Ki<span class="text-white">facil</span> <span class="text-xs font-medium text-green-200 block sm:inline sm:ml-2">Inovação & Tech</span></h1>
            </a>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('mapa.publico') }}" class="text-xs bg-green-900 hover:bg-green-950 px-3 py-1.5 rounded-lg font-bold transition">
                <i class="fas fa-satellite-dish text-amber-400 mr-1"></i> Radar de Vagas
            </a>
            <a href="{{ url('/') }}" class="text-xs bg-slate-700 hover:bg-slate-800 text-white px-3 py-1.5 rounded-lg font-bold transition">
                <i class="fas fa-home"></i> Início
            </a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6">
        
        <!-- Topo Informativo -->
        <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm">
            <span class="bg-green-100 text-green-800 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Mural Tecnológico</span>
            <h2 class="text-xl font-black text-slate-900 mt-1">Atualizações de Tecnologia & TI</h2>
            <p class="text-xs text-slate-500">Artigos e novidades focados exclusivamente no ecossistema de inovação, desenvolvimento e engenharia de software.</p>
        </div>

        <!-- Grelha de Notícias -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($noticias as $artigo)
                @if(!empty($artigo['title']) && $artigo['title'] !== '[Removed]')
                    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm flex flex-col justify-between hover:border-green-600/40 transition">
                        
                        <!-- Capa da Notícia -->
                        <div class="h-44 bg-slate-200 relative overflow-hidden">
                            @if(!empty($artigo['urlToImage']))
                                <img src="{{ $artigo['urlToImage'] }}" alt="Capa" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                                    <i class="fas fa-laptop-code text-3xl mb-2"></i>
                                    <span class="text-[10px] font-mono">Imagem indisponível</span>
                                </div>
                            @endif
                            <span class="absolute bottom-2 left-2 bg-slate-900/80 text-amber-400 text-[9px] font-mono px-2 py-0.5 rounded backdrop-blur-sm">
                                {{ $artigo['source']['name'] ?? 'Tech Source' }}
                            </span>
                        </div>

                        <!-- Conteúdo Retido -->
                        <div class="p-4 space-y-2 flex-grow">
                            <span class="text-[10px] text-slate-400 font-mono block">
                                <i class="far fa-calendar-alt mr-1"></i>{{ \Carbon\Carbon::parse($artigo['publishedAt'])->format('d/m/Y H:i') }}
                            </span>
                            <h3 class="font-bold text-slate-900 text-sm leading-snug line-clamp-2 hover:text-green-800 transition">
                                {{ $artigo['title'] }}
                            </h3>
                            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                                {{ $artigo['description'] ?? 'Sem resumo descritivo disponível para esta publicação.' }}
                            </p>
                        </div>

                        <!-- Rodapé do Card -->
                        <div class="p-4 pt-0 border-t border-slate-100 mt-2">
                            <a href="{{ $artigo['url'] }}" target="_blank" class="w-full mt-2 block bg-slate-100 hover:bg-green-800 hover:text-white text-slate-700 text-center font-bold py-2 rounded-xl text-xs transition">
                                Ler Artigo Completo <i class="fas fa-external-link-alt ml-1 text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-span-full bg-white border p-12 rounded-2xl text-center text-slate-400 text-xs">
                    <i class="fas fa-rss-off text-3xl mb-2 text-slate-300"></i>
                    <p class="font-bold text-slate-600">Nenhuma publicação de tecnologia encontrada.</p>
                    <p class="text-[10px] max-w-xs mx-auto mt-1">Por favor, tenta atualizar a página ou verifica os critérios de pesquisa no controlador.</p>
                </div>
            @endforelse
        </div>
    </main>

</body>
</html>