<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfilEstudante extends Model
{
    protected $table = 'perfis_estudantes';

    protected $fillable = [
        'user_id',
        'telefone',
        'instituicao_ensino',
        'curso',
        'valencias_tecnicas',
        'provincia',
        'municipio',
        'bairro',
        'latitude',
        'longitude'
    ];
}