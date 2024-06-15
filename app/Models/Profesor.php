<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    public $timestamps = false;
    protected $table = 'profesors';
    use HasFactory;
    protected $primaryKey = 'dni';
    public $incrementing = false;

    protected $fillable = ['dni', 'nombre','telefono','correo','habilitado',];

    public function parte () {
        return $this->hasMany(Parte::class);
    }

    public function cursos () {
        return $this->hasMany(Curso::class);
    }

}
