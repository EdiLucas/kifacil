<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VagaController extends Controller
{
    public function index()
    {
        $empresa = Auth::user();
        
        // Puxa as vagas desta empresa
        $vagas = DB::table('vaga_estagios')
            ->where('empresa_id', $empresa->id)
            ->orderBy('id', 'desc')
            ->get();

        return view('empresa.dashboard', compact('empresa', 'vagas'));
    }

    public function salvarVaga(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'valencias' => 'required|string', // Recebe a string do formulário
            'municipio' => 'required|string',
            'provincia' => 'required|string',
            'bairro' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'raio_atuacao_km' => 'required|integer',
        ]);

        // Transforma a string separada por vírgulas
        $valenciasArray = array_map('trim', explode(',', $request->valencias));
        $valenciasJson = json_encode($valenciasArray);

        DB::table('vaga_estagios')->insert([
            'empresa_id' => Auth::id(),
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'valencias_exigidas' => $valenciasJson,
            'bairro' => $request->bairro,
            'municipio' => $request->municipio,
            'provincia' => $request->provincia,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'raio_atuacao_km' => $request->raio_atuacao_km,
            'status' => 'ativa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('sucesso', 'Nova oportunidade de estágio publicada com sucesso!');
    }

    public function atualizarPerfil(Request $request)
    {
        $empresa = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $empresa->id,
            'municipio' => 'required|string',
            'provincia' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        DB::table('users')->where('id', $empresa->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'municipio' => $request->municipio,
            'provincia' => $request->provincia,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'updated_at' => now(),
        ]);

        return back()->with('sucesso', 'Dados da instituição atualizados com sucesso!');
    }
}