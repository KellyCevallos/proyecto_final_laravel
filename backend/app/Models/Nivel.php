<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    use HasFactory;

    # Se define que la clave primaria de la tabla es 'nivelId' en lugar de la predeterminada 'id'.
    protected $primaryKey = 'nivelId';
    
    # Los atributos que se pueden asignar masivamente son 'nivelName'.
    protected $fillable = ['nivelName'];
    
    # Se define el nombre de la tabla en la base de datos como 'nivel'.
    protected $table = 'nivel';
    
    # Relación uno a muchos (nivel tiene muchos cursos).
    # Se establece una relación con el modelo 'Curso', donde 'cursoNivelId' es la clave foránea y 'nivelId' es la clave local.
    public function cursos()
    {
        return $this->hasMany(Curso::class, 'cursoNivelId', 'nivelId');
    }
    
}
