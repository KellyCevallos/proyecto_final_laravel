<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    protected $primaryKey = 'claseId'; // Establecer la clave primaria

    protected $fillable = [
        'users_userId',
        'curso_cursoId',
        'relNota',
        'relAprobado',
        'relVerificacion',
        'pagoId'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'users_userId', 'userId');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_cursoId', 'cursoId');
    }

    public function pago()
    {
        return $this->belongsTo(Pago::class, 'pagoId', 'pagoId');
    }
}

