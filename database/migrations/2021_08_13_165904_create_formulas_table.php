<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormulasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formulas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('receita_id');
            $table->unsignedInteger('produto_id');
            $table->float('quantidade');
            $table->string('unidade');        
            $table->double('custo',15,4);
            $table->double('quantidade_mili',15,8);
            $table->string('unidade_mili');               
            $table->timestamps();
            $table->foreign('produto_id')->references('id')->on('produtos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('receita_id')->references('id')->on('receitas')->onUpdate('cascade')->onDelete('cascade');
        });
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formulas');
    }
}
