<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Radar Extrator de Vagas — Kifacil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 font-sans text-slate-800">

    <!-- Header Padrão -->
    <header class="bg-green-800 text-white shadow-md px-6 py-4 flex justify-between items-center">
        <div>
            <a href="{{ url('/') }}" class="hover:opacity-90 transition block">
                <h1 class="text-xl font-black tracking-tight text-amber-400">Ki<span class="text-white">facil</span> <span class="text-xs font-medium text-green-200 block sm:inline sm:ml-2">Scrapper & Indexer</span></h1>
            </a>
        </div>
        <div class="flex gap-4">
            <a href="{{ url('/noticias') }}" class="text-xs bg-green-900 hover:bg-green-950 px-3 py-1.5 rounded-lg font-bold transition">
                <i class="fas fa-newspaper text-amber-400 mr-1"></i> Mural Tech
            </a>
            <a href="{{ url('/') }}" class="text-xs bg-slate-700 hover:bg-slate-800 text-white px-3 py-1.5 rounded-lg font-bold transition">
                <i class="fas fa-home"></i> Início
            </a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6">
        
        <!-- Barra Operacional Superior -->
        <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm space-y-4">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-2">
                <div>
                    <span class="bg-amber-100 text-amber-800 text-[10px] font-black px-2 py-0.5 rounded uppercase tracking-wider font-mono">Crawler Ativo</span>
                    <h2 class="text-xl font-black text-slate-900 mt-1">Radar Global de Oportunidades</h2>
                    <p class="text-xs text-slate-500">Captura em tempo real de vagas publicadas nas plataformas concorrentes e portais corporativos externos.</p>
                </div>
                
                <!-- Filtros Rápidos Estilizados -->
                <div class="flex gap-1.5 bg-slate-100 p-1 rounded-xl border">
                    <button class="bg-green-800 text-white text-[11px] font-bold px-3 py-1.5 rounded-lg transition shadow-sm">Todas as Fontes</button>
                    <button class="text-slate-600 hover:bg-slate-200 text-[11px] font-bold px-3 py-1.5 rounded-lg transition">Jobartis</button>
                    <button class="text-slate-600 hover:bg-slate-200 text-[11px] font-bold px-3 py-1.5 rounded-lg transition">LinkedIn</button>
                </div>
            </div>
        </div>

        <!-- MURAL EXTERNO INDEXADO -->
        <div class="space-y-3">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-wider"><i class="fas fa-satellite text-slate-500 mr-1"></i> Sinais Externos Capturados</h3>
            
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm divide-y divide-slate-100 overflow-hidden">
                
                <!-- Vaga Exemplo 1 -->
                <div class="p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-slate-50/50 transition">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="bg-blue-600 text-white text-[9px] font-mono px-2 py-0.2 rounded font-bold uppercase tracking-tight">LinkedIn Indexer</span>
                            <h4 class="font-bold text-slate-900 text-sm">Administrador de Sistemas Linux RedHat</h4>
                        </div>
                        <p class="text-xs text-slate-600 max-w-2xl">Gerenciamento de infraestrutura local, segurança defensiva, parametrização de servidores Apache/Nginx e automação Shell Scripting.</p>
                        <div class="flex gap-2 text-[10px] font-mono text-slate-400 pt-1">
                            <span><i class="far fa-building mr-1"></i>BFA Institucional</span>
                            <span><i class="far fa-clock mr-1"></i>Há 2 horas</span>
                        </div>
                    </div>
                    <div class="flex sm:flex-col items-center sm:items-end justify-between w-full sm:w-auto border-t sm:border-t-0 pt-2 sm:pt-0 gap-2">
                        <span class="text-green-700 font-bold text-xs"><i class="fas fa-map-marker-alt text-red-500 mr-1"></i>Luanda, Talatona</span>
                        <a href="https://linkedin.com" target="_blank" class="bg-slate-900 hover:bg-green-800 text-white text-xs font-bold px-3 py-1.5 rounded-xl transition text-center whitespace-nowrap">
                            Candidatura Externa <i class="fas fa-external-link-alt text-[9px] ml-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Vaga Exemplo 2 -->
                <div class="p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-slate-50/50 transition">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="bg-orange-600 text-white text-[9px] font-mono px-2 py-0.2 rounded font-bold uppercase tracking-tight">Jobartis API</span>
                            <h4 class="font-bold text-slate-900 text-sm">Desenvolvedor Back-End Sênior (PHP / Laravel)</h4>
                        </div>
                        <p class="text-xs text-slate-600 max-w-2xl">Refatoração de APIsRESTful, integração com gateways de pagamento locais, otimização de queries MySQL complexas e barramentos internos.</p>
                        <div class="flex gap-2 text-[10px] font-mono text-slate-400 pt-1">
                            <span><i class="far fa-building mr-1"></i>Unitel S.A.</span>
                            <span><i class="far fa-clock mr-1"></i>Há 5 horas</span>
                        </div>
                    </div>
                    <div class="flex sm:flex-col items-center sm:items-end justify-between w-full sm:w-auto border-t sm:border-t-0 pt-2 sm:pt-0 gap-2">
                        <span class="text-green-700 font-bold text-xs"><i class="fas fa-map-marker-alt text-red-500 mr-1"></i>Luanda, Maianga</span>
                        <a href="https://jobartis.com" target="_blank" class="bg-slate-900 hover:bg-green-800 text-white text-xs font-bold px-3 py-1.5 rounded-xl transition text-center whitespace-nowrap">
                            Candidatura Externa <i class="fas fa-external-link-alt text-[9px] ml-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Vaga Exemplo 3 -->
                <div class="p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-slate-50/50 transition">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="bg-red-600 text-white text-[9px] font-mono px-2 py-0.2 rounded font-bold uppercase tracking-tight">NetEmprego</span>
                            <h4 class="font-bold text-slate-900 text-sm">Analista de Cibersegurança Júnior (Blue Team)</h4>
                        </div>
                        <p class="text-xs text-slate-600 max-w-2xl">Monitoria de logs através de SIEM, mitigação básica de incidentes, auditorias superficiais e mapeamento de vetores de risco internos.</p>
                        <div class="flex gap-2 text-[10px] font-mono text-slate-400 pt-1">
                            <span><i class="far fa-building mr-1"></i>Tecnologias de Angola</span>
                            <span><i class="far fa-clock mr-1"></i>Ontem</span>
                        </div>
                    </div>
                    <div class="flex sm:flex-col items-center sm:items-end justify-between w-full sm:w-auto border-t sm:border-t-0 pt-2 sm:pt-0 gap-2">
                        <span class="text-green-700 font-bold text-xs"><i class="fas fa-map-marker-alt text-red-500 mr-1"></i>Benguela</span>
                        <a href="#link-externo" class="bg-slate-900 hover:bg-green-800 text-white text-xs font-bold px-3 py-1.5 rounded-xl transition text-center whitespace-nowrap">
                            Candidatura Externa <i class="fas fa-external-link-alt text-[9px] ml-1"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </main>

</body>
</html>