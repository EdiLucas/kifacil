<?php

namespace App\Http\Controllers\Estudante; // O teu namespace atual

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    public function dashboard()
    {
        $estudante = Auth::user();

        // Procura vagas no mesmo município ou província do estudante (Match Geográfico)
        $vagasRecomendadas = DB::table('vaga_estagios')
            ->leftJoin('users', 'vaga_estagios.empresa_id', '=', 'users.id')
            ->select('vaga_estagios.*', 'users.name as empresa_nome')
            ->where('vaga_estagios.status', 'ativa')
            ->where(function($query) use ($estudante) {
                $query->where('vaga_estagios.municipio', 'LIKE', '%' . $estudante->municipio . '%')
                      ->orWhere('vaga_estagios.provincia', 'LIKE', '%' . $estudante->provincia . '%');
            })
            ->orderBy('vaga_estagios.id', 'desc')
            ->limit(3)
            ->get();

        // Restantes vagas ativas do sistema
        $todasVagas = DB::table('vaga_estagios')
            ->leftJoin('users', 'vaga_estagios.empresa_id', '=', 'users.id')
            ->select('vaga_estagios.*', 'users.name as empresa_nome')
            ->where('vaga_estagios.status', 'ativa')
            ->orderBy('vaga_estagios.id', 'desc')
            ->get();

        return view('estudante.dashboard', compact('estudante', 'vagasRecomendadas', 'todasVagas'));
    }

public function atualizarPerfil(Request $request)
{
    $estudante = Auth::user();

    $request->validate([
        'name' => 'required|string|max:255',
        'municipio' => 'required|string',
        'provincia' => 'required|string',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
    ]);

    DB::table('users')->where('id', $estudante->id)->update([
        'name' => $request->name,
        'municipio' => $request->municipio,
        'provincia' => $request->provincia,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
        'updated_at' => now(),
    ]);

    return back()->with('sucesso', 'Perfil atualizado com sucesso!');
}
}