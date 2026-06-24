<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerir Vagas — Kifacil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 font-sans text-slate-800 p-4 sm:p-6 lg:p-8">

    <div class="max-w-7xl mx-auto space-y-6">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-green-700 hover:text-green-800 transition inline-flex items-center gap-1">
                    <i class="fas fa-arrow-left"></i> Voltar ao Painel Central
                </a>
                <h1 class="text-2xl font-black text-slate-900 mt-1">Ofertas de Estágio Habilitadas</h1>
                <p class="text-xs text-slate-400">Controlo de oportunidades, requisitos e geolocalização do Radar</p>
            </div>
            <button onclick="abrirModalCriar()" class="inline-flex items-center gap-2 bg-green-700 hover:bg-green-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-sm">
                <i class="fas fa-plus-circle"></i> Publicar Nova Vaga
            </button>
        </div>

        @if(session('sucesso'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl text-xs font-bold flex items-center gap-2">
                <i class="fas fa-check-circle text-emerald-500 text-sm"></i> {{ session('sucesso') }}
            </div>
        @endif

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 uppercase tracking-wider text-[10px] font-bold border-b border-slate-100">
                            <th class="py-3 px-6 w-16">ID</th>
                            <th class="py-3 px-6">Título da Oportunidade / Empresa</th>
                            <th class="py-3 px-6">Local de Lotação (Radar)</th>
                            <th class="py-3 px-6">Tecnologias Solicitadas</th>
                            <th class="py-3 px-6 w-24">Estado</th>
                            <th class="py-3 px-6 w-40 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium">
                        @forelse($vagas as $vaga)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-mono text-slate-400">#{{ $vaga->id }}</td>
                            <td class="py-4 px-6 font-bold text-slate-900">
                                <div>{{ $vaga->titulo }}</div>
                                <div class="text-[10px] text-green-700 font-normal"><i class="fas fa-building text-[9px]"></i> {{ $vaga->empresa_nome ?? 'Empresa Desconhecida' }}</div>
                            </td>
                            <td class="py-4 px-6 space-y-1">
                                <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-700 px-2 py-0.5 rounded text-[11px] font-mono">
                                    <i class="fas fa-map-marker-alt text-red-500"></i> {{ $vaga->bairro }}
                                </span>
                                <div class="text-[10px] text-slate-400 font-normal pl-1">{{ $vaga->municipio }} — {{ $vaga->provincia }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex flex-wrap gap-1">
                                    @if(!empty($vaga->requisitos))
                                        @foreach(explode(',', $vaga->requisitos) as $req)
                                            <span class="bg-green-50 text-green-700 border border-green-200 px-2 py-0.5 rounded-full text-[10px] font-bold">{{ $req }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-slate-400 italic text-[11px]">Sem requisitos</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @if($vaga->status == 'ativa')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Aberta</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">Preenchida</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right flex justify-end gap-2">
                                <button onclick="abrirModalEditar({{ json_encode($vaga) }})" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-2.5 py-1.5 rounded-lg border border-slate-200 transition text-[11px] font-bold">
                                    <i class="fas fa-edit"></i> Alterar
                                </button>
                                <form action="{{ route('admin.vagas.eliminar', $vaga->id) }}" method="POST" onsubmit="return confirm('Remover esta vaga do sistema?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 px-2.5 py-1.5 rounded-lg border border-red-100 transition text-[11px] font-bold">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">Nenhuma vaga ativa publicada.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modalCriar" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl border border-slate-200 w-full max-w-2xl p-6 shadow-xl space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-black text-slate-900 text-base">Publicar Nova Oportunidade</h3>
                <button onclick="fecharModalCriar()" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
            </div>
            <form action="{{ route('admin.vagas.salvar') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-500 font-bold mb-1">Título da Vaga</label>
                        <input type="text" name="titulo" required placeholder="Ex: Estagiário de Redes, Dev PHP..." class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                    </div>
                    <div>
                        <label class="block text-slate-500 font-bold mb-1">Empresa Ofertante</label>
                        <select name="empresa_id" required class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                            <option value="">Selecione uma Empresa Homologada</option>
                            @foreach($empresas as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-500 font-bold mb-1">Bairro</label>
                        <input type="text" name="bairro" required class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                    </div>
                    <div>
                        <label class="block text-slate-500 font-bold mb-1">Município</label>
                        <input type="text" name="municipio" required class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                    </div>
                    <div>
                        <label class="block text-slate-500 font-bold mb-1">Província</label>
                        <input type="text" name="provincia" required class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                    </div>
                    <div>
                        <label class="block text-slate-500 font-bold mb-1">Estado</label>
                        <select name="status" class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                            <option value="ativa">Aberta (Visível no Radar)</option>
                            <option value="preenchida">Preenchida (Oculta)</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-slate-500 font-bold mb-1">Tecnologias / Requisitos Exigidos (Pressione Enter ou Vírgula)</label>
                    <input type="text" id="input_skills_criar" class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 mb-2">
                    <div id="container_skills_criar" class="flex flex-wrap gap-1.5 p-2 bg-slate-50 border border-dashed rounded-lg min-h-[40px]"></div>
                </div>
                <div class="flex gap-2 pt-2 justify-end border-t">
                    <button type="button" onclick="fecharModalCriar()" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-lg">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-green-700 text-white font-bold rounded-lg">Publicar Vaga</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalEditar" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl border border-slate-200 w-full max-w-2xl p-6 shadow-xl space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-black text-slate-900 text-base">Modificar Ficha da Vaga</h3>
                <button onclick="fecharModalEditar()" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
            </div>
            <form id="formEditar" method="POST" class="space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-500 font-bold mb-1">Título da Vaga</label>
                        <input type="text" id="edit_titulo" name="titulo" required class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                    </div>
                    <div>
                        <label class="block text-slate-500 font-bold mb-1">Empresa Ofertante</label>
                        <select id="edit_empresa_id" name="empresa_id" required class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                            @foreach($empresas as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-500 font-bold mb-1">Bairro</label>
                        <input type="text" id="edit_bairro" name="bairro" required class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                    </div>
                    <div>
                        <label class="block text-slate-500 font-bold mb-1">Município</label>
                        <input type="text" id="edit_municipio" name="municipio" required class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                    </div>
                    <div>
                        <label class="block text-slate-500 font-bold mb-1">Província</label>
                        <input type="text" id="edit_provincia" name="provincia" required class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                    </div>
                    <div>
                        <label class="block text-slate-500 font-bold mb-1">Estado Operacional</label>
                        <select id="edit_status" name="status" class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                            <option value="ativa">Aberta (Visível no Radar)</option>
                            <option value="preenchida">Preenchida (Oculta)</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-slate-500 font-bold mb-1">Requisitos Exigidos Fixos (Clique no X para revogar)</label>
                    <input type="text" id="input_skills_editar" class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 mb-2">
                    <div id="container_skills_editar" class="flex flex-wrap gap-1.5 p-2 bg-slate-50 border border-dashed rounded-lg min-h-[40px]"></div>
                </div>
                <div class="flex gap-2 pt-2 justify-end border-t">
                    <button type="button" onclick="fecharModalEditar()" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-lg">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-green-700 text-white font-bold rounded-lg">Atualizar Vaga</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModalCriar() { 
            document.getElementById('modalCriar').classList.remove('hidden'); 
            inicializarTags('input_skills_criar', 'container_skills_criar', []);
        }
        function fecharModalCriar() { document.getElementById('modalCriar').classList.add('hidden'); }

        function abrirModalEditar(vaga) {
            document.getElementById('edit_titulo').value = vaga.titulo;
            document.getElementById('edit_empresa_id').value = vaga.empresa_id;
            document.getElementById('edit_bairro').value = vaga.bairro;
            document.getElementById('edit_municipio').value = vaga.municipio || '';
            document.getElementById('edit_provincia').value = vaga.provincia || '';
            document.getElementById('edit_status').value = vaga.status;
            
            // Ajusta a URL absoluta dinâmica para a submissão via ID direto
            document.getElementById('formEditar').action = "{{ url('/admin/vagas/atualizar') }}/" + vaga.id;
            
            let requisitosIniciais = vaga.requisitos ? vaga.requisitos.split(',') : [];
            inicializarTags('input_skills_editar', 'container_skills_editar', requisitosIniciais);

            document.getElementById('modalEditar').classList.remove('hidden');
        }
        function fecharModalEditar() { document.getElementById('modalEditar').classList.add('hidden'); }

        function inicializarTags(inputId, containerId, listaInicial) {
            const input = document.getElementById(inputId);
            const container = document.getElementById(containerId);
            let tags = [...listaInicial];

            function render() {
                container.innerHTML = '';
                tags.forEach((tag, index) => {
                    if(!tag.trim()) return;
                    const badge = document.createElement('span');
                    badge.className = "inline-flex items-center gap-1 bg-green-700 text-white font-bold px-3 py-1 rounded-full text-[11px] shadow-sm";
                    badge.innerHTML = `${tag} <button type="button" class="hover:text-red-300 ml-1 font-black focus:outline-none">&times;</button>`;
                    
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'skills[]';
                    hiddenInput.value = tag;
                    badge.appendChild(hiddenInput);

                    badge.querySelector('button').addEventListener('click', () => {
                        tags.splice(index, 1);
                        render();
                    });
                    container.appendChild(badge);
                });
            }

            input.onkeydown = function(e) {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    let valor = input.value.trim().replace(',', '');
                    if (valor && !tags.includes(valor)) {
                        tags.push(valor);
                        input.value = '';
                        render();
                    }
                }
            };
            render();
        }
    </script>
</body>
</html>