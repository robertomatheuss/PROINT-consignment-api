<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'nome',
        'cpf',
        'data_nascimento',
        'email',
        'telefone',
        'end_logradouro',
        'end_numero',
        'end_complemento',
        'end_bairro',
        'end_cidade',
        'end_uf',
        'end_cep',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
    ];

    // ========== RELACIONAMENTOS ==========

    public function vendas()
    {
        return $this->hasMany(Venda::class);
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }

    public function notificacoes()
    {
        return $this->hasMany(NotificacaoOportunidade::class);
    }
}
