<?php

namespace App\Models;


// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    # Se define que la clave primaria de la tabla es 'userId' en lugar de la predeterminada 'id'.
    protected $primaryKey = 'userId';
    
    # Los atributos que se pueden asignar masivamente son 'userName', 'userPassword', 'userNombres', 'userApellidos' y 'userCorreo'.
    protected $fillable = ['userName', 'password', 'userNombres', 'userApellidos', 'userCorreo', 'userWordKey'];
    
    # Relación uno a muchos (un usuario puede tener muchas clases).
    # Se establece una relación con el modelo 'Clase', donde 'users_userId' es la clave foránea y 'userId' es la clave local.
    public function clases()
    {
        return $this->hasMany(Clase::class, 'users_userId', 'userId');
    }
    
    # Relación uno a muchos (un usuario puede tener muchos pagos).
    # Se establece una relación con el modelo 'Pago', donde 'clases_users_userId' es la clave foránea y 'userId' es la clave local.
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'clases_users_userId', 'userId');
    }
    
}
