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
        Schema::create('pagoType', function (Blueprint $table) {
            $table->id('pagoTypeId'); // Crea una columna 'pagoTypeId' de tipo BIGINT como clave primaria autoincremental.
            $table->string('pagoTypeName'); // Crea una columna 'pagoTypeName' de tipo VARCHAR para almacenar el nombre del tipo de pago.
            $table->timestamps(); // Crea dos columnas de tipo TIMESTAMP, 'created_at' y 'updated_at', para almacenar las marcas de tiempo de creación y actualización del registro.            
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagoType');
    }
};
