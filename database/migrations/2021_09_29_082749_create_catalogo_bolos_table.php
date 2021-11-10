<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogoBolosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogo_bolos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->unsignedInteger('bolo_id');
            $table->float('peso');
            $table->string('unidade_peso');
            $table->float('margem');
            $table->float('custo');
            $table->float('lucro');
            $table->float('valor');
            $table->float('custo_kg');
            $table->float('lucro_kg');
            $table->float('valor_kg'); 
            $table->json('componentes');             
            $table->timestamps();
            $table->foreign('bolo_id')->references('id')->on('bolos')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalogo_bolos');
    }
}
