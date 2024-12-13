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
        Schema::create('users', function (Blueprint $table) {
            $table->id('userId'); // Crea una columna 'userId' de tipo BIGINT como clave primaria autoincremental.
            $table->string('userName'); // Crea una columna 'userName' de tipo VARCHAR para almacenar el nombre de usuario.
            $table->string('password'); // Crea una columna 'userPassword' de tipo VARCHAR para almacenar la contraseña del usuario.
            $table->string('userNombres'); // Crea una columna 'userNombres' de tipo VARCHAR para almacenar los nombres del usuario.
            $table->string('userApellidos'); // Crea una columna 'userApellidos' de tipo VARCHAR para almacenar los apellidos del usuario.
            $table->string('userCorreo'); // Crea una columna 'userCorreo' de tipo VARCHAR para almacenar el correo electrónico del usuario.
            $table->string('userWordKey')->nullable(); // Crea una columna 'userWordKey' de tipo VARCHAR para almacenar la palabra clave del usuario.   
            $table->timestamps(); // Crea dos columnas de tipo TIMESTAMP, 'created_at' y 'updated_at', para almacenar las marcas de tiempo de creación y actualización del registro.            
        });
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
    }
};
