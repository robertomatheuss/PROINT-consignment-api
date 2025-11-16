<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'perfil',
        'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ========== RELACIONAMENTOS ==========

    // Um usuário vendedor registra muitas vendas
    public function vendas()
    {
        return $this->hasMany(Venda::class, 'vendedor_id');
    }

    // Um usuário pode ter enviado vários documentos (upload)
    public function documentosEnviados()
    {
        return $this->hasMany(Documento::class, 'enviado_por_usuario_id');
    }

    // Usuário que recebe notificações (gestor)
    public function notificacoesRecebidas()
    {
        return $this->hasMany(NotificacaoOportunidade::class, 'usuario_destino_id');
    }
}
