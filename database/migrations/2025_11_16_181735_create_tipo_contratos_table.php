<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipo_contratos', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('nome')->unique();
            $table->unsignedInteger('prazo_meses')->default(0);
            $table->unsignedInteger('tempo_nova_oportunidade_dias')->default(0);
            $table->boolean('ativo')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_contratos');
    }
};
