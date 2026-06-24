<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VagaEstagio extends Model
{
    protected $table = 'vagas_estagios';

    protected $fillable = [
        'empresa_id', // ID do User do tipo empresa
        'titulo',
        'descricao',
        'valencias_exigidas', 
        'provincia',
        'municipio',
        'bairro',
        'latitude',
        'longitude',
        'raio_atuacao_km' // Limite logístico para o match
    ];
}