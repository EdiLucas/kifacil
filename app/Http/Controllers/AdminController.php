<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalEstudantes = DB::table('users')->where('role', 'estudante')->count();
        $empresasAtivas = DB::table('users')->where('role', 'empresa')->where('status', 'ativo')->count();
        $empresasPendentes = DB::table('users')->where('role', 'empresa')->where('status', 'pendente')->count();
        $vagasAtivas = DB::table('vaga_estagios')->where('status', 'ativa')->count();
        $totalCandidaturas = DB::table('candidaturas')->count();

        $logs = DB::table('activity_log')->orderBy('created_at', 'desc')->get();

        $sistemaOnline = false;
        try {
            DB::connection()->getPdo();
            $sistemaOnline = true;
        } catch (\Exception $e) {
            $sistemaOnline = false;
        }

        return view('admin.dashboard', compact(
            'totalEstudantes', 'empresasAtivas', 'empresasPendentes', 'vagasAtivas', 'totalCandidaturas', 'logs', 'sistemaOnline'
        ));
    }

    public function listarEstudantes()
    {
        $estudantes = DB::table('users')->where('role', 'estudante')->orderBy('id', 'desc')->get();
        return view('admin.estudantes', compact('estudantes'));
    }

    public function salvarEstudante(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'bairro' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'status' => 'required|string'
        ]);

        // Trata as tags enviadas pelo array do formulário dinâmico
        $skills = $request->input('skills', []);
        $skillsString = implode(',', $skills);

        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'bairro' => $request->bairro,
            'municipio' => $request->municipio,
            'provincia' => $request->provincia,
            'skills' => $skillsString,
            'role' => 'estudante',
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('activity_log')->insert([
            'operacao' => 'Inscrição Administrativa',
            'detalhes' => "Estudante [{$request->name}] registado no bairro [{$request->bairro}, {$request->municipio} — {$request->provincia}].",
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('admin.estudantes')->with('sucesso', 'Estudante adicionado com sucesso!');
    }

    public function atualizarEstudante(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'bairro' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'status' => 'required|string'
        ]);

        $skills = $request->input('skills', []);
        $skillsString = implode(',', $skills);

        $dados = [
            'name' => $request->name,
            'email' => $request->email,
            'bairro' => $request->bairro,
            'municipio' => $request->municipio,
            'provincia' => $request->provincia,
            'skills' => $skillsString,
            'status' => $request->status,
            'updated_at' => now(),
        ];

        if ($request->filled('password')) {
            $dados['password'] = bcrypt($request->password);
        }

        DB::table('users')->where('id', $id)->update($dados);

        DB::table('activity_log')->insert([
            'operacao' => 'Perfil Modificado',
            'detalhes' => "Dados e geolocalização do bairro [{$request->bairro}, {$request->municipio} — {$request->provincia}] atualizados para o ID #{$id}.",
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('admin.estudantes')->with('sucesso', 'Estudante atualizado com sucesso!');
    }

    public function eliminarEstudante($id)
    {
        $estudante = DB::table('users')->where('id', $id)->first();

        if ($estudante) {
            DB::table('users')->where('id', $id)->delete();

            DB::table('activity_log')->insert([
                'operacao' => 'Revogação de Acesso',
                'detalhes' => "Estudante {$estudante->name} removido permanentemente.",
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return redirect()->route('admin.estudantes')->with('sucesso', 'Estudante removido do ecossistema.');
    }

    public function exportarLogs()
    {
        $logs = DB::table('activity_log')->orderBy('created_at', 'desc')->get();
        $filename = "logs_kifacil_" . date('Ymd_His') . ".csv";
        $handle = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        fputcsv($handle, ['ID', 'Data/Hora', 'Operacao', 'Detalhes']);
        foreach ($logs as $log) {
            fputcsv($handle, [$log->id, $log->created_at, $log->operacao, $log->detalhes]);
        }
        fclose($handle);
        exit;
    }

    public function listarEmpresas()
    {
        // Lista todas as empresas (ordenando primeiro as pendentes)
        $empresas = DB::table('users')->where('role', 'empresa')->orderBy('status', 'desc')->orderBy('id', 'desc')->get();
        return view('admin.empresas', compact('empresas'));
    }

    public function salvarEmpresa(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'bairro' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'status' => 'required|string'
        ]);

        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'bairro' => $request->bairro,
            'municipio' => $request->municipio,
            'provincia' => $request->provincia,
            'role' => 'empresa',
            'status' => $request->status, // Pode ser 'ativo' ou 'pendente'
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.empresas')->with('sucesso', 'Empresa registada com sucesso!');
    }

    public function atualizarEmpresa(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'bairro' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'status' => 'required|string'
        ]);

        $dados = [
            'name' => $request->name,
            'email' => $request->email,
            'bairro' => $request->bairro,
            'municipio' => $request->municipio,
            'provincia' => $request->provincia,
            'status' => $request->status,
            'updated_at' => now(),
        ];

        if ($request->filled('password')) {
            $dados['password'] = bcrypt($request->password);
        }

        DB::table('users')->where('id', $id)->update($dados);

        return redirect()->route('admin.empresas')->with('sucesso', 'Dados da empresa atualizados!');
    }

    public function validarEmpresa($id)
    {
        // Ativa a empresa pendente instantaneamente
        DB::table('users')->where('id', $id)->update([
            'status' => 'ativo',
            'updated_at' => now()
        ]);

        DB::table('activity_log')->insert([
            'operacao' => 'Validação de Empresa',
            'detalhes' => "A empresa ID #{$id} foi homologada e ativada no ecossistema.",
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('admin.empresas')->with('sucesso', 'Empresa validada e autorizada com sucesso!');
    }

    public function eliminarEmpresa($id)
    {
        DB::table('users')->where('id', $id)->delete();
        return redirect()->route('admin.empresas')->with('sucesso', 'Empresa removida do sistema.');
    }

    public function listarVagas()
    {
        // Procura as vagas e junta o nome da empresa associada
        $vagas = DB::table('vaga_estagios')
            ->leftJoin('users', 'vaga_estagios.empresa_id', '=', 'users.id')
            ->select('vaga_estagios.*', 'users.name as empresa_nome')
            ->orderBy('vaga_estagios.id', 'desc')
            ->get();

        // Lista de empresas para popular o select do formulário
        $empresas = DB::table('users')->where('role', 'empresa')->where('status', 'ativo')->get();

        return view('admin.vagas', compact('vagas', 'empresas'));
    }

    public function salvarVaga(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'empresa_id' => 'required|integer',
            'bairro' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'status' => 'required|string'
        ]);

        $requisitos = $request->input('skills', []);
        $requisitosString = implode(',', $requisitos);

        DB::table('vaga_estagios')->insert([
            'titulo' => $request->titulo,
            'empresa_id' => $request->empresa_id,
            'bairro' => $request->bairro,
            'municipio' => $request->municipio,
            'provincia' => $request->provincia,
            'requisitos' => $requisitosString,
            'status' => $request->status, // 'ativa' ou 'preenchida'
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.vagas')->with('sucesso', 'Vaga de estágio publicada!');
    }

    public function atualizarVaga(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'empresa_id' => 'required|integer',
            'bairro' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'status' => 'required|string'
        ]);

        $requisitos = $request->input('skills', []);
        $requisitosString = implode(',', $requisitos);

        DB::table('vaga_estagios')->where('id', $id)->update([
            'titulo' => $request->titulo,
            'empresa_id' => $request->empresa_id,
            'bairro' => $request->bairro,
            'municipio' => $request->municipio,
            'provincia' => $request->provincia,
            'requisitos' => $requisitosString,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.vagas')->with('sucesso', 'Vaga atualizada com sucesso!');
    }

    public function eliminarVaga($id)
    {
        DB::table('vaga_estagios')->where('id', $id)->delete();
        return redirect()->route('admin.vagas')->with('sucesso', 'Vaga removida do sistema.');
    }
}