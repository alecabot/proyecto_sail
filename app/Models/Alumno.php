<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alumno extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'alumnos';
    protected $primaryKey = 'dni';
    public $incrementing = false;

    protected $fillable = [
        'dni', 'nombre', 'puntos', 'id_unidad'
    ];

    public function partes()
    {
        return $this->belongsToMany(Parte::class,
        'alumno_partes', 'alumno_dni', 'dni');
    }

    public function unidad () {
        return $this->belongsTo(Unidad::class, 'id_unidad', 'id');
    }

    public function correos () {
        return $this->hasMany(Correo::class);
    }

}
