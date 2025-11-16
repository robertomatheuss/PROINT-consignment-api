<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendaDocumento extends Model
{
    use HasFactory;

    protected $table = 'venda_documentos';

    protected $fillable = [
        'venda_id',
        'documento_id',
    ];

    // ========== RELACIONAMENTOS ==========

    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }

    public function documento()
    {
        return $this->belongsTo(Documento::class);
    }
}
