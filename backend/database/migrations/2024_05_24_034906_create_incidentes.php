<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('incidentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitacao_transporte_id')->constrained('solicitacoes_transporte')->onDelete('cascade');
            $table->string('tipo'); 
            $table->text('descricao');
            $table->dateTime('data_hora'); 
            $table->foreignId('registrado_por')->constrained('usuarios'); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('incidentes');
    }
};
