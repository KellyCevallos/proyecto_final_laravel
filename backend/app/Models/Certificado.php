<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificado extends Model
{
    use HasFactory;
    //LLave primaria de la tabla certificado
    protected $primaryKey = 'certificadoId';
    /**
     * ruta= campo en donde se guarda la ruta del certificado dentro del proyecto
     * clases_users_userId = Campo de la relacion de usuario
     * clases_curso_cursoId = Campo de la relacion de curso
     */
    protected $fillable = ['ruta', 'clases_users_userId', 'clases_curso_cursoId'];
    // Relacion con clase uno a muchos
    public function clase()
    {
        return $this->belongsTo(Clase::class, ['clases_users_userId', 'clases_curso_cursoId'], ['users_userId', 'curso_cursoId']);
    }
}
