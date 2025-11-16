<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('cliente_id');

            $table->enum('tipo', [
                'RG',
                'CPF',
                'CNH',
                'CONTRACHEQUE',
                'COMP_RESIDENCIA',
                'OUTROS'
            ])->default('OUTROS');

            $table->string('nome_arquivo');   // nome original ou amigável
            $table->string('file_path');      // caminho no storage
            $table->string('file_url')->nullable(); // URL pública opcional (S3, etc)
            $table->unsignedBigInteger('tamanho_bytes')->nullable();
            $table->string('hash_conteudo')->nullable();

            $table->unsignedBigInteger('enviado_por_usuario_id')->nullable();
            $table->dateTime('enviado_em')->nullable();

            $table->timestamps();

            $table->foreign('cliente_id')
                  ->references('id')->on('clientes')
                  ->cascadeOnDelete();

            $table->foreign('enviado_por_usuario_id')
                  ->references('id')->on('users')
                  ->nullOnDelete();

            $table->index(['cliente_id', 'tipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
