<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotacao extends Model
{
    use HasFactory;

    protected $table = 'cotacoes';

    protected $fillable = [
        'moeda_origem',
        'moeda_destino',
        'valor_para_conversao',
        'forma_de_pagamento',
        'valor_moeda_destino_conversao',
        'valor_comprado_moeda_destino',
        'taxa_de_pagamento',
        'taxa_de_conversao',
        'valor_descontado_taxas',
        'usuario'
    ];

    public function usuario()
    {
        return $this->belongsTo('App\Models\User', 'usuario');
    }
}
