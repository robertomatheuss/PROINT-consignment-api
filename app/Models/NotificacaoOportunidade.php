<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificacaoOportunidade extends Model
{
    use HasFactory;

    protected $table = 'notificacoes_oportunidade';

    protected $fillable = [
        'cliente_id',
        'venda_id',
        'usuario_destino_id',
        'email_destino',
        'disparada_em',
        'status',
        'erro_msg',
    ];

    protected $casts = [
        'disparada_em' => 'datetime',
    ];

    // ========== RELACIONAMENTOS ==========

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }

    public function usuarioDestino()
    {
        return $this->belongsTo(User::class, 'usuario_destino_id');
    }
}
