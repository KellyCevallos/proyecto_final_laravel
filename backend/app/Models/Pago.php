<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;
    //Llave primaria de la tabla

    protected $primaryKey = 'pagoId';
     /**
     * PagoMonto = Valor de la tabla 
     * clase_users_userId = campo de la relacion con usuarios
     * clase_curso_cursoId= campo de la relacion de curso
     * pagoType_PagoTypeId = campo de la relacion con tipo de curso
     */

    protected $fillable = [
        'pagoMonto',
        'pagoType_pagoTypeId',  
        'pagoComprobante'
    ];

    //funcion de la relacion uno a muchos
    public function clase()
    {
        return $this->hasOne(Clase::class, 'pagoId');
    }
    //funcion de relacion uno a muchos

    public function pagoType()
    {
        return $this->belongsTo(PagoType::class, 'pagoType_pagoTypeId');
    }
}
