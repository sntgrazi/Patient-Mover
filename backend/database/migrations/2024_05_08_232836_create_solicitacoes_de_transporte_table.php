<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitacoes_de_transporte', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes');
            $table->string('origem');
            $table->string('destino');
            $table->enum('prioridade', ['baixa', 'media', 'alta']);
            $table->enum('status', ['pendente', 'em_andamento', 'concluido']);
            $table->foreignId('maqueiro_id')->nullable()->constrained('usuarios');
            $table->date('data');
            $table->time('hora'); 
            $table->text('descricao')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitacoes_de_transporte');
    }
};
