<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controlo Central — Kifacil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 font-sans text-slate-800">

<header class="bg-green-900 text-white shadow-md px-6 py-4 flex justify-between items-center">
        <div>
            <!-- Transformado em link para voltar ao Welcome -->
            <a href="{{ url('/') }}" class="hover:opacity-90 transition block">
                <h1 class="text-xl font-black tracking-tight text-amber-400">Ki<span class="text-white">facil Admin</span></h1>
                <p class="text-xs text-green-200">Painel Central de Monitorização e Governação</p>
            </a>
        </div>
        <div class="flex items-center gap-4">
            @if($sistemaOnline)
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Sistema Online
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-red-500/20 text-red-400 border border-red-500/30">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span> Sem Ligação
                </span>
            @endif

            <span class="text-xs bg-green-800 px-3 py-1.5 rounded-lg border border-green-700 font-medium">
                <i class="fas fa-user-shield text-amber-400 mr-1"></i> {{ Auth::user()->name }}
            </span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs bg-red-700 hover:bg-red-800 text-white font-bold px-3 py-1.5 rounded-lg transition">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </button>
            </form>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 space-y-8">

        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Gerir Estudantes</h3>
                        <p class="text-3xl font-black text-slate-900 mt-1">{{ $totalEstudantes }}</p>
                    </div>
                    <div class="w-10 h-10 bg-green-50 text-green-700 rounded-xl flex items-center justify-center text-lg shadow-sm">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>
                <div class="border-t border-slate-100 pt-3">
                    <a href="{{ route('admin.estudantes') }}" class="block text-xs font-bold text-green-700 hover:bg-green-50 px-2.5 py-1.5 rounded-lg transition border border-green-100 w-full text-center">
                        <i class="fas fa-list mr-1"></i> Listar Candidatos
                    </a>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Empresas (Contratantes)</h3>
                        <div class="flex items-baseline gap-2 mt-1">
                            <span class="text-3xl font-black text-slate-900">{{ $empresasAtivas }}</span>
                            <span class="text-xs font-bold text-slate-400">Verificadas</span>
                        </div>
                    </div>
                    <div class="w-10 h-10 bg-green-50 text-green-700 rounded-xl flex items-center justify-center text-lg shadow-sm">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
                <div class="border-t border-slate-100 pt-3 flex items-center justify-between gap-2">
                    <span class="text-xs font-bold text-red-600 bg-red-50 px-2.5 py-1.5 rounded-lg @if($empresasPendentes > 0) animate-pulse @endif">
                        {{ $empresasPendentes }} Pendentes
                    </span>
                    <a href="{{ route('admin.empresas') }}" class="text-xs font-bold text-green-700 hover:bg-green-50 px-2.5 py-1.5 rounded-lg transition border border-green-100">
                        Validar Alvarás
                    </a>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Monitorização de Vagas</h3>
                        <p class="text-3xl font-black text-slate-900 mt-1">{{ $vagasAtivas }} <span class="text-xs font-bold text-slate-400">Ativas</span></p>
                    </div>
                    <div class="w-10 h-10 bg-green-50 text-green-700 rounded-xl flex items-center justify-center text-lg shadow-sm">
                        <i class="fas fa-briefcase"></i>
                    </div>
                </div>
                <div class="border-t border-slate-100 pt-3">
                    <a href="{{ route('admin.vagas') }}" class="block text-xs font-bold text-green-700 hover:bg-green-50 px-2.5 py-1.5 rounded-lg transition border border-green-100 w-full text-center">
                        <i class="fas fa-search-location mr-1"></i> Auditar Motor Geográfico
                    </a>
                </div>
            </div>
        </section>

        <section class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/70 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Logs de Auditoria Securitária</h2>
                    <p class="text-xs text-slate-400">Clique em qualquer registo para expandir e verificar os detalhes operacionais</p>
                </div>
                <a href="{{ route('admin.logs.exportar') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-green-700 hover:bg-green-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-sm">
                    <i class="fas fa-file-export"></i> Exportar Histórico (.CSV)
                </a>
            </div>

            <div class="p-4 space-y-2">
                @forelse($logs as $log)
                    <details class="group border border-slate-200 rounded-xl bg-slate-50 overflow-hidden transition-all duration-300 [&_summary::-webkit-details-marker]:hidden">
                        <summary class="flex items-center justify-between p-4 bg-white cursor-pointer select-none group-open:bg-slate-50 hover:bg-slate-50/50 transition">
                            <div class="flex flex-wrap items-center gap-4 text-xs">
                                <span class="font-mono text-slate-400 bg-slate-100 px-2 py-1 rounded-md">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') }}
                                </span>
                                <span class="font-bold text-green-800 bg-green-50 px-2.5 py-1 rounded-md">
                                    {{ $log->operacao }}
                                </span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs text-slate-400 group-open:hidden"><i class="fas fa-chevron-down"></i> Expandir</span>
                                <span class="text-xs text-slate-400 hidden group-open:inline"><i class="fas fa-chevron-up"></i> Recolher</span>
                            </div>
                        </summary>

                        <div class="p-4 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div class="text-xs text-slate-600 font-medium space-y-1">
                                <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Detalhes do Evento:</p>
                                <p class="text-slate-800 bg-white p-3 rounded-lg border border-slate-100 shadow-sm font-mono max-w-2xl">
                                    {{ $log->detalhes }}
                                </p>
                            </div>
                            
                            <button onclick="alert('Log ID #{{ $log->id }} referenciado para auditoria.')" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold px-3 py-2 rounded-lg transition shadow-sm whitespace-nowrap">
                                <i class="fas fa-quote-right text-[10px]"></i> Citar Operação
                            </button>
                        </div>
                    </details>
                @empty
                    <div class="py-8 text-center text-slate-400 text-sm">
                        <i class="fas fa-folder-open block text-2xl mb-2"></i> Sem registos de auditoria na base de dados.
                    </div>
                @endforelse
            </div>
        </section>
    </main>
</body>
</html>