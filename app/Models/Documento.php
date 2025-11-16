<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $table = 'documentos';

    protected $fillable = [
        'cliente_id',
        'tipo',
        'nome_arquivo',
        'file_path',
        'file_url',
        'tamanho_bytes',
        'hash_conteudo',
        'enviado_por_usuario_id',
        'enviado_em',
    ];

    protected $casts = [
        'enviado_em'    => 'datetime',
        'tamanho_bytes' => 'integer',
    ];

    // ========== RELACIONAMENTOS ==========

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function enviadoPor()
    {
        return $this->belongsTo(User::class, 'enviado_por_usuario_id');
    }

    public function vendas()
    {
        return $this->belongsToMany(Venda::class, 'venda_documentos')
                    ->withTimestamps();
    }
}
