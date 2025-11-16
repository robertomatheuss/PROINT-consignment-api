<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venda_documentos', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('venda_id');
            $table->unsignedBigInteger('documento_id');

            $table->timestamps(); // created_at serve como seu "criadoEm"

            $table->foreign('venda_id')
                  ->references('id')->on('vendas')
                  ->cascadeOnDelete();

            $table->foreign('documento_id')
                  ->references('id')->on('documentos')
                  ->cascadeOnDelete();

            $table->unique(['venda_id', 'documento_id']); // evita duplicata
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venda_documentos');
    }
};
