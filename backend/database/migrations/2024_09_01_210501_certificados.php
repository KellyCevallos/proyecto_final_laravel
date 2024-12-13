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
        Schema::create('certificados', function (Blueprint $table) {
            $table->id('certificadoId'); // Crea una columna 'certificadoId' de tipo BIGINT como clave primaria autoincremental.
            $table->string('ruta'); // Crea una columna 'ruta' de tipo VARCHAR para almacenar la ruta o dirección del certificado.
            $table->foreignId('clases_users_userId')->constrained('users', 'userId'); // Crea una columna 'clases_users_userId' como clave foránea que referencia la columna 'userId' en la tabla 'users'.
            $table->foreignId('clases_curso_cursoId')->constrained('cursos', 'cursoId'); // Crea una columna 'clases_curso_cursoId' como clave foránea que referencia la columna 'cursoId' en la tabla 'cursos'.
            $table->timestamps(); // Crea dos columnas de tipo TIMESTAMP, 'created_at' y 'updated_at', para almacenar las marcas de tiempo de creación y actualización del registro.            
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificados');
    }
};
