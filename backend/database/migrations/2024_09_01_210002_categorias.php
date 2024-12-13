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
        Schema::create('categorias', function (Blueprint $table) {
            $table->id('categoriaId'); // Crea una columna 'categoriaId' de tipo BIGINT como clave primaria autoincremental.
            $table->string('categoriaName'); // Crea una columna 'categoriaName' de tipo VARCHAR para almacenar el nombre de la categoría.
            $table->string('categoriaDescripcion'); // Crea una columna 'categoriaDescripcion' de tipo VARCHAR para almacenar una descripción de la categoría.
            $table->string('categoriaImagen'); // Crea una columna 'categoriaImagen' de tipo VARCHAR para almacenar la URL de la imagen de la categoría.
            $table->timestamps(); // Crea dos columnas de tipo TIMESTAMP, 'created_at' y 'updated_at', para almacenar las marcas de tiempo de creación y actualización del registro.
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('categorias');
    }
};
