<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendas', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('vendedor_id');   // users.id
            $table->unsignedBigInteger('tipo_contrato_id');

            $table->decimal('valor', 12, 2);
            $table->date('data');
            $table->enum('status', ['CRIADA', 'ATIVA', 'QUITADA', 'CANCELADA'])
                  ->default('CRIADA');

            $table->timestamps();

            // FKs
            $table->foreign('cliente_id')
                  ->references('id')->on('clientes')
                  ->cascadeOnDelete();

            $table->foreign('vendedor_id')
                  ->references('id')->on('users')
                  ->restrictOnDelete();

            $table->foreign('tipo_contrato_id')
                  ->references('id')->on('tipo_contratos')
                  ->restrictOnDelete();

            $table->index(['cliente_id', 'vendedor_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendas');
    }
};
