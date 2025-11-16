<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificacoes_oportunidade', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('venda_id')->nullable();
            $table->unsignedBigInteger('usuario_destino_id')->nullable();

            $table->string('email_destino')->nullable();
            $table->dateTime('disparada_em')->nullable();
            $table->enum('status', ['PENDENTE', 'ENVIADA', 'FALHA'])
                  ->default('PENDENTE');
            $table->text('erro_msg')->nullable();

            $table->timestamps();

            $table->foreign('cliente_id')
                  ->references('id')->on('clientes')
                  ->cascadeOnDelete();

            $table->foreign('venda_id')
                  ->references('id')->on('vendas')
                  ->nullOnDelete();

            $table->foreign('usuario_destino_id')
                  ->references('id')->on('users')
                  ->nullOnDelete();

            $table->index(['cliente_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificacoes_oportunidade');
    }
};
