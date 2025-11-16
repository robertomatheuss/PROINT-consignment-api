<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;

    protected $table = 'vendas';

    protected $fillable = [
        'cliente_id',
        'vendedor_id',
        'tipo_contrato_id',
        'valor',
        'data',
        'status',
    ];

    protected $casts = [
        'data'  => 'date',
        'valor' => 'decimal:2',
    ];

    // ========== RELACIONAMENTOS ==========

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    public function tipoContrato()
    {
        return $this->belongsTo(TipoContrato::class, 'tipo_contrato_id');
    }

    // muitos-para-muitos com documentos via pivot venda_documentos
    public function documentos()
    {
        return $this->belongsToMany(Documento::class, 'venda_documentos')
                    ->withTimestamps();
    }

    public function notificacoes()
    {
        return $this->hasMany(NotificacaoOportunidade::class, 'venda_id');
    }
}
