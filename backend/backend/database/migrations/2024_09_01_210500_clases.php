<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('clases', function (Blueprint $table) {
            $table->id('claseId');
            $table->foreignId('users_userId')->constrained('users', 'userId'); 
            $table->foreignId('curso_cursoId')->constrained('cursos', 'cursoId'); 
            $table->unique(['users_userId', 'curso_cursoId']);
            $table->integer('relNota')->nullable();
            $table->boolean('relAprobado')->default(false);
            $table->boolean('relVerificacion')->default(false);
            $table->foreignId('pagoId')->nullable()->constrained('pagos', 'pagoId');
            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('clases');
    }
};
