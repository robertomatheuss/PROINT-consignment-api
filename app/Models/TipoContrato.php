<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoContrato extends Model
{
    use HasFactory;

    protected $table = 'tipo_contratos';

    protected $fillable = [
        'nome',
        'prazo_meses',
        'tempo_nova_oportunidade_dias',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    // ========== RELACIONAMENTOS ==========

    public function vendas()
    {
        return $this->hasMany(Venda::class, 'tipo_contrato_id');
    }
}
