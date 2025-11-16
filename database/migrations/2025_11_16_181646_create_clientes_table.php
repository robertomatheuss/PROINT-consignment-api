<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('nome');
            $table->string('cpf', 14)->unique();
            $table->date('data_nascimento')->nullable();
            $table->string('email')->nullable();
            $table->string('telefone', 20)->nullable();

            // Endereço (value object)
            $table->string('end_logradouro')->nullable();
            $table->string('end_numero')->nullable();
            $table->string('end_complemento')->nullable();
            $table->string('end_bairro')->nullable();
            $table->string('end_cidade')->nullable();
            $table->string('end_uf', 2)->nullable();
            $table->string('end_cep', 9)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
