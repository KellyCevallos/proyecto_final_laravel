<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoType extends Model
{
    use HasFactory;

    # Se define que la clave primaria de la tabla es 'pagoTypeId' en lugar de la predeterminada 'id'.
    protected $table = 'pagoType';
    protected $primaryKey = 'pagoTypeId';
    
    # Los atributos que se pueden asignar masivamente son 'pagoTypeName'.
    protected $fillable = ['pagoTypeName'];
    
    # Relación uno a muchos (un tipo de pago puede estar asociado a muchos pagos).
    # Se establece una relación con el modelo 'Pago', donde 'pagoType_pagoTypeId' es la clave foránea y 'pagoTypeId' es la clave local.
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'pagoType_pagoTypeId', 'pagoTypeId');
    }
    
}
