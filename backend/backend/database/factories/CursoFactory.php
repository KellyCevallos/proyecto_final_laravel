<?php

namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class CursoFactory extends Factory
{
    public function definition()
    {
        return [
            'cursoName' => $this->faker->sentence(3),  // Nombre del curso con 3 palabras más realistas
            'cursoDescripcion' => $this->faker->paragraph(4),  // Una descripción más larga y detallada
            'cursoNivelId' => $this->faker->numberBetween(1, 4),  // Nivel del curso entre 1 y 4
            'cursoValor' => $this->faker->numberBetween(100, 10000),  // Valor del curso entre 100 y 10000
            'cursoRequisito' => $this->faker->sentence(5),  // Requisito como una oración de 5 palabras
            'cursoContenido' => json_encode([
                [
                    'titulo' => $this->faker->sentence(3),  // Título del contenido
                    'media' => $this->faker->imageUrl(),  // Generar una URL de imagen falsa
                    'concepto' => $this->faker->paragraph(2),  // Concepto con 2 párrafos
                ]
            ]),  // Contenido del curso en formato JSON
            'createdBy' => 'admin',  // El nombre del creador puede ser estático o dinámico si prefieres
            'cursoCategoriaId' => Categoria::factory(),  // Generar una categoría existente
            'cursoExamen' => json_encode([
                [
                    'pregunta' => $this->faker->sentence(5),  // Pregunta de examen
                    'opciones' => [$this->faker->word(), $this->faker->word(), $this->faker->word(), $this->faker->word()],  // 4 opciones
                    'respuestaCorrecta' => $this->faker->randomElement(['1', '2', '3', '4']),  // Seleccionar una respuesta correcta
                ]
            ]),  // Examen en formato JSON
        ];
    }
}
