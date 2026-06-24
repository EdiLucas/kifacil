<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Estudante — Kifacil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        #mapa-radar { height: 450px; z-index: 1; }
        .drag-over { border-color: #16a34a !important; background-color: #f0fdf4 !important; }
        .modal { transition: opacity 0.25s ease; }
        body.modal-active { overflow: hidden; }
    </style>
</head>
<body class="bg-slate-100 font-sans text-slate-800">

    <header class="bg-green-800 text-white shadow-md px-6 py-4 flex justify-between items-center">
        <div>
            <a href="{{ url('/') }}" class="hover:opacity-90 transition block">
                <h1 class="text-xl font-black tracking-tight text-amber-400">Ki<span class="text-white">facil</span> <span class="text-xs font-medium text-green-200 block sm:inline sm:ml-2">Área do Candidato</span></h1>
            </a>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="toggleModal()" class="text-xs bg-green-900 hover:bg-green-950 px-3 py-1.5 rounded-lg border border-green-700/50 font-medium transition flex items-center gap-1">
                <i class="fas fa-user-edit text-amber-400 mr-1"></i> {{ $estudante->name }}
            </button>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="text-xs bg-red-700 hover:bg-red-800 text-white font-bold px-3 py-1.5 rounded-lg transition">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </button>
            </form>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6">
        
        @if(session('sucesso'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl text-xs font-bold">
                {{ session('sucesso') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div onclick="toggleModal()" class="bg-gradient-to-br from-green-700 to-emerald-600 rounded-2xl p-6 text-white shadow-md flex flex-col justify-between lg:col-span-2 cursor-pointer hover:opacity-95 transition relative overflow-hidden group">
                <div class="absolute right-4 top-4 opacity-10 group-hover:opacity-20 transition text-6xl"><i class="fas fa-user-cog"></i></div>
                <div>
                    <h2 class="text-xl font-black">Olá, {{ explode(' ', $estudante->name)[0] }}!</h2>
                    <p class="text-xs text-green-100 mt-1">O teu radar está ativo. Clica em qualquer ponto deste bloco para editar o teu perfil.</p>
                </div>
                <div class="flex items-center gap-3 bg-white/10 px-4 py-2.5 rounded-xl border border-white/20 mt-4 w-fit">
                    <i class="fas fa-map-marker-alt text-amber-400 text-lg"></i>
                    <div class="text-xs">
                        <p class="font-bold">Coordenadas Base de Match:</p>
                        <p class="text-green-200 font-mono">{{ $estudante->municipio }} — {{ $estudante->provincia }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                <div>
                    <h3 class="font-black text-slate-900 text-xs uppercase tracking-wider"><i class="fas fa-code text-green-700 mr-1"></i> Minhas Valências</h3>
                    <p class="text-[10px] text-slate-400">Prime Enter ou Vírgula para fixar novas habilidades.</p>
                </div>
                <div>
                    <input type="text" id="input_skills" placeholder="Adicionar tecnologia..." class="w-full p-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-600 text-xs mb-2">
                    <div id="container_skills" class="flex flex-wrap gap-1 p-1.5 bg-slate-50 border border-dashed rounded-xl min-h-[45px]"></div>
                </div>
                <button onclick="alert('Filtros salvos com sucesso!')" class="w-full bg-green-800 hover:bg-green-900 text-white font-bold py-1.5 rounded-xl text-xs transition">
                    Atualizar Competências
                </button>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden grid grid-cols-1 lg:grid-cols-3">
            <div class="lg:col-span-2 relative">
                <div id="mapa-radar" class="w-full bg-slate-200"></div>
            </div>

            <div class="p-6 bg-slate-50 border-t lg:border-t-0 lg:border-l border-slate-200 flex flex-col justify-between space-y-6">
                <div id="ficha-vaga" class="space-y-4">
                    <div class="text-center py-8 text-slate-400" id="vaga-placeholder">
                        <i class="fas fa-crosshairs text-3xl mb-2 text-slate-300 animate-pulse"></i>
                        <p class="text-xs font-bold">Nenhuma vaga inspecionada</p>
                        <p class="text-[10px]">Clica num marcador do mapa para abrir as informações operacionais da vaga.</p>
                    </div>

                    <div id="vaga-conteudo" class="hidden space-y-3">
                        <div class="border-b pb-2">
                            <span class="bg-green-100 text-green-800 text-[9px] font-bold px-2 py-0.5 rounded uppercase font-mono tracking-wider">Lotação Identificada</span>
                            <h4 id="vaga-titulo" class="font-black text-slate-900 text-sm mt-1 leading-tight"></h4>
                            <p id="vaga-empresa" class="text-xs text-green-700 font-bold mt-0.5"></p>
                        </div>
                        <div class="text-[11px] space-y-1 bg-white p-3 rounded-xl border font-mono">
                            <p class="text-slate-400 font-bold uppercase text-[9px]">Canal Corporativo:</p>
                            <p id="vaga-email" class="text-slate-800 break-all"></p>
                            <p class="text-slate-400 font-bold uppercase text-[9px] mt-2">Perímetro Operacional:</p>
                            <p id="vaga-local" class="text-slate-600 font-sans"></p>
                        </div>
                    </div>
                </div>

                <div id="drop-zone" class="border-2 border-dashed border-slate-300 bg-white rounded-xl p-4 text-center transition flex flex-col items-center justify-center min-h-[140px] pointer-events-none opacity-50">
                    <i class="fas fa-cloud-upload-alt text-2xl text-slate-400 mb-1" id="drop-icon"></i>
                    <p class="text-xs font-bold text-slate-700" id="drop-text">Submissão Direta por Email</p>
                    <p class="text-[10px] text-slate-400 px-2 mt-0.5">Arrasta o teu PDF aqui para disparar diretamente para a empresa</p>
                </div>
            </div>
        </div>

        <div class="space-y-3">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-wider"><i class="fas fa-list text-slate-500 mr-1"></i> Índice Geral de Oportunidades</h3>
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm divide-y divide-slate-100">
                @forelse($todasVagas as $vaga)
                    <div class="p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-slate-50/50 transition">
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">{{ $vaga->titulo }}</h4>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px] text-slate-500 font-mono">
                                <span class="text-green-700 font-bold font-sans"><i class="fas fa-building"></i> {{ $vaga->empresa_nome }}</span>
                                <span><i class="fas fa-map-marker-alt text-red-500"></i> {{ $vaga->bairro }} ({{ $vaga->municipio }})</span>
                            </div>
                        </div>
                        <button onclick="focarNoMapa({{ $vaga->latitude }}, {{ $vaga->longitude }}, '{{ $vaga->titulo }}')" class="w-full sm:w-auto bg-slate-100 hover:bg-green-800 hover:text-white text-slate-700 text-xs font-bold px-3 py-1.5 rounded-xl transition border border-slate-200 text-center">
                            <i class="fas fa-crosshairs mr-1"></i> Localizar
                        </button>
                    </div>
                @empty
                    <div class="p-8 text-center text-xs text-slate-400">Sem registos.</div>
                @endforelse
            </div>
        </div>
    </main>

    <div id="modal-perfil" class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center" style="z-index: 9999;">
        <div onclick="toggleModal()" class="modal-overlay absolute w-full h-full bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded-2xl shadow-xl z-50 overflow-y-auto border">
            <div class="modal-content py-4 text-left px-6 space-y-4">
                <div class="flex justify-between items-center border-b pb-2">
                    <h3 class="text-sm font-black uppercase text-slate-900"><i class="fas fa-user-cog text-green-700 mr-1"></i> Editar Meu Perfil</h3>
                    <button onclick="toggleModal()" class="text-slate-400 hover:text-slate-600 font-bold text-lg">&times;</button>
                </div>

                <form action="{{ route('estudante.perfil.atualizar') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="block text-slate-500 font-bold mb-1">Nome Completo</label>
                        <input type="text" name="name" value="{{ $estudante->name }}" class="w-full p-2.5 border rounded-xl focus:ring-2 focus:ring-green-600 outline-none" required>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-500 font-bold mb-1">Município</label>
                            <input type="text" name="municipio" id="form_municipio" value="{{ $estudante->municipio }}" class="w-full p-2.5 border rounded-xl focus:ring-2 focus:ring-green-600 outline-none" required>
                        </div>
                        <div>
                            <label class="block text-slate-500 font-bold mb-1">Província</label>
                            <input type="text" name="provincia" id="form_provincia" value="{{ $estudante->provincia }}" class="w-full p-2.5 border rounded-xl focus:ring-2 focus:ring-green-600 outline-none" required>
                        </div>
                    </div>

                    <input type="hidden" name="latitude" id="form_lat" value="{{ $estudante->latitude }}">
                    <input type="hidden" name="longitude" id="form_lng" value="{{ $estudante->longitude }}">

                    <div class="bg-slate-50 p-2.5 rounded-xl border border-dashed flex items-center justify-between">
                        <span class="text-[10px] text-slate-500"><i class="fas fa-satellite mr-1"></i> Sincronizar coordenadas GPS atuais?</span>
                        <button type="button" onclick="capturarCoordenadasDispositivo()" class="bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold px-2 py-1 rounded text-[10px] transition">Obter GPS</button>
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t">
                        <button type="button" onclick="toggleModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-4 py-2 rounded-xl transition">Cancelar</button>
                        <button type="submit" class="bg-green-800 hover:bg-green-900 text-white font-bold px-4 py-2 rounded-xl transition">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let vagaSelecionada = null;

        // Captura as coordenadas do estudante vindas da BD Laravel
        const estudanteLat = "{{ $estudante->latitude }}";
        const estudanteLng = "{{ $estudante->longitude }}";

        // Definição do ponto central padrão (Se houver GPS do estudante, foca nele, senão vai para Luanda)
        const centroInicial = (estudanteLat && estudanteLng) ? [parseFloat(estudanteLat), parseFloat(estudanteLng)] : [-8.8368, 13.2344];
        const zoomInicial = (estudanteLat && estudanteLng) ? 14 : 12; // Dá mais ZOOM automático se tiver a localização exata

        // 1. INICIALIZAÇÃO DO MAPA
        const mapa = L.map('mapa-radar').setView(centroInicial, zoomInicial);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(mapa);

        // Se o estudante tiver coordenadas, desenha um pino azul diferenciado para ele
        if (estudanteLat && estudanteLng) {
            L.circle(centroInicial, {
                color: '#10b981',
                fillColor: '#10b981',
                fillOpacity: 0.15,
                radius: 1000 // Raio de 1km visual em volta do estudante
            }).addTo(mapa);

            L.marker(centroInicial).addTo(mapa)
                .bindPopup("<b class='text-xs text-green-800'>Estás Aqui (Tua Posição Base)</b>")
                .openPopup();
        }

        // Renderizar marcadores de vagas no mapa
        const listaVagas = @json($todasVagas);
        listaVagas.forEach(vaga => {
            if (vaga.latitude && vaga.longitude) {
                const marcador = L.marker([vaga.latitude, vaga.longitude]).addTo(mapa);
                marcador.on('click', function() {
                    ativarInspecaoVaga(vaga);
                });
            }
        });

        function ativarInspecaoVaga(vaga) {
            vagaSelecionada = vaga;
            document.getElementById('vaga-placeholder').classList.add('hidden');
            document.getElementById('vaga-conteudo').classList.remove('hidden');
            document.getElementById('vaga-titulo').innerText = vaga.titulo;
            document.getElementById('vaga-empresa').innerText = vaga.empresa_nome;
            document.getElementById('vaga-email').innerText = vaga.email || 'recrutamento@' + vaga.empresa_nome.toLowerCase().replace(/\s+/g, '') + '.ao';
            document.getElementById('vaga-local').innerText = `${vaga.bairro || 'Sede'}, ${vaga.municipio} — ${vaga.provincia}`;

            const dropZone = document.getElementById('drop-zone');
            dropZone.classList.remove('opacity-50', 'pointer-events-none');
            document.getElementById('drop-text').innerText = "Arraste o seu Currículo (PDF)";
        }

        function focarNoMapa(lat, lng, titulo) {
            mapa.setView([lat, lng], 15);
            const vaga = listaVagas.find(v => v.latitude == lat && v.longitude == lng);
            if(vaga) ativarInspecaoVaga(vaga);
        }

        // 2. MOTOR DRAG AND DROP
        const dropZone = document.getElementById('drop-zone');
        window.addEventListener('dragover', e => e.preventDefault());
        window.addEventListener('drop', e => e.preventDefault());

        dropZone.addEventListener('dragover', (e) => { e.preventDefault(); if (vagaSelecionada) dropZone.classList.add('drag-over'); });
        dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
            if (!vagaSelecionada) return;
            const files = e.dataTransfer.files;
            if (files.length > 0 && files[0].type === "application/pdf") {
                const emailEmpresa = document.getElementById('vaga-email').innerText;
                alert(`Sucesso! O teu currículo "${files[0].name}" foi enviado para: ${emailEmpresa}`);
            }
        });

        // 3. CONTROLO DO MODAL DE EDIÇÃO
        function toggleModal () {
            const body = document.querySelector('body');
            const modal = document.getElementById('modal-perfil');
            modal.classList.toggle('opacity-0');
            modal.classList.toggle('pointer-events-none');
            body.classList.toggle('modal-active');
        }

        // Captura GPS Real via API do Navegador do Usuário
        function capturarCoordenadasDispositivo() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('form_lat').value = position.coords.latitude;
                    document.getElementById('form_lng').value = position.coords.longitude;
                    alert("Coordenadas de satélite capturadas com sucesso! Salva as alterações para atualizar o teu radar.");
                }, function() {
                    alert("Não foi possível obter a tua localização. Verifica as permissões do teu navegador.");
                });
            } else {
                alert("O teu dispositivo não suporta Geolocalização.");
            }
        }

        // Tags do Painel
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.getElementById('input_skills');
            const container = document.getElementById('container_skills');
            let tags = ['HTML', 'CSS', 'JavaScript', 'PHP'];

            function render() {
                container.innerHTML = '';
                tags.forEach((tag, index) => {
                    const badge = document.createElement('span');
                    badge.className = "inline-flex items-center gap-1 bg-green-700 text-white font-bold px-2.5 py-0.5 rounded-full text-[10px] shadow-sm";
                    badge.innerHTML = `${tag} <button type="button" class="hover:text-red-300 ml-1 font-black focus:outline-none">&times;</button>`;
                    badge.querySelector('button').addEventListener('click', () => { tags.splice(index, 1); render(); });
                    container.appendChild(badge);
                });
            }
            input.onkeydown = function(e) {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    let valor = input.value.trim().replace(',', '');
                    if (valor && !tags.includes(valor)) { tags.push(valor); input.value = ''; render(); }
                }
            };
            render();
        });
    </script>
</body>
</html>