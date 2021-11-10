<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoloFormulasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bolo_formulas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tipo_formula');
            $table->unsignedInteger('bolo_id');
            $table->unsignedInteger('receita_id');  
            $table->float('receita_custo');  
            $table->timestamps();
            $table->foreign('bolo_id')->references('id')->on('bolos')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('bolo_formulas');
    }
}
