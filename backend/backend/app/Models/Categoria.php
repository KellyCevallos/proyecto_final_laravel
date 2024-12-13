<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    //Modelo de tabla categoria
    //Llave primara de la tabla categoria
    protected $primaryKey = 'categoriaId';  


    /**
     * categpriaName = se guarda el nombre de la categoria
     * categoriaDescripcion = se guarda la descripcion de la categoria
     * categoriaImagen = se guarda la ruta de una imagen asociada a la categoria
     */
    protected $fillable = ['categoriaName, categoriaDescripcion, categoriaImagen'];

    //Funcion de relacion de cursos a categoria

    public function cursos()
    {
        return $this->hasMany(Curso::class, 'cursoCategoriaId', 'categoriaId');
    }
}
