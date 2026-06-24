<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kifacil — Recrutamento de Estágios por Georreferenciação</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-slate-50 flex flex-col min-h-screen justify-between text-slate-800 font-sans">

    <nav class="bg-green-800 text-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a class="text-2xl font-black tracking-tight" href="{{ url('/') }}">
                    Ki<span class="text-amber-400">facil</span>
                </a>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('noticias.index') }}" class="text-sm font-semibold hover:text-amber-400 transition flex items-center gap-1.5 px-2 py-1">
                        <i class="fas fa-newspaper text-base"></i> Notícias
                    </a>

                    @guest
                        <!-- Caso não esteja logado: Mostra Iniciar Sessão -->
                        <a href="{{ route('login') }}" class="bg-transparent border border-white hover:bg-white hover:text-green-800 text-sm font-bold px-4 py-2 rounded-xl transition shadow-sm">
                            <i class="fas fa-user-lock mr-1"></i> Iniciar Sessão
                        </a>
                    @else
                        <div class="flex items-center gap-3 bg-green-900/50 pl-3 pr-2 py-1.5 rounded-xl border border-green-700/50">
                            <span class="text-xs font-medium text-green-100">
                                <i class="fas fa-user text-amber-400 mr-1"></i> {{ Auth::user()->name }}
                            </span>
                            
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="bg-white text-green-800 hover:bg-green-50 text-xs font-bold px-3 py-1.5 rounded-lg transition shadow-sm flex items-center gap-1">
                                    <i class="fas fa-sliders-h"></i> Painel Admin
                                </a>
                            @elseif(Auth::user()->role === 'empresa')
                                <a href="{{ route('empresa.dashboard') }}" class="bg-white text-green-800 hover:bg-green-50 text-xs font-bold px-3 py-1.5 rounded-lg transition shadow-sm flex items-center gap-1">
                                    <i class="fas fa-building"></i> Painel Empresa
                                </a>
                            @else
                                <a href="{{ route('estudante.dashboard') }}" class="bg-white text-green-800 hover:bg-green-50 text-xs font-bold px-3 py-1.5 rounded-lg transition shadow-sm flex items-center gap-1">
                                    <i class="fas fa-graduation-cap"></i> Meu Painel
                                </a>
                            @endif

                            <form action="{{ route('logout') }}" method="POST" class="inline m-0">
                                @csrf
                                <button type="submit" class="text-xs bg-red-700/80 hover:bg-red-700 text-white font-bold px-2 py-1.5 rounded-lg transition" title="Sair">
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                            </form>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <header class="bg-gradient-to-br from-green-700 via-green-600 to-emerald-500 text-white text-center py-16 sm:py-24 px-4 shadow-inner">
        <div class="max-w-4xl mx-auto">
            <span class="inline-block bg-white text-green-700 text-xs font-bold uppercase tracking-wider px-3 py-1.5 rounded-full mb-4 shadow-sm">
                Inovação em Angola
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight mb-4 leading-tight">
                Recrutamento Inteligente de Estágios
            </h1>
            <p class="text-lg sm:text-xl text-green-50 max-w-2xl mx-auto font-light leading-relaxed mb-8">
                Conectamos estudantes e empresas através de um motor que cruza valências técnicas e viabilidade geográfica de transporte.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                <a href="{{ route('mapa.publico') }}" class="w-full sm:w-auto bg-white hover:bg-green-50 text-green-700 font-bold px-8 py-3.5 rounded-xl transition shadow-md flex items-center justify-center gap-2">
                    <i class="fas fa-map-marked-alt"></i> Radar de Vagas
                </a>
                <a href="{{ route('noticias.index') }}" class="w-full sm:w-auto bg-amber-400 hover:bg-amber-500 text-slate-900 font-bold px-8 py-3.5 rounded-xl transition shadow-md flex items-center justify-center gap-2">
                    <i class="fas fa-bolt"></i> Ver Novidades
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-12 sm:my-16 flex-grow w-full">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            
            <div class="bg-white border border-slate-100 p-8 rounded-2xl shadow-sm hover:shadow-md transition flex flex-col items-center">
                <div class="w-16 h-16 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-3xl mb-4">
                    <i class="fas fa-street-view"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Raio Geográfico</h3>
                <p class="text-slate-500 text-sm leading-relaxed">
                    A empresa define o limite logístico máximo em quilómetros, garantindo que o estagiário reside num perímetro de transporte viável.
                </p>
            </div>
            
            <div class="bg-white border border-slate-100 p-8 rounded-2xl shadow-sm hover:shadow-md transition flex flex-col items-center">
                <div class="w-16 h-16 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-3xl mb-4">
                    <i class="fas fa-code-branch"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Filtro por Valências</h3>
                <p class="text-slate-500 text-sm leading-relaxed">
                    Triagem meritocrática instantânea baseada no mapeamento das competências técnicas exigidas pelo mercado de trabalho.
                </p>
            </div>

            <div class="bg-white border border-slate-100 p-8 rounded-2xl shadow-sm hover:shadow-md transition flex flex-col items-center">
                <div class="w-16 h-16 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-3xl mb-4">
                   <i class="fa-solid fa-bell"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Alertas de vagas</h3>
                <p class="text-slate-500 text-sm leading-relaxed">
                    Disparo automático e em background para os candidatos com match ideal, garantindo o alcance das vagas mesmo offline.
                </p>
            </div>
            
        </div>
    </main>

    <footer class="bg-white border-t border-slate-100 py-6 text-center mt-auto w-full">
        <div class="max-w-7xl mx-auto px-4 text-xs text-slate-400 font-medium">
            <p>&copy; {{ date('Y') }} Kifacil. Todos os direitos reservados.</p>
        </div>
    </footer>

</body>

</html>