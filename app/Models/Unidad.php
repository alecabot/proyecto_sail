<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    public $timestamps = false;
    protected $table = 'unidades';
    protected $fillable = ['nombre', 'id_curso'];
    use HasFactory;

    public function curso () {
        return $this->belongsTo(Curso::class, 'id_curso', 'id');
    }

    public function alumnos () {
        return $this->hasMany(Alumno::class);
    }

    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'tutor_dni', 'dni');
    }
}
