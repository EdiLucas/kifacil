<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Corporativo — Kifacil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        #mapa-empresa { height: 450px; z-index: 1; }
        .modal { transition: opacity 0.25s ease; }
        body.modal-active { overflow: hidden; }
    </style>
</head>
<body class="bg-slate-100 font-sans text-slate-800">

    <header class="bg-green-800 text-white shadow-md px-6 py-4 flex justify-between items-center">
        <div>
            <a href="{{ url('/') }}" class="hover:opacity-90 transition block">
                <h1 class="text-xl font-black tracking-tight text-amber-400">Ki<span class="text-white">facil</span> <span class="text-xs font-medium text-green-200 block sm:inline sm:ml-2">Área Corporativa</span></h1>
            </a>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="toggleModal()" class="text-xs bg-green-900 hover:bg-green-950 px-3 py-1.5 rounded-lg border border-green-700/50 font-medium transition flex items-center gap-1">
                <i class="fas fa-building text-amber-400 mr-1"></i> {{ $empresa->name }}
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
            
            <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
                <div id="mapa-empresa" class="w-full bg-slate-200"></div>
                
                <div class="p-4 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div id="vaga-placeholder">
                        <p class="text-xs font-bold text-slate-700"><i class="fas fa-map-marker-alt text-green-700 mr-1 animate-pulse"></i> Mapeamento Automático Ativo</p>
                        <p class="text-[10px] text-slate-400">Clica em qualquer ponto do mapa para preencher a localização geográfica da vaga.</p>
                    </div>
                    <div id="vaga-conteudo" class="hidden font-mono text-xs">
                        <span class="bg-green-100 text-green-800 text-[9px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">Endereço Identificado:</span>
                        <h4 id="info-endereco" class="font-black text-slate-900 mt-1"></h4>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                <div>
                    <h3 class="font-black text-slate-900 text-xs uppercase tracking-wider"><i class="fas fa-plus-circle text-green-800 mr-1"></i> Publicar Vaga</h3>
                </div>

                <form action="{{ route('empresa.vagas.salvar') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="block text-slate-500 font-bold mb-1">Título do Cargo</label>
                        <input type="text" name="titulo" placeholder="Ex: Engenheiro de Software" class="w-full p-2 border rounded-xl outline-none focus:ring-2 focus:ring-green-600" required>
                    </div>

                    <div>
                        <label class="block text-slate-500 font-bold mb-1">Valências Requeridas (Vírgulas)</label>
                        <input type="text" name="valencias" placeholder="Ex: PHP, Java, Linux" class="w-full p-2 border rounded-xl outline-none focus:ring-2 focus:ring-green-600" required>
                    </div>

                    <div>
                        <label class="block text-slate-500 font-bold mb-1">Requisitos Detalhados</label>
                        <textarea name="descricao" rows="2" placeholder="Breve resumo da oportunidade..." class="w-full p-2 border rounded-xl outline-none focus:ring-2 focus:ring-green-600" required></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-slate-500 font-bold mb-1">Província</label>
                            <input type="text" name="provincia" id="vaga_provincia" placeholder="Clique no mapa..." class="w-full p-2 bg-slate-50 border rounded-xl outline-none font-bold" required>
                        </div>
                        <div>
                            <label class="block text-slate-500 font-bold mb-1">Município</label>
                            <input type="text" name="municipio" id="vaga_municipio" placeholder="Clique no mapa..." class="w-full p-2 bg-slate-50 border rounded-xl outline-none font-bold" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-slate-500 font-bold mb-1">Bairro / Distrito</label>
                            <input type="text" name="bairro" id="vaga_bairro" placeholder="Opcional" class="w-full p-2 border rounded-xl outline-none">
                        </div>
                        <div>
                            <label class="block text-slate-500 font-bold mb-1">Raio de Match (KM)</label>
                            <input type="number" name="raio_atuacao_km" min="1" max="100" value="15" class="w-full p-2 border rounded-xl outline-none focus:ring-2 focus:ring-green-600" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 font-mono text-[9px] bg-slate-900 text-slate-300 p-2 rounded-xl">
                        <div>
                            <span class="text-slate-500 block">LATITUDE:</span>
                            <input type="text" name="latitude" id="vaga_lat" readonly class="bg-transparent font-bold text-amber-400 outline-none w-full" placeholder="0.000000" required>
                        </div>
                        <div>
                            <span class="text-slate-500 block">LONGITUDE:</span>
                            <input type="text" name="longitude" id="vaga_lng" readonly class="bg-transparent font-bold text-amber-400 outline-none w-full" placeholder="0.000000" required>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-green-800 hover:bg-green-900 text-white font-bold py-1.5 rounded-xl text-xs transition">
                        Lançar Oportunidade
                    </button>
                </form>
            </div>
        </div>

        <div class="space-y-3">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-wider"><i class="fas fa-list text-slate-500 mr-1"></i> Nossas Oportunidades Emitindo Sinais</h3>
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm divide-y divide-slate-100">
                @forelse($vagas as $vaga)
                    <div class="p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-slate-50/50 transition">
                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-900 text-sm leading-tight">{{ $vaga->titulo }}</h4>
                            <p class="text-xs text-slate-600 max-w-2xl">{{ $vaga->descricao }}</p>
                            <div class="flex flex-wrap gap-1.5 pt-1">
                                @if(!empty($vaga->valencias_exigidas))
                                    @foreach(json_decode($vaga->valencias_exigidas) as $lang)
                                        <span class="bg-green-700 text-white font-bold px-2 py-0.5 rounded-full text-[10px] shadow-sm">{{ $lang }}</span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="flex sm:flex-col items-center sm:items-end justify-between w-full sm:w-auto border-t sm:border-t-0 pt-2 sm:pt-0 gap-2">
                            <div class="text-[11px] font-mono text-slate-500 text-left sm:text-right">
                                <p class="text-green-700 font-sans font-bold"><i class="fas fa-map-marker-alt text-red-500 mr-1"></i>{{ $vaga->municipio }} ({{ $vaga->bairro ?? 'Sede' }})</p>
                                <p class="text-[10px]">Raio: {{ $vaga->raio_atuacao_km }} KM</p>
                            </div>
                            <button onclick="focarNoMapa({{ $vaga->latitude }}, {{ $vaga->longitude }}, '{{ $vaga->titulo }}')" class="bg-slate-100 hover:bg-green-800 hover:text-white text-slate-700 text-xs font-bold px-3 py-1.5 rounded-xl transition border text-center">
                                <i class="fas fa-crosshairs mr-1"></i> Localizar
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-xs text-slate-400">Nenhuma vaga lançada para esta instituição até ao momento.</div>
                @endforelse
            </div>
        </div>
    </main>

    <div id="modal-perfil" class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center" style="z-index: 9999;">
        <div onclick="toggleModal()" class="modal-overlay absolute w-full h-full bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded-2xl shadow-xl z-50 overflow-y-auto border">
            <div class="modal-content py-4 text-left px-6 space-y-4">
                <div class="flex justify-between items-center border-b pb-2">
                    <h3 class="text-sm font-black uppercase text-slate-900"><i class="fas fa-user-cog text-green-700 mr-1"></i> Perfil Corporativo</h3>
                    <button onclick="toggleModal()" class="text-slate-400 hover:text-slate-600 font-bold text-lg">&times;</button>
                </div>

                <form action="{{ route('empresa.perfil.atualizar') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="block text-slate-500 font-bold mb-1">Nome de Registo / Instituição</label>
                        <input type="text" name="name" value="{{ $empresa->name }}" class="w-full p-2.5 border rounded-xl focus:ring-2 focus:ring-green-600 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-slate-500 font-bold mb-1">Email Central (Recebimento de CVs)</label>
                        <input type="email" name="email" value="{{ $empresa->email }}" class="w-full p-2.5 border rounded-xl focus:ring-2 focus:ring-green-600 outline-none" required>
                    </div>
                    
                    <div class="flex justify-end gap-2 pt-2 border-t">
                        <button type="button" onclick="toggleModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-4 py-2 rounded-xl transition">Cancelar</button>
                        <button type="submit" class="bg-green-800 hover:bg-green-900 text-white font-bold px-4 py-2 rounded-xl transition">Atualizar Dados</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const empresaLat = "{{ $empresa->latitude }}";
        const empresaLng = "{{ $empresa->longitude }}";
        const centroInicial = (empresaLat && empresaLng) ? [parseFloat(empresaLat), parseFloat(empresaLng)] : [-8.8368, 13.2344];
        
        const mapa = L.map('mapa-empresa').setView(centroInicial, 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(mapa);

        let marcadorVaga = null;

        // Ao clicar no mapa
        mapa.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            document.getElementById('vaga_lat').value = lat.toFixed(8);
            document.getElementById('vaga_lng').value = lng.toFixed(8);

            if (marcadorVaga) {
                marcadorVaga.setLatLng(e.latlng);
            } else {
                marcadorVaga = L.marker(e.latlng).addTo(mapa);
            }

            document.getElementById('vaga-placeholder').classList.add('hidden');
            document.getElementById('vaga-conteudo').classList.remove('hidden');
            document.getElementById('info-endereco').innerText = "A localizar endereço via GPS...";

            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data.address) {
                        const provincia = data.address.province || data.address.state || "Luanda";
                        const municipio = data.address.suburb || data.address.city_district || data.address.town || data.address.city || "Não identificado";
                        const bairro = data.address.neighbourhood || data.address.residential || data.address.suburb || "";

                        document.getElementById('vaga_provincia').value = provincia;
                        document.getElementById('vaga_municipio').value = municipio;
                        document.getElementById('vaga_bairro').value = bairro;

                        document.getElementById('info-endereco').innerText = `${bairro ? bairro + ', ' : ''}${municipio} — ${provincia}`;
                    } else {
                        document.getElementById('info-endereco').innerText = "Coordenadas fixadas. Complete os nomes manualmente.";
                    }
                })
                .catch(error => {
                    console.error(error);
                    document.getElementById('info-endereco').innerText = "Coordenadas salvas (Modo offline).";
                });
        });

        function focarNoMapa(lat, lng, titulo) {
            mapa.setView([lat, lng], 15);
            if (marcadorVaga) {
                marcadorVaga.setLatLng([lat, lng]);
            } else {
                marcadorVaga = L.marker([lat, lng]).addTo(mapa);
            }
            document.getElementById('vaga-placeholder').classList.add('hidden');
            document.getElementById('vaga-conteudo').classList.remove('hidden');
            document.getElementById('info-endereco').innerText = `Visualizando vaga: ${titulo}`;
        }

        function toggleModal() {
            const body = document.querySelector('body');
            const modal = document.getElementById('modal-perfil');
            modal.classList.toggle('opacity-0');
            modal.classList.toggle('pointer-events-none');
            body.classList.toggle('modal-active');
        }
    </script>
</body>
</html>