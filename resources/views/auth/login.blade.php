<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso ao Ecossistema — Kifacil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 font-sans min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden p-6 sm:p-8 space-y-6">
        
        <!-- Logo / Topo -->
        <div class="text-center">
            <a href="{{ url('/') }}" class="text-2xl font-black tracking-tight text-green-800">
                Ki<span class="text-amber-500">facil</span>
            </a>
            <p id="auth-subtitle" class="text-xs text-slate-400 mt-1">Insere as tuas credenciais para aceder ao painel</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 p-3 rounded-xl text-xs font-bold space-y-1">
                @foreach($errors->all() as $error)
                    <p><i class="fas fa-exclamation-circle mr-1"></i> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- FORMULÁRIO DE LOGIN -->
        <form id="form-login" action="{{ route('login') }}" method="POST" class="space-y-4 text-xs font-medium">
            @csrf
            <div>
                <label class="block text-slate-500 font-bold mb-1">E-mail Corporativo ou Académico</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-3 top-3 text-slate-400"></i>
                    <input type="email" name="email" required placeholder="exemplo@kifacil.com" class="w-full pl-9 pr-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-600 font-normal">
                </div>
            </div>

            <div>
                <div class="flex justify-between items-center mb-1">
                    <label class="text-slate-500 font-bold">Palavra-passe</label>
                    <a href="#" class="text-[11px] text-green-700 hover:underline">Esqueceu-se?</a>
                </div>
                <div class="relative">
                    <i class="fas fa-lock absolute left-3 top-3 text-slate-400"></i>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full pl-9 pr-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-600 font-normal">
                </div>
            </div>

            <button type="submit" class="w-full bg-green-800 hover:bg-green-900 text-white font-bold py-3 rounded-xl transition shadow-md text-xs tracking-wide">
                <i class="fas fa-sign-in-alt mr-1"></i> Iniciar Sessão
            </button>

            <div class="text-center pt-2 border-t border-slate-100">
                <p class="text-slate-400">Novo na plataforma? <button type="button" onclick="alternarFormas('registo')" class="text-green-700 font-bold hover:underline">Criar uma conta</button></p>
            </div>
        </form>

        <!-- FORMULÁRIO DE REGISTO (OCULTO POR PADRÃO) -->
        <form id="form-registo" action="{{ route('register') }}" method="POST" class="hidden space-y-4 text-xs font-medium">
            @csrf
            
            <!-- Seletor de Tipo de Conta (Tabs) -->
            <div>
                <label class="block text-slate-500 font-bold mb-2 text-center">Que tipo de conta pretendes criar?</label>
                <div class="grid grid-cols-2 gap-2 bg-slate-100 p-1 rounded-xl border">
                    <button type="button" id="tab-estudante" onclick="mudarPerfil('estudante')" class="py-2 rounded-lg font-bold transition text-center bg-white text-green-800 shadow-sm">
                        <i class="fas fa-graduation-cap mr-1"></i> Estudante
                    </button>
                    <button type="button" id="tab-empresa" onclick="mudarPerfil('empresa')" class="py-2 rounded-lg font-bold transition text-center text-slate-500 hover:text-slate-800">
                        <i class="fas fa-building mr-1"></i> Empresa
                    </button>
                </div>
                <!-- Input Oculto que envia o Role selecionado -->
                <input type="hidden" name="role" id="input-role" value="estudante">
            </div>

            <div>
                <label id="label-nome" class="block text-slate-500 font-bold mb-1">Nome Completo</label>
                <div class="relative">
                    <i class="fas fa-user absolute left-3 top-3 text-slate-400"></i>
                    <input type="text" name="name" required placeholder="Insere o nome completo" class="w-full pl-9 pr-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-600 font-normal">
                </div>
            </div>

            <div>
                <label class="block text-slate-500 font-bold mb-1">E-mail de Contacto</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-3 top-3 text-slate-400"></i>
                    <input type="email" name="email" required placeholder="exemplo@kifacil.com" class="w-full pl-9 pr-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-600 font-normal">
                </div>
            </div>

            <!-- Grid Localidade Alinhada com o Resto do Ecossistema -->
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-slate-500 font-bold mb-1">Município</label>
                    <input type="text" name="municipio" required placeholder="Ex: Viana" class="w-full px-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-600 font-normal">
                </div>
                <div>
                    <label class="block text-slate-500 font-bold mb-1">Província</label>
                    <input type="text" name="provincia" required placeholder="Ex: Luanda" class="w-full px-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-600 font-normal">
                </div>
            </div>

            <div>
                <label class="block text-slate-500 font-bold mb-1">Definir Palavra-passe</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-3 top-3 text-slate-400"></i>
                    <input type="password" name="password" required placeholder="No mínimo 6 caracteres" class="w-full pl-9 pr-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-600 font-normal">
                </div>
            </div>

            <button type="submit" class="w-full bg-green-800 hover:bg-green-900 text-white font-bold py-3 rounded-xl transition shadow-md text-xs tracking-wide">
                <i class="fas fa-user-plus mr-1"></i> Concluir Registo
            </button>

            <div class="text-center pt-2 border-t border-slate-100">
                <p class="text-slate-400">Já tens conta criada? <button type="button" onclick="alternarFormas('login')" class="text-green-700 font-bold hover:underline">Fazer Login</button></p>
            </div>
        </form>

    </div>

    <!-- CONTROLO INTERATIVO JAVASCRIPT -->
    <script>
        function alternarFormas(tela) {
            const loginForm = document.getElementById('form-login');
            const registoForm = document.getElementById('form-registo');
            const subitulo = document.getElementById('auth-subtitle');

            if(tela === 'registo') {
                loginForm.classList.add('hidden');
                registoForm.classList.remove('hidden');
                subitulo.innerText = "Regista-te no ecossistema e entra no radar geográfico";
            } else {
                registoForm.classList.add('hidden');
                loginForm.classList.remove('hidden');
                subitulo.innerText = "Insere as tuas credenciais para aceder ao painel";
            }
        }

        function mudarPerfil(perfil) {
            const tabEstudante = document.getElementById('tab-estudante');
            const tabEmpresa = document.getElementById('tab-empresa');
            const inputRole = document.getElementById('input-role');
            const labelNome = document.getElementById('label-nome');

            inputRole.value = perfil;

            if(perfil === 'estudante') {
                tabEstudante.className = "py-2 rounded-lg font-bold transition text-center bg-white text-green-800 shadow-sm";
                tabEmpresa.className = "py-2 rounded-lg font-bold transition text-center text-slate-500 hover:text-slate-800";
                labelNome.innerText = "Nome Completo";
            } else {
                tabEmpresa.className = "py-2 rounded-lg font-bold transition text-center bg-white text-green-800 shadow-sm";
                tabEstudante.className = "py-2 rounded-lg font-bold transition text-center text-slate-500 hover:text-slate-800";
                labelNome.innerText = "Nome da Instituição / Firma";
            }
        }
    </script>
</body>
</html>