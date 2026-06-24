<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /**
     * Processa a autenticação do utilizador (Login)
     */
    public function login(Request $request)
    {
        // Validação dos dados de entrada
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Tentativa de Autenticação
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirecionamento baseado no nível de acesso (role)
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'empresa':
                    return redirect()->route('empresa.dashboard');
                case 'estudante':
                default:
                    return redirect()->route('estudante.dashboard');
            }
        }

        // Retorna com erro se falhar
        return back()->withErrors([
            'email' => 'As credenciais fornecidas não coincidem com os nossos registos.',
        ])->onlyInput('email');
    }

    /**
     * Processa o registo de novos utilizadores (Estudante / Empresa)
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'municipio' => 'required|string',
            'provincia' => 'required|string',
            'role' => 'required|in:estudante,empresa'
        ]);

        // Se for empresa, entra como 'pendente' até o admin validar. Estudante entra 'ativo'.
        $statusInicial = ($request->role === 'empresa') ? 'pendente' : 'ativo';

        $userId = DB::table('users')->insertGetId([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'municipio' => $request->municipio,
            'provincia' => $request->provincia,
            'role' => $request->role,
            'status' => $statusInicial,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Se for estudante, faz login automático e manda para o painel
        if ($request->role === 'estudante') {
            Auth::loginUsingId($userId);
            return redirect()->route('estudante.dashboard');
        }

        // Se for empresa, redireciona de volta para o login com aviso de validação
        return redirect()->route('login')->withErrors([
            'email' => 'Conta de empresa registada com sucesso! Aguarda a validação do administrador.'
        ]);
    }

    /**
     * Termina a sessão do utilizador (Logout)
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}