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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id('pagoId'); // Crea una columna 'pagoId' de tipo BIGINT como clave primaria autoincremental.
            $table->decimal('pagoMonto', 10, 2); // Crea una columna 'pagoMonto' de tipo DECIMAL para almacenar el monto del pago con hasta 10 dígitos en total y 2 decimales.
            $table->foreignId('pagoType_pagoTypeId')->constrained('pagoType', 'pagoTypeId'); // Crea una columna 'pagoType_pagoTypeId' como clave foránea que referencia la columna 'pagoTypeId' en la tabla 'pagoType'.
            $table->text('pagoComprobante')->nullable(); // Crea una columna 'pagoComprobante' de tipo TEXT que puede ser nula, para almacenar el comprobante de pago.
            $table->timestamps();
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagos');
    }
};
