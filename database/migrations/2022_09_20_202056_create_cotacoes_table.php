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
        Schema::create('cotacoes', function (Blueprint $table) {
            $table->id();

            $table->enum('moeda_origem', ['BRL']);
            $table->enum('moeda_destino', ['USD', 'BOB', 'EUR', 'PYG']);

            $table->float('valor_para_conversao', 16, 2);

            $table->enum('forma_de_pagamento', ['boleto', 'cartao_de_credito']);

            $table->float('valor_moeda_destino_conversao', 16, 2);
            $table->float('valor_comprado_moeda_destino', 16, 2);

            $table->float('taxa_de_pagamento', 16, 2);
            $table->float('taxa_de_conversao', 16, 2);

            $table->float('valor_descontado_taxas', 16, 2);

            $table->unsignedBigInteger('usuario');
            $table->foreign('usuario')
                ->references('id')
                    ->on('users');

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
        Schema::dropIfExists('cotacoes');
    }
};
