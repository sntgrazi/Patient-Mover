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
        Schema::create('transporte_recusas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transporte_id');
            $table->unsignedBigInteger('maqueiro_id');

            $table->foreign('transporte_id')->references('id')->on('solicitacoes_transporte')->onDelete('cascade');
            $table->foreign('maqueiro_id')->references('id')->on('usuarios')->onDelete('cascade');
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
        Schema::dropIfExists('transporte_recusas');
    }
};
