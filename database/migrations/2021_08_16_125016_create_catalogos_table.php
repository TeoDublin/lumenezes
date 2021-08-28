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
            $table->unsignedInteger('receita_id');
            $table->float('custo');
            $table->integer('margem');
            $table->integer('lucro');
            $table->integer('valor');
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
