<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->unsignedInteger('receita_id');
            $table->float('peso');
            $table->string('unidade_peso');
            $table->integer('quantidade');
            $table->float('margem');
            $table->float('custo');
            $table->float('lucro');
            $table->float('valor');
            $table->float('custo_kg');
            $table->float('lucro_kg');
            $table->float('valor_kg');
            $table->float('custo_unidade');
            $table->float('lucro_unidade');
            $table->float('valor_unidade');            
            $table->timestamps();
            $table->foreign('receita_id')->references('id')->on('receitas')->onUpdate('cascade')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalogos');
    }
}
