<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;
    //Llave primaria de curso
    protected $primaryKey = 'cursoId';
    /**
     * cursoName = nombre del curso
     * cursoDescipcion = Descripcion 
     * cursoNivelId = Campo de la relacion con la tabla nivel
     * cursoValor= Valor del curso
     * cursoRquisito = campo del requisito del curso
     * cursoContenido = campo que guarda la estructura de curso
     * cursoCategoriaId = campo de la relacion de la categoria
     */
    protected $fillable = [
        'cursoName', 
        'cursoDescripcion', 
        'cursoNivelId', 
        'cursoValor', 
        'cursoRequisito', 
        'cursoContenido', 
        'cursoExamen',
        'createdBy',
        'cursoCategoriaId'
    ];

    public function clases()
    {
        return $this->hasMany(Clase::class, 'curso_cursoId', 'cursoId');
    }

    public function nivel()
    {
        return $this->belongsTo(Nivel::class, 'cursoNivelId', 'nivelId');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'cursoCategoriaId', 'categoriaId');
    }
}
