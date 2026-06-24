<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerir Empresas — Kifacil</title>
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
                <h1 class="text-2xl font-black text-slate-900 mt-1">Controlo de Parceiros Comerciais</h1>
                <p class="text-xs text-slate-400">Homologação de contas jurídicas, localização e publicação de vagas</p>
            </div>
            <button onclick="abrirModalCriar()" class="inline-flex items-center gap-2 bg-green-700 hover:bg-green-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-sm">
                <i class="fas fa-building"></i> Registar Nova Empresa
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
                            <th class="py-3 px-6">Nome da Instituição</th>
                            <th class="py-3 px-6">Sede / Localização</th>
                            <th class="py-3 px-6 w-24">Estado</th>
                            <th class="py-3 px-6 w-64 text-right">Ações Administrativas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium">
                        @forelse($empresas as $empresa)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-mono text-slate-400">#{{ $empresa->id }}</td>
                            <td class="py-4 px-6 font-bold text-slate-900">
                                <div>{{ $empresa->name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono font-normal">{{ $empresa->email }}</div>
                            </td>
                            <td class="py-4 px-6 space-y-1">
                                <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-700 px-2 py-0.5 rounded text-[11px] font-mono">
                                    <i class="fas fa-map-marker-alt text-red-500"></i> {{ $empresa->bairro }}
                                </span>
                                <div class="text-[10px] text-slate-400 font-normal pl-1">{{ $empresa->municipio }} — {{ $empresa->provincia }}</div>
                            </td>
                            <td class="py-4 px-6">
                                @if($empresa->status == 'ativo')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Regularizada</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 animate-pulse">Aguardando Validação</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right flex justify-end gap-1.5 items-center">
                                
                                @if($empresa->status == 'pendente')
                                <form action="{{ route('admin.empresas.validar', $empresa->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-2.5 py-1.5 rounded-lg transition text-[11px] font-bold inline-flex items-center gap-1">
                                        <i class="fas fa-check"></i> Validar
                                    </button>
                                </form>
                                @endif

                                <button onclick="abrirModalEditar({{ json_encode($empresa) }})" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-2.5 py-1.5 rounded-lg border border-slate-200 transition text-[11px] font-bold">
                                    <i class="fas fa-edit"></i> Alterar
                                </button>

                                <form action="{{ route('admin.empresas.eliminar', $empresa->id) }}" method="POST" onsubmit="return confirm('Remover esta empresa permanentemente?')">
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
                            <td colspan="5" class="py-12 text-center text-slate-400">Nenhuma empresa parceira registada.</td>
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
                <h3 class="font-black text-slate-900 text-base">Registar Nova Empresa</h3>
                <button onclick="fecharModalCriar()" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
            </div>
            <form action="{{ route('admin.empresas.salvar') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-500 font-bold mb-1">Nome Institucional</label>
                        <input type="text" name="name" required class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                    </div>
                    <div>
                        <label class="block text-slate-500 font-bold mb-1">Email Corporativo</label>
                        <input type="email" name="email" required class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                    </div>
                    <div>
                        <label class="block text-slate-500 font-bold mb-1">Palavra-passe de Acesso</label>
                        <input type="password" name="password" required class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
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
                </div>
                <div>
                    <label class="block text-slate-500 font-bold mb-1">Estado Inicial</label>
                    <select name="status" class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                        <option value="pendente">Pendente (Requer validação)</option>
                        <option value="ativo">Ativo (Aprovado direto)</option>
                    </select>
                </div>
                <div class="flex gap-2 pt-2 justify-end border-t">
                    <button type="button" onclick="fecharModalCriar()" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-lg">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-green-700 text-white font-bold rounded-lg">Gravar</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalEditar" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl border border-slate-200 w-full max-w-2xl p-6 shadow-xl space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-black text-slate-900 text-base">Atualizar Dados Cadastrais</h3>
                <button onclick="fecharModalEditar()" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
            </div>
            <form id="formEditar" method="POST" class="space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-500 font-bold mb-1">Nome Institucional</label>
                        <input type="text" id="edit_name" name="name" required class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                    </div>
                    <div>
                        <label class="block text-slate-500 font-bold mb-1">Email Corporativo</label>
                        <input type="email" id="edit_email" name="email" required class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                    </div>
                    <div>
                        <label class="block text-slate-500 font-bold mb-1">Nova Palavra-passe <span class="text-slate-400 font-normal">(Deixe em branco para manter)</span></label>
                        <input type="password" name="password" class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
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
                </div>
                <div>
                    <label class="block text-slate-500 font-bold mb-1">Estado de Acesso</label>
                    <select id="edit_status" name="status" class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                        <option value="ativo">Regularizada (Ativa)</option>
                        <option value="pendente">Pendente (Bloqueada)</option>
                    </select>
                </div>
                <div class="flex gap-2 pt-2 justify-end border-t">
                    <button type="button" onclick="fecharModalEditar()" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-lg">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-green-700 text-white font-bold rounded-lg">Atualizar Informações</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModalCriar() { 
            document.getElementById('modalCriar').classList.remove('hidden'); 
        }
        function fecharModalCriar() { document.getElementById('modalCriar').classList.add('hidden'); }

        function abrirModalEditar(empresa) {
            document.getElementById('edit_name').value = empresa.name;
            document.getElementById('edit_email').value = empresa.email;
            document.getElementById('edit_bairro').value = empresa.bairro;
            document.getElementById('edit_municipio').value = empresa.municipio || '';
            document.getElementById('edit_provincia').value = empresa.provincia || '';
            document.getElementById('edit_status').value = empresa.status;
            
            // Define o destino dinâmico do formulário com URL absoluta estável
            document.getElementById('formEditar').action = "{{ url('/admin/empresas/atualizar') }}/" + empresa.id;

            document.getElementById('modalEditar').classList.remove('hidden');
        }
        function fecharModalEditar() { document.getElementById('modalEditar').classList.add('hidden'); }
    </script>
</body>
</html>